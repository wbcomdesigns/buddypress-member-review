<?php
/**
 * Owner reply on a review: the reply, or the reply form for the reviewed member.
 * Shared by the reviews list and the single review view.
 *
 * Expects $review_id.
 *
 * @package BuddyPress_Member_Reviews
 * @since   3.8.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $bupr;

// Only show if enabled in settings.
$owner_replies_enabled = isset( $bupr['enable_owner_replies'] ) ? $bupr['enable_owner_replies'] : 'yes';
if ( 'yes' === $owner_replies_enabled ) :
	$owner_reply      = get_post_meta( $review_id, 'bupr_owner_reply', true );
	$owner_reply_date = get_post_meta( $review_id, 'bupr_owner_reply_date', true );
	$linked_member_id = get_post_meta( $review_id, 'linked_bp_member', true );
	$current_user_id  = get_current_user_id();
	// A locked password-protected review shows no reply form until the password is entered.
	$can_reply        = ( (int) $linked_member_id === $current_user_id ) && empty( $owner_reply ) && ! post_password_required( $review_id );
	?>
<div class="bupr-reply-section" data-review="<?php echo esc_attr( $review_id ); ?>">
	<?php if ( ! empty( $owner_reply ) ) : ?>
		<?php
		$owner_data   = get_userdata( $linked_member_id );
		$owner_name   = $owner_data ? $owner_data->display_name : __( 'Profile Owner', 'bp-member-reviews' );
		$owner_avatar = bp_core_fetch_avatar(
			array(
				'item_id' => $linked_member_id,
				'object'  => 'user',
				'html'    => false,
			)
		);
		?>
		<div class="bupr-owner-reply">
			<div class="bupr-reply-header">
				<img src="<?php echo esc_url( $owner_avatar ); ?>" class="avatar avatar-32" alt="<?php echo esc_attr( $owner_name ); ?>" width="32" height="32">
				<span class="bupr-reply-author"><?php echo esc_html( $owner_name ); ?></span>
				<span class="bupr-reply-badge"><?php esc_html_e( 'Owner Response', 'bp-member-reviews' ); ?></span>
				<?php if ( $owner_reply_date ) : ?>
					<span class="bupr-reply-date"><?php echo esc_html( bupr_date_label( (int) $owner_reply_date ) ); ?></span>
				<?php endif; ?>
			</div>
			<div class="bupr-reply-content"><?php echo wp_kses_post( wpautop( $owner_reply ) ); ?></div>
		</div>
	<?php elseif ( $can_reply ) : ?>
		<div class="bupr-reply-form-wrapper">
			<button type="button" class="bupr-show-reply-form button" data-review="<?php echo esc_attr( $review_id ); ?>">
				<?php esc_html_e( 'Reply to this review', 'bp-member-reviews' ); ?>
			</button>
			<div class="bupr-reply-form" style="display: none;">
				<textarea class="bupr-reply-textarea" rows="3" placeholder="<?php esc_attr_e( 'Write your response...', 'bp-member-reviews' ); ?>"></textarea>
				<div class="bupr-reply-actions">
					<button type="button" class="bupr-submit-reply button" data-review="<?php echo esc_attr( $review_id ); ?>">
						<?php esc_html_e( 'Submit Reply', 'bp-member-reviews' ); ?>
					</button>
					<button type="button" class="bupr-cancel-reply button">
						<?php esc_html_e( 'Cancel', 'bp-member-reviews' ); ?>
					</button>
					<span class="bupr-reply-spinner" style="display: none;"></span>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
<?php endif; // End owner replies enabled check. ?>
