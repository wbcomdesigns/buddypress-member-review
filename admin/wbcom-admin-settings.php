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
		// public static function instance() {
		// 	if ( is_null( self::$_instance ) ) {
		// 		self::$_instance = new self();
		// 	}
		// 	return self::$_instance;
		// }

		public function __construct() {
			add_shortcode( 'wbcom_admin_setting_header', array( $this, 'wbcom_admin_setting_header_html' ) );
			add_action( 'admin_menu', array( $this, 'wbcom_admin_additional_pages' ), 999 );
			add_action( 'tgmpa_register', array( $this, 'wbcom_required_plugins' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'wbcom_enqueue_admin_scripts' ) );

			add_action('wp_ajax_wbcom_manage_plugin_installation', array( $this, 'wbcom_do_plugin_action' ) );

		//	add_action( 'init', array( $this ,'populate_plugins' ) );
		//	add_action( 'tgmpa_register', array( $this, 'required_plugins' ) );

		}

		public function wbcom_do_plugin_action() {
			$action	= !empty( $_POST['plugin_action'] ) ? $_POST['plugin_action'] 	: false;
			$slug	= !empty( $_POST['plugin_slug'] ) 			? $_POST['plugin_slug'] 			: false;

			switch ( $action ) {
				// case 'enable_plugin':
				// 	$this->wbcom_do_plugin_activate( $slug );
				// 	break;
				case 'install_plugin':
					$this->wbcom_do_plugin_install( $slug );
					break;
				default:
					break;
			}
		}


		function replace_plugin() {
		  // modify these variables with your new/old plugin values
		  $plugin_slug = 'wp-reset/wp-reset.php';
		  $plugin_zip = $this->get_download_url( $slug );
		  $old_plugin_slug = 'reset-wp/reset-wp.php';
		   
		  echo 'If things are not done in a minute <a href="plugins.php">click here to return to Plugins page</a><br><br>';
		  echo 'Starting ...<br><br>';
		   
		  echo 'Check if new plugin is already installed - ';
		  if ( is_plugin_installed( $plugin_slug ) ) {
		    echo 'it\'s installed! Making sure it\'s the latest version.';
		    upgrade_plugin( $plugin_slug );
		    $installed = true;
		  } else {
		    echo 'it\'s not installed. Installing.';
		    $installed = install_plugin( $plugin_zip );
		  }
		   
		  if ( !is_wp_error( $installed ) && $installed ) {
		    echo 'Activating new plugin.';
		    $activate = activate_plugin( $plugin_slug );
		     
		    if ( is_null($activate) ) {
		      echo '<br>Deactivating old plugin.<br>';
		      deactivate_plugins( array( $old_plugin_slug ) );
		       
		      echo '<br>Done! Everything went smooth.';
		    }
		  } else {
		    echo 'Could not install the new plugin.';
		  }
		}
		   
		function is_plugin_installed( $slug ) {
			$plugin_zip = $this->get_download_url( $slug );
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}	
			$all_plugins = get_plugins();

			if ( !empty( $all_plugins[$slug] ) ) {
				return true;
			} else {
				return false;
			}
		}
		 
		function wbcom_do_plugin_install( $slug ) {
			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			wp_cache_flush();

			$upgrader   = new Plugin_Upgrader();
			$plugin_zip = $this->get_download_url( $slug );
			$installed  = $upgrader->install( $plugin_zip );
			if( $installed ) {

			}
		}
		 
		function upgrade_plugin( $plugin_slug ) {
		  include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		  wp_cache_flush();
		   
		  $upgrader = new Plugin_Upgrader();
		  $upgraded = $upgrader->upgrade( $plugin_slug );
		  	
		  return $upgraded;
		}

		/**
		 * Performs plugins installation
		 * @param string $slug
		 * @param boolean $echo
		 * @return void | array
		 */
		// function wbcom_do_plugin_install( $slug, $echo = true ) {		
		
		// 	$url = $this->get_download_url( $slug );
		// 	$status = $this->get_plugin_status( $slug );
  //           echo '<pre>'; print_r( $url ); echo '</pre>';
  //           echo '<pre>'; print_r( $status ); echo '</pre>'; die('jjj');
		// 	if( ! current_user_can( 'install_plugins' ) ){
		// 		$status['error'] = esc_html_x( 'You don\'t have permissions to install plugins', '' );
		// 		if ( $echo ) {
		// 			wp_send_json_error( $status );
		// 		} else {
		// 			return $status;
		// 		}
		// 	}

		// 	$method = ''; // Leave blank so WP_Filesystem can populate it as necessary.

		// 	if ( false === ( $creds = request_filesystem_credentials( esc_url_raw( $url ), $method, false, false, array() ) ) ) {
		// 		return true;
		// 	}

		// 	if ( ! WP_Filesystem( $creds ) ) {
		// 		request_filesystem_credentials( esc_url_raw( $url ), $method, true, false, array() ); // Setup WP_Filesystem.
		// 		return true;
		// 	}

		// 	if ( ! class_exists( 'Plugin_Upgrader', false ) ) {
		// 		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		// 	}

		// 	$skin = new Automatic_Upgrader_Skin();
		// 	$upgrader = new Plugin_Upgrader( $skin, array( 'clear_destination' => true ) );

		// 	$result = $upgrader->install( $url );

		// 	// There is a bug in WP where the install method can return null in case the folder already exists
		// 	// see https://core.trac.wordpress.org/ticket/27365
		// 	if( $result === null && ! empty( $skin->result ) ){
		// 		$result = $skin->result;
		// 	}

		// 	if ( is_wp_error( $skin->result ) ) {
		// 		$status['error'] = $result->get_error_message();
		// 		if ( $echo ) {
		// 			wp_send_json_error( $status );
		// 		} else {
		// 			return $status;
		// 		}
		// 	}

		// 	$this->tgmpa->populate_file_path( $slug );
		// 	$plugin_activate = $upgrader->plugin_info();
		// 	$activate = activate_plugin( $plugin_activate );
		// 	if ( is_wp_error( $activate ) ) {
		// 		$status['error'] = wp_kses_post( $activate->get_error_message() );
		// 		if ( $echo ) {
		// 			wp_send_json_error( $status );
		// 		} else {
		// 			return $status;
		// 		}
		// 	}

		// 	$status = $this->get_plugin_status( $slug );

		// 	if ( $echo ) {
		// 		wp_send_json_success( $status );
		// 	} else {
		// 		return $status;
		// 	}

		// }

		/**
		 * Returns the install url for the current plugin
		 * @param string $slug
		 * @return string
		 */
		public function get_download_url( $slug ) {
			$dl_source = '';
			if( isset( $this->plugins[ $slug ]['external_url'] ) && !empty( $this->plugins[ $slug ]['external_url'] ) ) {
				return $this->plugins[ $slug ]['external_url'];
			}
			else {
				$plugin_source_type = 'repo';
			}
			switch ( $plugin_source_type ) {
				case 'repo':
					return $this->get_wp_repo_download_url( $slug );
				case 'external':
					return $this->plugins[ $slug ]['source'];
			}

			return $dl_source; // Should never happen.
		}

		function get_wp_repo_download_url( $slug ) {
			include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' ); // for plugins_api..
			$api = plugins_api('plugin_information', array('slug' => $slug, 'fields' => array('sections' => false) ) ); //Save on a bit of bandwidth.
			if ( is_wp_error( $api ) ) {
				$status['error'] = $api->get_error_message();
				wp_send_json_error( $status );
			}
			return $api->download_link;
		}

		function get_addon_config( $plugin_slug ) {
			if( ! empty( $this->plugins[ $plugin_slug ] ) ) {
				return $this->plugins[ $plugin_slug ];
			}
		}

		/**
		 * Check if a plugin is installed. Does not take must-use plugins into account.
		 *
		 * @since 1.0.0
		 *
		 * @param string $slug Plugin slug.
		 * @return bool True if installed, false otherwise.
		 */
		// public function is_plugin_installed( $slug ) {
		// 	return $this->tgmpa->is_plugin_installed( $slug );
		// }

		/**
		 * Check whether there is an update available for a plugin.
		 *
		 * @since 1.0.0
		 *
		 * @param string $slug Plugin slug.
		 * @return false|string Version number string of the available update or false if no update available.
		 */
		public function does_plugin_have_update( $slug ) {
			return $this->tgmpa->does_plugin_have_update( $slug );
		}

		/**
		 * Check if a plugin is active.
		 *
		 * @since 1.0.0
		 *
		 * @param string $slug Plugin slug.
		 * @return bool True if active, false otherwise.
		 */
		public function is_plugin_active( $slug ) {
			return $this->tgmpa->is_plugin_active( $slug );
		}

		/**
		 * Retrieve the version number of an installed plugin.
		 *
		 * @since 1.0.0
		 *
		 * @param string $slug Plugin slug.
		 * @return string Version number as string or an empty string if the plugin is not installed
		 *                or version unknown (plugins which don't comply with the plugin header standard).
		 */
		public function get_installed_version( $slug ) {

			return $this->tgmpa->get_installed_version( $slug );
		}

		/**
		 * Wrapper around the core WP get_plugins function, making sure it's actually available.
		 *
		 * @since 1.0.0
		 *
		 * @param string $plugin_folder Optional. Relative path to single plugin folder.
		 * @return array Array of installed plugins with plugin information.
		 */
		public function get_plugins( $plugin_folder = '' ) {
			return $this->tgmpa->get_plugins( $plugin_folder );
		}

		/**
		 * Returns the status and actions for a plugin
		 * @param  string $plugin_slug The plugin slug
		 * @return array  The status and actions for the requested plugin
		 */
		function get_plugin_status( $plugin_slug ) {
			$status = array();
			$plugin_config = $this->get_addon_config( $plugin_slug );

			if ( isset( $plugin_config['addon_type'] ) && $plugin_config['addon_type'] === 'child_theme' ) {
				// We have a theme
				if( $this->is_child_theme_installed() ) {
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
			} else {
				if ( $this->is_plugin_installed( $plugin_slug ) ) {
				    echo '<pre>'; print_r('hhhh'); echo "</pre>"; die('ertettrtt');
					if ( $this->is_plugin_active( $plugin_slug ) ) {
						$status['status']         = 'wbcom-active';
						$status['status_text'] = __( 'Active', '');
						$status['action_text']           = __( 'Already Installed & Activated', '');
						$status['action']         = 'disable_plugin';
					} else  {
						$status['status']         = 'wbcom-inactive';
						$status['status_text'] = __( 'Inactive', '');
						$status['action_text']           = __( 'Activate Plugin', '');
						$status['action']         = 'enable_plugin';
					}
				} else {
					echo '<pre>'; print_r(',mmmm'); echo "</pre>"; die('ertettrtt');
					$status['status']         = 'wbcom-not-installed';
					$status['status_text'] = __( 'Not Installed', '');
					$status['action_text']           = __( 'Install Plugin', '' );
					$status['action']         = 'install_plugin';

					if( ! current_user_can( 'install_plugins' ) ){
						$status['status']         = 'wbcom-not-installed wbcom-addons-disabled';
						$status['action_text']    = __( 'You don\'t have permission to install plugins. Contact site administrator.', '');
						$status['action']         = 'contact_network_admin';
					}

				}
			}


			return $status;
		}


		public function wbcom_enqueue_admin_scripts() {
			if ( ! wp_script_is( 'wbcom-admin-setting-js', 'enqueued' ) ) {

				wp_register_script(
					$handle		=	'wbcom_admin_setting_js',
					$src		=	BUPR_PLUGIN_URL . 'admin/assets/js/wbcom-admin-setting.js',
					$deps		=	array( 'jquery' ),
					$ver		=	time(),
					$in_footer	=	true
				);
				wp_localize_script(
					'wbcom_admin_setting_js',
					'wbcom_plugin_installer_params',
					array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						// 'success_url' => $this->get_demo_installer_page_url( array( 'success' => 'success' ) )
					)
				);
				wp_enqueue_script( 'wbcom_admin_setting_js' );

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

	function instantiate_wbcom_importer_plugins_manager() {
		// include_once 'class-tgm-plugin-activation.php';
		new Wbcom_Admin_Settings();
	}
	instantiate_wbcom_importer_plugins_manager();
}