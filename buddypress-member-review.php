<?php
/**
 * Plugin Name: Wbcom Designs - BuddyPress Member Reviews
 * Plugin URI: https://wbcomdesigns.com/downloads/buddypress-user-profile-reviews/
 * Description: Enhances the BuddyPress community by allowing registered users to post reviews on other members' profiles. This feature is exclusive to registered members, ensuring unbiased feedback by preventing users from reviewing their own profiles.
 * Version: 3.2.0
 * Author: Wbcom Designs
 * Author URI: https://wbcomdesigns.com
 * License: GPLv2+
 * Text Domain: bp-member-reviews
 * Domain Path: /languages
 *
 * @package BuddyPress_Member_Reviews
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Plugin version and path constants.
 */
define( 'BUPR_PLUGIN_VERSION', '3.2.0' );
define( 'BUPR_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'BUPR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Load plugin textdomain for translations.
 */
if ( ! function_exists( 'bupr_load_textdomain' ) ) {
	add_action( 'init', 'bupr_load_textdomain' );
	function bupr_load_textdomain() {
		$domain = 'bp-member-reviews';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		load_textdomain( $domain, BUPR_PLUGIN_PATH . 'languages/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}
}

/**
 * Include required plugin files after BuddyPress is loaded.
 */
if ( ! function_exists( 'bupr_plugins_files' ) ) {
	add_action( 'plugins_loaded', 'bupr_plugins_files' );
	function bupr_plugins_files() {
		$network_active_plugins = get_site_option( 'active_sitewide_plugins' );
		if ( class_exists( 'BuddyPress' )  && array_key_exists( plugin_basename( __FILE__ ) , $network_active_plugins )) {
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'bupr_admin_page_link' );
			
			// List of required files to include.
			$include_files = array(
				'includes/class-buprglobals.php',
				'admin/wbcom/wbcom-admin-settings.php',
				'includes/bupr-scripts.php',
				'admin/bupr-admin.php',
				'admin/class-bupr-admin-feedback.php',
				'includes/bupr-filters.php',
				'includes/bupr-shortcodes.php',
				'includes/widgets/display-review.php',
				'includes/widgets/member-rating.php',
				'includes/bupr-ajax.php',
				'includes/bupr-notification.php',
				'includes/bupr-general-functions.php',
			);

			foreach ( $include_files as $file ) {
				include_once BUPR_PLUGIN_PATH . $file;
			}
		}
	}
}

/**
 * Add settings link to plugin action links.
 *
 * @param array $links The plugin setting links array.
 *
 * @return array
 */
function bupr_admin_page_link( $links ) {
	$settings_link = array(
		'<a href="' . admin_url( 'admin.php?page=bp-member-review-settings' ) . '">' . esc_html__( 'Settings', 'bp-member-reviews' ) . '</a>',
	);
	return array_merge( $links, $settings_link );
}

/**
 * Deactivate the plugin if BuddyPress is not active.
 */

 function bupr_requires_buddypress() {
	
        if ( ! class_exists( 'BuddyPress' ) ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            add_action( 'admin_notices', 'bupr_required_plugin_admin_notice' );
        }
		elseif( is_multisite() ){
			global $current_blog;
			$current_blog_id = (int) $current_blog->blog_id;
			$network_active_plugins = get_site_option( 'active_sitewide_plugins' );
			if ( ! class_exists( 'BuddyPress' ) || !array_key_exists( plugin_basename( __FILE__ ) , $network_active_plugins )) {
				switch_to_blog( $current_blog_id );
				deactivate_plugins( plugin_basename( __FILE__ ) );
				restore_current_blog();
			}
		}
}
add_action( 'admin_init', 'bupr_requires_buddypress' );


/**
 * Deactivate the plugin if BuddyPress is not active on multisite.
 */
function modify_sitewide_plugins($value) {
    global $current_blog;
	$current_blog_id = (int) $current_blog->blog_id;
	$dependent_plugin_basename = 'buddypress/bp-loader.php';
	if ( !is_network_admin() ) {
		if( ! array_key_exists( $dependent_plugin_basename, $value ) ){
			add_action( 'admin_notices', 'bupr_required_plugin_admin_notice' );
			unset($value[plugin_basename( __FILE__ )]);
		}else{
			if(defined( 'BP_ROOT_BLOG' ) && ( BP_ROOT_BLOG != $current_blog_id )){
				add_action( 'admin_notices', 'bupr_show_buddypress_root_blog_notice' );
				unset($value[plugin_basename( __FILE__ )]);
			}elseif(!defined( 'BP_ROOT_BLOG' ) &&  !is_main_site($current_blog_id) ){
				add_action( 'admin_notices', 'bupr_show_buddypress_root_blog_notice' );
				unset($value[plugin_basename( __FILE__ )]);
			}
		}
	}else{
		if( ! array_key_exists( $dependent_plugin_basename , $value ) ){
			add_action( 'admin_notices', 'bupr_required_plugin_admin_notice' );
			unset($value[plugin_basename( __FILE__ )]);
		}
	}
    return $value;
}

add_filter('site_option_active_sitewide_plugins', 'modify_sitewide_plugins');



/**
 * Admin notice to indicate BuddyPress is required.
 */
function bupr_required_plugin_admin_notice() {
	$plugin_name = esc_html__( 'BuddyPress Member Reviews', 'bp-member-reviews' );
	$bp_plugin   = esc_html__( 'BuddyPress', 'bp-member-reviews' );
	echo '<div class="error"><p>';
	printf(
		 /* translators: %1$s: BuddyPress Member Reviews; %2$s: Buddypress. */
		esc_html__( '%1$s requires %2$s to be installed and active.', 'bp-member-reviews' ),
		'<strong>' . esc_html( $plugin_name ) . '</strong>',
		'<strong>' . esc_html( $bp_plugin ) . '</strong>'
	);
	echo '</p></div>';
}

/**
 * Show admin notice to inform the user that To-Do List can only be activated on the BuddyPress root blog.
 */
function bupr_show_buddypress_root_blog_notice() {
    echo '<div class="notice notice-error is-dismissible">';
    echo '<p>' . esc_html__( 'Wbcom Designs BP Member Review can only be activated on the BuddyPress root blog.', 'bp-member-reviews' ) . '</p>';
    echo '</div>';
}

/**
 * Redirect to plugin settings page after activation.
 */
function bupr_activation_redirect_settings( $plugin ) {
	if ( $plugin === plugin_basename( __FILE__ ) && class_exists( 'BuddyPress' ) && ! is_multisite() ) {
		wp_redirect( admin_url( 'admin.php?page=bp-member-review-settings' ) );
		exit;
	}
}
add_action( 'activated_plugin', 'bupr_activation_redirect_settings' );

/**
 * Perform plugin activation redirect.
 */
function bp_member_review_do_activation_redirect() {
	if ( get_transient( '_bp_member_review_is_new_install' ) && ! is_multisite() ) {
		delete_transient( '_bp_member_review_is_new_install' );
		wp_safe_redirect( admin_url( 'admin.php?page=bp-member-review-settings' ) );
	}
}
add_action( 'admin_init', 'bp_member_review_do_activation_redirect' );

/**
 * Translate site URL using WPML for frontend use.
 *
 * @param string $url The URL to translate.
 *
 * @return string Translated URL.
 */
function bupr_site_url( $url ) {
	if ( ! is_admin() && false === strpos( $url, 'wp-admin' ) ) {
		return untrailingslashit( apply_filters( 'wpml_home_url', $url ) );
	}
	return $url;
}

/**
 * Unschedule the cron event on plugin deactivation.
 */
function bupr_unschedule_review_recalculation() {
    $timestamp = wp_next_scheduled( 'bupr_cron_recalculate_user_reviews_batch' );
    if ( $timestamp ) {
        wp_unschedule_event( $timestamp, 'bupr_cron_recalculate_user_reviews_batch' );
    }
    delete_option( 'bupr_current_batch' ); // Clean up the stored batch number
}
register_deactivation_hook( __FILE__, 'bupr_unschedule_review_recalculation' );

