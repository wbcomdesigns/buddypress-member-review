<?php
/**
 * BuddyPress Member Review criteria tab.
 *
 * @package BuddyPress_Member_Reviews
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/* admin setting on dashboard */
global $bupr;
$bupr_multi_rating_allowed_class = 'bupr-show-if-allowed';
if ( '1' === $bupr['multi_criteria_allowed'] ) {
	$bupr_multi_rating_allowed_class = '';
}

// Check if Pro is active and not in ratings mode.
$is_pro_active       = class_exists( 'BUPR_Pro_Features' ) && method_exists( 'BUPR_Pro_Features', 'is_pro_active' ) && BUPR_Pro_Features::is_pro_active();
$is_not_ratings_mode = $is_pro_active && method_exists( 'BUPR_Pro_Features', 'is_ratings_mode' ) && ! BUPR_Pro_Features::is_ratings_mode();
?>
<div class="wbcom-tab-content">
	<?php
	// If Pro is active but not in ratings mode, show a notice.
	if ( $is_not_ratings_mode ) :
		$current_mode = BUPR_Pro_Features::get_review_mode();
		$mode_label   = 'testimonials' === $current_mode ? __( 'Testimonials', 'bp-member-reviews' ) : __( 'Endorsements', 'bp-member-reviews' );
		?>
		<div class="wbcom-admin-title-section">
			<h3><?php esc_html_e( 'Review Criteria', 'bp-member-reviews' ); ?></h3>
		</div>
		<div class="wbcom-admin-option-wrap wbcom-admin-option-wrap-view">
			<div class="wbcom-settings-section-wrap" style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px;">
				<p style="margin: 0;">
					<span class="dashicons dashicons-info" style="color: #856404;"></span>
					<strong><?php esc_html_e( 'Rating Criteria Not Available', 'bp-member-reviews' ); ?></strong>
				</p>
				<p style="margin: 10px 0 0 0; color: #856404;">
					<?php
					printf(
						/* translators: 1: Current mode label, 2: Link to Add-ons tab */
						esc_html__( 'Rating criteria are only used in Star Ratings mode. You are currently using %1$s mode. To manage criteria, switch to Star Ratings mode in the %2$s tab.', 'bp-member-reviews' ),
						'<strong>' . esc_html( $mode_label ) . '</strong>',
						'<a href="' . esc_url( admin_url( 'admin.php?page=bp-member-review-settings&tab=bupr-pro-features' ) ) . '">' . esc_html__( 'Add-ons', 'bp-member-reviews' ) . '</a>'
					);
					?>
				</p>
			</div>
		</div>
		<?php
		return; // Stop here, no form needed.
	endif;

	/**
	 * Action hook for Pro addon to render enhanced criteria builder.
	 *
	 * If Pro addon renders content here, the base form is skipped.
	 *
	 * @since 3.7.0
	 */
	ob_start();
	do_action( 'bupr_admin_settings_criteria_content' );
	$pro_content = ob_get_clean();

	if ( ! empty( trim( $pro_content ) ) ) {
		// Pro addon provided enhanced criteria builder - use it.
		echo $pro_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} else {
		// Default base plugin criteria form.
		?>
	<form method="post" action="options.php">
		<?php
		settings_fields( 'bupr_admin_settings' );
		do_settings_sections( 'bupr_admin_settings' );
		?>
		<div class="wbcom-admin-title-section">
		<h3 class="title">
			<?php esc_html_e( 'Review Criteria', 'bp-member-reviews' ); ?>
		</h3>
		</div>
		<div class="wbcom-admin-option-wrap wbcom-admin-option-wrap-view">
			<div class="form-table">
					<div class="wbcom-settings-section-wrap">
						<div class="wbcom-settings-section-options-heading">
							<label for="bupr_allow_multiple_criteria">
								<?php esc_html_e( 'Multiple Criteria', 'bp-member-reviews' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Rate members by multiple criteria (e.g., Communication, Skills) instead of a single overall rating.', 'bp-member-reviews' ); ?></p>
						</div>
						<label class="bupr-switch">
							<input type="hidden" name="bupr_admin_settings[profile_multi_rating_allowed]" value="0" />
							<input type="checkbox" value='1' name="bupr_admin_settings[profile_multi_rating_allowed]" id="bupr_allow_multiple_criteria" <?php checked( esc_attr( $bupr['multi_criteria_allowed'] ), '1' ); ?>>
							<div class="bupr-slider bupr-round"></div>
						</label>
					</div>
			</div>
			<div class="wbcom-settings-section-wrap <?php echo esc_attr( $bupr_multi_rating_allowed_class ); ?>">
				<div class="bupr-admin-settings-block">
					<div id="bupr-settings-tbl" class="bupr-table bupr-criteria-settings-tbl">
					<div id="buprTextBoxContainer">
						<?php
						if ( ! empty( $bupr['active_rating_fields'] ) ) {
							$key = 0;
							foreach ( $bupr['active_rating_fields'] as $profile_rating_field => $bupr_criteria_setting ) :
								$key_val = wp_rand();
								if ( function_exists( 'icl_register_string' ) ) {
									icl_register_string( 'bp-member-reviews', 'rating_field_name_' . ( $key++ ), $profile_rating_field );
								}
								?>
								<div id="bupr-admin-row-criteria" class="bupr-admin-row bupr-criteria bupr-criteria-fields border draggable">
									<div class="bupr-admin-col-6">
										<span class="move-icons">&equiv;</span>
										<input name="bupr_admin_settings[rating_field_name][<?php echo esc_attr( $key_val ); ?>]" class="buprDynamicTextBox" type="text" value="<?php echo esc_attr( $profile_rating_field ); ?>" aria-label="<?php esc_attr_e( 'Review criteria name', 'bp-member-reviews' ); ?>" />
									</div>

									<div class="bupr-admin-col-6 buprcriteria">
										<p class="bupr-delete-tag">
											<input type="button" value="<?php esc_attr_e( 'Delete', 'bp-member-reviews' ); ?>" aria-label="<?php esc_attr_e( 'Delete this review criteria', 'bp-member-reviews' ); ?>" class="bupr-criteria-remove-button bupr-remove button button-secondary" />
											<!-- Removed description span. -->
										</p>
									</div>
								</div>
								<?php
							endforeach;
						}
						?>
						</div>
					</div>

				<div id="bupr-add-criteria-action" class="bupr-admin-row border">
					<div class="bupr-admin-col-12">
						<input id="bupr-btnAdd" type="button" value="<?php esc_attr_e( 'Add Criteria', 'bp-member-reviews' ); ?>" aria-label="<?php esc_attr_e( 'Add a new review criteria', 'bp-member-reviews' ); ?>" class="button button-secondary"/>
						<p class="description"><?php esc_html_e( 'This option allows you to add multiple rating criteria. By default, no criteria are shown until you activate them.', 'bp-member-reviews' ); ?></p>
					</div>
				</div>
				</div>
			</div>
			<?php submit_button(); ?>
		</div>
	</form>
	<?php } // End else base form. ?>
</div>
