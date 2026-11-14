<?php
/**
 * Add-ons tab template - mount point for add-on settings.
 *
 * This tab owns no settings of its own. It exists so add-ons (currently the
 * Member Reviews Pro addon) can render their settings UI inside the free
 * plugin's admin shell by hooking `bupr_admin_settings_pro_content`. The tab
 * is only registered when something hooks that action (see get_tabs()).
 *
 * Wrapper contract (do NOT change without a paired Pro release): the addon
 * callback renders form contents only and relies on this template for the
 * surrounding `wbcom-tab-content` > `wbcom-admin-title-section` >
 * `wbcom-admin-option-wrap` structure.
 *
 * @package BuddyPress_Member_Reviews
 * @since 3.8.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wbcom-tab-content">
	<div class="wbcom-admin-title-section">
		<h3><?php esc_html_e( 'Add-ons', 'bp-member-reviews' ); ?></h3>
	</div>
	<div class="wbcom-admin-option-wrap wbcom-admin-option-wrap-view">
		<?php
		/**
		 * Render add-on settings inside the Add-ons tab.
		 *
		 * @since 3.8.0
		 */
		do_action( 'bupr_admin_settings_pro_content' );
		?>
	</div>
</div>
