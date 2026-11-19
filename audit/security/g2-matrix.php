<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Gate G2 authorisation matrix for Member Reviews Free 3.8.3 + Pro 1.0.1 (runs in-process via wp eval-file).
// Actors are real users; nonces are created for the actor. A cell is PASS when the observed status matches the expectation.
$GLOBALS['out'] = array(); $GLOBALS['fails'] = 0;
function rec( $label, $actor, $expect, $got ) {
	global $out, $fails;
	$ok = ( $expect === 'deny' ) ? ( $got !== 'ok' ) : ( $got === 'ok' );
	if ( ! $ok ) { $fails++; }
	$out[] = sprintf( '%-4s %-46s %-8s expect=%-5s got=%s', $ok ? 'PASS' : 'FAIL', $label, $actor, $expect, $got );
}
class Done extends Exception {}
add_filter( 'wp_doing_ajax', '__return_true' );
add_filter( 'wp_die_ajax_handler', function () { return function ( $m = '' ) { throw new Done( is_scalar( $m ) ? (string) $m : 'die' ); }; } );
add_filter( 'wp_die_handler', function () { return function ( $m = '' ) { throw new Done( 'DIE:' . ( is_scalar( $m ) ? wp_strip_all_tags( (string) $m ) : '' ) ); }; } );
add_filter( 'wp_redirect', function ( $l ) { throw new Done( 'REDIRECT:' . $l ); } );
add_filter( 'pre_wp_mail', '__return_true' );

$GLOBALS['U'] = array(); $U =& $GLOBALS['U'];
foreach ( array( 'admin' => 1, 'editor' => 'mx_editor', 'author' => 'mx_author', 'other' => 'mx_other', 'target' => 'mx_target' ) as $k => $v ) {
	if ( is_int( $v ) ) { $U[ $k ] = $v; continue; }
	$u = get_user_by( 'login', $v );
	if ( ! $u ) { $id = wp_insert_user( array( 'user_login' => $v, 'user_pass' => 'pw', 'user_email' => $v . '@example.com', 'role' => 'editor' === $k ? 'editor' : 'subscriber' ) ); } else { $id = $u->ID; }
	$U[ $k ] = $id;
}
$U['guest'] = 0;
function as_user( $a ) { global $U; wp_set_current_user( $U[ $a ] ); }
function mk( $status = 'publish', $author = 'author', $member = 'target', $meta = array(), $type = 'review' ) {
	global $U;
	$id = wp_insert_post( array( 'post_type' => $type, 'post_status' => $status, 'post_title' => 'mx', 'post_content' => 'mx body', 'post_author' => $U[ $author ] ) );
	if ( $member ) { update_post_meta( $id, 'linked_bp_member', $U[ $member ] ); update_post_meta( $id, 'profile_star_rating', array( 'Member Response' => 4, 'Member Skills' => 4 ) ); }
	foreach ( $meta as $k => $v ) { update_post_meta( $id, $k, $v ); }
	return $id;
}
function ajax( $action, $actor, $post, $nonce_action = 'review-nonce' ) {
	as_user( $actor );
	$_POST = $post; $_POST['action'] = $action;
	if ( $nonce_action ) { $_POST['nonce'] = wp_create_nonce( $nonce_action ); }
	$_REQUEST = $_POST; $_GET = array();
	ob_start();
	try { do_action( 'wp_ajax_' . $action ); $raw = ob_get_clean(); } catch ( Done $e ) { $raw = ob_get_clean(); }
	$j = json_decode( $raw, true );
	if ( is_array( $j ) && isset( $j['success'] ) ) { return $j['success'] ? 'ok' : 'err:' . substr( wp_json_encode( $j['data'] ), 0, 60 ); }
	return 'noresp:' . substr( trim( $raw ), 0, 30 );
}
function rest( $method, $route, $actor, $params = array() ) {
	as_user( $actor );
	$r = new WP_REST_Request( $method, $route );
	foreach ( $params as $k => $v ) { $r->set_param( $k, $v ); }
	$res = rest_do_request( $r );
	return $res;
}
function st( $res ) { $s = $res->get_status(); return ( $s >= 200 && $s < 300 ) ? 'ok' : (string) $s; }
function admin_action( $action, $actor, $get, $nonce_action ) {
	as_user( $actor );
	$_GET = $get; $_GET['_wpnonce'] = wp_create_nonce( $nonce_action ); $_REQUEST = $_GET; $_POST = array();
	try { do_action( 'admin_action_' . $action ); return 'ok:noexit'; } catch ( Done $e ) { $m = $e->getMessage(); return ( 0 === strpos( $m, 'REDIRECT:' ) ) ? ( false !== strpos( $m, 'not_member' ) ? 'denied-notmember' : 'ok' ) : 'denied:' . substr( $m, 0, 40 ); }
}
function post_action( $action, $actor, $get, $nonce_action ) {
	as_user( $actor );
	$_GET = $get; $_GET['_wpnonce'] = wp_create_nonce( $nonce_action ); $_REQUEST = $_GET; $_POST = array();
	try { do_action( 'admin_post_' . $action ); return 'ok:noexit'; } catch ( Done $e ) { $m = $e->getMessage(); return ( 0 === strpos( $m, 'REDIRECT:' ) ) ? 'ok' : 'denied:' . substr( $m, 0, 40 ); }
}

global $bupr;
$bupr['enable_owner_replies'] = 'yes'; $bupr['allow_update'] = 'yes'; $bupr['auto_approve_reviews'] = 'no';

// ---------- AJAX (Free) ----------
foreach ( array( 'other' => 'deny', 'author' => 'deny', 'target' => 'deny', 'editor' => 'ok', 'admin' => 'ok' ) as $a => $exp ) {
	$id = mk( 'draft' );
	rec( 'ajax bupr_approve_review (draft)', $a, $exp, ajax( 'bupr_approve_review', $a, array( 'review_id' => $id ), 'bupr_member_review_ajax' ) );
	if ( 'deny' === $exp ) { rec( '  -> stays draft', $a, 'ok', 'draft' === get_post_status( $id ) ? 'ok' : 'CHANGED' ); }
}
rec( 'ajax bupr_approve_review (group review; moderator action, accepted)', 'admin', 'ok', ajax( 'bupr_approve_review', 'admin', array( 'review_id' => mk( 'draft', 'author', null ) ), 'bupr_member_review_ajax' ) );
rec( 'ajax bupr_approve_review (wrong nonce)', 'admin', 'deny', ( function () { as_user( 'admin' ); $_POST = array( 'action' => 'bupr_approve_review', 'nonce' => 'bad', 'review_id' => mk( 'draft' ) ); $_REQUEST = $_POST; ob_start(); try { do_action( 'wp_ajax_bupr_approve_review' ); } catch ( Done $e ) {} $r = ob_get_clean(); $j = json_decode( $r, true ); return ( is_array( $j ) && ! empty( $j['success'] ) ) ? 'ok' : 'blocked'; } )() );

foreach ( array( 'other' => 'deny', 'target' => 'deny', 'author' => 'ok', 'editor' => 'ok', 'admin' => 'ok' ) as $a => $exp ) {
	rec( 'ajax bupr_edit_review', $a, $exp, ajax( 'bupr_edit_review', $a, array( 'review' => mk() ) ) );
}
foreach ( array( 'other' => 'deny', 'target' => 'deny', 'author' => 'ok', 'editor' => 'ok', 'admin' => 'ok' ) as $a => $exp ) {
	$id = mk();
	$r  = ajax( 'bupr_update_review', $a, array( 'review_id' => $id, 'bupr_review_desc' => 'changed by ' . $a, 'bupr_review_rating' => array( 'Member Response' => '5', 'Member Skills' => '5' ) ) );
	rec( 'ajax bupr_update_review', $a, $exp, $r );
	if ( 'deny' === $exp ) { rec( '  -> content untouched', $a, 'ok', 'mx body' === get_post_field( 'post_content', $id ) ? 'ok' : 'CHANGED' ); }
}
// Group Review (no linked member) must not be editable by a member who is not its author.
rec( 'ajax bupr_update_review (someone elses group review)', 'other', 'deny', ajax( 'bupr_update_review', 'other', array( 'review_id' => mk( 'publish', 'author', null ), 'bupr_review_desc' => 'x' ) ) );
foreach ( array( 'other' => 'deny', 'author' => 'deny', 'target' => 'ok', 'admin' => 'ok' ) as $a => $exp ) {
	$id = mk();
	$r  = ajax( 'bupr_add_owner_reply', $a, array( 'review_id' => $id, 'reply_content' => 'thanks from ' . $a ) );
	rec( 'ajax bupr_add_owner_reply', $a, $exp, $r );
	if ( 'deny' === $exp ) { rec( '  -> no reply stored', $a, 'ok', '' === (string) get_post_meta( $id, 'bupr_owner_reply', true ) ? 'ok' : 'STORED' ); }
}
rec( 'ajax bupr_add_owner_reply (draft review)', 'target', 'deny', ajax( 'bupr_add_owner_reply', 'target', array( 'review_id' => mk( 'draft' ), 'reply_content' => 'x' ) ) );
foreach ( array( 'other' => 'ok', 'author' => 'ok', 'target' => 'ok' ) as $a => $exp ) {
	rec( 'ajax bupr_flag_review (published member review)', $a, $exp, ajax( 'bupr_flag_review', $a, array( 'review_id' => mk(), 'flag_reason' => 'spam' ) ) );
}
rec( 'ajax bupr_flag_review (group review)', 'other', 'deny', ajax( 'bupr_flag_review', 'other', array( 'review_id' => mk( 'publish', 'author', null ), 'flag_reason' => 'spam' ) ) );
rec( 'ajax bupr_flag_review (draft review)', 'other', 'deny', ajax( 'bupr_flag_review', 'other', array( 'review_id' => mk( 'draft' ), 'flag_reason' => 'spam' ) ) );
// ---------- AJAX (Pro) ----------
foreach ( array( 'other' => 'deny', 'author' => 'deny', 'editor' => 'deny', 'admin' => 'ok' ) as $a => $exp ) {
	rec( 'ajax bupr_save_criteria', $a, $exp, ajax( 'bupr_save_criteria', $a, array( 'criteria' => array( array( 'label' => 'Mx', 'type' => 'stars', 'required' => 'true' ) ) ), 'bupr_criteria_nonce' ) );
}
foreach ( array( 'other' => 'deny', 'author' => 'deny', 'editor' => 'deny' ) as $a => $exp ) {
	rec( 'ajax bupr_reset_criteria', $a, $exp, ajax( 'bupr_reset_criteria', $a, array(), 'bupr_criteria_nonce' ) );
}
foreach ( array( 'other' => 'deny', 'author' => 'ok', 'editor' => 'ok' ) as $a => $exp ) {
	rec( 'ajax bupr_get_custom_criteria_values', $a, $exp, ajax( 'bupr_get_custom_criteria_values', $a, array( 'review_id' => mk() ), '' ) );
}
// Endorsements (handlers exist only in Endorsements mode: assert they are registered so a missing hook cannot read as a pass)
foreach ( array( 'bupr_request_endorsement', 'bupr_approve_endorsement', 'bupr_decline_endorsement', 'bupr_give_endorsement' ) as $h ) { rec( 'precondition ' . $h . ' registered', 'n/a', 'ok', has_action( 'wp_ajax_' . $h ) ? 'ok' : 'NOT-REGISTERED (run with bupr_pro_review_mode=endorsements)' ); }
$req = function ( $from_actor, $to_actor ) use ( $U ) { return mk( 'publish', $from_actor, $from_actor, array( 'bupr_endorsement_type' => 'requested', 'bupr_endorsement_status' => 'pending', 'bupr_requested_from' => $U[ $to_actor ] ) ); };
foreach ( array( 'other' => 'deny', 'author' => 'deny', 'target' => 'ok', 'admin' => 'deny' ) as $a => $exp ) {
	$id = $req( 'author', 'target' );
	rec( 'ajax bupr_approve_endorsement', $a, $exp, ajax( 'bupr_approve_endorsement', $a, array( 'endorsement_id' => $id, 'message' => 'm' ) ) );
}
foreach ( array( 'other' => 'deny', 'author' => 'deny', 'target' => 'ok' ) as $a => $exp ) {
	$id = $req( 'author', 'target' );
	$r  = ajax( 'bupr_decline_endorsement', $a, array( 'endorsement_id' => $id, 'reason' => 'no' ) );
	rec( 'ajax bupr_decline_endorsement', $a, $exp, $r );
	if ( 'deny' === $exp ) { rec( '  -> still pending', $a, 'ok', 'pending' === get_post_meta( $id, 'bupr_endorsement_status', true ) ? 'ok' : 'CHANGED' ); }
}
rec( 'ajax bupr_approve_endorsement (a normal review id)', 'target', 'deny', ajax( 'bupr_approve_endorsement', 'target', array( 'endorsement_id' => mk() ) ) );
rec( 'ajax bupr_request_endorsement (from self)', 'other', 'deny', ajax( 'bupr_request_endorsement', 'other', array( 'member_id' => $U['other'], 'skills' => array( 'x' ) ) ) );
rec( 'ajax bupr_give_endorsement (self)', 'other', 'deny', ajax( 'bupr_give_endorsement', 'other', array( 'member_id' => $U['other'], 'skills' => array( 'x' ) ) ) );

// ---------- admin_action / admin_post ----------
foreach ( array( 'other' => 'deny', 'author' => 'deny', 'target' => 'deny', 'editor' => 'ok', 'admin' => 'ok' ) as $a => $exp ) {
	$id = mk(); $x = admin_action( 'bupr_verify', $a, array( 'review_id' => $id ), 'bupr_verify_' . $id );
	rec( 'admin_action bupr_verify', $a, $exp, 0 === strpos( $x, 'ok' ) ? 'ok' : $x );
	if ( 'deny' === $exp ) { rec( '  -> not verified', $a, 'ok', ! get_post_meta( $id, 'bupr_verified', true ) && ! get_post_meta( $id, 'bupr_verified_review', true ) ? 'ok' : 'VERIFIED' ); }
	$id = mk(); $x = admin_action( 'bupr_feature', $a, array( 'review_id' => $id, 'featured' => 1 ), 'bupr_feature_' . $id );
	rec( 'admin_action bupr_feature', $a, $exp, 0 === strpos( $x, 'ok' ) ? 'ok' : $x );
	$id = mk(); $x = admin_action( 'bupr_unverify', $a, array( 'review_id' => $id ), 'bupr_unverify_' . $id );
	rec( 'admin_action bupr_unverify', $a, $exp, 0 === strpos( $x, 'ok' ) ? 'ok' : $x );
}
foreach ( array( 'other' => 'deny', 'author' => 'deny', 'target' => 'deny', 'editor' => 'ok', 'admin' => 'ok' ) as $a => $exp ) {
	$id = mk( 'publish', 'author', 'target', array( 'bupr_flag_status' => 'flagged' ) );
	rec( 'admin_post bupr_dismiss_flag', $a, $exp, post_action( 'bupr_dismiss_flag', $a, array( 'review_id' => $id ), 'bupr_dismiss_flag_' . $id ) );
	$id = mk( 'publish', 'author', 'target', array( 'bupr_flag_status' => 'flagged' ) );
	rec( 'admin_post bupr_resolve_flag', $a, $exp, post_action( 'bupr_resolve_flag', $a, array( 'review_id' => $id ), 'bupr_resolve_flag_' . $id ) );
}
// Bulk verify filter
foreach ( array( 'other' => 'deny', 'author' => 'deny', 'editor' => 'ok' ) as $a => $exp ) {
	$id = mk(); as_user( $a );
	apply_filters( 'handle_bulk_actions-edit-review', 'edit.php', 'bupr_verify_reviews', array( $id ) );
	$v = get_post_meta( $id, 'bupr_verified', true ) . get_post_meta( $id, 'bupr_verified_review', true );
	rec( 'bulk action bupr_verify', $a, $exp, '' !== $v ? 'ok' : 'noop' );
}

$bulk_ids = array(); 
// ---------- REST (Pro) ----------
$R1 = mk(); $R2 = mk( 'draft' ); $R3 = mk( 'publish', 'author', 'target', array( 'bupr_anonymous_review_post' => 'yes' ) );
$RG = mk( 'publish', 'author', null ); $RGd = mk( 'draft', 'author', null );
$RP = wp_insert_post( array( 'post_type' => 'review', 'post_status' => 'publish', 'post_title' => 'pw', 'post_content' => 'secret', 'post_password' => 'pw', 'post_author' => $U['author'] ) ); update_post_meta( $RP, 'linked_bp_member', $U['target'] );
$ns = '/bupr/v1';
foreach ( array( 'guest', 'other', 'author', 'target', 'editor', 'admin' ) as $a ) {
	rec( 'REST GET index', $a, 'ok', st( rest( 'GET', $ns, $a ) ) );
}
$read = array( 'draft' => $R2, 'group publish' => $RG, 'group draft' => $RGd, 'password-protected' => $RP );
foreach ( $read as $lab => $id ) {
	foreach ( array( 'guest' => 'deny', 'other' => 'deny', 'target' => 'deny' ) as $a => $exp ) {
		$res = rest( 'GET', "$ns/reviews/$id", $a ); $body = wp_json_encode( $res->get_data() );
		rec( "REST GET review ($lab)", $a, $exp, st( $res ) . ( 'ok' === st( $res ) && false !== strpos( $body, 'secret' ) ? '+LEAK' : '' ) );
	}
}
foreach ( array( 'author' => 'ok', 'editor' => 'ok', 'admin' => 'ok' ) as $a => $exp ) { rec( 'REST GET review (own/mod draft)', $a, $exp, st( rest( 'GET', "$ns/reviews/$R2", $a ) ) ); }
rec( 'REST GET review (group publish) by admin is told why, not served', 'admin', 'deny', st( rest( 'GET', "$ns/reviews/$RG", 'admin' ) ) );
// Anonymous author hidden
foreach ( array( 'guest' => true, 'other' => true, 'target' => true, 'author' => false, 'editor' => false, 'admin' => false ) as $a => $hidden ) {
	$d = rest( 'GET', "$ns/reviews/$R3", $a )->get_data(); $json = wp_json_encode( $d );
	$shows = false !== strpos( $json, '"author":' . $U['author'] ) || false !== strpos( $json, '"author_id":' . $U['author'] ) || false !== strpos( $json, 'mx_author' );
	rec( 'REST GET anonymous review hides author', $a, 'ok', $hidden ? ( $shows ? 'AUTHOR-SHOWN' : 'ok' ) : 'ok' );
}
// Lists
foreach ( array( 'guest', 'other', 'target' ) as $a ) {
	$res = rest( 'GET', "$ns/reviews", $a, array( 'status' => 'any', 'per_page' => 100 ) ); $d = $res->get_data(); $ids = array_map( function ( $x ) { return (int) ( $x['id'] ?? 0 ); }, (array) $d );
	$bad = array_intersect( $ids, array( $R2, $RG, $RGd, $RP ) );
	rec( 'REST list status=any never shows draft/group/password', $a, 'ok', $bad ? 'LEAK:' . implode( ',', $bad ) : 'ok' );
	$hdr = $res->get_headers(); $pub = (int) ( new WP_Query( array( 'post_type' => 'review', 'post_status' => 'publish', 'has_password' => false, 'meta_query' => array( bupr_member_review_meta_clause() ), 'fields' => 'ids', 'posts_per_page' => -1 ) ) )->found_posts;
	rec( 'REST list X-WP-Total honest (member published only)', $a, 'ok', ( (int) ( $hdr['X-WP-Total'] ?? -1 ) === $pub ) ? 'ok' : 'total=' . ( $hdr['X-WP-Total'] ?? 'none' ) . ' vs ' . $pub );
	$res = rest( 'GET', "$ns/reviews", $a, array( 'author_id' => $U['author'] ) ); $d = $res->get_data(); $ids = array_map( function ( $x ) { return (int) ( $x['id'] ?? 0 ); }, (array) $d );
	rec( 'REST list author_id= hides anonymous', $a, 'ok', in_array( $R3, $ids, true ) ? 'ANON-LISTED' : 'ok' );
	$res = rest( 'GET', "$ns/members/{$U['target']}/reviews", $a ); $d = $res->get_data(); $ids = array_map( function ( $x ) { return (int) ( $x['id'] ?? 0 ); }, (array) $d );
	$bad = array_intersect( $ids, array( $R2, $RG, $RGd, $RP ) );
	rec( 'REST members/{id}/reviews never shows draft/group/password', $a, 'ok', $bad ? 'LEAK:' . implode( ',', $bad ) : 'ok' );
	$shows = false; foreach ( (array) $d as $x ) { if ( (int) ( $x['id'] ?? 0 ) === $R3 ) { $j = wp_json_encode( $x ); $shows = false !== strpos( $j, 'mx_author' ) || false !== strpos( $j, '"id":"' . $U['author'] . '"' ) || false !== strpos( $j, '"id":' . $U['author'] . ',' ); } }
	rec( 'REST members/{id}/reviews hides anonymous author', $a, 'ok', $shows ? 'AUTHOR-SHOWN' : 'ok' );
	rec( 'REST members/{id}/rating', $a, 'ok', st( rest( 'GET', "$ns/members/{$U['target']}/rating", $a ) ) );
}
$bm = rest( 'GET', "$ns/members/{$U['target']}/reviews", 'guest', array( 'per_page' => 100000 ) ); rec( 'REST members/{id}/reviews per_page=100000 clamped or rejected', 'guest', 'ok', ( 'ok' !== st( $bm ) || count( (array) $bm->get_data() ) <= 100 ) ? 'ok' : 'UNBOUNDED:' . count( (array) $bm->get_data() ) );
$big = rest( 'GET', "$ns/reviews", 'guest', array( 'per_page' => 100000 ) ); rec( 'REST list per_page=100000 is clamped or rejected', 'guest', 'ok', ( 'ok' !== st( $big ) || count( (array) $big->get_data() ) <= 100 ) ? 'ok' : 'UNBOUNDED:' . count( (array) $big->get_data() ) );
// Writes
foreach ( array( 'guest' => 'deny', 'other' => 'deny', 'target' => 'deny', 'author' => 'ok', 'editor' => 'ok', 'admin' => 'ok' ) as $a => $exp ) {
	rec( 'REST PUT review', $a, $exp, st( rest( 'PUT', "$ns/reviews/" . mk(), $a, array( 'content' => 'edited by ' . $a, 'rating' => 4 ) ) ) );
}
foreach ( array( 'guest' => 'deny', 'other' => 'deny', 'target' => 'deny', 'author' => 'deny', 'editor' => 'ok', 'admin' => 'ok' ) as $a => $exp ) {
	$id = mk(); $x = st( rest( 'DELETE', "$ns/reviews/$id", $a, array( 'force' => true ) ) );
	rec( 'REST DELETE review', $a, $exp, $x );
	if ( 'deny' === $exp ) { rec( '  -> still exists', $a, 'ok', get_post( $id ) ? 'ok' : 'DELETED' ); }
}
rec( 'REST PUT someone elses group review', 'other', 'deny', st( rest( 'PUT', "$ns/reviews/$RG", 'other', array( 'content' => 'x' ) ) ) );
rec( 'REST DELETE group review by non-author', 'other', 'deny', st( rest( 'DELETE', "$ns/reviews/$RG", 'other', array( 'force' => true ) ) ) );
rec( 'REST DELETE group review by admin (member API must not touch it)', 'admin', 'deny', st( rest( 'DELETE', "$ns/reviews/" . mk( 'publish', 'author', null ), 'admin', array( 'force' => true ) ) ) );
foreach ( array( 'guest' => 'deny', 'other' => 'ok', 'author' => 'ok', 'target' => 'deny' ) as $a => $exp ) {
	rec( 'REST POST review (create)', $a, $exp, st( rest( 'POST', "$ns/reviews", $a, array( 'member_id' => $U['target'], 'content' => 'rest create ' . $a, 'rating' => 5 ) ) ) );
}
foreach ( array( 'guest' => 'deny', 'other' => 'deny', 'author' => 'deny', 'target' => 'ok' ) as $a => $exp ) {
	$id = mk(); $x = st( rest( 'POST', "$ns/reviews/$id/reply", $a, array( 'content' => 'rest reply ' . $a, 'reply' => 'rest reply ' . $a ) ) );
	rec( 'REST POST reply', $a, $exp, $x );
	if ( 'deny' === $exp ) { rec( '  -> no reply stored', $a, 'ok', '' === (string) get_post_meta( $id, 'bupr_owner_reply', true ) ? 'ok' : 'STORED' ); }
}
foreach ( array( 'guest' => 'deny', 'other' => 'ok', 'target' => 'ok' ) as $a => $exp ) {
	rec( 'REST POST flag', $a, $exp, st( rest( 'POST', "$ns/reviews/" . mk() . '/flag', $a, array( 'reason' => 'spam', 'flag_reason' => 'spam' ) ) ) );
}
rec( 'REST POST flag (draft review)', 'other', 'deny', st( rest( 'POST', "$ns/reviews/" . mk( 'draft' ) . '/flag', 'other', array( 'reason' => 'spam', 'flag_reason' => 'spam' ) ) ) );
rec( 'REST POST flag (group review)', 'other', 'deny', st( rest( 'POST', "$ns/reviews/$RG/flag", 'other', array( 'reason' => 'spam', 'flag_reason' => 'spam' ) ) ) );
foreach ( array( 'verify', 'approve', 'feature', 'dismiss-flag' ) as $route ) {
	foreach ( array( 'guest' => 'deny', 'other' => 'deny', 'author' => 'deny', 'target' => 'deny', 'editor' => 'deny', 'admin' => 'ok' ) as $a => $exp ) {
		$id = mk( 'approve' === $route ? 'draft' : 'publish' ); $x = st( rest( 'POST', "$ns/reviews/$id/$route", $a, array( 'featured' => 1 ) ) );
		rec( "REST POST $route", $a, $exp, $x );
	}
	rec( "REST POST $route (group review) by admin", 'admin', 'deny', st( rest( 'POST', "$ns/reviews/" . mk( 'draft', 'author', null ) . "/$route", 'admin' ) ) );
}

// ---------- Pro licence handlers (admin_init, nonce + capability) ----------
$GLOBALS['http_calls'] = 0;
add_filter( 'pre_http_request', function ( $pre ) { $GLOBALS['http_calls']++; return new WP_Error( 'mx_blocked', 'blocked in test' ); } );
foreach ( array( 'activate', 'deactivate' ) as $kind ) {
	foreach ( array( 'guest' => 'deny', 'other' => 'deny', 'editor' => 'deny', 'admin' => 'ok' ) as $a => $exp ) {
		as_user( $a ); $GLOBALS['http_calls'] = 0;
		$_POST = array( 'edd_bupr_pro_license_' . $kind => '1', 'edd_wbcom_bupr_pro_nonce' => wp_create_nonce( 'edd_wbcom_bupr_pro_nonce' ), 'edd_wbcom_bupr_pro_license_key' => 'MX-KEY' );
		$_REQUEST = $_POST; $_GET = array();
		update_option( 'edd_wbcom_bupr_pro_license_key', 'MX-KEY' );
		$fn = 'edd_wbcom_bupr_pro_' . $kind . '_license';
		ob_start(); try { $fn(); } catch ( Done $e ) {} ob_end_clean();
		rec( "licence $kind handler reaches the store", $a, $exp, $GLOBALS['http_calls'] > 0 ? 'ok' : 'no-call' );
	}
}
delete_option( 'edd_wbcom_bupr_pro_license_key' ); delete_option( 'edd_wbcom_bupr_pro_license_status' ); $_POST = array();
wp_set_current_user( 0 );
echo implode( "\n", $GLOBALS["out"] ), "\n";
echo "TOTAL " . count( $GLOBALS["out"] ) . " cells, FAIL " . $GLOBALS["fails"] . "\n";
