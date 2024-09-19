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
		if ( class_exists( 'BuddyPress' ) ) {
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
}
add_action( 'admin_init', 'bupr_requires_buddypress' );

/**
 * Admin notice to indicate BuddyPress is required.
 */
function bupr_required_plugin_admin_notice() {
	$plugin_name = esc_html__( 'BuddyPress Member Reviews', 'bp-member-reviews' );
	$bp_plugin   = esc_html__( 'BuddyPress', 'bp-member-reviews' );
	echo '<div class="error"><p>';
	printf(
		esc_html__( '%1$s requires %2$s to be installed and active.', 'bp-member-reviews' ),
		'<strong>' . esc_html( $plugin_name ) . '</strong>',
		'<strong>' . esc_html( $bp_plugin ) . '</strong>'
	);
	echo '</p></div>';
}

/**
 * Redirect to plugin settings page after activation.
 */
function bupr_activation_redirect_settings( $plugin ) {
	if ( $plugin === plugin_basename( __FILE__ ) && class_exists( 'BuddyPress' ) ) {
		wp_redirect( admin_url( 'admin.php?page=bp-member-review-settings' ) );
		exit;
	}
}
add_action( 'activated_plugin', 'bupr_activation_redirect_settings' );

/**
 * Perform plugin activation redirect.
 */
function bp_member_review_do_activation_redirect() {
	if ( get_transient( '_bp_member_review_is_new_install' ) ) {
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
