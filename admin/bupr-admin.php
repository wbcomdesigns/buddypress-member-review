<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Add admin page for importing Review(s)
if ( ! class_exists( 'BUPR_Admin' ) ) {
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
			/* Register custom post type review */
			$bupr_post_types = get_post_types();
			if ( ! in_array( 'review', $bupr_post_types ) ) {
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
		 * Include admin option page
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		/*
		public function bupr_admin_options_page() {
			include 'review-admin-options-page.php';
		}*/


		function bupr_admin_options_page( $current = 'general' ) {?>
			<div id="wpbody-content" class="bupr-setting-page" aria-label="Main content" tabindex="0">
				<div class="wrap">
					<div class="bupr-header">
						<div class="bupr-extra-actions">
							<button type="button" class="button button-secondary" onclick="window.open('https://wbcomdesigns.com/contact/', '_blank');"><i class="fa fa-envelope" aria-hidden="true"></i> <?php _e( 'Email Support', 'bp-member-reviews' ); ?></button>
							<button type="button" class="button button-secondary" onclick="window.open('https://wbcomdesigns.com/helpdesk/article-categories/buddypress-user-profile-reviews/', '_blank');"><i class="fa fa-file" aria-hidden="true"></i> <?php _e( 'User Manual', 'bp-member-reviews' ); ?></button>
							<button type="button" class="button button-secondary" onclick="window.open('https://wordpress.org/support/plugin/bp-user-profile-reviews/reviews/', '_blank');"><i class="fa fa-star" aria-hidden="true"></i> <?php _e( 'Rate Us on WordPress.org', 'bp-member-reviews' ); ?></button>
						</div>
					</div>
					<h1>
						<?php _e( 'BuddyPress Member Reviews Settings', 'bp-member-reviews' ); ?>
					</h1>
					<div id="bupr_settings_updated" class="updated settings-error notice is-dismissible">
						<p>
							<strong>
								<?php _e( 'BuddyPress Member Reviews Settings Saved.', 'bp-member-reviews' ); ?>
							</strong>
						</p>
						<button type="button" class="notice-dismiss">
							<span class="screen-reader-text">
								<?php _e( 'BuddyPress Member Reviews Settings Saved.', 'bp-member-reviews' ); ?>
							</span>
						</button>
					</div>

					<?php

					$bupr_tabs = array(
						'general'   => __( 'General', 'bp-member-reviews' ),
						'criteria'  => __( 'Criteria', 'bp-member-reviews' ),
						'shortcode' => __( 'Shortcode', 'bp-member-reviews' ),
						'display'   => __( 'Display', 'bp-member-reviews' ),
						'support'   => __( 'Support', 'bp-member-reviews' ),
					);

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
				'name'              => _x( 'Reviews Category', 'taxonomy general name' ),
				'singular_name'     => _x( 'Review Category', 'taxonomy singular name' ),
				'search_items'      => __( 'Search Categories' ),
				'all_items'         => __( 'All Categories' ),
				'parent_item'       => __( 'Parent Category' ),
				'parent_item_colon' => __( 'Parent Category:' ),
				'edit_item'         => __( 'Edit Category' ),
				'update_item'       => __( 'Update Category' ),
				'add_new_item'      => __( 'Add Category' ),
				'new_item_name'     => __( 'New Category Name' ),
				'menu_name'         => __( 'Category' ),
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
