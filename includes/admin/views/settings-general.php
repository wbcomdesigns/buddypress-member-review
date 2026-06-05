<?php
/**
 * Settings tab: General.
 *
 * Wraps the existing General tab template (ported 1:1) inside a card so
 * the field markup, hidden defaults, and Save button stay byte-identical
 * to the legacy admin while the chrome matches the card panel.
 *
 * @package BuddyPress_Member_Reviews
 * @since   3.8.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="bupr-card bupr-card--legacy">
	<div class="bupr-card__body">
		<?php require BUPR_PLUGIN_PATH . 'admin/tab-templates/bupr-setting-general-tab.php'; ?>
	</div>
</div>
