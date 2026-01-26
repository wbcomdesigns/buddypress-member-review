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
<style>
.bupr-comparison-table {
	width: 100%;
	border-collapse: collapse;
	margin-bottom: 30px;
	background: #fff;
	box-shadow: 0 10px 25px rgba(173, 180, 210, 0.20);
	border-radius: var(--border-radius-large, 12px);
	overflow: hidden;
	border: 1px solid rgba(200, 197, 218, 0.35);
}
.bupr-comparison-table th,
.bupr-comparison-table td {
	padding: 15px 20px;
	text-align: left;
	border-bottom: 1px solid rgba(200, 197, 218, 0.30);
}
.bupr-comparison-table thead th {
	background: var(--primary-color, #2A32EF);
	color: #fff;
	font-weight: 600;
	text-transform: uppercase;
	font-size: 12px;
	letter-spacing: 0.5px;
}
.bupr-comparison-table thead th:first-child {
	text-align: left;
}
.bupr-comparison-table thead th:not(:first-child) {
	text-align: center;
	width: 120px;
}
.bupr-comparison-table tbody td:not(:first-child) {
	text-align: center;
}
.bupr-comparison-table tbody tr:hover {
	background: var(--background-light, #F6F7FE);
}
.bupr-comparison-table .feature-name {
	font-weight: 500;
	color: var(--text-dark, #1d2327);
}
.bupr-comparison-table .feature-desc {
	font-size: 12px;
	color: var(--text-light, #646F89);
	margin-top: 4px;
}
.bupr-check-yes {
	color: var(--success-color, #4fb845);
	font-size: 18px;
}
.bupr-check-no {
	color: #d1d5db;
	font-size: 18px;
}
.bupr-version-badge {
	display: inline-block;
	padding: 4px 12px;
	border-radius: 20px;
	font-size: 11px;
	font-weight: 600;
	text-transform: uppercase;
}
.bupr-version-free {
	background: #E1E6F4;
	color: var(--secondary-color, #272B41);
}
.bupr-version-pro {
	background: var(--primary-color, #2A32EF);
	color: #fff;
}
.bupr-upgrade-cta {
	text-align: center;
	padding: 30px;
	background: var(--background-light, #F6F7FE);
	border-radius: var(--border-radius-large, 12px);
	margin-top: 20px;
	border: 1px solid rgba(200, 197, 218, 0.35);
}
.bupr-upgrade-cta h3 {
	margin: 0 0 10px;
	color: var(--text-dark, #1d2327);
}
.bupr-upgrade-cta p {
	margin: 0 0 20px;
	color: var(--text-light, #646F89);
}
.bupr-pro-badge {
	background: var(--primary-color, #2A32EF) !important;
}
</style>

<div class="wbcom-tab-content">
	<div class="wbcom-admin-title-section">
		<h3><?php esc_html_e( 'Free vs Pro Comparison', 'bp-member-reviews' ); ?></h3>
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
		<?php endif; ?>

		<!-- Feature Comparison Table -->
		<table class="bupr-comparison-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Feature', 'bp-member-reviews' ); ?></th>
					<th><span class="bupr-version-badge bupr-version-free"><?php esc_html_e( 'Free', 'bp-member-reviews' ); ?></span></th>
					<th><span class="bupr-version-badge bupr-version-pro"><?php esc_html_e( 'Pro', 'bp-member-reviews' ); ?></span></th>
				</tr>
			</thead>
			<tbody>
				<!-- Free Features -->
				<tr>
					<td>
						<div class="feature-name"><?php esc_html_e( 'Star Ratings', 'bp-member-reviews' ); ?></div>
						<div class="feature-desc"><?php esc_html_e( '5-star ratings with multiple criteria', 'bp-member-reviews' ); ?></div>
					</td>
					<td><span class="dashicons dashicons-yes-alt bupr-check-yes"></span></td>
					<td><span class="dashicons dashicons-yes-alt bupr-check-yes"></span></td>
				</tr>
				<tr>
					<td>
						<div class="feature-name"><?php esc_html_e( 'Reviews Tab on Profile', 'bp-member-reviews' ); ?></div>
						<div class="feature-desc"><?php esc_html_e( 'Dedicated tab to display member reviews', 'bp-member-reviews' ); ?></div>
					</td>
					<td><span class="dashicons dashicons-yes-alt bupr-check-yes"></span></td>
					<td><span class="dashicons dashicons-yes-alt bupr-check-yes"></span></td>
				</tr>
				<tr>
					<td>
						<div class="feature-name"><?php esc_html_e( 'Owner Replies', 'bp-member-reviews' ); ?></div>
						<div class="feature-desc"><?php esc_html_e( 'Profile owners can respond to reviews', 'bp-member-reviews' ); ?></div>
					</td>
					<td><span class="dashicons dashicons-yes-alt bupr-check-yes"></span></td>
					<td><span class="dashicons dashicons-yes-alt bupr-check-yes"></span></td>
				</tr>
				<tr>
					<td>
						<div class="feature-name"><?php esc_html_e( 'Review Flagging', 'bp-member-reviews' ); ?></div>
						<div class="feature-desc"><?php esc_html_e( 'Report inappropriate reviews', 'bp-member-reviews' ); ?></div>
					</td>
					<td><span class="dashicons dashicons-yes-alt bupr-check-yes"></span></td>
					<td><span class="dashicons dashicons-yes-alt bupr-check-yes"></span></td>
				</tr>
				<tr>
					<td>
						<div class="feature-name"><?php esc_html_e( 'Anonymous Reviews', 'bp-member-reviews' ); ?></div>
						<div class="feature-desc"><?php esc_html_e( 'Submit reviews without revealing identity', 'bp-member-reviews' ); ?></div>
					</td>
					<td><span class="dashicons dashicons-yes-alt bupr-check-yes"></span></td>
					<td><span class="dashicons dashicons-yes-alt bupr-check-yes"></span></td>
				</tr>
				<tr>
					<td>
						<div class="feature-name"><?php esc_html_e( 'Email Notifications', 'bp-member-reviews' ); ?></div>
						<div class="feature-desc"><?php esc_html_e( 'Notify members when they receive reviews', 'bp-member-reviews' ); ?></div>
					</td>
					<td><span class="dashicons dashicons-yes-alt bupr-check-yes"></span></td>
					<td><span class="dashicons dashicons-yes-alt bupr-check-yes"></span></td>
				</tr>
				<tr>
					<td>
						<div class="feature-name"><?php esc_html_e( 'Moderation Controls', 'bp-member-reviews' ); ?></div>
						<div class="feature-desc"><?php esc_html_e( 'Approve/reject reviews before publishing', 'bp-member-reviews' ); ?></div>
					</td>
					<td><span class="dashicons dashicons-yes-alt bupr-check-yes"></span></td>
					<td><span class="dashicons dashicons-yes-alt bupr-check-yes"></span></td>
				</tr>

				<!-- Pro Only Features -->
				<tr>
					<td>
						<div class="feature-name"><?php esc_html_e( 'Testimonial Mode', 'bp-member-reviews' ); ?></div>
						<div class="feature-desc"><?php esc_html_e( 'Text-only reviews without star ratings', 'bp-member-reviews' ); ?></div>
					</td>
					<td><span class="dashicons dashicons-minus bupr-check-no"></span></td>
					<td><span class="dashicons dashicons-yes-alt bupr-check-yes"></span></td>
				</tr>
				<tr>
					<td>
						<div class="feature-name"><?php esc_html_e( 'Endorsement Mode', 'bp-member-reviews' ); ?></div>
						<div class="feature-desc"><?php esc_html_e( 'LinkedIn-style skill endorsements with request flow', 'bp-member-reviews' ); ?></div>
					</td>
					<td><span class="dashicons dashicons-minus bupr-check-no"></span></td>
					<td><span class="dashicons dashicons-yes-alt bupr-check-yes"></span></td>
				</tr>
				<tr>
					<td>
						<div class="feature-name"><?php esc_html_e( 'Verified Badges', 'bp-member-reviews' ); ?></div>
						<div class="feature-desc"><?php esc_html_e( 'Trust signals for authentic reviews', 'bp-member-reviews' ); ?></div>
					</td>
					<td><span class="dashicons dashicons-minus bupr-check-no"></span></td>
					<td><span class="dashicons dashicons-yes-alt bupr-check-yes"></span></td>
				</tr>
				<tr>
					<td>
						<div class="feature-name"><?php esc_html_e( 'Flexible Criteria Builder', 'bp-member-reviews' ); ?></div>
						<div class="feature-desc"><?php esc_html_e( 'Custom rating criteria with stars, thumbs, or text', 'bp-member-reviews' ); ?></div>
					</td>
					<td><span class="dashicons dashicons-minus bupr-check-no"></span></td>
					<td><span class="dashicons dashicons-yes-alt bupr-check-yes"></span></td>
				</tr>
				<tr>
					<td>
						<div class="feature-name"><?php esc_html_e( 'REST API', 'bp-member-reviews' ); ?></div>
						<div class="feature-desc"><?php esc_html_e( 'Full CRUD API for headless/mobile integration', 'bp-member-reviews' ); ?></div>
					</td>
					<td><span class="dashicons dashicons-minus bupr-check-no"></span></td>
					<td><span class="dashicons dashicons-yes-alt bupr-check-yes"></span></td>
				</tr>
				<tr>
					<td>
						<div class="feature-name"><?php esc_html_e( 'Auto-Verify by Tenure', 'bp-member-reviews' ); ?></div>
						<div class="feature-desc"><?php esc_html_e( 'Automatically verify reviews from long-time members', 'bp-member-reviews' ); ?></div>
					</td>
					<td><span class="dashicons dashicons-minus bupr-check-no"></span></td>
					<td><span class="dashicons dashicons-yes-alt bupr-check-yes"></span></td>
				</tr>
				<tr>
					<td>
						<div class="feature-name"><?php esc_html_e( 'Custom Skills Management', 'bp-member-reviews' ); ?></div>
						<div class="feature-desc"><?php esc_html_e( 'Add/remove endorsement skills from admin', 'bp-member-reviews' ); ?></div>
					</td>
					<td><span class="dashicons dashicons-minus bupr-check-no"></span></td>
					<td><span class="dashicons dashicons-yes-alt bupr-check-yes"></span></td>
				</tr>
			</tbody>
		</table>

		<?php if ( ! $is_pro_active ) : ?>
		<!-- Upgrade CTA -->
		<div class="bupr-upgrade-cta">
			<h3><?php esc_html_e( 'Unlock All Pro Features', 'bp-member-reviews' ); ?></h3>
			<p><?php esc_html_e( 'Get testimonials, endorsements, verified badges, and more with the Pro addon.', 'bp-member-reviews' ); ?></p>
			<a href="https://wbcomdesigns.com/downloads/buddypress-member-reviews/" target="_blank" class="button button-primary button-hero">
				<?php esc_html_e( 'Get Pro Version', 'bp-member-reviews' ); ?>
			</a>
		</div>
		<?php endif; ?>

		<h3 style="margin-top: 40px;"><?php esc_html_e( 'Pro Features Preview', 'bp-member-reviews' ); ?></h3>
		<p class="description" style="margin-bottom: 20px;"><?php esc_html_e( 'These settings become available when you upgrade to Pro.', 'bp-member-reviews' ); ?></p>

		<div class="form-table">

			<!-- Review Mode -->
			<div class="wbcom-settings-section-wrap">
				<div class="wbcom-settings-section-options-heading">
					<label>
						<?php esc_html_e( 'Review Mode', 'bp-member-reviews' ); ?>
						<?php if ( ! $is_pro_active ) : ?>
							<span class="bupr-pro-badge" style="background: #2A32EF; color: #fff; font-size: 10px; padding: 2px 6px; border-radius: 3px; margin-left: 5px;"><?php esc_html_e( 'PRO', 'bp-member-reviews' ); ?></span>
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

			<!-- REST API -->
			<div class="wbcom-settings-section-wrap">
				<div class="wbcom-settings-section-options-heading">
					<label for="bupr_enable_rest_api">
						<?php esc_html_e( 'REST API', 'bp-member-reviews' ); ?>
						<?php if ( ! $is_pro_active ) : ?>
							<span class="bupr-pro-badge" style="background: #2A32EF; color: #fff; font-size: 10px; padding: 2px 6px; border-radius: 3px; margin-left: 5px;"><?php esc_html_e( 'PRO', 'bp-member-reviews' ); ?></span>
						<?php endif; ?>
					</label>
					<p class="description"><?php esc_html_e( 'Full CRUD REST API for reviews integration.', 'bp-member-reviews' ); ?></p>
				</div>
				<div class="wbcom-settings-section-options">
					<label class="bupr-switch">
						<input type="checkbox" id="bupr_enable_rest_api" value="yes" checked <?php echo esc_attr( $disabled ); ?>>
						<div class="bupr-slider bupr-round"></div>
					</label>
				</div>
			</div>

			<!-- Verified Badges -->
			<div class="wbcom-settings-section-wrap">
				<div class="wbcom-settings-section-options-heading">
					<label for="bupr_enable_verified_badges">
						<?php esc_html_e( 'Verified Badges', 'bp-member-reviews' ); ?>
						<?php if ( ! $is_pro_active ) : ?>
							<span class="bupr-pro-badge" style="background: #2A32EF; color: #fff; font-size: 10px; padding: 2px 6px; border-radius: 3px; margin-left: 5px;"><?php esc_html_e( 'PRO', 'bp-member-reviews' ); ?></span>
						<?php endif; ?>
					</label>
					<p class="description"><?php esc_html_e( 'Trust signals for authentic reviews.', 'bp-member-reviews' ); ?></p>
				</div>
				<div class="wbcom-settings-section-options">
					<label class="bupr-switch">
						<input type="checkbox" id="bupr_enable_verified_badges" value="yes" checked <?php echo esc_attr( $disabled ); ?>>
						<div class="bupr-slider bupr-round"></div>
					</label>
				</div>
			</div>

			<!-- Flexible Criteria -->
			<div class="wbcom-settings-section-wrap">
				<div class="wbcom-settings-section-options-heading">
					<label for="bupr_enable_flexible_criteria">
						<?php esc_html_e( 'Flexible Criteria', 'bp-member-reviews' ); ?>
						<?php if ( ! $is_pro_active ) : ?>
							<span class="bupr-pro-badge" style="background: #2A32EF; color: #fff; font-size: 10px; padding: 2px 6px; border-radius: 3px; margin-left: 5px;"><?php esc_html_e( 'PRO', 'bp-member-reviews' ); ?></span>
						<?php endif; ?>
					</label>
					<p class="description"><?php esc_html_e( 'Dynamic rating criteria instead of hardcoded.', 'bp-member-reviews' ); ?></p>
				</div>
				<div class="wbcom-settings-section-options">
					<label class="bupr-switch">
						<input type="checkbox" id="bupr_enable_flexible_criteria" value="yes" checked <?php echo esc_attr( $disabled ); ?>>
						<div class="bupr-slider bupr-round"></div>
					</label>
				</div>
			</div>

		</div>

	</div>
</div>
