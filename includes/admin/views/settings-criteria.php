<?php
/**
 * Settings tab: Criteria.
 *
 * Wraps the existing Criteria tab template (ported 1:1). The template
 * conditionally swaps the base form for the Pro criteria builder via the
 * `bupr_admin_settings_criteria_content` action — both paths are preserved.
 *
 * @package BuddyPress_Member_Reviews
 * @since   3.8.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="bupr-card bupr-card--legacy">
	<div class="bupr-card__body">
		<?php require BUPR_PLUGIN_PATH . 'admin/tab-templates/bupr-setting-criteria-tab.php'; ?>
	</div>
</div>
