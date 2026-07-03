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

/**
 * Filter to add BuddyPress Member Review to registered notification components.
 *
 * @since 1.0.0
 *
 * @param array $component_names Array of registered component names.
 * @return array Modified array with buddypress_member_review component added.
 */
function bupr_filter_notifications_get_registered_components( $component_names = array() ) {

	// Force $component_names to be an array.
	if ( ! is_array( $component_names ) ) {
		$component_names = array();
	}

	// Add 'buddypress_member_review' component to registered components array.
	array_push( $component_names, 'buddypress_member_review' );

	// Return component's with 'buddypress_member_review' appended.
	return $component_names;
}
add_filter( 'bp_notifications_get_registered_components', 'bupr_filter_notifications_get_registered_components', 10 );



add_filter( 'bp_notifications_get_notifications_for_user', 'bupr_add_review_notification_format', 10, 7 );
/**
 * Formatting notifications for review when added.
 *
 * @since    1.0.0
 * @access   public
 *
 * @param    string $action                Action.
 * @param    string $item_id               Review id.
 * @param    string $secondary_item_id     Secondary item id.
 * @param    int    $total_items           Total items count.
 * @param    string $format                Notification format.
 * @param    string $component_action_name Component action name.
 * @param    string $component_name        Component name.
 * @return   string|array Formatted notification.
 * @author   Wbcom Designs
 */
function bupr_add_review_notification_format( $action, $item_id, $secondary_item_id, $total_items, $format, $component_action_name, $component_name ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
	global $bp, $bupr;
	$post_status = get_post_status( $item_id ); // getting review status.

	if ( 'bupr_add_review_action' === $component_action_name ) {

		$anonymous_review = get_post_meta( $item_id, 'bupr_anonymous_review_post', true );
		$user_name        = '';
		if ( 'yes' === $anonymous_review ) {
			$user_name = __( 'An anonymous user', 'bp-member-reviews' );
		} else {
			$post_author_id = get_post_field( 'post_author', $item_id );
			$admin_info     = get_userdata( $secondary_item_id );
			if ( $admin_info ) {
				$admin_name = $admin_info->user_nicename;
			} else {
				$admin_name = '';
			}

			$user_info = get_userdata( $post_author_id );

			if ( $user_info ) {
				$user_name = $user_info->display_name;
			}
		}

		$user_id              = bp_loggedin_user_id();
		$notification_link    = '';
		$notification_content = '';
		$linked_member_id     = get_post_meta( $item_id, 'linked_bp_member', true );

		$linked_user_info = get_userdata( $linked_member_id );
		$linked_user_name = '';

		if ( $linked_user_info ) {
			$linked_user_name = $linked_user_info->display_name;
		}
		// Check if bp_members_get_user_url() exists and use it; otherwise, fall back to bp_core_get_user_domain().
		if ( is_array( $bupr ) ) {
			if ( function_exists( 'bp_members_get_user_url' ) ) {
				// Use bp_members_get_user_url() if it exists (for BuddyPress v12.0.0 and above).
				$notification_link = bp_members_get_user_url( $linked_member_id ) . sanitize_title( $bupr['review_label_plural'] ) . '/view/' . $item_id;
			} else {
				// Fall back to bp_core_get_user_domain() for older versions of BuddyPress.
				$notification_link = bp_core_get_user_domain( $linked_member_id ) . sanitize_title( $bupr['review_label_plural'] ) . '/view/' . $item_id;
			}

			/* translators: %s: */
			$notification_title = sprintf( esc_html__( 'A new %s posted.', 'bp-member-reviews' ), esc_html( $bupr['review_label'] ) );
			/* translators: %s: */
			$notification_content = sprintf( esc_html__( '%1$s posted a %2$s', 'bp-member-reviews' ), esc_html( $user_name ), esc_html( strtolower( $bupr['review_label'] ) ) );
		}
		if ( 'draft' === $post_status ) {
			/* translators: %s: */
			$notification_content = sprintf( esc_html__( '( Pending Approval )  %1$s posted a %2$s', 'bp-member-reviews' ), esc_html( $user_name ), esc_html( strtolower( $bupr['review_label'] ) ) );
			$notification_link    = admin_url() . 'post.php?post=' . $item_id . '&action=edit';

			bupr_buddypress_notification_marked_as_read(); // function to mark read pending approval notifications.
		}
		$notification_link = add_query_arg( array( 'referer' => 'user_notification' ), $notification_link );          // For identification of mark as read action.
		$notification_link = add_query_arg( array( 'id' => $item_id ), $notification_link );
		$notification_link = wp_nonce_url( $notification_link, 'mark-as-read-notification-' . $user_id );     // for security purpose when mark as read notification.
		// Prepare the return value based on the specified format.
		if ( 'string' === $format ) {
			// Ensure that the notification content is not empty before creating the link.
			if ( ! empty( $notification_content ) ) {
				$return = sprintf( "<a href='%s' title='%s'>%s</a>", esc_url( $notification_link ), esc_attr( $notification_title ), esc_html( $notification_content ) );
			} else {
				// Return an empty string if there is no content.
				$return = '';
			}
		} else {
			// Return an array format.
			$return = array(
				'text' => $notification_content,
				'link' => $notification_link,
			);
		}

		return apply_filters( 'bupr_add_review_notification_format', $return, $user_id, $format );

	}
	return $action;
}

/**
 * Add BuddyPress notification when a new review is submitted.
 *
 * @param integer $bupr_memberid The ID of the member who received the review.
 * @param integer $review_id     The ID of the review post.
 * @return void
 */
function bupr_buddypress_add_notification( $bupr_memberid, $review_id ) {

	// Get author information.
	$current_user = wp_get_current_user();
	$member_id    = $current_user->ID;

	if ( function_exists( 'bp_notifications_add_notification' ) ) {

		// Dedupe: a single review must notify a given recipient only once.
		// The status-transition path (draft->publish on approval) and the
		// submission path can both fire `bupr_sent_review_notification` for the
		// same review, and a re-save/re-approve can fire it again. Bail if a
		// notification already exists for this (review, recipient, action),
		// ignoring secondary_item_id/is_new so a read-but-present notification
		// still blocks a duplicate.
		if ( class_exists( 'BP_Notifications_Notification' ) ) {
			$existing = BP_Notifications_Notification::get(
				array(
					'user_id'          => $bupr_memberid,
					'item_id'          => $review_id,
					'component_name'   => 'buddypress_member_review',
					'component_action' => 'bupr_add_review_action',
				)
			);

			if ( ! empty( $existing ) ) {
				return;
			}
		}

		// Get some reply information.
		$args = array(
			'user_id'           => $bupr_memberid,
			'item_id'           => $review_id,
			'secondary_item_id' => $member_id,
			'component_name'    => 'buddypress_member_review',
			'component_action'  => 'bupr_add_review_action',
			'date_notified'     => bp_core_current_time(),
			'is_new'            => 1,
			'allow_duplicate'   => false,
		);
		bp_notifications_add_notification( $args );
	} else {
		return;
	}
}
add_action( 'bupr_sent_review_notification', 'bupr_buddypress_add_notification', 10, 2 );

/**
 * Delete all BuddyPress notifications tied to a review.
 *
 * Called when a review is trashed or permanently deleted so its notifications
 * do not linger. Orphaned notifications render actor-less ("posted a review")
 * and link nowhere once the underlying review is gone.
 *
 * @since 3.8.0
 *
 * @param int $review_id The review post ID.
 * @return void
 */
function bupr_delete_review_notifications( $review_id ) {
	$review_id = absint( $review_id );

	if ( ! $review_id || ! class_exists( 'BP_Notifications_Notification' ) ) {
		return;
	}

	// Removes the notification for every recipient (reviewed member + the
	// admin approval notice) in one query, keyed on the review item id.
	BP_Notifications_Notification::delete(
		array(
			'item_id'          => $review_id,
			'component_name'   => 'buddypress_member_review',
			'component_action' => 'bupr_add_review_action',
		)
	);
}

add_action( 'bp_actions', 'bupr_buddypress_notification_marked_as_read' );

/**
 * Mark notifications as read when users click on notification.
 *
 * @since 1.0.0
 * @author   Wbcom Designs
 * @return bool|void False if verification fails, void otherwise.
 */
function bupr_buddypress_notification_marked_as_read() {

	$current_user_id = bp_loggedin_user_id();

	$wp_nonce  = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';
	$review_id = isset( $_GET['id'] ) ? absint( wp_unslash( $_GET['id'] ) ) : 0;
	$is_user   = bp_is_user();

	if ( ! wp_verify_nonce( $wp_nonce, "mark-as-read-notification-{$current_user_id}" ) ) {
		return false;
	}

	if ( empty( $_GET['referer'] ) || 'user_notification' !== $_GET['referer'] ) {
		return false;
	}
	$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : 0;
	if ( $post_id > 0 && $review_id === $post_id ) { // If redirected to the review edit page assign bp_is_user to mark notification as read (in case of pending approval).
		$is_user = (bool) 1;
	}

	if ( ! $is_user || ! is_user_logged_in() ) {
		return false;
	}

	BP_Notifications_Notification::update(
		array( 'is_new' => false ),
		array(
			'component_action' => 'bupr_add_review_action',
			'user_id'          => $current_user_id,
			'item_id'          => $review_id,
		)
	);
}
