<?php
/**
 * Settings tab: Pro Features (read-only Free vs Pro preview).
 *
 * Wraps the existing Pro Features tab template (ported 1:1). When the Pro
 * addon is active it delegates rendering via `bupr_admin_settings_pro_content`.
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
