<?php
/**
 * Class to serve notification Calls.
 *
 * @since    1.0.0
 * @author   Wbcom Designs
 * @package BuddyPress_Member_Reviews
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'BUPR_Notifications' ) ) {
	/**
	 * Class to add notifications.
	 *
	 * @since    1.0.0
	 * @author   Wbcom Designs
	 */
	class BUPR_Notifications extends BP_Component {

		protected $_bupr_component_name = 'bupr_bp_review';

		/**
		 * Constructor.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function __construct() {
			$this->slug = $this->_bupr_component_name;

			parent::start(
				$this->_bupr_component_name, esc_html__( 'Member Reviews', 'bp-member-reviews' ), dirname( __FILE__ )
			);

			buddypress()->active_components[ $this->_bupr_component_name ] = '1';
		}

		/**
		 * Setup globals.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @param    array $args  Globals array.
		 * @author   Wbcom Designs
		 */
		public function setup_globals( $args = array() ) {
			parent::setup_globals(
				array(
					'slug'                  => $this->_bupr_component_name,
					'has_directory'         => false,
					'notification_callback' => 'bupr_format_notifications',
				)
			);
		}

		/**
		 * Function to send review notification.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function setup_actions() {
			// When review added.
			add_action( 'bupr_sent_review_notification', array( $this, 'bupr_add_review_notification' ), 99, 2 );

			parent::setup_actions();
		}

		/**
		 * Function to get component name.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function component_name() {
			return $this->_bupr_component_name;
		}

		/**
		 * Adding notifications for new review posted by any buddypress member.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @param    string $bupr_memberid Member id.
		 * @param    string $review_id     Review id.
		 * @author   Wbcom Designs
		 */
		public function bupr_add_review_notification( $bupr_memberid, $review_id ) {
			if ( bp_is_active( 'notifications' ) ) {
				$current_user = wp_get_current_user();
				$member_id    = $current_user->ID;
				$args         = array(
					'user_id'           => $bupr_memberid,
					'item_id'           => $review_id,
					'secondary_item_id' => $bupr_memberid,
					'component_name'    => $this->_bupr_component_name,
					'component_action'  => 'bupr_add_review_action',
					'date_notified'     => bp_core_current_time(),
					'is_new'            => 1,
					'allow_duplicate'   => true,
				);
				bp_notifications_add_notification( $args );
			}
		}

		/**
		 * Formatting notifications for review when added
		 *
		 * @since    1.0.0
		 * @access   public
		 * @param    string $review_id     Review id.
		 * @param    string $member_id     Member id.
		 * @param    string $user_id       User id.
		 * @param    string $format        Format.
		 * @author   Wbcom Designs
		 */
		public function bupr_add_review_notification_format( $review_id, $member_id, $user_id, $format = '' ) {
			global $bp;
			$admin_id             = bp_displayed_user_id();
			$post_author_id       = get_post_field( 'post_author', $review_id );
			$admin_info           = get_userdata( $member_id );
			
			if($admin_info){
				$admin_name  = $admin_info->display_name;
			}else{
				$admin_name  = '';
			}
			
			$user_info = get_userdata( $post_author_id );
			$user_name = '';
			if($user_info){
				$user_name = $user_info->display_name;				
			}
			$bupr_display_settings = get_option( BUPR_DISPLAY_OPTIONS, true );
			if ( ! empty( $bupr_display_settings ) && ! empty( $bupr_display_settings['bupr_review_title'] ) ) {
				$bupr_review_title = $bupr_display_settings['bupr_review_title'];
			}
			
			$notification_link    = home_url() . '/members/' . $admin_name . '/reviews/view/' . $review_id;
			$notification_title   = sprintf( esc_html__( 'A new %s posted.', 'bp-member-reviews' ), esc_html( $bupr_review_title ) );
			$notification_content = sprintf( esc_html__( '%1$s posted a %2$s.', 'bp-member-reviews' ), esc_html( $user_name ), esc_html( $bupr_review_title ) );

			if ( 'string' == $format ) {
				$return = sprintf( "<a href='%s' title='%s'>%s</a>", esc_url( $notification_link ), esc_attr( $notification_title ), esc_html( $notification_content ) );
			} else {
				$return = array(
					'text' => $notification_content,
					'link' => $notification_link,
				);
			}
			return apply_filters( 'bupr_add_review_notification_format', $return, $admin_id, $format );
		}
	}
}

add_filter( 'bp_notifications_get_notifications_for_user', 'bupr_format_notifications', 10, 5 );
/**
 * Formatting notifications for review when added.
 *
 * @since    1.0.0
 * @access   public
 * @param    string $action     Action.
 * @param    string $item_id    Item id.
 * @param    string $secondary_item_id       Secondary item id.
 * @param    string $user_id        User id.
 * @param    string $format        Format.
 * @author   Wbcom Designs
 */
function bupr_format_notifications( $action, $item_id, $secondary_item_id, $user_id, $format = 'string' ) {
	if ( bp_is_active( 'notifications' ) ) {
		switch ( $action ) {

			case 'bupr_add_review_action':
					$return = buddypress()->bupr_bp_review->bupr_add_review_notification_format( $item_id, $secondary_item_id, $user_id, $format );
				break;
			default:
					$return = '';
				break;
		}
		if ( $return ) {
			return $return;
		}
	}
}
