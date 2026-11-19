<?php
/**
 * Settings tab: Add-ons.
 *
 * Wraps the Add-ons tab template, which is the mount point add-ons render
 * into via `bupr_admin_settings_pro_content`.
 *
 * @package BuddyPress_Member_Reviews
 * @since   3.8.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="bupr-card bupr-card--legacy">
	<div class="bupr-card__body">
		<?php require BUPR_PLUGIN_PATH . 'admin/tab-templates/bupr-setting-pro-features-tab.php'; ?>
	</div>
</div>
