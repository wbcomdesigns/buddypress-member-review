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
        ",
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
 */
function bupr_recalculate_user_reviews_batch( $batch_size = 50, $paged = 1 ) {
	global $wpdb;

	$processed_count = 0;
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
			++$processed_count;
		}
	}

	return $processed_count;
}

/**
 * Schedule a cron job for recalculating user reviews in batches.
 */
function bupr_schedule_review_recalculation() {
	if ( ! wp_next_scheduled( 'bupr_cron_recalculate_user_reviews_batch' ) ) {
		wp_schedule_event( time(), 'hourly', 'bupr_cron_recalculate_user_reviews_batch' );
	}
}
add_action( 'wp', 'bupr_schedule_review_recalculation' );

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

		// Force recalculation.
		bupr_recalculate_user_reviews_for_user( $user_id );
	}
}

add_action( 'before_delete_post', 'bupr_clear_review_caches' );
add_action( 'trashed_post', 'bupr_clear_review_caches' );
add_action( 'untrashed_post', 'bupr_clear_review_caches' );

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

	// Get the reviewed user ID.
	$user_id = get_post_meta( $post->ID, 'linked_bp_member', true );

	if ( ! $user_id ) {
		return;
	}

	// Recalculate when transitioning to/from publish status.
	$status_triggers = array( 'publish', 'draft', 'pending', 'trash' );
	if ( in_array( $new_status, $status_triggers, true ) || in_array( $old_status, $status_triggers, true ) ) {
		bupr_recalculate_user_reviews_for_user( $user_id );
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
