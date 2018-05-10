<?php
/**
 * BuddyPress Member Review admin function class file.
 *
 * @package BuddyPress_Member_Reviews
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Add admin page for importing Review(s).
if ( ! class_exists( 'BUPR_Admin' ) ) {
	/**
	 * The admin-facing functionality of the plugin.
	 *
	 * @package    BuddyPress_Member_Reviews
	 * @author     wbcomdesigns <admin@wbcomdesigns.com>
	 */
	class BUPR_Admin {

		/**
		 * Constructor.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'bupr_add_submenu_page_admin_settings' ) );
			add_action( 'init', array( $this, 'bupr_add_member_type_criteria_register_setting' ) );
			add_action( 'init', array( $this, 'bupr_update_all_member_types_criteria' ) );
			/* Register custom post type review */
			$bupr_post_types = get_post_types();
			if ( ! in_array( 'review', $bupr_post_types, true ) ) {
				add_action( 'init', array( $this, 'bupr_review_cpt' ) );
				add_action( 'init', array( $this, 'bupr_review_taxonomy_cpt' ) );				
			}
		}

		/**
		 * Actions performed on loading admin_menu
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function bupr_add_submenu_page_admin_settings() {
			add_submenu_page( 'edit.php?post_type=review', __( 'Reviews Admin Settings', 'bp-member-reviews' ), __( 'Member Settings', 'bp-member-reviews' ), 'manage_options', 'bp-member-review-settings', array( $this, 'bupr_admin_options_page' ) );
		}

		/**
		 * Register member type criteria setting.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function bupr_add_member_type_criteria_register_setting() {
			register_setting( 'bupr_member_type_criteria', 'bupr_member_type_criteria' ); // plugin's member type criteria setting.
		}

		public function bupr_update_all_member_types_criteria() {
			$bupr_member_type_criteria = get_option( 'bupr_member_type_criteria' );

			$enabled_criterias = array();
			$bupr_admin_settings = get_option( 'bupr_admin_settings', true );
			if ( ! empty( $bupr_admin_settings ) && ! empty( $bupr_admin_settings['profile_rating_fields'] ) ) {
				$profile_rating_fields = $bupr_admin_settings['profile_rating_fields'];
				foreach ($profile_rating_fields as $criteria => $criteria_setting) {
					if( $criteria_setting == 'yes'){
						$enabled_criterias[$criteria] = $criteria;
					}
				}
			}

			if( empty( $bupr_member_type_criteria ) ){
				$bupr_member_type_criteria = array();
				$bupr_member_type_criteria['all_members'] = $enabled_criterias;
				update_option( 'bupr_member_type_criteria', $bupr_member_type_criteria );
			}
		}

		/**
		 * Include admin option page
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function bupr_admin_options_page( $current = 'general' ) {?>
			<div id="wpbody-content" class="bupr-setting-page" aria-label="Main content" tabindex="0">
				<div class="wrap">
					<div class="bupr-header">
						<div class="bupr-extra-actions">
							<button type="button" class="button button-secondary" onclick="window.open('https://wbcomdesigns.com/contact/', '_blank');"><i class="fa fa-envelope" aria-hidden="true"></i> <?php esc_html_e( 'Email Support', 'bp-member-reviews' ); ?></button>
							<button type="button" class="button button-secondary" onclick="window.open('https://wbcomdesigns.com/helpdesk/article-categories/buddypress-user-profile-reviews/', '_blank');"><i class="fa fa-file" aria-hidden="true"></i> <?php esc_html_e( 'User Manual', 'bp-member-reviews' ); ?></button>
							<button type="button" class="button button-secondary" onclick="window.open('https://wordpress.org/support/plugin/bp-user-profile-reviews/reviews/', '_blank');"><i class="fa fa-star" aria-hidden="true"></i> <?php esc_html_e( 'Rate Us on WordPress.org', 'bp-member-reviews' ); ?></button>
						</div>
					</div>
					<h1>
						<?php esc_html_e( 'BuddyPress Member Reviews Settings', 'bp-member-reviews' ); ?>
					</h1>
					<div id="bupr_settings_updated" class="updated settings-error notice is-dismissible">
						<p>
							<strong>
								<?php esc_html_e( 'BuddyPress Member Reviews Settings Saved.', 'bp-member-reviews' ); ?>
							</strong>
						</p>
						<button type="button" class="notice-dismiss">
							<span class="screen-reader-text">
								<?php esc_html_e( 'BuddyPress Member Reviews Settings Saved.', 'bp-member-reviews' ); ?>
							</span>
						</button>
					</div>

					<?php

					
					if( class_exists( 'BuddyBoss_BMT_Plugin' ) ) {
						$bupr_tabs = array(
						'general'   => __( 'General', 'bp-member-reviews' ),
						'criteria'  => __( 'Criteria', 'bp-member-reviews' ),
						'member_type_criteria' => __( 'Member Types Criteria', 'bp-member-reviews' ),
						'shortcode' => __( 'Shortcode', 'bp-member-reviews' ),
						'display'   => __( 'Display', 'bp-member-reviews' ),
						'support'   => __( 'Support', 'bp-member-reviews' ),
						);
					}else{
						$bupr_tabs = array(
						'general'   => __( 'General', 'bp-member-reviews' ),
						'criteria'  => __( 'Criteria', 'bp-member-reviews' ),
						'shortcode' => __( 'Shortcode', 'bp-member-reviews' ),
						'display'   => __( 'Display', 'bp-member-reviews' ),
						'support'   => __( 'Support', 'bp-member-reviews' ),
						);
					}

					$bupr_tab_html = '<h2 class="nav-tab-wrapper">';
					foreach ( $bupr_tabs as $bupr_tab => $bupr_name ) {
						$class          = ( $bupr_tab == $current ) ? 'nav-tab-active' : '';
						$bupr_tab_html .= '<a class="nav-tab ' . $bupr_tab . '' . $class . '" href="admin.php?page=bp-member-review-settings&tab=' . $bupr_tab . '">' . $bupr_name . '</a>';
					}
					$bupr_tab_html .= '</h2>';
					_e( $bupr_tab_html, 'bp-member-reviews' );
					include 'review-admin-options-page.php';
					?>
				</div>
			</div>
		<?php
		}

		/**
		 * Actions performed to create Review cpt
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function bupr_review_cpt() {
			$labels = array(
				'name'               => __( 'Reviews', 'bp-member-reviews' ),
				'singular_name'      => __( 'Review', 'bp-member-reviews' ),
				'menu_name'          => __( 'Reviews', 'bp-member-reviews' ),
				'name_admin_bar'     => __( 'Reviews', 'bp-member-reviews' ),
				'add_new'            => __( 'Add New Review', 'bp-member-reviews' ),
				'add_new_item'       => __( 'Add New Review', 'bp-member-reviews' ),
				'new_item'           => __( 'New Review', 'bp-member-reviews' ),
				'view_item'          => __( 'View Reviews', 'bp-member-reviews' ),
				'all_items'          => __( 'All Reviews', 'bp-member-reviews' ),
				'search_items'       => __( 'Search Reviews', 'bp-member-reviews' ),
				'parent_item_colon'  => __( 'Parent Review:', 'bp-member-reviews' ),
				'not_found'          => __( 'No Review Found', 'bp-member-reviews' ),
				'not_found_in_trash' => __( 'No Review Found In Trash', 'bp-member-reviews' ),
			);
			$args   = array(
				'labels'             => $labels,
				'public'             => true,
				'menu_icon'          => 'dashicons-testimonial',
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array(
					'slug'       => 'review',
					'with_front' => false,
				),
				'capability_type'    => 'post',
				'capabilities'       => array(
					'create_posts' => 'do_not_allow',
				),
				'map_meta_cap'       => true,
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => null,
				'supports'           => array( 'title', 'editor', 'author', 'thumbnail' ),
			);
			register_post_type( 'review', $args );
		}

		/**
		 * Actions performed to create Review cpt taxonomy
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function bupr_review_taxonomy_cpt() {
			$category_labels = array(
				'name'              => _x( 'Reviews Category', 'taxonomy general name', 'bp-member-reviews' ),
				'singular_name'     => _x( 'Review Category', 'taxonomy singular name', 'bp-member-reviews' ),
				'search_items'      => __( 'Search Categories', 'bp-member-reviews' ),
				'all_items'         => __( 'All Categories', 'bp-member-reviews' ),
				'parent_item'       => __( 'Parent Category', 'bp-member-reviews' ),
				'parent_item_colon' => __( 'Parent Category:', 'bp-member-reviews' ),
				'edit_item'         => __( 'Edit Category', 'bp-member-reviews' ),
				'update_item'       => __( 'Update Category', 'bp-member-reviews' ),
				'add_new_item'      => __( 'Add Category', 'bp-member-reviews' ),
				'new_item_name'     => __( 'New Category Name', 'bp-member-reviews' ),
				'menu_name'         => __( 'Category', 'bp-member-reviews' ),
			);
			$category_args   = array(
				'hierarchical'      => true,
				'labels'            => $category_labels,
				'show_ui'           => false,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => 'review_category' ),
			);
			register_taxonomy( 'review_category', array( 'review' ), $category_args );
		}
	}
	new BUPR_Admin();
}
