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

		/**
		 * @var WBCOM_Demo_Importer_Plugins_Manager The single instance of the class
		 * @since 1.0.0
		 */
		protected static $_instance = null;

		var $plugins = array();

		/**
		 * @var TGM_Plugin_Activation Instance
		 */
		var $tgmpa;

		const WP_REPO_REGEX = '|^http[s]?://wordpress\.org/(?:extend/)?plugins/|';
		const IS_URL_REGEX = '|^http[s]?://|';

		/**
		 * Main WBCOM_Demo_Importer_Plugins_Manager Instance
		 *
		 * Ensures only one instance of WBCOM_Demo_Importer_Plugins_Manager is loaded or can be loaded.
		 *
		 * @since 1.0.0
		 * @static
		 * @see WBCOM_Demo_Importer_Plugins_Manager()
		 * @return WBCOM_Demo_Importer_Plugins_Manager - Main instance
		 */
		public static function instance() {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function __construct() {
			add_shortcode( 'wbcom_admin_setting_header', array( $this, 'wbcom_admin_setting_header_html' ) );
			add_action( 'admin_menu', array( $this, 'wbcom_admin_additional_pages' ), 999 );
			add_action( 'admin_enqueue_scripts', array( $this, 'wbcom_enqueue_admin_scripts' ) );

			add_action('wp_ajax_wbcom_admin_plugin_installation', array( $this, 'wbcom_do_plugin_action' ) );
		}

		public function wbcom_enqueue_admin_scripts() {
			if ( ! wp_script_is( 'wbcom-admin-setting-js', 'enqueued' ) ) {
				wp_enqueue_script( 'wbcom-admin-setting-js', BUPR_PLUGIN_URL . 'admin/assets/js/wbcom-admin-setting.js', array( 'jquery' ) );

				wp_enqueue_style( 'wbcom-admin-setting-css', BUPR_PLUGIN_URL . 'admin/assets/css/wbcom-admin-setting.css' );
			}
			if ( function_exists( 'get_current_screen' ) ) {
				$screen = get_current_screen();			
				if ( 'toplevel_page_wbcomplugins' === $screen->base || 'wbcom_page_wbcom-support-page' === $screen->base || 'wbcom_page_wbcom-plugins-page' === $screen->id || 'wbcom_page_wbcom-themes-page' === $screen->base ) {
					wp_enqueue_script( 'jquery' );
					wp_enqueue_script( 'jquery-ui-sortable' );
					wp_enqueue_style( 'bupr-font-awesome', BUPR_PLUGIN_URL . 'admin/assets/css/font-awesome.min.css' );
					if ( ! wp_style_is( 'wbcom-selectize-css', 'enqueued' ) ) {
						wp_enqueue_style( 'wbcom-selectize-css', BUPR_PLUGIN_URL . 'admin/assets/css/selectize.css' );
					}
					if ( ! wp_script_is( 'wbcom-selectize-js', 'enqueued' ) ) {
						wp_enqueue_script( 'wbcom-selectize-js', BUPR_PLUGIN_URL . 'admin/assets/js/selectize.min.js', array( 'jquery' ) );
					}
					wp_enqueue_script( 'bupr-js-admin', BUPR_PLUGIN_URL . 'admin/assets/js/bupr-admin.js', array( 'jquery' ) );

					wp_localize_script(
						'bupr-js-admin',
						'bupr_admin_ajax_object',
						array(
							'ajaxurl' => admin_url( 'admin-ajax.php' ),
						)
					);
					wp_enqueue_style( 'bupr-css-admin', BUPR_PLUGIN_URL . 'admin/assets/css/bupr-admin.css' );
					if ( ! wp_script_is( 'wp-color-picker', 'enqueued' ) ) {
						wp_enqueue_style( 'wp-color-picker' );
					}
					/* add wp color picker */
					wp_enqueue_script( 'bupr-color-picker', BUPR_PLUGIN_URL . 'admin/assets/js/bupr-color-picker.js', array( 'wp-color-picker' ), false, true );
				}
			}
		}			

		public function wbcom_do_plugin_action() {

			$action	= !empty( $_POST['plugin_action'] ) ? $_POST['plugin_action'] : false;
			$slug	= !empty( $_POST['plugin_slug'] ) ? $_POST['plugin_slug'] : false;

			switch ( $action ) {
				case 'enable_plugin':
					$this->do_plugin_activate( $slug );
					break;
				case 'install_plugin':
					$this->do_plugin_install( $slug );
					break;
				default:
					break;
			}

		}

		/**
		 * Performs plugins activation
		 * @param string $slug
		 * @param bool $echo
		 * @return void | array
		 */
		function do_plugin_activate( $slug, $echo = true ){

			$status           = $this->wbcom_get_plugin_status( $slug );
			$plugin_file_path = $this->_get_plugin_file_path_from_slug( $slug );
			$result = activate_plugin( $plugin_file_path );

			if ( is_wp_error( $result ) ) {
				$status['error'] = $result->get_error_message();
				if( $echo ) {
					wp_send_json_error( $status );
				} else {
					return $status;
				}
			}

			$status = $this->get_plugin_status( $slug );
			if( $echo ) {
				wp_send_json_success( $status );
			} else {
				return $status;
			}
		}

		/**
		 * Returns the status and actions for a plugin
		 * @param  string $plugin_slug The plugin slug
		 * @return array  The status and actions for the requested plugin
		 */
		function wbcom_get_plugin_status( $plugin_slug ) {

			$status = array();
			$plugin_config = $this->get_addon_config( $plugin_slug );

			if( isset( $plugin_config['addon_type'] ) && $plugin_config['addon_type'] === 'child_theme' ){
				// We have a theme
				if( $this->is_child_theme_installed() ){
					// Check if the theme is active or not
					if ( $this->is_child_theme_active() ) {
						$status['status']      = 'wbcom-active wbcom-addons-disabled';
						$status['status_text'] = __( 'Active', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
						$status['action_text'] = __( 'Child theme installed and active', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
						$status['action']      = 'no_action';
					} else  {
						$status['status']      = 'wbcom-inactive';
						$status['status_text'] = __( 'Inactive', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
						$status['action_text'] = __( 'Activate child theme', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
						$status['action']      = 'enable_child_theme';
					}
				}
				else{
					$status['status']      = 'wbcom-needs-install';
					$status['status_text'] = __( 'Not installed', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
					$status['action_text'] = __( 'Install child theme', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
					$status['action']      = 'install_theme';

					if( ! current_user_can( 'install_themes' ) ){
						$status['status']         = 'wbcom-not-installed wbcom-addons-disabled';
						$status['action_text']    = __( 'Permissions needed to install child themes. Contact site administrator.', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
						$status['action']         = 'contact_network_admin';
					}

				}
			}
			else{
				if( $this->is_plugin_installed( $plugin_slug ) ) {
					if ( $this->is_plugin_active( $plugin_slug ) ) {
						$status['status']         = 'wbcom-active';
						$status['status_text'] = __( 'Active', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
						$status['action_text']           = __( 'Already Installed & Activated', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
						$status['action']         = 'disable_plugin';
					} else  {
						$status['status']         = 'wbcom-inactive';
						$status['status_text'] = __( 'Inactive', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
						$status['action_text']           = __( 'Activate Plugin', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
						$status['action']         = 'enable_plugin';
					}
				}
				else{
					$status['status']         = 'wbcom-not-installed';
					$status['status_text'] = __( 'Not Installed', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
					$status['action_text']           = __( 'Install Plugin', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
					$status['action']         = 'install_plugin';

					if( ! current_user_can( 'install_plugins' ) ){
						$status['status']         = 'wbcom-not-installed wbcom-addons-disabled';
						$status['action_text']    = __( 'You don\'t have permission to install plugins. Contact site administrator.', WBCOM_Theme_Demo_Installer_TEXT_DOMAIN);
						$status['action']         = 'contact_network_admin';
					}

				}
			}


			return $status;
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
					<img src="<?php echo BUPR_PLUGIN_URL.'admin/assets/imgs/logowbcom.png'; ?>">
					<div class="wb_admin_right"></div>
				</div>

				<nav id="wb_admin_nav">
					<ul>
						<li class="wb_admin_nav_item">
                            <a href="<?php echo get_admin_url().'admin.php?page=wbcomplugins'; ?>" id="wb_admin_nav_trigger_settings">
                                <i class="fa fa-sliders "></i>
                                <h4><?php esc_html_e( 'Settings', 'bp-member-reviews' ); ?></h4>
                            </a>
                        </li>
						<li class="wb_admin_nav_item">
							<a href="<?php echo get_admin_url().'admin.php?page=wbcom-plugins-page'; ?>" id="wb_admin_nav_trigger_extensions">
								<i class="fa fa-th"></i>
								<h4><?php esc_html_e( 'Components', 'bp-member-reviews' ); ?></h4>
							</a>
						</li>
						<li class="wb_admin_nav_item">
							<a href="<?php echo get_admin_url().'admin.php?page=wbcom-themes-page'; ?>" id="wb_admin_nav_trigger_themes">
								<i class="fa fa-magic"></i>
								<h4><?php esc_html_e( 'Themes', 'bp-member-reviews' ); ?></h4>
							</a>
						</li>
						<li class="wb_admin_nav_item">
							<a href="<?php echo get_admin_url().'admin.php?page=wbcom-support-page'; ?>" id="wb_admin_nav_trigger_support">
								<i class="fa fa-question-circle"></i>
								<h4><?php esc_html_e( 'Support', 'bp-member-reviews' ); ?></h4>
							</a>
						</li>
					</ul>
				</nav>
			</div><?php
		}

	}

	/**
	 * Shortcut to WBCOM_Importer_Plugins_Manager class
	 */
	function instantiate_wbcom_importer_plugins_manager() {
		return Wbcom_Admin_Settings::instance();
	}
	instantiate_wbcom_importer_plugins_manager();
}