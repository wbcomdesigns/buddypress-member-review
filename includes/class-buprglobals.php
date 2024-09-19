<?php
/**
 * Class to define all the global variables related to the plugin.
 *
 * @since    1.0.9
 * @package  BuddyPress_Member_Reviews
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'BUPRGlobals' ) ) {

	/**
	 * Class to add global variables for this plugin.
	 *
	 * @since    1.0.9
	 * @access   public
	 */
	class BUPRGlobals {

		/**
		 * Constructor to hook into init action.
		 *
		 * @since    1.0.9
		 * @access   public
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'bupr_review_globals' ), 10 );
		}

		/**
		 * Define all global variables related to this plugin.
		 *
		 * @since    1.0.9
		 * @access   public
		 */
		public function bupr_review_globals() {
			global $bupr, $wp_roles;

			// Ensure $wp_roles is set correctly.
			if ( ! isset( $wp_roles ) || ! is_object( $wp_roles ) || ! isset( $wp_roles->roles ) ) {
				$wp_roles = wp_roles();
			}

			// Get settings from options.
			$bupr_general_settings  = get_option( 'bupr_admin_general_options', array() );
			$bupr_criteria_settings = get_option( 'bupr_admin_settings', array() );
			$bupr_display_settings  = get_option( 'bupr_admin_display_options', array() );
			$name                   = array_keys( $wp_roles->roles );

			/**
			 * Global variables for general settings.
			 */
			$auto_approve_reviews    = $bupr_general_settings['bupr_auto_approve_reviews'] ?? 'no';
			$reviews_per_page        = $bupr_general_settings['profile_reviews_per_page'] ?? 3;
			$dir_view_ratings        = $bupr_general_settings['bupr_member_dir_reviews'] ?? 'no';
			$dir_view_review_btn     = $bupr_general_settings['bupr_member_dir_add_reviews'] ?? 'no';
			$allow_email             = $bupr_general_settings['bupr_allow_email'] ?? 'no';
			$allow_notification      = $bupr_general_settings['bupr_allow_notification'] ?? 'no';
			$allow_update            = $bupr_general_settings['bupr_allow_update'] ?? 'no';
			$exclude_given_members   = $bupr_general_settings['bupr_exc_member'] ?? array();
			$add_taken_members       = $bupr_general_settings['bupr_add_member'] ?? array();
			$multi_reviews           = $bupr_general_settings['bupr_multi_reviews'] ?? 'no';
			$hide_button             = $bupr_general_settings['bupr_hide_review_button'] ?? 'no';
			$anonymous_reviews       = $bupr_general_settings['bupr_enable_anonymous_reviews'] ?? 'no';
			$review_activity         = $bupr_general_settings['bupr_enable_review_activity'] ?? 'no';
			$review_email_subject    = $bupr_general_settings['review_email_subject'] ?? '';
			$review_email_message    = $bupr_general_settings['review_email_message'] ?? '';

			/**
			 * Global variables for display settings.
			 */
			$review_label        = $bupr_display_settings['bupr_review_title'] ?? esc_html__( 'Review', 'bp-member-reviews' );
			$review_label_plural = $bupr_display_settings['bupr_review_title_plural'] ?? esc_html__( 'Reviews', 'bp-member-reviews' );
			$rating_color        = $bupr_display_settings['bupr_star_color'] ?? '#FFC400';

			/**
			 * Global variables for criteria settings.
			 */
			$bupr_multi_criteria_allowed = $bupr_criteria_settings['profile_multi_rating_allowed'] ?? '0';
			$active_rating_fields        = $bupr_criteria_settings['profile_rating_fields'] ?? array();

			// Define global variables for use throughout the plugin.
			$bupr = array(
				'anonymous_reviews'      => $anonymous_reviews,
				'auto_approve_reviews'   => $auto_approve_reviews,
				'dir_view_ratings'       => $dir_view_ratings,
				'dir_view_review_btn'    => $dir_view_review_btn,
				'multi_reviews'          => $multi_reviews,
				'hide_review_button'     => $hide_button,
				'reviews_per_page'       => $reviews_per_page,
				'allow_email'            => $allow_email,
				'allow_notification'     => $allow_notification,
				'allow_update'           => $allow_update,
				'exclude_given_members'  => $exclude_given_members,
				'add_taken_members'      => $add_taken_members,
				'rating_color'           => $rating_color,
				'review_label'           => $review_label,
				'review_label_plural'    => $review_label_plural,
				'multi_criteria_allowed' => $bupr_multi_criteria_allowed,
				'active_rating_fields'   => $active_rating_fields,
				'review_activity'        => $review_activity,
			);
		}
	}

	// Initialize the class.
	new BUPRGlobals();
}
