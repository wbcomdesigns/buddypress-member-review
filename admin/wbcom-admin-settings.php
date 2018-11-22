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
			add_action( 'tgmpa_register', array( $this, 'wbcom_required_plugins' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'wbcom_enqueue_admin_scripts' ) );

			add_action('wp_ajax_wbcom_manage_plugin_installation', array( $this, 'wbcom_do_plugin_action' ) );

		//	add_action( 'init', array( $this ,'populate_plugins' ) );
		//	add_action( 'tgmpa_register', array( $this, 'required_plugins' ) );

		}

		public function wbcom_do_plugin_action() {
			$action	= !empty( $_POST['plugin_action'] ) ? $_POST['plugin_action'] : false;
			$slug	= !empty( $_POST['plugin_slug'] ) ? $_POST['plugin_slug'] : false;

			if( 'install_plugin' == $action ) {
				$this->wbcom_do_plugin_install( $slug );				
			} else if( 'activate_plugin' == $action ) {
				$this->wbcom_do_plugin_activate( $slug );
			} else {
				$this->wbcom_do_plugin_deactivate( $slug );
			}
			wp_send_json_success( $response );
			die;
		}

		function wbcom_do_plugin_activate( $slug ) {
			$plugin_file_path = $this->_get_plugin_file_path_from_slug( $slug );
			$result = activate_plugin( $plugin_file_path );
		}

		function wbcom_do_plugin_deactivate( $slug ) {
			$plugin_file_path = $this->_get_plugin_file_path_from_slug( $slug );
			$result = deactivate_plugins( $plugin_file_path );
		}

		function _get_plugin_file_path_from_slug( $slug ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$plugins_list = get_plugins();
			$keys = array_keys( $plugins_list );
			foreach ( $keys as $key ) {
				if ( preg_match( '|^' . $slug . '/|', $key ) ) {
					return $key;
				}
			}
			return $slug;
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
			if ( $installed ) {
				$response = array( 'status' => 'installed' );
				echo wp_send_json_success( $response );
			} else {
				return false;
			}
			exit;
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
			$api = plugins_api( 'plugin_information', array('slug' => $slug, 'fields' => array('sections' => false) ) ); //Save on a bit of bandwidth.

			if ( is_wp_error( $api ) ) {				
				$status['error'] = $api->get_error_message();
				wp_send_json_error( $status );
			}

			return $api->download_link;
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
						'activate_text' => esc_html__( 'Activate', 'bp-member-reviews' ),
						'deactivate_text' => esc_html__( 'Deactivate', 'bp-member-reviews' )
					)
				);
				wp_enqueue_script( 'wbcom_admin_setting_js' );

				wp_enqueue_style( 'wbcom-admin-setting-css', BUPR_PLUGIN_URL . 'admin/assets/css/wbcom-admin-setting.css' );
			}

			if ( function_exists( 'get_current_screen' ) ) {
				$screen = get_current_screen();			
				if ( 'toplevel_page_wbcomplugins' === $screen->base || 'wbcom_page_wbcom-support-page' === $screen->base || 'wbcom_page_wbcom-plugins-page' === $screen->base || 'wbcom_page_wbcom-themes-page' === $screen->base ) {
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
			$page = filter_input( INPUT_GET, 'page' ) ? filter_input( INPUT_GET, 'page' ) : 'wbcom-themes-page';
			$plugin_active = $theme_active = $support_active = $settings_active = '';
			switch( $page ) {
				case 'wbcom-plugins-page': $plugin_active = 'is_active'; 
				                      break;  
				case 'wbcom-themes-page' : $theme_active = 'is_active'; 
				                      break; 
                case 'wbcom-support-page' : $support_active = 'is_active'; 
				                      break; 
				default : $settings_active = 'is_active';                    
			}
			?>
			<div id="wb_admin_header" class="wp-clearfix">

				<div id="wb_admin_logo">
					<img src="<?php echo BUPR_PLUGIN_URL.'admin/assets/imgs/logowbcom.png'; ?>">
					<div class="wb_admin_right"></div>
				</div>

				<nav id="wb_admin_nav">
					<ul>
						<li class="wb_admin_nav_item <?php echo esc_attr( $settings_active ); ?>">
                            <a href="<?php echo get_admin_url().'admin.php?page=wbcomplugins'; ?>" id="wb_admin_nav_trigger_settings">
                                <i class="fa fa-sliders "></i>
                                <h4><?php esc_html_e( 'Settings', 'bp-member-reviews' ); ?></h4>
                            </a>
                        </li>
						<li class="wb_admin_nav_item <?php echo esc_attr( $plugin_active ); ?>">
							<a href="<?php echo get_admin_url().'admin.php?page=wbcom-plugins-page'; ?>" id="wb_admin_nav_trigger_extensions">
								<i class="fa fa-th"></i>
								<h4><?php esc_html_e( 'Components', 'bp-member-reviews' ); ?></h4>
							</a>
						</li>
						<li class="wb_admin_nav_item <?php echo esc_attr( $theme_active ); ?>">
							<a href="<?php echo get_admin_url().'admin.php?page=wbcom-themes-page'; ?>" id="wb_admin_nav_trigger_themes">
								<i class="fa fa-magic"></i>
								<h4><?php esc_html_e( 'Themes', 'bp-member-reviews' ); ?></h4>
							</a>
						</li>
						<li class="wb_admin_nav_item <?php echo esc_attr( $support_active ); ?>">
							<a href="<?php echo get_admin_url().'admin.php?page=wbcom-support-page'; ?>" id="wb_admin_nav_trigger_support">
								<i class="fa fa-question-circle"></i>
								<h4><?php esc_html_e( 'Support', 'bp-member-reviews' ); ?></h4>
							</a>
						</li>
					</ul>
				</nav>
			</div><?php
		}

		public function wbcom_all_free_plugins() {
			$free_plugins = array( 
				'0' => array( 
						'name' => esc_html__( 'BuddyPress Activity Filter', 'bp-member-reviews' ), 
						'slug' => 'bp-activity-filter',
						'description' => esc_html__( 'Admin can set default and customised activities to be listed on front-end.', 'bp-member-reviews' ),
						'status' => $this->wbcom_plugin_status( 'bp-activity-filter' )
						),
				'1' => array( 
						'name' => esc_html__( 'BuddyPress Activity Social Share', 'bp-member-reviews' ), 
						'slug' => 'bp-activity-social-share',
						'description' => esc_html__( 'A perfect plugin to make your user activities on your website social-share-friendly, and increase your members social reach dramatically!', 'bp-member-reviews' ),
						'status' => $this->wbcom_plugin_status( 'bp-activity-social-share' ) 
						),
				'2' => array( 
						'name' => esc_html__( 'BuddyPress Favorite Notification', 'bp-member-reviews' ), 
						'slug' => 'bp-favorite-notification',
						'description' => esc_html__( 'BuddyPress Favorite Notification adds a notification for BuddyPress activity.', 'bp-member-reviews' ),
						'status' => $this->wbcom_plugin_status( 'bp-favorite-notification' )
						),
				'3' => array(
						'name' => esc_html__( 'BuddyPress user ToDo List', 'bp-member-reviews' ), 
						'slug' => 'bp-user-to-do-list',
						'description' => esc_html__( 'Todo List is a modern task personal task management solution for BuddyPress.', 'bp-member-reviews' ),
						'status' => $this->wbcom_plugin_status( 'bp-user-to-do-list' ) 
						),
			);	

			return $free_plugins;
		}

		public function wbcom_plugin_status( $slug ) {
			// if( $this->wbcom_is_plugin_installed( $plugin_slug ) ) {
			// 	// if( $this->wbcom_is_plugin_active( $plugin_slug ) ) {

			// 	// } else {

			// 	// }	
			// } else {

			// }	
		}

		/**
		 * Check if a plugin is active.
		 *
		 * @since 2.5.0
		 *
		 * @param string $slug Plugin slug.
		 * @return bool True if active, false otherwise.
		 */
		// function wbcom_is_plugin_active( $slug ) {
		// 	return ( ( ! empty( $this->plugins[ $slug ]['is_callable'] ) && is_callable( $this->plugins[ $slug ]['is_callable'] ) ) || is_plugin_active( $this->plugins[ $slug ]['file_path'] ) );
		// }

		// function wbcom_is_plugin_installed( $slug ) {
		// 	if ( ! function_exists( 'get_plugins' ) ) {
		// 		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		// 	}
		// 	$plugin_basename = $this->_get_wbcom_plugin_file_path_from_slug( $slug );
		// 	get_plugins( $plugin_folder );

		// 	$installed_plugins = $this->get_plugins(); // Retrieve a list of all installed plugins (WP cached).

		// 	return ( ! empty( $installed_plugins[ $this->plugins[ $slug ]['file_path'] ] ) );
		// }

		function _get_wbcom_plugin_file_path_from_slug( $slug ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			$plugins_list = get_plugins();
			$keys = array_keys( $plugins_list );
			foreach ( $keys as $key ) {
				if ( preg_match( '|^' . $slug . '/|', $key ) ) {
					return $key;
				}
			}
			return $slug;
		}

	}

	function instantiate_wbcom_importer_plugins_manager() {
		new Wbcom_Admin_Settings();
	}
	instantiate_wbcom_importer_plugins_manager();
}