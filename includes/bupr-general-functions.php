<?php
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
        extract( $args ); // Extract the args into variables.
    endif;

    $template_file = bupr_locate_template( $template_name, $template_path, $default_path );

    if ( ! file_exists( $template_file ) ) :
        _doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', esc_html( $template_file ) ), '1.0.0' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        return;
    endif;

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
function buddypress_member_review_bp_activity_triggers( $triggers ) {
    $triggers[ __( 'BuddyPress Member Review', 'bp-member-reviews' ) ] = array(
        'gamipress_bp_member_review' => __( 'Give Member Review', 'bp-member-reviews' ),
    );

    return $triggers;
}
add_filter( 'gamipress_activity_triggers', 'buddypress_member_review_bp_activity_triggers' );

/**
 * Get the user ID for the review trigger in GamiPress.
 *
 * @param int    $user_id The current user ID.
 * @param string $trigger The trigger identifier.
 * @param array  $args    Trigger arguments.
 * @return int Modified user ID.
 */
function buddypress_member_review_trigger_get_user_id( $user_id, $trigger, $args ) {
    if ( 'gamipress_bp_member_review' === $trigger ) {
        $user_id = $args[0];
    }

    return $user_id;
}
add_filter( 'gamipress_trigger_get_user_id', 'buddypress_member_review_trigger_get_user_id', 10, 3 );


/**
 * Handle review submission and update user meta with review data.
 *
 * This function is triggered after a review is submitted and will calculate
 * the average rating for the reviewed user. It will then update the 
 * user's metadata to store the total review count and the new aggregate rating.
 *
 * @param int $review_id       ID of the submitted review.
 * @param int $reviewed_user_id ID of the user who received the review.
 */
add_action( 'bupr_member_review_after_review_insert', 'bupr_handle_review_submission', 10, 2 );

function bupr_handle_review_submission( $review_id, $reviewed_user_id ) {
    // Get the star rating from the review meta
    $review_ratings = get_post_meta( $review_id, 'profile_star_rating', true );

    // Calculate the average rating if it's an array of multiple criteria
    if ( is_array( $review_ratings ) && ! empty( $review_ratings ) ) {
        $total_rating = array_sum( $review_ratings );
        $rating_count = count( $review_ratings );
        $average_rating = $total_rating / $rating_count;
    } else {
        // If it's a single value, cast it to an integer
        $average_rating = (int) $review_ratings;
    }

    // Update the user meta with the new review data
    bupr_update_user_meta_for_reviews( $reviewed_user_id, $average_rating );
}

/**
 * Update the reviewed user's meta data for review count and aggregate rating.
 *
 * This function will update two user meta fields:
 * 1. 'bupr_review_count' - total number of reviews the user has received.
 * 2. 'bupr_aggregate_rating' - the average rating based on all reviews received.
 *
 * @param int $reviewed_user_id ID of the user being reviewed.
 * @param float $new_rating     Rating given in the current review.
 */
function bupr_update_user_meta_for_reviews( $reviewed_user_id, $new_rating ) {
    // Retrieve current review count and aggregate rating from user meta
    $current_review_count = (int) get_user_meta( $reviewed_user_id, 'bupr_review_count', true );
    $current_aggregate_rating = (float) get_user_meta( $reviewed_user_id, 'bupr_aggregate_rating', true );

    // Increment the review count
    $new_review_count = $current_review_count + 1;
    update_user_meta( $reviewed_user_id, 'bupr_review_count', $new_review_count );

    // Calculate the new aggregate rating based on the new review
    $new_aggregate_rating = ( ( $current_aggregate_rating * $current_review_count ) + $new_rating ) / $new_review_count;
    update_user_meta( $reviewed_user_id, 'bupr_aggregate_rating', $new_aggregate_rating );
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
    // Set up user query arguments for pagination
    $args = array(
        'number' => $batch_size,
        'paged'  => $paged,
    );

    $users = get_users( $args );

    foreach ( $users as $user ) {
        $user_id = $user->ID;

        // Fetch all reviews for this user
        $bupr_args = array(
            'post_type'   => 'review',
            'post_status' => 'publish',
            'meta_query'  => array(
                array(
                    'key'     => 'linked_bp_member',
                    'value'   => $user_id,
                    'compare' => '=',
                ),
            ),
        );
        $reviews = get_posts( $bupr_args );

        // Initialize variables to calculate aggregate rating
        $total_rating = 0;
        $review_count = count( $reviews );

        if ( $review_count > 0 ) {
            foreach ( $reviews as $review ) {
                $review_ratings = get_post_meta( $review->ID, 'profile_star_rating', true );

                if ( is_array( $review_ratings ) && ! empty( $review_ratings ) ) {
                    $total_rating += array_sum( $review_ratings ) / count( $review_ratings );
                } else {
                    $total_rating += (int) $review_ratings;
                }
            }

            // Calculate the aggregate rating
            $aggregate_rating = $total_rating / $review_count;

            // Update user meta
            update_user_meta( $user_id, 'bupr_review_count', $review_count );
            update_user_meta( $user_id, 'bupr_aggregate_rating', $aggregate_rating );
        }
    }

    return count( $users ); // Return the number of users processed in this batch
}

/**
 * Admin interface to trigger batch recalculation of user reviews.
 */
function bupr_trigger_recalculate_reviews_batch() {
    $batch_size = 50; // Number of users to process per batch
    $paged = isset( $_GET['paged'] ) ? (int) $_GET['paged'] : 1;

    $users_processed = bupr_recalculate_user_reviews_batch( $batch_size, $paged );

    if ( $users_processed < $batch_size ) {
        echo '<div class="notice notice-success is-dismissible"><p>' . __( 'User reviews recalculation completed.', 'bp-member-reviews' ) . '</p></div>';
    } else {
        $next_page = $paged + 1;
        echo '<div class="notice notice-info"><p>' . __( 'Processing batch ', 'bp-member-reviews' ) . $paged . '...</p></div>';
        echo '<script>window.location.href = "' . admin_url( 'tools.php?page=bupr-recalculate-reviews&paged=' . $next_page ) . '";</script>';
    }
}

/**
 * Add a submenu page to trigger batch recalculation.
 */
function bupr_add_admin_recalculate_reviews_batch_menu() {
    add_submenu_page(
        'tools.php',
        __( 'Recalculate User Reviews in Batches', 'bp-member-reviews' ),
        __( 'Recalculate Reviews (Batch)', 'bp-member-reviews' ),
        'manage_options',
        'bupr-recalculate-reviews',
        'bupr_trigger_recalculate_reviews_batch'
    );
}
add_action( 'admin_menu', 'bupr_add_admin_recalculate_reviews_batch_menu' );


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
    $paged = (int) get_option( 'bupr_current_batch', 1 ); // Get current batch from the database

    $users_processed = bupr_recalculate_user_reviews_batch( $batch_size, $paged );

    if ( $users_processed < $batch_size ) {
        // If fewer users were processed than the batch size, reset the batch counter
        update_option( 'bupr_current_batch', 1 );
    } else {
        // Otherwise, move to the next batch
        update_option( 'bupr_current_batch', $paged + 1 );
    }
}

/**
 * Unschedule the cron event on plugin deactivation.
 */
function bupr_unschedule_review_recalculation() {
    $timestamp = wp_next_scheduled( 'bupr_cron_recalculate_user_reviews_batch' );
    if ( $timestamp ) {
        wp_unschedule_event( $timestamp, 'bupr_cron_recalculate_user_reviews_batch' );
    }
    delete_option( 'bupr_current_batch' ); // Clean up the stored batch number
}
register_deactivation_hook( __FILE__, 'bupr_unschedule_review_recalculation' );



