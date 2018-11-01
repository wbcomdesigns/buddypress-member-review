<?php
/**
 * Class to define all the global variables related to plugin.
 *
 * @since    1.0.9
 * @author   Wbcom Designs
 * @package BuddyPress_Member_Reviews
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'BUPRGlobals' ) ) {
	/**
	 * Class to add global variables of this plugin.
	 *
	 * @since    1.0.9
	 * @access   public
	 * @author   Wbcom Designs
	 */
	class BUPRGlobals {

		/**
		 * Constructor.
		 *
		 * @since    1.0.9
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'bupr_review_globals' ), 10 );
		}

		/**
		 * All the global variables related to this plugin.
		 *
		 * @since    1.0.9
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function bupr_review_globals() {
			global $bupr;
			$bupr_general_settings  = get_option( 'bupr_admin_general_options' );
			$bupr_criteria_settings = get_option( 'bupr_admin_settings' );
			$bupr_display_settings  = get_option( 'bupr_admin_display_options' );

			/**** Global variable values for General settings */
			if ( ! empty( $bupr_general_settings ) ) {
				if ( array_key_exists( 'bupr_auto_approve_reviews', $bupr_general_settings ) ) {
					$auto_approve_reviews = $bupr_general_settings['bupr_auto_approve_reviews'];
				}
				if ( array_key_exists( 'profile_reviews_per_page', $bupr_general_settings ) ) {
					$reviews_per_page = $bupr_general_settings['profile_reviews_per_page'];
				}
				if ( array_key_exists( 'bupr_member_dir_reviews', $bupr_general_settings ) ) {
					$dir_view_ratings = $bupr_general_settings['bupr_member_dir_reviews'];
				}
				if ( array_key_exists( 'bupr_member_dir_add_reviews', $bupr_general_settings ) ) {
					$dir_view_review_btn = $bupr_general_settings['bupr_member_dir_add_reviews'];
				}
				if ( array_key_exists( 'bupr_allow_email', $bupr_general_settings ) ) {
					$allow_email = $bupr_general_settings['bupr_allow_email'];
				}
				if ( array_key_exists( 'bupr_allow_notification', $bupr_general_settings ) ) {
					$allow_notification = $bupr_general_settings['bupr_allow_notification'];
				}
				if ( array_key_exists( 'bupr_exc_member', $bupr_general_settings ) ) {
					$exclude_members = $bupr_general_settings['bupr_exc_member'];
				}
				if ( array_key_exists( 'bupr_multi_reviews', $bupr_general_settings ) ) {
					$multi_reviews = $bupr_general_settings['bupr_multi_reviews'];
				}
				if ( array_key_exists( 'bupr_enable_anonymous_reviews', $bupr_general_settings ) ) {
					$anonymous_reviews = $bupr_general_settings['bupr_enable_anonymous_reviews'];
				}
				if ( empty( $anonymous_reviews ) ) {
					$anonymous_reviews = 'no';
				}
				if ( empty( $dir_view_ratings ) ) {
					$dir_view_ratings = 'no';
				}
				if ( empty( $dir_view_review_btn ) ) {
					$dir_view_review_btn = 'no';
				}
				if ( empty( $multi_reviews ) ) {
					$multi_reviews = 'no';
				}
				if ( empty( $auto_approve_reviews ) ) {
					$auto_approve_reviews = 'no';
				}
				if ( empty( $reviews_per_page ) ) {
					$reviews_per_page = 3;
				}
				if ( empty( $allow_email ) ) {
					$allow_email = 'no';
				}
				if ( empty( $allow_notification ) ) {
					$allow_notification = 'no';
				}
				if ( empty( $exclude_members ) ) {
					$exclude_members = array();
				}
			} else {
				$anonymous_reviews    = 'yes';
				$dir_view_ratings     = 'yes';
				$dir_view_review_btn  = 'yes';
				$multi_reviews        = 'yes';
				$reviews_per_page     = 3;
				$allow_email          = 'yes';
				$allow_notification   = 'yes';
				$exclude_members      = array();
				$auto_approve_reviews = 'yes';
			}

			/**** Global variable values for Display settings */
			if ( ! empty( $bupr_display_settings ) ) {
				if ( array_key_exists( 'bupr_review_title', $bupr_display_settings ) ) {
					$review_label = $bupr_display_settings['bupr_review_title'];
				}
				if ( array_key_exists( 'bupr_review_title_plural', $bupr_display_settings ) ) {
					$review_label_plural = $bupr_display_settings['bupr_review_title_plural'];
				}
				if ( array_key_exists( 'bupr_star_color', $bupr_display_settings ) ) {
					$rating_color = $bupr_display_settings['bupr_star_color'];
				}
				if ( empty( $review_label ) ) {
					$review_label = esc_html__( 'Review', 'bp-member-reviews' );
				}
				if ( empty( $review_label_plural ) ) {
					$review_label_plural = esc_html__( 'Reviews', 'bp-member-reviews' );
				}
				if ( empty( $rating_color ) ) {
					$rating_color = '#FFC400';
				}
			} else {
				$review_label        = esc_html__( 'Review', 'bp-member-reviews' );
				$review_label_plural = esc_html__( 'Reviews', 'bp-member-reviews' );
				$rating_color        = '#FFC400';
			}

			/**** Global variable values for Criteria settings */			
			if ( ! empty( $bupr_criteria_settings ) ) {
				if ( array_key_exists( 'profile_multi_rating_allowed', $bupr_criteria_settings ) ) {
					$bupr_multi_criteria_allowed = $bupr_criteria_settings['profile_multi_rating_allowed'];
				}
				if ( array_key_exists( 'profile_rating_fields', $bupr_criteria_settings ) ) {
					$active_rating_fields = $bupr_criteria_settings['profile_rating_fields'];
				}
				if ( empty( $bupr_multi_criteria_allowed ) ) {
					$bupr_multi_criteria_allowed = '0';
				}
				if ( empty( $active_rating_fields ) ) {
					$active_rating_fields = array();
				}
			} else {
				$bupr_multi_criteria_allowed = '1';
				$active_rating_fields = array();
			}

			$bupr = array(
				'anonymous_reviews'      => $anonymous_reviews,
				'auto_approve_reviews'   => $auto_approve_reviews,
				'dir_view_ratings'       => $dir_view_ratings,
				'dir_view_review_btn'    => $dir_view_review_btn,
				'multi_reviews'          => $multi_reviews,
				'reviews_per_page'       => $reviews_per_page,
				'allow_email'            => $allow_email,
				'allow_notification'     => $allow_notification,
				'exclude_members'        => $exclude_members,
				'rating_color'           => $rating_color,
				'review_label'           => $review_label,
				'review_label_plural'    => $review_label_plural,    
				'multi_criteria_allowed' => $bupr_multi_criteria_allowed,    
				'active_rating_fields'   => $active_rating_fields,
			);

		}

	}
	new BUPRGlobals();
}
