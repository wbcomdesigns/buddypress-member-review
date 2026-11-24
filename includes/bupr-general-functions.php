<?php
/**
 * General functions for BuddyPress Member Reviews.
 *
 * @package BuddyPress_Member_Reviews
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Locate template.
 *
 * Locate the called template.
 * Search Order:
 * 1. /themes/theme/buddypress-member-review/$template_name
 * 2. /themes/theme/$template_name
 * 3. /plugins/buddypress-member-review/templates/$template_name.
 *
 * @since 1.0.0
 *
 * @param   string $template_name          Template to load.
 * @param   string $template_path          Path to templates.
 * @param   string $default_path           Default path to template files.
 * @return  string                          Path to the template file.
 */
function bupr_locate_template( $template_name, $template_path = '', $default_path = '' ) {

	if ( ! $template_path ) :
		$template_path = 'buddypress-member-review/';
	endif;

	// Set default plugin templates path.
	if ( ! $default_path ) :
		$default_path = BUPR_PLUGIN_PATH . 'includes/templates/';
	endif;

	// Search template file in theme folder.
	$template = locate_template(
		array(
			$template_path . $template_name,
			$template_name,
		)
	);

	// Get plugin template file if not found in theme.
	if ( ! $template ) :
		$template = $default_path . $template_name;
	endif;

	// Verify the template file exists.
	if ( ! file_exists( $template ) ) {
		// Log the error but don't fail completely.
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( sprintf( 'BuddyPress Member Reviews: Template file %s not found', $template ) );
		}

		// Return a default template that should always exist.
		$template = $default_path . 'bupr-reviews-tab-template.php';
	}

	return apply_filters( 'bupr_locate_template', $template, $template_name, $template_path, $default_path );
}

/**
 * Get template.
 *
 * Search for the template and include the file.
 *
 * @since 1.0.0
 *
 * @param string $template_name          Template to load.
 * @param array  $args                   Arguments to pass to the template file.
 * @param string $template_path          Path to templates.
 * @param string $default_path           Default path to template files.
 */
function bupr_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if ( is_array( $args ) && ! empty( $args ) ) :
        // phpcs:ignore WordPress.PHP.DontExtract.extract_extract -- Variables needed in template scope.
		extract( $args, EXTR_SKIP );
	endif;

	$template_file = bupr_locate_template( $template_name, $template_path, $default_path );

	if ( ! file_exists( $template_file ) ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped -- trigger_error output goes to error log, not browser.
			trigger_error(
				sprintf(
					/* translators: %s: Template file path that does not exist. */
					__( 'Template file %s does not exist.', 'bp-member-reviews' ),
					esc_html( $template_file )
				),
				E_USER_NOTICE
			);
            // phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
		}
		return;
	}

	include $template_file;
}

/**
 * Get the singular review tab name on a member's profile.
 *
 * @return string Singular tab name.
 */
function bupr_profile_review_singular_tab_name() {
	global $bupr;

	$tab_name = isset( $bupr['review_label'] ) ? esc_html( $bupr['review_label'] ) : __( 'Review', 'bp-member-reviews' );

	return apply_filters( 'bupr_profile_review_singular_tab_name', $tab_name );
}

/**
 * Get the plural review tab name on a member's profile.
 *
 * @return string Plural tab name.
 */
function bupr_profile_review_tab_name() {
	global $bupr;

	$tab_name = isset( $bupr['review_label_plural'] ) ? esc_html( $bupr['review_label_plural'] ) : __( 'Reviews', 'bp-member-reviews' );

	return apply_filters( 'bupr_review_tab_name', $tab_name );
}

/**
 * Get the slug for the plural review tab on a member's profile.
 *
 * @return string Slug for plural tab.
 */
function bupr_profile_review_tab_plural_slug() {
	global $bupr;

	$tab_slug = isset( $bupr['review_label_plural'] ) ? sanitize_title( $bupr['review_label_plural'] ) : 'reviews';

	return apply_filters( 'bupr_review_tab_plural_slug', esc_html( $tab_slug ) );
}

/**
 * Get the slug for the singular review tab on a member's profile.
 *
 * @return string Slug for singular tab.
 */
function bupr_profile_review_tab_singular_slug() {
	global $bupr;

	$tab_slug = isset( $bupr['review_label'] ) ? sanitize_title( $bupr['review_label'] ) : 'review';

	return apply_filters( 'bupr_review_tab_singular_slug', esc_html( $tab_slug ) );
}

/**
 * Register BuddyPress Member Review triggers for GamiPress integration.
 *
 * @param array $triggers GamiPress triggers.
 * @return array Modified triggers.
 */
function bupr_bp_activity_triggers( $triggers ) {
	$triggers[ __( 'BuddyPress Member Review', 'bp-member-reviews' ) ] = array(
		'gamipress_bp_member_review' => __( 'Give Member Review', 'bp-member-reviews' ),
	);

	return $triggers;
}
add_filter( 'gamipress_activity_triggers', 'bupr_bp_activity_triggers' );

/**
 * Get the user ID for the review trigger in GamiPress.
 *
 * @param int    $user_id The current user ID.
 * @param string $trigger The trigger identifier.
 * @param array  $args    Trigger arguments.
 * @return int Modified user ID.
 */
function bupr_trigger_get_user_id( $user_id, $trigger, $args ) {
	if ( 'gamipress_bp_member_review' === $trigger ) {
		$user_id = $args[0];
	}

	return $user_id;
}
add_filter( 'gamipress_trigger_get_user_id', 'bupr_trigger_get_user_id', 10, 3 );


/**
 * Handle review submission and update user meta with review data.
 *
 * This function is triggered after a review is submitted or updated.
 *
 * @param int $review_id       ID of the submitted review.
 * @param int $reviewed_user_id ID of the user who received the review.
 */
add_action( 'bupr_member_review_after_review_insert', 'bupr_handle_review_submission', 10, 2 );

/**
 * Handle review submission and update user meta with review data.
 *
 * This function is triggered after a review is submitted or updated.
 * It validates the review exists and is published, then recalculates
 * the aggregate rating for the reviewed user.
 *
 * @since 3.7.0
 *
 * @param int $review_id        ID of the submitted review.
 * @param int $reviewed_user_id ID of the user who received the review.
 * @return void
 */
function bupr_handle_review_submission( $review_id, $reviewed_user_id ) {
	if ( empty( $review_id ) || empty( $reviewed_user_id ) ) {
		return;
	}
	$review = get_post( $review_id );
	if ( ! $review || 'publish' !== $review->post_status ) {
		return;
	}
	// Recalculate the review data for the reviewed user.
	bupr_recalculate_user_reviews_for_user( $reviewed_user_id );
}

/**
 * Recalculate and update review data for a specific user using SQL.
 *
 * This function recalculates the total number of reviews and the aggregate rating for a specific user.
 *
 * @param int $user_id The ID of the user whose reviews need recalculation.
 */
function bupr_recalculate_user_reviews_for_user( $user_id ) {
	global $wpdb;
	// Fetch all reviews for this user using raw SQL.

	$reviews = $wpdb->get_results(
		$wpdb->prepare(
			"
        SELECT p.ID 
        FROM {$wpdb->posts} p
        INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
        WHERE p.post_type = 'review'
        AND p.post_status = 'publish'
        AND pm.meta_key = 'linked_bp_member'
        AND pm.meta_value = %d
        " . bupr_mode_sql_exclusion( 'p' ), // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- A fixed fragment with no user input.
			$user_id
		)
	);

	// Initialize variables to calculate aggregate rating.
	$total_rating        = 0;
	$review_count        = count( $reviews );
	$rated_reviews_count = 0;

	if ( $review_count > 0 ) {
		// Collect all review IDs.
		$review_ids = wp_list_pluck( $reviews, 'ID' );

		// Safety check: ensure we have valid review IDs before querying.
		if ( ! empty( $review_ids ) && is_array( $review_ids ) ) {
			// Prepare the query for all ratings at once.
			$placeholders = implode( ',', array_fill( 0, count( $review_ids ), '%d' ) );
            // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
			$query = $wpdb->prepare(
				"SELECT post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = 'profile_star_rating' AND post_id IN ($placeholders)",
				...$review_ids
			);
            // phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare

			// Get all ratings in a single query.
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.PreparedSQL.NotPrepared -- Query prepared above, one-time calculation.
			$ratings_data = $wpdb->get_results( $query, ARRAY_A );

			// Process each rating.
			foreach ( $ratings_data as $rating_data ) {
				$review_ratings = maybe_unserialize( $rating_data['meta_value'] );

				if ( is_array( $review_ratings ) && ! empty( $review_ratings ) ) {
					// Calculate average for this review.
					$review_values = array_filter( $review_ratings, 'is_numeric' );
					$values_count  = count( $review_values );
					if ( $values_count > 0 ) {
						$total_rating += array_sum( $review_values ) / $values_count;
						++$rated_reviews_count;
					}
				}
			}
		}

		// Calculate the aggregate rating (protected against division by zero).
		$aggregate_rating = $rated_reviews_count > 0 ? $total_rating / $rated_reviews_count : 0;

		/**
		 * Filter the aggregate rating before saving.
		 * Used by Pro addon to apply weighted averages for flexible criteria.
		 *
		 * @since 3.8.0
		 * @param float $aggregate_rating    The calculated aggregate rating.
		 * @param int   $user_id             The user ID being recalculated.
		 * @param array $review_ids          Array of review post IDs.
		 * @param int   $rated_reviews_count Number of reviews with ratings.
		 */
		$aggregate_rating = apply_filters( 'bupr_aggregate_rating', $aggregate_rating, $user_id, $review_ids, $rated_reviews_count );

		// Update user meta with the calculated review count and aggregate rating.
		update_user_meta( $user_id, 'bupr_review_count', $review_count );
		update_user_meta( $user_id, 'bupr_aggregate_rating', $aggregate_rating );

	} else {
		// If no reviews are found, reset the user's review count and aggregate rating.
		update_user_meta( $user_id, 'bupr_review_count', 0 );
		update_user_meta( $user_id, 'bupr_aggregate_rating', 0 );

	}
}



/**
 * Recalculate and update review data for a batch of users.
 *
 * This function processes a limited number of users at a time to avoid memory and timeout issues.
 * It uses pagination to process the users in batches.
 *
 * @param int $batch_size The number of users to process in each batch.
 * @param int $paged      The current batch number (for pagination).
 * @return int Rows this page read. Fewer than $batch_size means it was the last page; counting only the valid
 *             IDs instead would end the cycle early whenever a page held a bad member ID.
 */
function bupr_recalculate_user_reviews_batch( $batch_size = 50, $paged = 1 ) {
	global $wpdb;

	$paged = max( 1, (int) $paged );
	// Get users who have reviews (more efficient than getting all users).
	$user_ids = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT DISTINCT pm.meta_value 
         FROM {$wpdb->postmeta} pm
         INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
         WHERE pm.meta_key = 'linked_bp_member'
         AND p.post_type = 'review'
         AND p.post_status = 'publish'
         ORDER BY pm.meta_value ASC
         LIMIT %d, %d",
			( $paged - 1 ) * $batch_size,
			$batch_size
		)
	);
	// Process each user.
	foreach ( $user_ids as $user_id ) {
		if ( ! empty( $user_id ) && is_numeric( $user_id ) ) {
			bupr_recalculate_user_reviews_for_user( $user_id );
		}
	}

	return count( $user_ids );
}

/**
 * Schedule the batch review-recalculation cron.
 *
 * Single source of truth for the schedule (3.8.0). The activation hook no
 * longer schedules this event, and the legacy `wp`-hook hourly registration
 * has been replaced by this guarded `init` registration at the `daily`
 * interval — so the hook is registered exactly once, at one interval, with
 * a deterministic outcome regardless of activation vs. upgrade path.
 */
function bupr_schedule_review_recalculation() {
	if ( ! wp_next_scheduled( 'bupr_cron_recalculate_user_reviews_batch' ) ) {
		wp_schedule_event( time(), 'daily', 'bupr_cron_recalculate_user_reviews_batch' );
	}
}
add_action( 'init', 'bupr_schedule_review_recalculation' );
add_action( 'bupr_cron_recalculate_user_reviews_batch', 'bupr_cron_recalculate_user_reviews_batch' );

/**
 * Perform batch recalculation via cron job.
 */
function bupr_cron_recalculate_user_reviews_batch() {
	$batch_size = 50;
	$paged      = (int) get_option( 'bupr_current_batch', 1 ); // Get current batch from the database.

	$users_processed = bupr_recalculate_user_reviews_batch( $batch_size, $paged );

	if ( $users_processed < $batch_size ) {
		// If fewer users were processed than the batch size, reset the batch counter.
		update_option( 'bupr_current_batch', 1 );
		do_action( 'bupr_recalculation_completed' );
	} else {
		// Otherwise, move to the next batch.
		update_option( 'bupr_current_batch', $paged + 1 );
	}
}

/**
 * Process one batch of the stale-meta repair migration.
 *
 * Unlike bupr_recalculate_user_reviews_batch() (which walks members that
 * currently HAVE published reviews), this walks members that carry the cached
 * `bupr_review_count` usermeta. That set includes orphans whose reviews were
 * deleted under pre-3.8 versions - before delete-time invalidation existed -
 * leaving a stale non-zero count with a 0 aggregate. Recalculating each rebuilds
 * the count/aggregate from the actual review posts (0/0 for a true orphan).
 *
 * @since 3.8.0
 *
 * @param int $batch_size Users to process per batch.
 * @param int $paged      1-based batch number.
 * @return int Number of users processed in this batch.
 */
function bupr_meta_repair_process_batch( $batch_size = 50, $paged = 1 ) {
	global $wpdb;

	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- One-time migration over an indexed meta_key.
	$user_ids = $wpdb->get_col(
		$wpdb->prepare(
			"SELECT user_id FROM {$wpdb->usermeta}
			 WHERE meta_key = 'bupr_review_count'
			 ORDER BY user_id ASC
			 LIMIT %d, %d",
			( $paged - 1 ) * $batch_size,
			$batch_size
		)
	);

	foreach ( $user_ids as $user_id ) {
		if ( is_numeric( $user_id ) ) {
			bupr_recalculate_user_reviews_for_user( (int) $user_id );
		}
	}

	return count( $user_ids );
}

/**
 * Count members carrying the cached review-count usermeta.
 *
 * Used to decide whether the one-time repair migration runs inline (small base)
 * or is deferred to Action Scheduler / WP-Cron (large base).
 *
 * @since 3.8.0
 *
 * @return int Number of users with the `bupr_review_count` usermeta.
 */
function bupr_meta_repair_count_members() {
	global $wpdb;

	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- One-time migration count over an indexed meta_key.
	return (int) $wpdb->get_var(
		"SELECT COUNT(*) FROM {$wpdb->usermeta} WHERE meta_key = 'bupr_review_count'"
	);
}

/**
 * One-time migration: repair stale review count/aggregate usermeta.
 *
 * Reviews deleted under pre-3.8 versions left orphaned `bupr_review_count` /
 * `bupr_aggregate_rating` usermeta (e.g. count stuck at 1 while the aggregate is
 * 0), so a profile renders "0.00/5 (1 review)" with no reviews. Current delete
 * paths invalidate correctly, but nothing repaired meta that had already drifted
 * on existing installs. This runs exactly once per install to rebuild them.
 *
 * Gate is the `bupr_meta_repair_v1_done` flag (set here on completion, and set
 * up-front on fresh activations which have no drift). Deliberately NOT gated on
 * the stored plugin version, since an install may already be on 3.8.0 yet still
 * carry drift caused by an earlier version.
 *
 * @since 3.8.0
 *
 * @return void
 */
function bupr_maybe_run_meta_repair_migration() {
	if ( 'yes' === get_option( 'bupr_meta_repair_v1_done' ) ) {
		return;
	}

	$batch_size = 50;

	// Large base: defer to the background so admin_init is never blocked.
	if ( bupr_meta_repair_count_members() > ( $batch_size * 5 ) ) {
		update_option( 'bupr_meta_repair_paged', 1 );
		bupr_schedule_meta_repair_batch();

		// Mark done so the migration is kicked off exactly once; the scheduled
		// chain owns completion (leaderboard flush) from here.
		update_option( 'bupr_meta_repair_v1_done', 'yes' );
		return;
	}

	// Small base: repair inline right now.
	$paged = 1;
	do {
		$processed = bupr_meta_repair_process_batch( $batch_size, $paged );
		++$paged;
	} while ( $processed >= $batch_size );

	bupr_flush_top_members_cache();
	update_option( 'bupr_meta_repair_v1_done', 'yes' );
}
add_action( 'admin_init', 'bupr_maybe_run_meta_repair_migration' );

/**
 * Queue the next meta-repair batch, preferring Action Scheduler.
 *
 * @since 3.8.0
 *
 * @return void
 */
function bupr_schedule_meta_repair_batch() {
	if ( function_exists( 'as_enqueue_async_action' ) ) {
		as_enqueue_async_action( 'bupr_meta_repair_batch', array(), 'bupr-member-review' );
	} else {
		wp_schedule_single_event( time() + MINUTE_IN_SECONDS, 'bupr_meta_repair_batch' );
	}
}

/**
 * Process one deferred meta-repair batch, then reschedule until complete.
 *
 * @since 3.8.0
 *
 * @return void
 */
function bupr_run_meta_repair_batch() {
	$batch_size = 50;
	$paged      = (int) get_option( 'bupr_meta_repair_paged', 1 );

	$processed = bupr_meta_repair_process_batch( $batch_size, $paged );

	if ( $processed >= $batch_size ) {
		update_option( 'bupr_meta_repair_paged', $paged + 1 );
		bupr_schedule_meta_repair_batch();
	} else {
		// Done: the leaderboard reflects the rebuilt aggregates now.
		delete_option( 'bupr_meta_repair_paged' );
		bupr_flush_top_members_cache();
	}
}
add_action( 'bupr_meta_repair_batch', 'bupr_run_meta_repair_batch' );

/**
 * Update the activity action with youzify.
 *
 * @param mixed  $action   The activity action.
 * @param object $activity The activity object.
 * @return mixed The modified action.
 */
function bupr_youzify_activity_action_wall_posts( $action, $activity ) {
	$action = $activity->action;
	return $action;
}
add_filter( 'youzify_activity_new_post_action', 'bupr_youzify_activity_action_wall_posts', 10, 2 );

/**
 * Check Bp Classic plugin is activated.
 */
function bupr_bp_classic_activate() {
	if ( in_array( 'bp-classic/class-bp-classic.php', (array) get_option( 'active_plugins' ), true ) ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Clear review caches when a review is trashed or deleted.
 *
 * @param int $post_id The post ID being deleted.
 */
function bupr_clear_review_caches( $post_id ) {
	$post_type = get_post_type( $post_id );

	if ( 'review' !== $post_type ) {
		return;
	}

	// Delete this review's BuddyPress notifications so they don't linger as
	// actor-less "posted a review" entries linking nowhere once it's gone.
	if ( function_exists( 'bupr_delete_review_notifications' ) ) {
		bupr_delete_review_notifications( $post_id );
	}

	// Get the reviewed user ID.
	$user_id = get_post_meta( $post_id, 'linked_bp_member', true );

	if ( $user_id ) {
		// Clear user meta cache.
		delete_user_meta( $user_id, 'bupr_last_calculated' );
		delete_user_meta( $user_id, 'bupr_review_count' );
		delete_user_meta( $user_id, 'bupr_aggregate_rating' );

		// Clear transients.
		$transient_keys = array(
			'bupr_user_reviews_' . $user_id,
			'bupr_member_reviews_' . $user_id,
		);

		foreach ( $transient_keys as $key_base ) {
			// Clear potential variations of the transient.
			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
					$wpdb->esc_like( '_transient_' . $key_base ) . '%'
				)
			);
		}

		if ( 'before_delete_post' === current_action() ) {
			// The row still exists here, so recalculating now would count it again.
			// Drop its activity and recalculate once the delete has gone through.
			bupr_delete_review_activity( $post_id );
			add_action(
				'deleted_post',
				function ( $deleted_id ) use ( $post_id, $user_id ) {
					if ( (int) $deleted_id === (int) $post_id ) {
						bupr_recalculate_user_reviews_for_user( $user_id );
						bupr_flush_top_members_cache();
					}
				}
			);
			return;
		}

		// Force recalculation.
		bupr_recalculate_user_reviews_for_user( $user_id );

		// The aggregate meta changed, so the top-members leaderboard is stale.
		bupr_flush_top_members_cache();
	}
}

add_action( 'before_delete_post', 'bupr_clear_review_caches' );
add_action( 'trashed_post', 'bupr_clear_review_caches' );
add_action( 'untrashed_post', 'bupr_clear_review_caches' );

/**
 * Flush the cached [bupr_display_top_members] shortcode results.
 *
 * The shortcode and the widget cache their CAST()-ordered usermeta query in the
 * object cache, keyed on wp_cache_get_last_changed( 'bupr_top_members' ). Moving
 * that token invalidates every attribute variation in O(1).
 *
 * @since 3.8.0
 */
function bupr_flush_top_members_cache() {
	wp_cache_set_last_changed( 'bupr_top_members' );
}
add_action( 'bupr_recalculation_completed', 'bupr_flush_top_members_cache' );
add_action( 'deleted_user', 'bupr_flush_top_members_cache' );

/**
 * Published reviews a member has received, from the cached aggregate (recalculated once if missing).
 *
 * Replaces per-request get_posts( posts_per_page => -1 ) counts, which loaded every
 * review a member has into memory on every page view.
 *
 * @since 3.8.1
 * @param int $user_id Member ID.
 * @return int
 */
function bupr_get_member_review_count( $user_id ) {
	$user_id = (int) $user_id;
	if ( ! $user_id ) {
		return 0;
	}
	$count = get_user_meta( $user_id, 'bupr_review_count', true );
	if ( '' === $count ) {
		bupr_recalculate_user_reviews_for_user( $user_id );
		$count = get_user_meta( $user_id, 'bupr_review_count', true );
	}
	return (int) $count;
}

/**
 * A member's profile URL, with a trailing slash, on every supported BuddyPress.
 *
 * BuddyPress 12 deprecated bp_core_get_user_domain() (a notice on every call under
 * WP_DEBUG), and bp_members_get_user_url() does not exist before 12.
 *
 * @since 3.8.1
 * @param int $user_id User ID.
 * @return string
 */
function bupr_member_url( $user_id ) {
	$url = function_exists( 'bp_members_get_user_url' ) ? bp_members_get_user_url( (int) $user_id ) : bp_core_get_user_domain( (int) $user_id );
	return trailingslashit( (string) $url );
}

/**
 * Whether the Reviewer Roles / Reviewee Roles settings allow one member to review another.
 *
 * An empty list means "every role". A user passes when ANY of their roles is listed.
 * The header button, the Add Review tab, the shortcode picker and the submit all use this check.
 *
 * @since 3.8.1
 * @param int $reviewer_id User submitting the review.
 * @param int $member_id   User being reviewed.
 * @return bool
 */
function bupr_roles_allow_review( $reviewer_id, $member_id ) {
	global $bupr;
	$reviewer = get_userdata( (int) $reviewer_id );
	$member   = get_userdata( (int) $member_id );
	if ( ! $reviewer || ! $member ) {
		return false;
	}
	$reviewer_roles = isset( $bupr['exclude_given_members'] ) && is_array( $bupr['exclude_given_members'] ) ? $bupr['exclude_given_members'] : array();
	$reviewee_roles = isset( $bupr['add_taken_members'] ) && is_array( $bupr['add_taken_members'] ) ? $bupr['add_taken_members'] : array();
	if ( $reviewer_roles && ! array_intersect( (array) $reviewer->roles, $reviewer_roles ) ) {
		return false;
	}
	if ( $reviewee_roles && ! array_intersect( (array) $member->roles, $reviewee_roles ) ) {
		return false;
	}
	return true;
}

/**
 * Number of member reviews whose reports are still open (published or hidden).
 *
 * @since 3.8.1
 * @return int
 */
function bupr_get_flagged_reviews_count() {
	global $wpdb;
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
	return (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(DISTINCT p.ID) FROM {$wpdb->posts} p
			INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
			INNER JOIN {$wpdb->postmeta} mm ON p.ID = mm.post_id AND mm.meta_key = 'linked_bp_member' AND CAST(mm.meta_value AS UNSIGNED) > 0
			WHERE p.post_type = %s AND p.post_status IN ('publish', 'draft')
			AND pm.meta_key = 'bupr_flag_status' AND pm.meta_value = 'flagged'",
			'review'
		)
	);
}

/**
 * Number of member reviews currently hidden because they reached the report threshold.
 *
 * @since 3.8.1
 * @return int
 */
function bupr_get_auto_hidden_reviews_count() {
	global $wpdb;
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
	return (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(DISTINCT p.ID) FROM {$wpdb->posts} p
			INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
			INNER JOIN {$wpdb->postmeta} mm ON p.ID = mm.post_id AND mm.meta_key = 'linked_bp_member' AND CAST(mm.meta_value AS UNSIGNED) > 0
			WHERE p.post_type = %s AND p.post_status = 'draft'
			AND pm.meta_key = 'bupr_auto_hidden' AND pm.meta_value <> ''",
			'review'
		)
	);
}

/**
 * Activity type used for review entries. Youzify only renders its own status type.
 *
 * @since 3.8.1
 * @return string
 */
function bupr_review_activity_type() {
	return defined( 'YOUZIFY_VERSION' ) ? 'activity_status' : 'member_review';
}

/**
 * Whether a post is a member review (Group Reviews shares the review post type).
 *
 * @since 3.8.3
 * @param int|WP_Post $post Post or post ID.
 * @return bool
 */
function bupr_is_member_review( $post ) {
	$post = get_post( $post );
	return $post && 'review' === $post->post_type && (int) get_post_meta( $post->ID, 'linked_bp_member', true ) > 0;
}

/**
 * Meta query clause that keeps a review query to member reviews, the query
 * form of bupr_is_member_review(). Append it to a meta_query, never replace one.
 *
 * @since 3.8.3
 * @return array
 */
function bupr_member_review_meta_clause() {
	return array(
		'key'     => 'linked_bp_member',
		'value'   => 0,
		'compare' => '>',
		'type'    => 'NUMERIC',
	);
}

/**
 * Whether a review was posted anonymously.
 *
 * @since 3.8.3
 * @param int|string $review_id Review post ID.
 * @return bool
 */
function bupr_is_anonymous_review( $review_id ) {
	return 'yes' === get_post_meta( (int) $review_id, 'bupr_anonymous_review_post', true );
}

/**
 * Whether an activity entry is the one recorded for a member review.
 *
 * Checks component, type and that item_id is a review of the member in
 * secondary_item_id, so unrelated activity whose IDs happen to match a
 * review never gets review stars or anonymous masking.
 *
 * @since 3.8.3
 * @param object $activity Activity object.
 * @return bool
 */
function bupr_is_review_activity( $activity ) {
	if ( empty( $activity->item_id ) || empty( $activity->secondary_item_id ) || ! isset( $activity->component, $activity->type ) ) {
		return false;
	}
	if ( buddypress()->members->id !== $activity->component || bupr_review_activity_type() !== $activity->type ) {
		return false;
	}
	return bupr_is_member_review( (int) $activity->item_id )
		&& (int) get_post_meta( (int) $activity->item_id, 'linked_bp_member', true ) === (int) $activity->secondary_item_id;
}

/**
 * Register the member review activity type so it appears in the activity filter.
 *
 * Youzify records reviews as its own status type, which BuddyPress already registers.
 *
 * @since 3.8.3
 */
function bupr_register_activity_actions() {
	if ( defined( 'YOUZIFY_VERSION' ) || ! function_exists( 'bp_activity_set_action' ) ) {
		return;
	}
	bp_activity_set_action(
		buddypress()->members->id,
		'member_review',
		__( 'Posted a member review', 'bp-member-reviews' ),
		false,
		bupr_profile_review_tab_name(),
		array( 'activity', 'member' )
	);
}
add_action( 'bp_register_activity_actions', 'bupr_register_activity_actions' );

/**
 * ID of the activity entry recorded for a review, or 0 when there is none.
 *
 * @since 3.8.1
 * @param int $review_id Review post ID.
 * @return int
 */
function bupr_get_review_activity_id( $review_id ) {
	if ( ! function_exists( 'bp_activity_get_activity_id' ) ) {
		return 0;
	}
	return (int) bp_activity_get_activity_id(
		array(
			'component' => buddypress()->members->id,
			'type'      => bupr_review_activity_type(),
			'item_id'   => (int) $review_id,
		)
	);
}

/**
 * Remove the activity entries recorded for a review.
 *
 * @since 3.8.1
 * @param int $review_id Review post ID.
 */
function bupr_delete_review_activity( $review_id ) {
	if ( ! function_exists( 'bp_activity_delete' ) ) {
		return;
	}
	bp_activity_delete(
		array(
			'component' => buddypress()->members->id,
			'type'      => bupr_review_activity_type(),
			'item_id'   => (int) $review_id,
		)
	);
}

/**
 * Handle review status transitions to trigger cache invalidation.
 *
 * Fires when a review post status changes (draft->publish, pending->publish, etc.).
 *
 * @param string  $new_status New post status.
 * @param string  $old_status Old post status.
 * @param WP_Post $post       Post object.
 */
function bupr_handle_review_status_transition( $new_status, $old_status, $post ) {
	// Only process review post type.
	if ( 'review' !== $post->post_type ) {
		return;
	}

	// Skip if status hasn't changed.
	if ( $new_status === $old_status ) {
		return;
	}

	// A review that stops being public takes its activity entry with it. Re-publishing
	// creates a fresh one through bupr_member_review_after_review_insert.
	if ( 'publish' === $old_status ) {
		bupr_delete_review_activity( $post->ID );
	}

	// An admin re-approving an auto-hidden review clears the auto-hidden marker.
	if ( 'publish' === $new_status ) {
		delete_post_meta( $post->ID, 'bupr_auto_hidden' );
		delete_post_meta( $post->ID, 'bupr_auto_hidden_date' );
	}

	// Get the reviewed user ID.
	$user_id = get_post_meta( $post->ID, 'linked_bp_member', true );

	if ( ! $user_id ) {
		return;
	}

	// Recalculate when transitioning to/from publish status.
	$status_triggers = array( 'publish', 'draft', 'pending', 'trash' );
	if ( in_array( $new_status, $status_triggers, true ) || in_array( $old_status, $status_triggers, true ) ) {
		bupr_recalculate_user_reviews_for_user( $user_id );

		// The aggregate meta changed, so the top-members leaderboard is stale.
		bupr_flush_top_members_cache();
	}
}
add_action( 'transition_post_status', 'bupr_handle_review_status_transition', 10, 3 );

/**
 * Get the default review view slug.
 *
 * @return string The review view slug.
 */
function bupr_default_review_view_slug() {

	$default_slug = apply_filters( 'bupr_default_subnav_slug', 'view' );
	return $default_slug;
}


/**
 * Timezone every date the plugin shows is rendered in: the site timezone, filterable per member.
 *
 * @since 3.8.3
 * @param int $user_id Optional member whose timezone applies. Default: the current user.
 * @return DateTimeZone
 */
function bupr_display_timezone( $user_id = 0 ) {
	$tz = apply_filters( 'bupr_display_timezone', wp_timezone(), $user_id ? (int) $user_id : get_current_user_id() );
	return $tz instanceof DateTimeZone ? $tz : wp_timezone();
}

/**
 * The one date formatter. Times are stored as UTC timestamps; this is the only place they are formatted.
 *
 * @since 3.8.3
 * @param int    $timestamp UTC Unix timestamp.
 * @param string $format    PHP date format. Default: the site date format.
 * @return string
 */
function bupr_date_label( $timestamp, $format = '' ) {
	$format = '' === $format ? get_option( 'date_format' ) : $format;
	return (string) wp_date( $format, (int) $timestamp, bupr_display_timezone() );
}

/**
 * Enqueue the star glyph font and the rules that use it.
 *
 * Stars are drawn from the bundled star glyph font files (subset of Font Awesome Free, SIL OFL 1.1) under their own family name ("BUPR Star"), so no
 * icon stylesheet is loaded and another plugin or the theme registering "font-awesome" (Elementor ships 4.7)
 * cannot change which font or weight the .fas / .far classes get. Inline, because the font URL must be absolute.
 *
 * @since 3.8.3
 */
function bupr_enqueue_star_font() {
	$fonts = plugins_url( 'assets/fonts/', dirname( __DIR__ ) . '/buddypress-member-review.php' );
	$css   = '@font-face{font-family:"BUPR Star";font-style:normal;font-weight:900;font-display:swap;src:url("' . esc_url( $fonts ) . 'bupr-star-solid.woff2") format("woff2"),url("' . esc_url( $fonts ) . 'bupr-star-solid.woff") format("woff")}'
		. '@font-face{font-family:"BUPR Star";font-style:normal;font-weight:400;font-display:swap;src:url("' . esc_url( $fonts ) . 'bupr-star-regular.woff2") format("woff2"),url("' . esc_url( $fonts ) . 'bupr-star-regular.woff") format("woff")}'
		. '.bupr-star-rate.bupr-star-rate,.bupr-star-rate.bupr-star-rate::before{font-family:"BUPR Star",sans-serif !important;font-style:normal;line-height:1}'
		. '.bupr-star-rate.bupr-star-rate.fas{font-weight:900 !important}.bupr-star-rate.bupr-star-rate.far{font-weight:400 !important}'
		. '.bupr-star-rate.fa-star::before{content:"\\f005"}.bupr-star-rate.fa-star-half-alt::before{content:"\\f5c0"}';
	wp_register_style( 'bupr-stars', false, array(), '3.8.3' );
	wp_enqueue_style( 'bupr-stars' );
	wp_add_inline_style( 'bupr-stars', $css );
}

/**
 * Convert a pre-3.8.3 stored stamp (site-local wall clock shifted into a Unix timestamp) to a real UTC timestamp.
 *
 * Before 3.8.3 the plugin stored current_time( 'timestamp' ), which is UTC plus the site offset. Reading it back
 * as UTC (what bupr_date_label() does) would show the time shifted by that offset.
 *
 * @since 3.8.3
 * @param int $stamp Local-shifted stamp.
 * @return int UTC timestamp.
 */
function bupr_local_stamp_to_utc( $stamp ) {
	$stamp = (int) $stamp;
	if ( $stamp <= 0 ) {
		return $stamp;
	}
	try {
		return ( new DateTime( gmdate( 'Y-m-d H:i:s', $stamp ), wp_timezone() ) )->getTimestamp();
	} catch ( Exception $e ) {
		return $stamp;
	}
}

/**
 * One-time upgrade: store reply, report and auto-hide times as UTC.
 *
 * Starts from bupr_maybe_run_utc_migration(), which writes the new plugin version FIRST and passes the highest
 * existing meta_id as $cap. Each run converts only rows with $after < meta_id <= $cap, ascending, 500 at a time,
 * until its time budget is spent, then chains the next run with the last meta_id as an event argument. Rows written
 * after the upgrade have a higher meta_id than $cap and are never touched.
 *
 * The temporary option bupr_utc_migration_state (autoload off, deleted when the chain finishes) holds
 * array( last, cap, at ) so a lost cron event (a cron-clearing plugin, a host reset, a restore) is rescheduled from
 * the last batch instead of leaving rows unconverted. A run that stopped for more than five minutes with no event
 * queued counts as lost; a run in progress refreshes `at`, so admin_init never starts a second one beside it.
 *
 * ponytail: a fatal mid-batch can leave that one batch half converted; the state still holds the previous `last`, so
 * the resume repeats it. Wrap the batch in a transaction if that ever matters.
 *
 * Known limit: a time already stored as UTC before the upgrade starts carries no marker in the same two meta keys,
 * so it cannot be told apart from a local-shifted one and is shifted once more.
 *
 * @since 3.8.3
 * @param int $after Last meta_id already converted.
 * @param int $cap   Highest meta_id that existed when the upgrade started.
 */
function bupr_run_utc_migration( $after = 0, $cap = 0 ) {
	global $wpdb;
	$cursor = (int) $after;
	$cap    = (int) $cap;

	// No saved state means no upgrade is running (it finished, or this event is a late duplicate of one that did);
	// a state that is further along means this event covers ground already done. Either way: do nothing.
	$state = get_option( 'bupr_utc_migration_state' );
	if ( ! is_array( $state ) || ! isset( $state['last'] ) || (int) $state['last'] > $cursor ) {
		return;
	}
	// First thing, before any filter or query: a run that is alive must look fresh to admin_init, however long the
	// state sat idle before WP-Cron picked the event up.
	update_option(
		'bupr_utc_migration_state',
		array(
			'last' => $cursor,
			'cap'  => $cap,
			'at'   => time(),
		),
		false
	);
	$deadline = time() + (int) apply_filters( 'bupr_utc_migration_budget', 15 );

	do {
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- One-time upgrade; meta_key is indexed and the walk is by meta_id.
		$rows = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT meta_id, post_id, meta_key, meta_value FROM {$wpdb->postmeta} WHERE meta_key IN ('bupr_owner_reply_date','bupr_auto_hidden_date','bupr_flags') AND meta_id > %d AND meta_id <= %d ORDER BY meta_id ASC LIMIT 500",
				$cursor,
				$cap
			)
		);
		foreach ( $rows as $row ) {
			$cursor = (int) $row->meta_id;
			if ( 'bupr_flags' === $row->meta_key ) {
				$flags = maybe_unserialize( $row->meta_value );
				if ( ! is_array( $flags ) ) {
					continue;
				}
				foreach ( $flags as $i => $flag ) {
					if ( is_array( $flag ) && ! empty( $flag['date'] ) ) {
						$flags[ $i ]['date'] = bupr_local_stamp_to_utc( $flag['date'] );
					}
				}
				$new = maybe_serialize( $flags );
			} else {
				$new = (string) bupr_local_stamp_to_utc( $row->meta_value );
			}
			// By meta_id: one UPDATE per row, however many rows share the key.
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			$wpdb->update( $wpdb->postmeta, array( 'meta_value' => $new ), array( 'meta_id' => (int) $row->meta_id ) );
			wp_cache_delete( (int) $row->post_id, 'post_meta' );
		}
		if ( count( $rows ) < 500 ) {
			delete_option( 'bupr_utc_migration_state' );
			return;
		}
		update_option(
			'bupr_utc_migration_state',
			array(
				'last' => $cursor,
				'cap'  => $cap,
				'at'   => time(),
			),
			false
		);
	} while ( time() < $deadline );

	// Out of time: the next run picks up after the last converted row, still capped.
	wp_schedule_single_event( time(), 'bupr_utc_migration', array( $cursor, $cap ) );
}
add_action( 'bupr_utc_migration', 'bupr_run_utc_migration', 10, 2 );

/**
 * Start the upgrade above on a site that still holds pre-3.8.3 stamps.
 *
 * The version moves forward here, before any row is touched, so no later admin_init (or a second request racing
 * this one) can start a second pass over the same rows.
 *
 * @since 3.8.3
 */
function bupr_maybe_run_utc_migration() {
	global $wpdb;
	if ( version_compare( (string) get_option( 'bupr_plugin_version', '0' ), '3.8.3', '>=' ) ) {
		// A chain that lost its cron event: carry on from the last finished batch.
		$state = get_option( 'bupr_utc_migration_state' );
		if ( is_array( $state ) && isset( $state['last'], $state['cap'], $state['at'] ) && time() - (int) $state['at'] >= (int) apply_filters( 'bupr_utc_migration_stale', 300 ) ) {
			foreach ( (array) _get_cron_array() as $events ) {
				if ( isset( $events['bupr_utc_migration'] ) ) {
					return;
				}
			}
			wp_schedule_single_event( time(), 'bupr_utc_migration', array( (int) $state['last'], (int) $state['cap'] ) );
		}
		return;
	}
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- One read at upgrade time; meta_key is indexed.
	$cap = (int) $wpdb->get_var( "SELECT MAX(meta_id) FROM {$wpdb->postmeta} WHERE meta_key IN ('bupr_owner_reply_date','bupr_auto_hidden_date','bupr_flags')" );
	update_option( 'bupr_plugin_version', BUPR_PLUGIN_VERSION );
	if ( $cap > 0 ) {
		update_option(
			'bupr_utc_migration_state',
			array(
				'last' => 0,
				'cap'  => $cap,
				'at'   => time(),
			),
			false
		);
		wp_schedule_single_event( time(), 'bupr_utc_migration', array( 0, $cap ) );
	}
}
add_action( 'admin_init', 'bupr_maybe_run_utc_migration' );

/**
 * Default text of the four email fields on the General settings tab.
 *
 * The settings form, the review email, the approval email and the global settings all read their defaults from
 * here, so what an owner sees in the form before saving is what is actually sent.
 *
 * @since 3.8.3
 * @param string $key review_email_subject, review_email_message, review_approve_email_subject or review_approve_email_message.
 * @return string
 */
function bupr_email_default( $key ) {
	switch ( $key ) {
		case 'review_email_subject':
			/* translators: [site-name] is a token replaced at send time - keep it verbatim. */
			return __( 'You Have Received a New Review on [site-name]', 'bp-member-reviews' );
		case 'review_email_message':
			/* translators: [user-name], [reviewer-name], [review-link] and [site-name] are tokens replaced at send time - keep them verbatim. */
			return __(
				'Hello [user-name],

We hope this message finds you well.

We are pleased to inform you that [reviewer-name] has recently submitted a review on your profile. To view the full review and respond, please click on the link below:

[review-link]

We appreciate your engagement in our community and value your contributions.

Best regards,
The [site-name] Team',
				'bp-member-reviews'
			);
		case 'review_approve_email_subject':
			/* translators: [site-name] is a token replaced at send time - keep it verbatim. */
			return __( 'A New Review Requires Your Approval on [site-name]', 'bp-member-reviews' );
		case 'review_approve_email_message':
			/* translators: [site-admin], [site-name] and [review-aproval-link] are tokens replaced at send time - keep them verbatim. */
			return __(
				'Hello [site-admin],

Greetings!

You have received a new review on [site-name] that is pending approval. To review and approve the submission, please click on the link below:

[review-aproval-link]

Thank you for helping us maintain a vibrant and engaged community.

Best regards,
The [site-name] Team',
				'bp-member-reviews'
			);
	}
	return '';
}

/**
 * An email field as the owner saved it, or its default when it is empty or never saved.
 *
 * @since 3.8.3
 * @param string $key Same keys as bupr_email_default().
 * @return string
 */
function bupr_email_setting( $key ) {
	$saved = get_option( 'bupr_admin_general_options' );
	return ( is_array( $saved ) && ! empty( $saved[ $key ] ) ) ? (string) $saved[ $key ] : bupr_email_default( $key );
}

/**
 * Repair criterion names that were turned into slugs ("Member Response" became "memberresponse").
 *
 * Before 3.8.3 the settings sanitizer ran sanitize_key() on the criterion names when Member Reviews Pro synced its
 * criteria to bupr_admin_settings. The real names are still in Pro's own list (bupr_pro_rating_criteria), so the
 * slug keys are mapped back to them, in the settings and in the stored ratings of any review written meanwhile.
 * Safe to repeat: it only touches a key that is exactly the slug of a known name, and it keeps no state of its own.
 * The star-key pass is checked at most once a day (a transient), in batches of 200 reviews.
 *
 * @since 3.8.3
 * @param array|string $map Optional slug => name map (cron continuation). Default: built from Pro's list.
 */
function bupr_repair_slug_criteria( $map = array() ) {
	if ( ! $map ) {
		$map = array(); // WordPress passes an empty string when admin_init has no arguments.
		$pro = get_option( 'bupr_pro_rating_criteria' );
		foreach ( is_array( $pro ) ? $pro : array() as $criterion ) {
			$name = is_array( $criterion ) ? (string) ( $criterion['label'] ?? '' ) : '';
			if ( '' !== $name && sanitize_key( $name ) !== $name ) {
				$map[ sanitize_key( $name ) ] = $name;
			}
		}
	}
	if ( ! $map ) {
		return;
	}

	// 1. The criteria list.
	$settings = get_option( 'bupr_admin_settings' );
	$fields   = is_array( $settings ) && isset( $settings['profile_rating_fields'] ) && is_array( $settings['profile_rating_fields'] ) ? $settings['profile_rating_fields'] : array();
	$fixed    = array();
	$changed  = false;
	foreach ( $fields as $key => $enabled ) {
		if ( isset( $map[ $key ] ) && ! isset( $fields[ $map[ $key ] ] ) ) {
			$fixed[ $map[ $key ] ] = $enabled;
			$changed               = true;
		} else {
			$fixed[ $key ] = $enabled;
		}
	}
	if ( $changed ) {
		$settings['profile_rating_fields'] = $fixed;
		update_option( 'bupr_admin_settings', $settings );
	}

	// 2. Ratings stored under a slug key.
	if ( ! $changed && false !== get_transient( 'bupr_star_keys_checked' ) ) {
		return;
	}
	global $wpdb;
	$deadline = time() + 10;
	$cursor   = array_fill_keys( array_keys( $map ), 0 );
	do {
		$done = true;
		foreach ( $map as $slug => $name ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Repair pass, at most daily; meta_key is indexed.
			$rows = $wpdb->get_results( $wpdb->prepare( "SELECT meta_id, post_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key = 'profile_star_rating' AND meta_value LIKE %s AND meta_id > %d ORDER BY meta_id ASC LIMIT 200", '%"' . $wpdb->esc_like( $slug ) . '"%', $cursor[ $slug ] ) );
			foreach ( $rows as $row ) {
				$cursor[ $slug ] = (int) $row->meta_id;
				$ratings         = maybe_unserialize( $row->meta_value );
				// A review that somehow has both keys is left alone rather than losing a rating.
				if ( ! is_array( $ratings ) || ! isset( $ratings[ $slug ] ) || isset( $ratings[ $name ] ) ) {
					continue;
				}
				$rekeyed = array();
				foreach ( $ratings as $k => $v ) {
					$rekeyed[ $k === $slug ? $name : $k ] = $v;
				}
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
				$wpdb->update( $wpdb->postmeta, array( 'meta_value' => maybe_serialize( $rekeyed ) ), array( 'meta_id' => (int) $row->meta_id ) );
				wp_cache_delete( (int) $row->post_id, 'post_meta' );
			}
			if ( 200 === count( $rows ) ) {
				$done = false;
			}
		}
	} while ( ! $done && time() < $deadline );

	if ( ! $done ) {
		wp_schedule_single_event( time() + MINUTE_IN_SECONDS, 'bupr_repair_slug_criteria', array( $map ) );
		return;
	}
	set_transient( 'bupr_star_keys_checked', 1, DAY_IN_SECONDS );
}
add_action( 'admin_init', 'bupr_repair_slug_criteria' );
add_action( 'bupr_repair_slug_criteria', 'bupr_repair_slug_criteria' );

/**
 * The active review mode: "ratings" unless an add-on (Member Reviews Pro) says otherwise.
 *
 * @since 3.8.3
 * @return string ratings, testimonials or endorsements.
 */
function bupr_review_mode() {
	return (string) apply_filters( 'bupr_review_mode', 'ratings' );
}

/**
 * Meta query clauses that keep posts of another review mode out of a ratings list.
 *
 * Endorsement requests and given endorsements are review posts too (meta bupr_endorsement_type), and Pro marks
 * testimonial and endorsement reviews with bupr_review_mode. After the site goes back to Star Ratings they must not
 * appear as blank review cards or be counted. In Endorsements mode nothing is excluded.
 *
 * @since 3.8.3
 * @return array Meta query clauses (empty when nothing is excluded).
 */
function bupr_mode_meta_query() {
	$mode = bupr_review_mode();
	if ( 'endorsements' === $mode ) {
		return array();
	}
	$clauses = array(
		array(
			'key'     => 'bupr_endorsement_type',
			'compare' => 'NOT EXISTS',
		),
	);
	if ( 'ratings' === $mode ) {
		$clauses[] = array(
			'key'     => 'bupr_review_mode',
			'compare' => 'NOT EXISTS',
		);
	}
	return $clauses;
}

/**
 * The same exclusion as bupr_mode_meta_query(), as SQL for the recalculation query.
 *
 * @since 3.8.3
 * @param string $alias Alias of the posts table in the query.
 * @return string An " AND NOT EXISTS ..." fragment, or an empty string.
 */
function bupr_mode_sql_exclusion( $alias = 'p' ) {
	global $wpdb;
	$mode = bupr_review_mode();
	if ( 'endorsements' === $mode ) {
		return '';
	}
	$keys = 'ratings' === $mode ? "'bupr_endorsement_type','bupr_review_mode'" : "'bupr_endorsement_type'";
	return " AND NOT EXISTS ( SELECT 1 FROM {$wpdb->postmeta} ex WHERE ex.post_id = {$alias}.ID AND ex.meta_key IN ({$keys}) )";
}

/**
 * Rebuild every member's cached review count and rating now (first 50 members) and in the background (the rest).
 *
 * Used when the review mode changes, because the cached numbers count only the posts of the active mode.
 *
 * @since 3.8.3
 */
function bupr_recalculate_all_members() {
	$processed = bupr_meta_repair_process_batch( 50, 1 );
	if ( $processed >= 50 ) {
		update_option( 'bupr_meta_repair_paged', 2 );
		bupr_schedule_meta_repair_batch();
		return;
	}
	bupr_flush_top_members_cache();
}

/**
 * Open a member-facing notice in the markup of the active BuddyPress template pack, so it looks like the
 * BuddyPress notice on the same page and every theme draws exactly one icon.
 *
 * Nouveau matches core's common/notices/template-notices.php: an <aside class="bp-feedback ..."> with a .bp-icon
 * span and no id. An id="message" there made themes that style div#message (Reign 8.1.0 and earlier) draw a
 * second icon. Legacy keeps the <div id="message"> it styles. Close with bupr_notice_close().
 *
 * @since 3.8.3
 * @param string $type        Notice type: info, success, error or warning.
 * @param string $extra_class Optional extra classes for the wrapper.
 */
function bupr_notice_open( $type, $extra_class = '' ) {
	$classes = trim( $type . ' ' . $extra_class );
	if ( 'nouveau' === bp_get_theme_package_id() ) {
		printf( '<aside class="%s bp-feedback bp-messages bp-template-notice"><span class="bp-icon" aria-hidden="true"></span>', esc_attr( $classes ) );
		return;
	}
	printf( '<div id="message" class="%s">', esc_attr( $classes ) );
}

/**
 * Close a notice opened with bupr_notice_open().
 *
 * @since 3.8.3
 */
function bupr_notice_close() {
	echo 'nouveau' === bp_get_theme_package_id() ? '</aside>' : '</div>';
}
