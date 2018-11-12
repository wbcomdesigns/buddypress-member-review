<?php
/**
 * Class to add reviews shortcode.
 *
 * @since    1.0.0
 * @author   Wbcom Designs
 * @package  BuddyPress_Member_Reviews
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Wbcom_Admin_Settings' ) ) {

	/**
	 * Class to serve AJAX Calls.
	 *
	 * @author   Wbcom Designs
	 * @since    1.0.0
	 */
	class Wbcom_Admin_Settings {

		public function __construct() {
			add_shortcode( 'wbcom_admin_setting_header', array( $this, 'wbcom_admin_setting_header_html' ) );
			add_action( 'admin_menu', array( $this, 'wbcom_admin_additional_pages' ), 999 );
		}

		public function wbcom_admin_additional_pages() {
			add_submenu_page(
			'wbcomplugins', esc_html__( 'Components', 'bp-member-reviews' ), esc_html__( 'Components', 'bp-member-reviews' ), 'manage_options', 'wbcom-plugins-page', array( $this, 'wbcom_plugins_submenu_page_callback' )
			);
			add_submenu_page(
			'wbcomplugins', esc_html__( 'Themes', 'bp-member-reviews' ), esc_html__( 'Themes', 'bp-member-reviews' ), 'manage_options', 'wbcom-themes-page', array( $this, 'wbcom_themes_submenu_page_callback' )
			);
			add_submenu_page(
			'wbcomplugins', esc_html__( 'Support', 'bp-member-reviews' ), esc_html__( 'Support', 'bp-member-reviews' ), 'manage_options', 'wbcom-support-page', array( $this, 'wbcom_support_submenu_page_callback' )
			);
		}

		public function wbcom_plugins_submenu_page_callback() {
			include 'templates/wbcom-plugins-page.php';
		}

		public function wbcom_themes_submenu_page_callback() {
			include 'templates/wbcom-themes-page.php';
		}

		public function wbcom_support_submenu_page_callback() {
			include 'templates/wbcom-support-page.php';
		}

		public function wbcom_admin_setting_header_html() {
			?>
			<div id="wb_admin_header" class="wp-clearfix">

				<div id="wb_admin_logo">
					<img src="http://localhost/ttest/wp-content/plugins/wbnet-live-notifications/core/assets/images/logo_colored.png">
					<div class="wb_admin_right"></div>
				</div>


				<nav id="wb_admin_nav">
					<ul>
						<li class="wb_admin_nav_item is-active">
							<a href="http://localhost/ttest/wp-admin/admin.php?page=wbcom-plugins-page" id="wb_admin_nav_trigger_extensions">
								<i class="fa fa-th"></i>
								<h4>Components</h4>
							</a>
						</li>
						<li class="wb_admin_nav_item ">
							<a href="http://localhost/ttest/wp-admin/admin.php?page=wbcom-themes-page" id="wb_admin_nav_trigger_themes">
								<i class="fa fa-magic"></i>
								<h4>Themes</h4>
							</a>
						</li>
						<li class="wb_admin_nav_item ">
							<a href="http://localhost/ttest/wp-admin/admin.php?page=wbcom-support-page" id="wb_admin_nav_trigger_support">
								<i class="fa fa-question-circle"></i>
								<h4>Support</h4>
							</a>
						</li>
					</ul>
				</nav>

			</div><?php
		}

	}

	new Wbcom_Admin_Settings();
}