<?php
/**
 * Pro Features tab template - Shows Pro settings as read-only preview.
 *
 * When Pro is NOT active: Shows disabled/read-only preview of Pro features
 * When Pro IS active: Delegates to Pro addon settings
 *
 * @package BuddyPress_Member_Reviews
 * @since 3.8.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Check if Pro addon is active and licensed.
$is_pro_active   = class_exists( 'BuddyPress_Member_Review_Pro' ) && defined( 'BUPR_PRO_VERSION' );
$is_pro_licensed = $is_pro_active && class_exists( 'BUPR_Pro_Features' ) && BUPR_Pro_Features::is_pro_active();

// If Pro is active and licensed, let Pro addon render its settings.
if ( $is_pro_licensed ) :
?>
<div class="wbcom-tab-content">
	<div class="wbcom-admin-title-section">
		<h3><?php esc_html_e( 'Pro Features', 'bp-member-reviews' ); ?></h3>
	</div>
	<div class="wbcom-admin-option-wrap wbcom-admin-option-wrap-view">
		<div class="wbcom-settings-section-wrap" style="background: #d4edda; border-left: 4px solid #28a745; padding: 15px; margin-bottom: 20px;">
			<p style="margin: 0;">
				<span class="dashicons dashicons-yes-alt" style="color: #28a745;"></span>
				<strong><?php esc_html_e( 'Pro Addon is Active!', 'bp-member-reviews' ); ?></strong>
			</p>
		</div>
		<?php
		// Let Pro addon render its settings form.
		do_action( 'bupr_admin_settings_pro_content' );
		?>
	</div>
</div>
<?php
	return; // Stop here, Pro addon handles the rest.
endif;

// Pro is not active - show preview.
$disabled = 'disabled';
?>
<div class="wbcom-tab-content">
	<div class="wbcom-admin-title-section">
		<h3><?php esc_html_e( 'Pro Features', 'bp-member-reviews' ); ?></h3>
	</div>
	<div class="wbcom-admin-option-wrap wbcom-admin-option-wrap-view">

		<?php if ( $is_pro_active && ! $is_pro_licensed ) : ?>
			<!-- Pro is installed but not licensed -->
			<div class="wbcom-settings-section-wrap" style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin-bottom: 20px;">
				<p style="margin: 0 0 10px;">
					<span class="dashicons dashicons-warning" style="color: #ffc107;"></span>
					<strong><?php esc_html_e( 'Pro Addon Installed - License Required', 'bp-member-reviews' ); ?></strong>
				</p>
				<p style="margin: 0;">
					<?php esc_html_e( 'Please activate your license to unlock Pro features.', 'bp-member-reviews' ); ?>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=wbcom-license-page' ) ); ?>" class="button button-primary" style="margin-left: 10px;">
						<?php esc_html_e( 'Activate License', 'bp-member-reviews' ); ?>
					</a>
				</p>
			</div>
		<?php else : ?>
			<!-- Pro is NOT active - show upgrade notice -->
			<div class="wbcom-settings-section-wrap" style="background: #f0f6fc; border-left: 4px solid #667eea; padding: 15px; margin-bottom: 20px;">
				<p style="margin: 0 0 10px;">
					<span class="dashicons dashicons-lock" style="color: #667eea;"></span>
					<strong><?php esc_html_e( 'Pro Features Preview', 'bp-member-reviews' ); ?></strong>
					<?php esc_html_e( '- These settings are available with the Pro addon.', 'bp-member-reviews' ); ?>
				</p>
				<a href="https://wbcomdesigns.com/downloads/buddypress-member-reviews/" target="_blank" class="button button-primary">
					<?php esc_html_e( 'Get Pro Version', 'bp-member-reviews' ); ?>
				</a>
			</div>
		<?php endif; // End of notice if/else. ?>

		<div class="form-table">

			<!-- Review Mode -->
			<div class="wbcom-settings-section-wrap">
				<div class="wbcom-settings-section-options-heading">
					<label>
						<?php esc_html_e( 'Review Mode', 'bp-member-reviews' ); ?>
						<?php if ( ! $is_pro_active ) : ?>
							<span class="bupr-pro-badge" style="background: #667eea; color: #fff; font-size: 10px; padding: 2px 6px; border-radius: 3px; margin-left: 5px;"><?php esc_html_e( 'PRO', 'bp-member-reviews' ); ?></span>
						<?php endif; ?>
					</label>
					<p class="description"><?php esc_html_e( 'Choose how members can review each other on your site.', 'bp-member-reviews' ); ?></p>
				</div>
				<div class="wbcom-settings-section-options">
					<fieldset <?php echo esc_attr( $disabled ); ?>>
						<label style="display: block; margin-bottom: 8px;">
							<input type="radio" name="bupr_pro_options[review_mode]" value="ratings" checked <?php echo esc_attr( $disabled ); ?>>
							<?php esc_html_e( 'Ratings', 'bp-member-reviews' ); ?> <em>(<?php esc_html_e( 'Default - Star ratings with criteria', 'bp-member-reviews' ); ?>)</em>
						</label>
						<label style="display: block; margin-bottom: 8px;">
							<input type="radio" name="bupr_pro_options[review_mode]" value="testimonials" <?php echo esc_attr( $disabled ); ?>>
							<?php esc_html_e( 'Testimonials', 'bp-member-reviews' ); ?> <em>(<?php esc_html_e( 'Text-only reviews without stars', 'bp-member-reviews' ); ?>)</em>
						</label>
						<label style="display: block;">
							<input type="radio" name="bupr_pro_options[review_mode]" value="endorsements" <?php echo esc_attr( $disabled ); ?>>
							<?php esc_html_e( 'Endorsements', 'bp-member-reviews' ); ?> <em>(<?php esc_html_e( 'LinkedIn-style skill endorsements', 'bp-member-reviews' ); ?>)</em>
						</label>
					</fieldset>
				</div>
			</div>

			<!-- Verified Badges -->
			<div class="wbcom-settings-section-wrap">
				<div class="wbcom-settings-section-options-heading">
					<label for="bupr_enable_verified_badges">
						<?php esc_html_e( 'Enable Verified Badges', 'bp-member-reviews' ); ?>
						<?php if ( ! $is_pro_active ) : ?>
							<span class="bupr-pro-badge" style="background: #667eea; color: #fff; font-size: 10px; padding: 2px 6px; border-radius: 3px; margin-left: 5px;"><?php esc_html_e( 'PRO', 'bp-member-reviews' ); ?></span>
						<?php endif; ?>
					</label>
					<p class="description"><?php esc_html_e( 'Show a verified badge on reviews marked as verified by admin.', 'bp-member-reviews' ); ?></p>
				</div>
				<div class="wbcom-settings-section-options">
					<label class="bupr-switch">
						<input type="checkbox" id="bupr_enable_verified_badges" value="yes" checked <?php echo esc_attr( $disabled ); ?>>
						<div class="bupr-slider bupr-round"></div>
					</label>
				</div>
			</div>

			<!-- Tenure Verification -->
			<div class="wbcom-settings-section-wrap">
				<div class="wbcom-settings-section-options-heading">
					<label for="bupr_enable_tenure_verification">
						<?php esc_html_e( 'Tenure Verification', 'bp-member-reviews' ); ?>
						<?php if ( ! $is_pro_active ) : ?>
							<span class="bupr-pro-badge" style="background: #667eea; color: #fff; font-size: 10px; padding: 2px 6px; border-radius: 3px; margin-left: 5px;"><?php esc_html_e( 'PRO', 'bp-member-reviews' ); ?></span>
						<?php endif; ?>
					</label>
					<p class="description"><?php esc_html_e( 'Auto-verify reviews from members registered for a minimum period.', 'bp-member-reviews' ); ?></p>
				</div>
				<div class="wbcom-settings-section-options">
					<label class="bupr-switch">
						<input type="checkbox" id="bupr_enable_tenure_verification" value="yes" <?php echo esc_attr( $disabled ); ?>>
						<div class="bupr-slider bupr-round"></div>
					</label>
				</div>
			</div>

			<!-- Minimum Tenure -->
			<div class="wbcom-settings-section-wrap">
				<div class="wbcom-settings-section-options-heading">
					<label for="bupr_min_tenure_months">
						<?php esc_html_e( 'Minimum Tenure', 'bp-member-reviews' ); ?>
						<?php if ( ! $is_pro_active ) : ?>
							<span class="bupr-pro-badge" style="background: #667eea; color: #fff; font-size: 10px; padding: 2px 6px; border-radius: 3px; margin-left: 5px;"><?php esc_html_e( 'PRO', 'bp-member-reviews' ); ?></span>
						<?php endif; ?>
					</label>
					<p class="description"><?php esc_html_e( 'Minimum months of membership for auto-verification.', 'bp-member-reviews' ); ?></p>
				</div>
				<div class="wbcom-settings-section-options">
					<input type="number" id="bupr_min_tenure_months" class="small-text" value="6" min="1" <?php echo esc_attr( $disabled ); ?>>
					<?php esc_html_e( 'Months', 'bp-member-reviews' ); ?>
				</div>
			</div>

			<!-- REST API -->
			<div class="wbcom-settings-section-wrap">
				<div class="wbcom-settings-section-options-heading">
					<label for="bupr_enable_rest_api">
						<?php esc_html_e( 'Enable REST API', 'bp-member-reviews' ); ?>
						<?php if ( ! $is_pro_active ) : ?>
							<span class="bupr-pro-badge" style="background: #667eea; color: #fff; font-size: 10px; padding: 2px 6px; border-radius: 3px; margin-left: 5px;"><?php esc_html_e( 'PRO', 'bp-member-reviews' ); ?></span>
						<?php endif; ?>
					</label>
					<p class="description"><?php esc_html_e( 'Enable the reviews REST API at /wp-json/bupr/v1/ for external integrations.', 'bp-member-reviews' ); ?></p>
				</div>
				<div class="wbcom-settings-section-options">
					<label class="bupr-switch">
						<input type="checkbox" id="bupr_enable_rest_api" value="yes" checked <?php echo esc_attr( $disabled ); ?>>
						<div class="bupr-slider bupr-round"></div>
					</label>
				</div>
			</div>

			<!-- Flexible Criteria - Enhancement to existing Criteria tab -->
			<div class="wbcom-settings-section-wrap">
				<div class="wbcom-settings-section-options-heading">
					<label for="bupr_enable_flexible_criteria">
						<?php esc_html_e( 'Flexible Criteria', 'bp-member-reviews' ); ?>
						<?php if ( ! $is_pro_active ) : ?>
							<span class="bupr-pro-badge" style="background: #667eea; color: #fff; font-size: 10px; padding: 2px 6px; border-radius: 3px; margin-left: 5px;"><?php esc_html_e( 'PRO', 'bp-member-reviews' ); ?></span>
						<?php endif; ?>
					</label>
					<p class="description">
						<?php
						printf(
							/* translators: %s: link to criteria tab */
							esc_html__( 'Enhance the %s with additional input types (thumbs up/down, text) and weight settings.', 'bp-member-reviews' ),
							'<a href="' . esc_url( admin_url( 'admin.php?page=bp-member-review-settings&tab=bupr-criteria' ) ) . '">' . esc_html__( 'Criteria tab', 'bp-member-reviews' ) . '</a>'
						);
						?>
					</p>
				</div>
				<div class="wbcom-settings-section-options">
					<label class="bupr-switch">
						<input type="checkbox" id="bupr_enable_flexible_criteria" value="yes" <?php echo esc_attr( $disabled ); ?>>
						<div class="bupr-slider bupr-round"></div>
					</label>
				</div>
			</div>

			<!-- Criteria Input Types (shown when Flexible Criteria enabled) -->
			<div class="wbcom-settings-section-wrap">
				<div class="wbcom-settings-section-options-heading">
					<label>
						<?php esc_html_e( 'Available Criteria Types', 'bp-member-reviews' ); ?>
						<?php if ( ! $is_pro_active ) : ?>
							<span class="bupr-pro-badge" style="background: #667eea; color: #fff; font-size: 10px; padding: 2px 6px; border-radius: 3px; margin-left: 5px;"><?php esc_html_e( 'PRO', 'bp-member-reviews' ); ?></span>
						<?php endif; ?>
					</label>
					<p class="description"><?php esc_html_e( 'With Flexible Criteria enabled, each criterion in the Criteria tab can use different input types.', 'bp-member-reviews' ); ?></p>
				</div>
				<div class="wbcom-settings-section-options">
					<p style="margin: 0;">
						<span style="display: inline-block; padding: 5px 10px; background: #f5f5f5; border-radius: 4px; margin-right: 10px;">
							<span class="dashicons dashicons-star-filled" style="color: #ffc107;"></span>
							<?php esc_html_e( 'Stars (1-5)', 'bp-member-reviews' ); ?>
						</span>
						<span style="display: inline-block; padding: 5px 10px; background: #f5f5f5; border-radius: 4px; margin-right: 10px;">
							<span class="dashicons dashicons-thumbs-up" style="color: #28a745;"></span>
							<?php esc_html_e( 'Thumbs', 'bp-member-reviews' ); ?>
						</span>
						<span style="display: inline-block; padding: 5px 10px; background: #f5f5f5; border-radius: 4px;">
							<span class="dashicons dashicons-editor-textcolor" style="color: #667eea;"></span>
							<?php esc_html_e( 'Text', 'bp-member-reviews' ); ?>
						</span>
					</p>
				</div>
			</div>

			<!-- Endorsement Skills (for Endorsement Mode) -->
			<div class="wbcom-settings-section-wrap">
				<div class="wbcom-settings-section-options-heading">
					<label for="bupr_allow_custom_skills">
						<?php esc_html_e( 'Allow Custom Skills', 'bp-member-reviews' ); ?>
						<?php if ( ! $is_pro_active ) : ?>
							<span class="bupr-pro-badge" style="background: #667eea; color: #fff; font-size: 10px; padding: 2px 6px; border-radius: 3px; margin-left: 5px;"><?php esc_html_e( 'PRO', 'bp-member-reviews' ); ?></span>
						<?php endif; ?>
					</label>
					<p class="description"><?php esc_html_e( 'Allow members to add their own skills beyond predefined list. Only used in Endorsement mode.', 'bp-member-reviews' ); ?></p>
				</div>
				<div class="wbcom-settings-section-options">
					<label class="bupr-switch">
						<input type="checkbox" id="bupr_allow_custom_skills" value="yes" checked <?php echo esc_attr( $disabled ); ?>>
						<div class="bupr-slider bupr-round"></div>
					</label>
				</div>
			</div>

			<!-- Predefined Skills -->
			<div class="wbcom-settings-section-wrap">
				<div class="wbcom-settings-section-options-heading">
					<label for="bupr_predefined_skills">
						<?php esc_html_e( 'Predefined Skills', 'bp-member-reviews' ); ?>
						<?php if ( ! $is_pro_active ) : ?>
							<span class="bupr-pro-badge" style="background: #667eea; color: #fff; font-size: 10px; padding: 2px 6px; border-radius: 3px; margin-left: 5px;"><?php esc_html_e( 'PRO', 'bp-member-reviews' ); ?></span>
						<?php endif; ?>
					</label>
					<p class="description"><?php esc_html_e( 'Enter one skill per line. Only used in Endorsement mode.', 'bp-member-reviews' ); ?></p>
				</div>
				<div class="wbcom-settings-section-options">
					<textarea id="bupr_predefined_skills" rows="5" class="large-text" <?php echo esc_attr( $disabled ); ?>>Leadership
Communication
Problem Solving
Teamwork
Project Management</textarea>
				</div>
			</div>

		</div>

		<div style="text-align: center; padding: 20px; margin-top: 20px; background: #f9f9f9; border-radius: 4px;">
			<p>
				<span class="dashicons dashicons-unlock" style="color: #667eea;"></span>
				<?php esc_html_e( 'Unlock all Pro features!', 'bp-member-reviews' ); ?>
			</p>
			<?php if ( $is_pro_active && ! $is_pro_licensed ) : ?>
				<a href="<?php echo esc_url( admin_url( 'admin.php?page=wbcom-license-page' ) ); ?>" class="button button-primary button-hero">
					<?php esc_html_e( 'Activate License', 'bp-member-reviews' ); ?>
				</a>
			<?php else : ?>
				<a href="https://wbcomdesigns.com/downloads/buddypress-member-reviews/" target="_blank" class="button button-primary button-hero">
					<?php esc_html_e( 'Get Pro Version', 'bp-member-reviews' ); ?>
				</a>
			<?php endif; ?>
		</div>

	</div>
</div>
