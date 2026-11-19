<?php
/**
 * Self-check for the 3.8.3 UTC upgrade. Run on a dev site: wp eval-file bin/check-utc-migration.php
 *
 * Seeds pre-3.8.3 rows, fires admin_init in the middle of the chain (what a real visitor does), adds a reply
 * after the upgrade started, then asserts every old row was shifted exactly once and the new one not at all.
 * Exits non-zero on failure. Cleans up after itself.
 */
global $wpdb;
wp_clear_scheduled_hook( 'bupr_utc_migration' ); // Leftovers from an earlier aborted run would be picked up as a lost chain.
delete_option( 'bupr_utc_migration_state' );
$fail  = array();
$ids   = array();
$stamp = strtotime( '2026-11-19 10:00:00 UTC' ); // Wall-clock 10:00 as 3.8.2 stored it.
for ( $i = 0; $i < 3; $i++ ) {
	$ids[] = wp_insert_post( array( 'post_type' => 'review', 'post_title' => 'utc check', 'post_status' => 'publish' ) );
}
$rows = array();
for ( $i = 0; $i < 1300; $i++ ) {
	$rows[] = '(' . $ids[ $i % 3 ] . ',"bupr_owner_reply_date","' . $stamp . '")';
}
$wpdb->query( "INSERT INTO {$wpdb->postmeta} (post_id,meta_key,meta_value) VALUES " . implode( ',', $rows ) ); // phpcs:ignore
update_option( 'bupr_plugin_version', '3.8.2' );
add_filter( 'bupr_utc_migration_budget', '__return_zero' ); // Every run stops after one batch of 500.

$offset = $stamp - bupr_local_stamp_to_utc( $stamp );
$next   = function () {
	foreach ( _get_cron_array() as $ts => $events ) {
		if ( isset( $events['bupr_utc_migration'] ) ) {
			foreach ( $events['bupr_utc_migration'] as $e ) {
				return array( $ts, $e['args'] );
			}
		}
	}
	return null;
};

do_action( 'admin_init' ); // First trigger.
$runs = 0;
$new  = 0;
while ( $runs < 10 && ( $e = $next() ) ) { // phpcs:ignore
	// WordPress drops a single event before it runs, so nothing is queued while the batch works.
	wp_unschedule_event( $e[0], 'bupr_utc_migration', $e[1] );
	do_action( 'admin_init' ); // A visitor loads wp-admin mid-chain.
	if ( 1 === $runs ) {
		add_post_meta( $ids[0], 'bupr_owner_reply_date', $stamp ); // A reply written after the upgrade started (already UTC).
		$new = (int) $wpdb->insert_id;
	}
	if ( $next() ) {
		$fail[] = 'admin_init queued a second chain';
	}
	do_action( 'bupr_utc_migration', $e[1][0], $e[1][1] );
	if ( 0 === $runs && ( $lost = $next() ) ) { // phpcs:ignore
		// The chain loses its event (cron-clearing plugin, host reset). Nothing is queued and the state is stale.
		wp_unschedule_event( $lost[0], 'bupr_utc_migration', $lost[1] );
		add_filter( 'bupr_utc_migration_stale', '__return_zero' );
		do_action( 'admin_init' );
		remove_filter( 'bupr_utc_migration_stale', '__return_zero' );
		$resumed = $next();
		if ( ! $resumed || $resumed[1] !== $lost[1] ) {
			$fail[] = 'a lost chain event was not rescheduled from the saved state';
		}
	}
	++$runs;
}
if ( false !== get_option( 'bupr_utc_migration_state' ) ) {
	$fail[] = 'bupr_utc_migration_state still exists after the chain finished';
}
$count = function ( $value ) use ( $wpdb ) {
	return (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key='bupr_owner_reply_date' AND meta_value=%s AND meta_id<>%d", (string) $value, 0 ) ); // phpcs:ignore
};
if ( 1300 !== $count( $stamp - $offset ) ) {
	$fail[] = 'expected 1300 rows shifted once, got ' . $count( $stamp - $offset );
}
if ( 0 !== $count( $stamp - 2 * $offset ) ) {
	$fail[] = 'rows shifted twice: ' . $count( $stamp - 2 * $offset );
}
if ( $new && (string) $stamp !== (string) $wpdb->get_var( $wpdb->prepare( "SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_id=%d", $new ) ) ) { // phpcs:ignore
	$fail[] = 'a reply written after the upgrade started was shifted';
}
if ( version_compare( get_option( 'bupr_plugin_version' ), '3.8.3', '<' ) ) {
	$fail[] = 'version not moved forward';
}
foreach ( $ids as $id ) {
	wp_delete_post( $id, true );
}
echo $fail ? 'FAIL: ' . implode( '; ', $fail ) . "\n" : "PASS: {$runs} runs, 1300 rows shifted once, post-upgrade reply untouched, lost event resumed, state option gone\n";
exit( $fail ? 1 : 0 );
