<?php
/**
 * BuddyPress Member Review general tab.
 *
 * @package BuddyPress_Member_Reviews
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* admin setting on dashboard */
global $bupr;

/* translators: [user-name], [reviewer-name], [review-link] and [site-name] are tokens replaced at send time - keep them verbatim. */
$message = __(
	'Hello [user-name],

We hope this message finds you well.

We are pleased to inform you that [reviewer-name] has recently submitted a review on your profile. To view the full review and respond, please click on the link below:

[review-link]

We appreciate your engagement in our community and value your contributions.

Best regards,
The [site-name] Team',
	'bp-member-reviews'
);

/* translators: [site-admin], [site-name] and [review-aproval-link] are tokens replaced at send time - keep them verbatim. */
$approve_message = __(
	'Hello [site-admin],

Greetings!

You have received a new review on [site-name] that is pending approval. To review and approve the submission, please click on the link below:

[review-aproval-link]

Thank you for helping us maintain a vibrant and engaged community.

Best regards,
The [site-name] Team',
	'bp-member-reviews'
);
$bupr_admin_general = get_option( 'bupr_admin_general_options' );

/* translators: [site-name] is a token replaced at send time - keep it verbatim. */
$review_email_subject         = ( ! empty( $bupr_admin_general['review_email_subject'] ) ) ? $bupr_admin_general['review_email_subject'] : __( 'You Have Received a New Review on [site-name]', 'bp-member-reviews' );
$review_email_message         = ( ! empty( $bupr_admin_general['review_email_message'] ) ) ? $bupr_admin_general['review_email_message'] : $message;
/* translators: [site-name] is a token replaced at send time - keep it verbatim. */
$review_approve_email_subject = ( ! empty( $bupr_admin_general['review_approve_email_subject'] ) ) ? $bupr_admin_general['review_approve_email_subject'] : __( 'A New Review Requires Your Approval on [site-name]', 'bp-member-reviews' );
$review_approve_email_message = ( ! empty( $bupr_admin_general['review_approve_email_message'] ) ) ? $bupr_admin_general['review_approve_email_message'] : $approve_message;

// get all user for exclude for review.
$user_roles = array_reverse( get_editable_roles() );
?>
<div class="wbcom-tab-content">
	<div class="wbcom-wrapper-admin">
		<div class="wbcom-admin-title-section">
			<h3><?php esc_html_e( 'General Settings', 'bp-member-reviews' ); ?></h3>
		</div>
		<div class="wbcom-admin-option-wrap wbcom-admin-option-wrap-view">
			<form method="post" action="options.php">
				<?php
				settings_fields( 'bupr_admin_general_options' );
				do_settings_sections( 'bupr_admin_general_options' );
				?>
				<div class="form-table">
					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="bupr-multi-review">
								<?php esc_html_e( 'Multiple Reviews', 'bp-member-reviews' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Allow users to submit multiple reviews to the same user.', 'bp-member-reviews' ); ?></p>
						</div>
						<div class="wbcom-settings-section-options">
							<label class="bupr-switch">
								<!-- Hidden field to default the value to 'no' if unchecked -->
								<input type="hidden" name="bupr_admin_general_options[bupr_multi_reviews]" value="no">
								<input name="bupr_admin_general_options[bupr_multi_reviews]" type="checkbox" id="bupr-multi-review" <?php checked( esc_attr( $bupr['multi_reviews'] ), 'yes' ); ?> value="yes">
								<div class="bupr-slider bupr-round"></div>
							</label>
						</div>
					</div>
					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="bupr-hide-review-button">
								<?php esc_html_e( 'Show Review Button', 'bp-member-reviews' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Display the "Add Review" button on member profile headers.', 'bp-member-reviews' ); ?></p>
						</div>
						<div class="wbcom-settings-section-options">
							<label class="bupr-switch">
								<input type="hidden" name="bupr_admin_general_options[bupr_hide_review_button]" value="no">
								<input name="bupr_admin_general_options[bupr_hide_review_button]" type="checkbox" id="bupr-hide-review-button" <?php checked( esc_attr( $bupr['hide_review_button'] ), 'yes' ); ?> value="yes">
								<div class="bupr-slider bupr-round"></div>
							</label>
						</div>
					</div>
					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="bupr_review_auto_approval">
								<?php esc_html_e( 'Auto Approve Reviews', 'bp-member-reviews' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Automatically approve reviews without requiring manual approval.', 'bp-member-reviews' ); ?>
							</p>
						</div>
						<div class="wbcom-settings-section-options">
							<label class="bupr-switch">
								<!-- Hidden field to handle unchecked state -->
								<input type="hidden" name="bupr_admin_general_options[bupr_auto_approve_reviews]" value="no">
								<input type="checkbox" id="bupr_review_auto_approval" name="bupr_admin_general_options[bupr_auto_approve_reviews]" <?php checked( esc_attr( $bupr['auto_approve_reviews'] ), 'yes' ); ?> value="yes">
								<div class="bupr-slider bupr-round"></div>
							</label>
						</div>
					</div>

					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="bupr_member_dir_reviews">
								<?php esc_html_e( 'Member Directory Ratings', 'bp-member-reviews' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Display member ratings on the member directory page.', 'bp-member-reviews' ); ?>
							</p>
						</div>
						<div class="wbcom-settings-section-options">
							<label class="bupr-switch">
								<!-- Hidden field to handle unchecked state -->
								<input type="hidden" name="bupr_admin_general_options[bupr_member_dir_reviews]" value="no">
								<input type="checkbox" id="bupr_member_dir_reviews" name="bupr_admin_general_options[bupr_member_dir_reviews]" <?php checked( esc_attr( $bupr['dir_view_ratings'] ), 'yes' ); ?> value="yes">
								<div class="bupr-slider bupr-round"></div>
							</label>
						</div>
					</div>

					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="bupr_allow_email">
								<?php esc_html_e( 'Email Notifications', 'bp-member-reviews' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Send an email notification to members when someone reviews their profile.', 'bp-member-reviews' ); ?>
							</p>
						</div>
						<div class="wbcom-settings-section-options">
							<label class="bupr-switch">
								<!-- Hidden field to handle unchecked state -->
								<input type="hidden" name="bupr_admin_general_options[bupr_allow_email]" value="no">
								<input type="checkbox" id="bupr_allow_email" name="bupr_admin_general_options[bupr_allow_email]" <?php checked( esc_attr( $bupr['allow_email'] ), 'yes' ); ?> value="yes">
								<div class="bupr-slider bupr-round"></div>
							</label>
						</div>
					</div>

					<div class="bgr-row wbcom-settings-section-wrap review-deny-email-section">
						<div class="wbcom-settings-section-options-heading">
							<label for="review_deny_email_subject">
								<?php esc_html_e( 'Email Subject', 'bp-member-reviews' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Enter the subject line for review notification emails.', 'bp-member-reviews' ); ?></p>
						</div>
						<div class="wbcom-settings-section-options">
							<input id="review_deny_email_subject" class="large-text" name="bupr_admin_general_options[review_email_subject]" value="<?php echo esc_attr( $review_email_subject ); ?>" type="text" placeholder="<?php esc_attr_e( 'Please enter review email subject.', 'bp-member-reviews' ); ?>">
						</div>
					</div>
					<div class="bgr-row wbcom-settings-section-wrap review-deny-email-section">
						<div class="wbcom-settings-section-options-heading">
							<label for="review-email-message">
								<?php esc_html_e( 'Email Message', 'bp-member-reviews' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Enter the message content for review notification emails.', 'bp-member-reviews' ); ?></p>
						</div>
						<div class="wbcom-settings-section-options">
							<?php
							wp_editor(
								$review_email_message,
								'review-email-message',
								array(
									'media_buttons' => false,
									'textarea_name' => 'bupr_admin_general_options[review_email_message]',
								)
							);
							?>
						</div>					
					</div>
					<?php
					// Always rendered so the Auto Approve switch can show/hide them without a save.
					$bupr_approval_hidden = ( isset( $bupr_admin_general['bupr_auto_approve_reviews'] ) && 'no' === $bupr_admin_general['bupr_auto_approve_reviews'] ) ? '' : ' bupr-show-if-allowed';
					?>
					<!-- admin email section -->
					<div class="bgr-row wbcom-settings-section-wrap review-approve-email-section<?php echo esc_attr( $bupr_approval_hidden ); ?>">
							<div class="wbcom-settings-section-options-heading">
								<label for="review_approve_email_subject">
									<?php esc_html_e( 'Approval Email Subject', 'bp-member-reviews' ); ?>
								</label>
								<p class="description"><?php esc_html_e( 'Enter the subject line for review approval notification emails.', 'bp-member-reviews' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<input id="review_approve_email_subject" class="large-text" name="bupr_admin_general_options[review_approve_email_subject]" value="<?php echo esc_attr( $review_approve_email_subject ); ?>" type="text" placeholder="<?php esc_attr_e( 'Please enter review approval email subject.', 'bp-member-reviews' ); ?>">
							</div>
						</div>
						<div class="bgr-row wbcom-settings-section-wrap review-approve-email-section<?php echo esc_attr( $bupr_approval_hidden ); ?>">
					<div class="wbcom-settings-section-options-heading">
						<label for="review-approve-email-message">
							<?php esc_html_e( 'Approval Email Message', 'bp-member-reviews' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Enter the message content for review approval notification emails.', 'bp-member-reviews' ); ?></p>
					</div>
					<div class="wbcom-settings-section-options">
							<?php
							wp_editor(
								$review_approve_email_message,
								'review-approve-email-message',
								array(
									'media_buttons' => false,
									'textarea_name' => 'bupr_admin_general_options[review_approve_email_message]',
								)
							);
							?>
					</div>
						</div>
					<!-- admin email section end -->
					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="bupr_review_notification">
								<?php esc_html_e( 'BuddyPress Notifications', 'bp-member-reviews' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Notify members through BuddyPress when they receive a new review.', 'bp-member-reviews' ); ?>
							</p>
						</div>
						<div class="wbcom-settings-section-options">
							<label class="bupr-switch">
								<!-- Hidden field to handle unchecked state -->
								<input type="hidden" name="bupr_admin_general_options[bupr_allow_notification]" value="no">
								<input type="checkbox" id="bupr_review_notification" name="bupr_admin_general_options[bupr_allow_notification]" <?php checked( esc_attr( $bupr['allow_notification'] ), 'yes' ); ?> value="yes">
								<div class="bupr-slider bupr-round"></div>
							</label>
						</div>
					</div>

					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="bupr_review_update">
								<?php esc_html_e( 'Update Review', 'bp-member-reviews' ); ?>
							</label>
							<p class="description">
								<?php esc_html_e( 'Allow members to update or modify their reviews.', 'bp-member-reviews' ); ?>
							</p>
						</div>
						<div class="wbcom-settings-section-options">
							<label class="bupr-switch">
								<!-- Hidden field to handle unchecked state -->
								<input type="hidden" name="bupr_admin_general_options[bupr_allow_update]" value="no">
								<input type="checkbox" id="bupr_review_update" name="bupr_admin_general_options[bupr_allow_update]" <?php checked( esc_attr( $bupr['allow_update'] ), 'yes' ); ?> value="yes">
								<div class="bupr-slider bupr-round"></div>
							</label>
						</div>
					</div>

					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="profile_reviews_per_page">
								<?php esc_html_e( 'Reviews Per Page', 'bp-member-reviews' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Number of reviews to display per page.', 'bp-member-reviews' ); ?></p>
						</div>
						<div class="wbcom-settings-section-options">
							<input id="profile_reviews_per_page" class="small-text" name="bupr_admin_general_options[profile_reviews_per_page]" step="1" min="1" value="<?php echo esc_attr( $bupr['reviews_per_page'] ); ?>" type="number">
							<?php esc_html_e( 'Reviews', 'bp-member-reviews' ); ?>
						</div>
					</div>
					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="bupr_exc_member">
								<?php esc_html_e( 'Reviewer Roles', 'bp-member-reviews' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Select user roles that can submit reviews. A member with any of the selected roles can. An empty list allows every role.', 'bp-member-reviews' ); ?></p>
						</div>
						<div class="wbcom-settings-section-options">
							<select name="bupr_admin_general_options[bupr_exc_member][]" id="bupr_exc_member" class="bupr_excluding_member" multiple>
								<?php
								// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- $role is used locally in foreach loop.
								foreach ( $user_roles as $role => $details ) {
									$name = translate_user_role( $details['name'] );
									if ( ! empty( $bupr['exclude_given_members'] ) ) {
										if ( in_array( $role, $bupr['exclude_given_members'], true ) ) {
											?>
												<option value="<?php echo esc_attr( $role ); ?>" <?php echo 'selected = "selected"'; ?>><?php echo esc_html( $name ); ?></option>
											<?php
										} else {
											?>
												<option value='<?php echo esc_attr( $role ); ?>'><?php echo esc_html( $name ); ?></option>
											<?php
										}
									} else {
										?>
											<option value="<?php echo esc_attr( $role ); ?>"><?php echo esc_html( $name ); ?></option>
										<?php
									}
								}
								?>
							</select>
						</div>
					</div>
					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="bupr_add_member">
								<?php esc_html_e( 'Reviewee Roles', 'bp-member-reviews' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Select user roles that can receive reviews. A member with any of the selected roles can. An empty list allows every role.', 'bp-member-reviews' ); ?></p>
						</div>
						<div class="wbcom-settings-section-options">
							<select name="bupr_admin_general_options[bupr_add_member][]" id="bupr_add_member" class="bupr_adding_member" multiple>
								<?php
								// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- $role is used locally in foreach loop.
								foreach ( $user_roles as $role => $details ) {
									$name = translate_user_role( $details['name'] );
									if ( ! empty( $bupr['add_taken_members'] ) ) {
										if ( in_array( $role, $bupr['add_taken_members'], true ) ) {
											?>
												<option value="<?php echo esc_attr( $role ); ?>" <?php echo 'selected = "selected"'; ?>><?php echo esc_html( $name ); ?></option>
											<?php
										} else {
											?>
												<option value='<?php echo esc_attr( $role ); ?>'><?php echo esc_html( $name ); ?></option>
											<?php
										}
									} else {
										?>
											<option value="<?php echo esc_attr( $role ); ?>"><?php echo esc_html( $name ); ?></option>
										<?php
									}
								}


								?>
							</select>
						</div>
					</div>
					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="bupr_enable_anonymous_reviews">
								<?php esc_html_e( 'Anonymous Reviews', 'bp-member-reviews' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Allow users to submit reviews anonymously.', 'bp-member-reviews' ); ?></p>
						</div>
						<div class="wbcom-settings-section-options">
							<label class="bupr-switch">
								<input type="checkbox" id="bupr_enable_anonymous_reviews" value="yes" name="bupr_admin_general_options[bupr_enable_anonymous_reviews]" <?php checked( esc_attr( $bupr['anonymous_reviews'] ), 'yes' ); ?>>
								<div class="bupr-slider bupr-round"></div>
							</label>
						</div>
					</div>
					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="bupr_enable_review_activity">
								<?php esc_html_e( 'Activity Stream', 'bp-member-reviews' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Post reviews to the BuddyPress activity stream.', 'bp-member-reviews' ); ?></p>
						</div>
						<div class="wbcom-settings-section-options">
							<label class="bupr-switch">
								<input type="checkbox" id="bupr_enable_review_activity" value="yes" name="bupr_admin_general_options[bupr_enable_review_activity]" <?php checked( esc_attr( $bupr['review_activity'] ), 'yes' ); ?>>
								<div class="bupr-slider bupr-round"></div>
							</label>
						</div>
					</div>

					<!-- Owner Replies Section -->
					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="bupr_enable_owner_replies">
								<?php esc_html_e( 'Owner Replies', 'bp-member-reviews' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Allow profile owners to respond once to each review they receive.', 'bp-member-reviews' ); ?></p>
						</div>
						<div class="wbcom-settings-section-options">
							<label class="bupr-switch">
								<input type="hidden" name="bupr_admin_general_options[bupr_enable_owner_replies]" value="no">
								<input type="checkbox" id="bupr_enable_owner_replies" value="yes" name="bupr_admin_general_options[bupr_enable_owner_replies]" <?php checked( isset( $bupr['enable_owner_replies'] ) ? esc_attr( $bupr['enable_owner_replies'] ) : 'yes', 'yes' ); ?>>
								<div class="bupr-slider bupr-round"></div>
							</label>
						</div>
					</div>

					<!-- Reporting Section -->
					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="bupr_enable_flagging">
								<?php esc_html_e( 'Review Reporting', 'bp-member-reviews' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Allow users to report inappropriate reviews for admin moderation.', 'bp-member-reviews' ); ?></p>
						</div>
						<div class="wbcom-settings-section-options">
							<label class="bupr-switch">
								<input type="hidden" name="bupr_admin_general_options[bupr_enable_flagging]" value="no">
								<input type="checkbox" id="bupr_enable_flagging" value="yes" name="bupr_admin_general_options[bupr_enable_flagging]" <?php checked( isset( $bupr['enable_flagging'] ) ? esc_attr( $bupr['enable_flagging'] ) : 'yes', 'yes' ); ?>>
								<div class="bupr-slider bupr-round"></div>
							</label>
						</div>
					</div>

					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="bupr_flag_threshold">
								<?php esc_html_e( 'Auto-Hide Threshold', 'bp-member-reviews' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Automatically hide reviews after this many reports. Set to 0 to disable.', 'bp-member-reviews' ); ?></p>
						</div>
						<div class="wbcom-settings-section-options">
							<input id="bupr_flag_threshold" class="small-text" name="bupr_admin_general_options[bupr_flag_threshold]" step="1" min="0" value="<?php echo esc_attr( isset( $bupr['flag_threshold'] ) ? $bupr['flag_threshold'] : 5 ); ?>" type="number">
							<?php esc_html_e( 'Reports', 'bp-member-reviews' ); ?>
						</div>
					</div>
				</div>
				<?php submit_button(); ?>
			</form>
		</div>
	</div>
</div>
