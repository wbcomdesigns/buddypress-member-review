<?php
/**
 * Overview dashboard partial: rendered inside shell.php.
 *
 * @package BuddyPress_Member_Reviews
 * @since   3.8.0
 */

defined( 'ABSPATH' ) || exit;

global $bupr;

$bupr_general = get_option( 'bupr_admin_general_options', array() );
$bupr_general = is_array( $bupr_general ) ? $bupr_general : array();

// Review counts from the CPT.
$bupr_counts    = wp_count_posts( 'review' );
$bupr_published = isset( $bupr_counts->publish ) ? (int) $bupr_counts->publish : 0;
$bupr_pending   = isset( $bupr_counts->draft ) ? (int) $bupr_counts->draft : 0;

global $wpdb;

// Distinct members who have received at least one published review.
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
$bupr_reviewed_members = (int) $wpdb->get_var(
	$wpdb->prepare(
		"SELECT COUNT(DISTINCT pm.meta_value)
		 FROM {$wpdb->postmeta} pm
		 INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
		 WHERE pm.meta_key = %s
		 AND p.post_type = %s
		 AND p.post_status = %s",
		'linked_bp_member',
		'review',
		'publish'
	)
);

// Flagged reviews still pending moderation.
// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
$bupr_flagged = (int) $wpdb->get_var(
	$wpdb->prepare(
		"SELECT COUNT(DISTINCT p.ID)
		 FROM {$wpdb->posts} p
		 INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
		 WHERE p.post_type = %s
		 AND p.post_status IN ('publish', 'draft')
		 AND pm.meta_key = %s
		 AND pm.meta_value = %s",
		'review',
		'bupr_flag_status',
		'flagged'
	)
);

// Feature flags (read from the hydrated global, falling back to option).
$bupr_auto_approve   = isset( $bupr['auto_approve_reviews'] ) ? 'yes' === $bupr['auto_approve_reviews'] : true;
$bupr_email_on       = isset( $bupr['allow_email'] ) ? 'yes' === $bupr['allow_email'] : true;
$bupr_notif_on       = isset( $bupr['allow_notification'] ) ? 'yes' === $bupr['allow_notification'] : true;
$bupr_anonymous_on   = isset( $bupr['anonymous_reviews'] ) ? 'yes' === $bupr['anonymous_reviews'] : false;
$bupr_owner_reply_on = isset( $bupr['enable_owner_replies'] ) ? 'yes' === $bupr['enable_owner_replies'] : true;
$bupr_flagging_on    = isset( $bupr['enable_flagging'] ) ? 'yes' === $bupr['enable_flagging'] : true;

$bupr_general_url  = admin_url( 'admin.php?page=bp-member-review-settings&tab=bupr-general' );
$bupr_criteria_url = admin_url( 'admin.php?page=bp-member-review-settings&tab=bupr-criteria' );
$bupr_reviews_url  = admin_url( 'edit.php?post_type=review' );
?>

<?php if ( $bupr_flagged > 0 ) : ?>
	<div class="bupr-notice bupr-notice--warn">
		<span class="dashicons dashicons-flag" aria-hidden="true"></span>
		<div>
			<strong><?php esc_html_e( 'Reported reviews need attention.', 'bp-member-reviews' ); ?></strong>
			<?php
			echo esc_html(
				sprintf(
					/* translators: %d: number of reported reviews */
					_n( '%d review has been reported and is awaiting moderation.', '%d reviews have been reported and are awaiting moderation.', $bupr_flagged, 'bp-member-reviews' ),
					$bupr_flagged
				)
			);
			?>
			<a class="bupr-inline-hint" href="<?php echo esc_url( add_query_arg( 'bupr_flagged', '1', $bupr_reviews_url ) ); ?>"><?php esc_html_e( 'Review now', 'bp-member-reviews' ); ?></a>
		</div>
	</div>
<?php endif; ?>

<div class="bupr-stats-grid">
	<div class="bupr-stat">
		<p class="bupr-stat__label"><?php esc_html_e( 'Published Reviews', 'bp-member-reviews' ); ?></p>
		<p class="bupr-stat__value"><?php echo esc_html( number_format_i18n( $bupr_published ) ); ?></p>
		<p class="bupr-stat__trend"><?php esc_html_e( 'Live reviews across all profiles', 'bp-member-reviews' ); ?></p>
	</div>
	<div class="bupr-stat">
		<p class="bupr-stat__label"><?php esc_html_e( 'Pending Approval', 'bp-member-reviews' ); ?></p>
		<p class="bupr-stat__value"><?php echo esc_html( number_format_i18n( $bupr_pending ) ); ?></p>
		<p class="bupr-stat__trend"><?php esc_html_e( 'Drafts awaiting moderation', 'bp-member-reviews' ); ?></p>
	</div>
	<div class="bupr-stat">
		<p class="bupr-stat__label"><?php esc_html_e( 'Members Reviewed', 'bp-member-reviews' ); ?></p>
		<p class="bupr-stat__value"><?php echo esc_html( number_format_i18n( $bupr_reviewed_members ) ); ?></p>
		<p class="bupr-stat__trend"><?php esc_html_e( 'Profiles with at least one review', 'bp-member-reviews' ); ?></p>
	</div>
	<div class="bupr-stat">
		<p class="bupr-stat__label"><?php esc_html_e( 'Reported', 'bp-member-reviews' ); ?></p>
		<p class="bupr-stat__value"><?php echo esc_html( number_format_i18n( $bupr_flagged ) ); ?></p>
		<p class="bupr-stat__trend"><?php esc_html_e( 'Reviews flagged for moderation', 'bp-member-reviews' ); ?></p>
	</div>
</div>

<div class="bupr-card">
	<div class="bupr-card__head">
		<p class="bupr-card__title"><?php esc_html_e( 'Current Configuration', 'bp-member-reviews' ); ?></p>
		<p class="bupr-card__desc"><?php esc_html_e( 'What happens right now when a member reviews someone on this site.', 'bp-member-reviews' ); ?></p>
	</div>
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e( 'Review approval', 'bp-member-reviews' ); ?></th>
			<td>
				<?php if ( $bupr_auto_approve ) : ?>
					<span class="bupr-status-on"><?php esc_html_e( 'Automatic: reviews publish immediately', 'bp-member-reviews' ); ?></span>
				<?php else : ?>
					<span class="bupr-status-off"><?php esc_html_e( 'Manual: reviews wait for admin approval', 'bp-member-reviews' ); ?></span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Email notifications', 'bp-member-reviews' ); ?></th>
			<td>
				<?php if ( $bupr_email_on ) : ?>
					<span class="bupr-status-on"><?php esc_html_e( 'On: members are emailed about new reviews', 'bp-member-reviews' ); ?></span>
				<?php else : ?>
					<span class="bupr-status-off"><?php esc_html_e( 'Off', 'bp-member-reviews' ); ?></span>
					<a href="<?php echo esc_url( $bupr_general_url ); ?>" class="bupr-inline-hint"><?php esc_html_e( 'Turn on', 'bp-member-reviews' ); ?></a>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'BuddyPress notifications', 'bp-member-reviews' ); ?></th>
			<td>
				<?php if ( $bupr_notif_on ) : ?>
					<span class="bupr-status-on"><?php esc_html_e( 'On: members get a notification for new reviews', 'bp-member-reviews' ); ?></span>
				<?php else : ?>
					<span class="bupr-status-off"><?php esc_html_e( 'Off', 'bp-member-reviews' ); ?></span>
					<a href="<?php echo esc_url( $bupr_general_url ); ?>" class="bupr-inline-hint"><?php esc_html_e( 'Turn on', 'bp-member-reviews' ); ?></a>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Anonymous reviews', 'bp-member-reviews' ); ?></th>
			<td>
				<?php if ( $bupr_anonymous_on ) : ?>
					<span class="bupr-status-on"><?php esc_html_e( 'On: members can review without revealing their identity', 'bp-member-reviews' ); ?></span>
				<?php else : ?>
					<span class="bupr-status-off"><?php esc_html_e( 'Off', 'bp-member-reviews' ); ?></span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Owner replies', 'bp-member-reviews' ); ?></th>
			<td>
				<?php if ( $bupr_owner_reply_on ) : ?>
					<span class="bupr-status-on"><?php esc_html_e( 'On: profile owners can reply once to each review', 'bp-member-reviews' ); ?></span>
				<?php else : ?>
					<span class="bupr-status-off"><?php esc_html_e( 'Off', 'bp-member-reviews' ); ?></span>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Review reporting', 'bp-member-reviews' ); ?></th>
			<td>
				<?php if ( $bupr_flagging_on ) : ?>
					<span class="bupr-status-on"><?php esc_html_e( 'On: members can report inappropriate reviews', 'bp-member-reviews' ); ?></span>
				<?php else : ?>
					<span class="bupr-status-off"><?php esc_html_e( 'Off', 'bp-member-reviews' ); ?></span>
				<?php endif; ?>
			</td>
		</tr>
	</table>
</div>

<div class="bupr-card">
	<div class="bupr-card__head">
		<p class="bupr-card__title"><?php esc_html_e( 'Quick Actions', 'bp-member-reviews' ); ?></p>
	</div>
	<div class="bupr-card__body">
		<div class="bupr-action-row">
			<a href="<?php echo esc_url( $bupr_reviews_url ); ?>" class="bupr-btn bupr-btn-secondary">
				<span class="dashicons dashicons-testimonial" aria-hidden="true"></span>
				<?php esc_html_e( 'Manage Reviews', 'bp-member-reviews' ); ?>
			</a>
			<a href="<?php echo esc_url( $bupr_general_url ); ?>" class="bupr-btn bupr-btn-secondary">
				<span class="dashicons dashicons-admin-generic" aria-hidden="true"></span>
				<?php esc_html_e( 'General Settings', 'bp-member-reviews' ); ?>
			</a>
			<a href="<?php echo esc_url( $bupr_criteria_url ); ?>" class="bupr-btn bupr-btn-secondary">
				<span class="dashicons dashicons-star-filled" aria-hidden="true"></span>
				<?php esc_html_e( 'Rating Criteria', 'bp-member-reviews' ); ?>
			</a>
		</div>
	</div>
</div>
