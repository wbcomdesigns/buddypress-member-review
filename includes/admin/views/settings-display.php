<?php
/**
 * Settings tab: Display.
 *
 * Wraps the existing Display tab template (ported 1:1).
 *
 * @package BuddyPress_Member_Reviews
 * @since   3.8.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="bupr-card bupr-card--legacy">
	<div class="bupr-card__body">
		<?php require BUPR_PLUGIN_PATH . 'admin/tab-templates/bupr-setting-display-tab.php'; ?>
	</div>
</div>
