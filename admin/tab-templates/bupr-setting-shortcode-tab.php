<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="bupr-adming-setting">
	<div class="bupr-tab-header">
		<h3>
			<?php _e( 'Reviews Shortcode ', BUPR_TEXT_DOMAIN ); ?>
		</h3>
		<input type="hidden" class="bupr-tab-active" value="shortcode"/>
	</div>

	<div class="bupr-admin-settings-block">
		<div id="bupr-settings-tbl" class="bupr-table">
			<div class="bupr-admin-row border">
				<div class="bupr-admin-col-6 bupr-label">
					<strong>
						<?php echo '[add_profile_review_form]'; ?>
					</strong>
				</div>
				<div class="bupr-admin-col-6">
					<p class="description">
						<?php _e( ' This shortcode will be display BP member review form.', BUPR_TEXT_DOMAIN ); ?>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
