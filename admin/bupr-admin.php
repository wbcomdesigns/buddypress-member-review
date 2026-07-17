<?php // phpcs:disable WordPress.Files.FileName.InvalidClassFileName
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

			// Admin menu, settings page, settings registration, and notice
			// suppression are owned by BUPR_Admin_Panel (the card-panel
			// admin) as of 3.8.0. This class is retained for its non-UI
			// service logic: the review CPT + taxonomy, the rating meta box,
			// the flag/report tooling + admin-post handlers, the pending-count
			// menu badge, the review->activity/email transition, the criteria
			// pre-update transform, and the shared sanitizer the panel calls.
			add_action( 'admin_menu', array( $this, 'bupr_get_review_count' ) );
			add_filter( 'pre_update_option_bupr_admin_settings', array( $this, 'bupr_update_admin_settings' ), 10, 2 );
			add_action( 'transition_post_status', array( $this, 'bupr_generate_activity_review_status_change' ), 10, 3 );
			/* Register custom post type review */
			$bupr_post_types = get_post_types();
			if ( ! in_array( 'review', $bupr_post_types, true ) ) {
				add_action( 'init', array( $this, 'bupr_review_cpt' ) );
				add_action( 'init', array( $this, 'bupr_review_taxonomy_cpt' ) );
				add_action( 'add_meta_boxes', array( $this, 'bupr_review_add_meta_box_ratings' ) );
			}

			// Flagged reviews admin columns and filters.
			add_filter( 'manage_review_posts_columns', array( $this, 'bupr_add_flag_column' ) );
			add_action( 'manage_review_posts_custom_column', array( $this, 'bupr_flag_column_content' ), 10, 2 );
			add_filter( 'views_edit-review', array( $this, 'bupr_add_flagged_filter_link' ) );
			add_action( 'pre_get_posts', array( $this, 'bupr_filter_flagged_reviews' ) );
			add_action( 'add_meta_boxes', array( $this, 'bupr_add_flag_meta_box' ) );

			// Bulk action for flagged reviews.
			add_filter( 'bulk_actions-edit-review', array( $this, 'bupr_add_flag_bulk_actions' ) );
			add_filter( 'handle_bulk_actions-edit-review', array( $this, 'bupr_handle_flag_bulk_actions' ), 10, 3 );
			add_action( 'admin_notices', array( $this, 'bupr_flag_bulk_action_notice' ) );

			// Admin-post handlers for flag actions.
			add_action( 'admin_post_bupr_dismiss_flag', array( $this, 'bupr_handle_dismiss_flag' ) );
			add_action( 'admin_post_bupr_resolve_flag', array( $this, 'bupr_handle_resolve_flag' ) );
		}

		/**
		 *  Function to add meta box at custom post type page.
		 *
		 *  @since 3.3.2
		 */
		public function bupr_review_add_meta_box_ratings() {
			add_meta_box(
				'custom_meta_box_ratings',
				__( 'Member Ratings', 'bp-member-reviews' ),
				array( $this, 'bupr_review_render_custom_meta_box_ratings' ),
				'review',
				'side',
				'low'
			);
		}

		/**
		 *  Function to display Member Ratings at custom post type.
		 *
		 *  @since 3.3.2
		 */
		public function bupr_review_render_custom_meta_box_ratings() {
			global $post;

			if ( empty( $post ) || 'review' !== $post->post_type ) {
				return;
			}

			$member_review_ratings = get_post_meta( $post->ID, 'profile_star_rating', true );

			wp_enqueue_style( 'bupr-font-awesome-2', BUPR_PLUGIN_URL . 'assets/css/fontawesome/all.min.css', array(), BUPR_PLUGIN_VERSION, 'all' );

			if ( ! empty( $member_review_ratings ) && is_array( $member_review_ratings ) ) {

				foreach ( $member_review_ratings as $field_name => $bupr_value ) {

					if ( ! empty( $field_name ) ) {
						echo esc_attr( $field_name );
					}

					/*** Star rating Ratings */
					$stars_on  = $bupr_value;
					$stars_off = 5 - $stars_on;
					echo '<div>';
					for ( $i = 1; $i <= $stars_on; $i++ ) {
						?>
						<span class="fas fa-star bupr-star-rate"></span>
						<?php
					}

					for ( $i = 1; $i <= $stars_off; $i++ ) {
						?>
						<span class="far fa-star stars bupr-star-rate"></span>
						<?php
					}
					/*star rating end */
					echo '</div>';

				}
			}
		}

		/**
		 * Transform the criteria builder rows into profile_rating_fields.
		 *
		 * Hooked to pre_update_option_bupr_admin_settings (registered in the
		 * constructor). Runs AFTER the panel's sanitize_criteria(), so the
		 * raw rating_field_name[] rows are still present here.
		 *
		 * The stored shape is a map, `array( 'Member Response' => 'yes', ... )`.
		 * Only the KEY carries meaning: it is the criterion identifier, it is
		 * what every consumer reads, and bupr_get_criteria_label() translates it
		 * at render time. Keep the map shape - existing sites have it.
		 *
		 * The VALUE is currently read nowhere. It was meant to drive a
		 * per-criterion "show this one" checkbox in the criteria builder that
		 * was never built: this method used to derive yes/no from a POST field
		 * named `rating_field_name_display[]` that no template has ever
		 * rendered. The field was therefore never submitted, the lookup never
		 * matched, and EVERY save wrote 'no' to EVERY criterion - flipping a
		 * fresh site's seeded 'yes' the first time anyone touched the tab.
		 *
		 * That was invisible because nothing reads the value, which is exactly
		 * what made it dangerous: the first reader to treat 'no' as "hidden"
		 * would have silently emptied the criteria list on every site that had
		 * ever saved this screen. The dead lookup is gone and the value is
		 * written as the seeded default. Do not resurrect a yes/no here without
		 * ALSO rendering the checkbox and adding a reader - deriving state from
		 * a field that does not exist is what caused this.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 *
		 * @param array $new_value The new option value.
		 * @param array $old_value The old option value (unused).
		 * @return array The modified option value.
		 */
		public function bupr_update_admin_settings( $new_value, $old_value ) {
			if ( ! empty( $new_value ) && array_key_exists( 'rating_field_name', $new_value ) ) {
				$rating_fields = isset( $new_value['rating_field_name'] ) ? $new_value['rating_field_name'] : '';
				$criteria_arr  = array();
				if ( ! empty( $rating_fields ) && isset( $rating_fields ) ) {
					foreach ( $rating_fields as $fname ) {
						// Check if $fname is not empty before adding it to the array.
						if ( ! empty( trim( $fname ) ) ) {
							$criteria_arr[ $fname ] = 'yes';
						}
					}
					unset( $new_value['rating_field_name'] );
					unset( $new_value['rating_field_name_display'] );
					$new_value['profile_rating_fields'] = $criteria_arr;
				}
			}
			return $new_value;
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
				'name'               => esc_html__( 'Reviews', 'bp-member-reviews' ),
				'singular_name'      => esc_html__( 'Review', 'bp-member-reviews' ),
				'menu_name'          => esc_html__( 'Reviews', 'bp-member-reviews' ),
				'name_admin_bar'     => esc_html__( 'Reviews', 'bp-member-reviews' ),
				'add_new'            => esc_html__( 'Add New Review', 'bp-member-reviews' ),
				'add_new_item'       => esc_html__( 'Add New Review', 'bp-member-reviews' ),
				'new_item'           => esc_html__( 'New Review', 'bp-member-reviews' ),
				'view_item'          => esc_html__( 'View Reviews', 'bp-member-reviews' ),
				'all_items'          => esc_html__( 'All Reviews', 'bp-member-reviews' ),
				'search_items'       => esc_html__( 'Search Reviews', 'bp-member-reviews' ),
				'parent_item_colon'  => esc_html__( 'Parent Review:', 'bp-member-reviews' ),
				'not_found'          => esc_html__( 'No Review Found', 'bp-member-reviews' ),
				'not_found_in_trash' => esc_html__( 'No Review Found In Trash', 'bp-member-reviews' ),
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
				'supports'           => array( 'title', 'editor', 'author' ),
			);
			register_post_type( 'review', $args );
		}

		/**
		 * Get review count and display in admin menu.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function bupr_get_review_count() {
			global $bupr, $menu; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- Intentional WordPress admin menu modification.
			if ( 'yes' !== $bupr['auto_approve_reviews'] ) {

				foreach ( $menu as $each_menu ) {
					if ( 'edit.php?post_type=review' === $each_menu[2] ) {
						$count = wp_count_posts( 'review' );
						if ( $count ) {
							$count = $count->draft;

							$key = $this->bupr_recursive_array_search( 'edit.php?post_type=review', $menu );

							if ( false === $key ) {
								return;
							}

							// phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- Intentional WordPress admin menu modification.
							$menu[ $key ][0] .= sprintf(
								'<span class="awaiting-mod update-plugins count-%1$s"><span class="plugin-count">%1$s</span></span>',
								$count
							);
						}
					}
				}
			}
		}

		/**
		 * Recursively search for a value in an array.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param string $needle   The value to search for.
		 * @param array  $haystack The array to search in.
		 * @return int|false The array key if found, false otherwise.
		 */
		public function bupr_recursive_array_search( $needle, $haystack ) {
			foreach ( $haystack as $key => $value ) {
				$current_key = $key;
				if (
					$needle === $value
					|| (
						is_array( $value )
						&& false !== $this->bupr_recursive_array_search( $needle, $value )
					)
				) {
					return $current_key;
				}
			}
			return false;
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
				'name'              => esc_html_x( 'Reviews Category', 'taxonomy general name', 'bp-member-reviews' ),
				'singular_name'     => esc_html_x( 'Review Category', 'taxonomy singular name', 'bp-member-reviews' ),
				'search_items'      => esc_html__( 'Search Categories', 'bp-member-reviews' ),
				'all_items'         => esc_html__( 'All Categories', 'bp-member-reviews' ),
				'parent_item'       => esc_html__( 'Parent Category', 'bp-member-reviews' ),
				'parent_item_colon' => esc_html__( 'Parent Category:', 'bp-member-reviews' ),
				'edit_item'         => esc_html__( 'Edit Category', 'bp-member-reviews' ),
				'update_item'       => esc_html__( 'Update Category', 'bp-member-reviews' ),
				'add_new_item'      => esc_html__( 'Add Category', 'bp-member-reviews' ),
				'new_item_name'     => esc_html__( 'New Category Name', 'bp-member-reviews' ),
				'menu_name'         => esc_html__( 'Category', 'bp-member-reviews' ),
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

		/**
		 * Generate activity when review status changes from draft to publish.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param string  $new_status The new post status.
		 * @param string  $old_status The old post status.
		 * @param WP_Post $post       The post object.
		 */
		public function bupr_generate_activity_review_status_change( $new_status, $old_status, $post ) {
			global $bupr;
			$bupr_member_id = get_post_meta( $post->ID, 'linked_bp_member', true );
			if ( '' !== $bupr_member_id ) {
				if ( ( 'publish' === $new_status && 'draft' === $old_status ) && 'review' === $post->post_type ) {

					do_action( 'bupr_member_review_after_review_insert', $post->ID, $bupr_member_id );
					do_action( 'bupr_sent_review_notification', $bupr_member_id, $post->ID );

					$bupr_admin_general = get_option( 'bupr_admin_general_options' );
					$site_name          = get_bloginfo( 'name' );
					$site_admins        = get_users( array( 'role' => 'administrator' ) );
					$anonymous_review   = get_post_meta( $post->ID, 'bupr_anonymous_review_post', true );
					$bupr_sender_data   = get_userdata( $post->post_author );
					$bupr_reciever_data = get_userdata( $bupr_member_id );

					// Bail if sender or receiver user data is not found.
					if ( ! $bupr_sender_data || ! $bupr_reciever_data ) {
						return;
					}

					$bupr_sender_email   = $bupr_sender_data->data->user_email;
					$bupr_reciever_email = $bupr_reciever_data->data->user_email;
					$bupr_reciever_name  = $bupr_reciever_data->data->user_nicename;
					$bupr_reciever_login = $bupr_reciever_data->data->user_login;
					if ( isset( $anonymous_review ) && 'yes' === $anonymous_review ) {
						$user_name = __( 'An anonymous user', 'bp-member-reviews' );
					} else {
						$user_name = $bupr_sender_data->display_name;
					}
					$review_subject = sprintf(
						/* translators: 1: User display name, 2: Review tab name (singular). */
						__( '%1$s received a %2$s', 'bp-member-reviews' ),
						$bupr_reciever_name,
						bupr_profile_review_tab_singular_slug()
					);

					// Check BuddyPress version and use the appropriate function.
					// Check if the bp_members_get_user_url() function exists (introduced in BuddyPress 12.0.0).
					if ( function_exists( 'bp_members_get_user_url' ) ) {
						// Use bp_members_get_user_url() if it exists (for BuddyPress v12.0.0 and above).
						$bupr_review_url = bp_members_get_user_url( $bupr_member_id ) . sanitize_title( $bupr['review_label_plural'] ) . '/view/' . $post->ID;
					} else {
						// Fall back to bp_core_get_user_domain() for older versions of BuddyPress.
						$bupr_review_url = bp_core_get_user_domain( $bupr_member_id ) . sanitize_title( $bupr['review_label_plural'] ) . '/view/' . $post->ID;
					}
					$bupr_to = $bupr_reciever_email;
					// Define a default value for $bupr_subject with translation support.
					$bupr_subject = __( 'New Review on Your Profile at [site-name]', 'bp-member-reviews' );
					$review_link  = '<a href="' . $bupr_review_url . '">' . $review_subject . '</a>';
					// Check if a custom subject line is set in the settings and update $bupr_subject accordingly.
					if ( isset( $bupr_admin_general['review_email_subject'] ) && ! empty( $bupr_admin_general['review_email_subject'] ) ) {
						$bupr_subject = $bupr_admin_general['review_email_subject'];
					}

					// Replace '[site-name]' placeholder in the subject with the actual site name.
					$bupr_subject = str_replace( '[site-name]', $site_name, $bupr_subject );

					/* translators: [user-name], [reviewer-name], [review-link] and [site-name] are tokens replaced at send time - keep them verbatim. */
					$message = __( 'Hello [user-name], <br><br>We are pleased to inform you that [reviewer-name] has recently reviewed your profile.<br><br>To view the review, simply click on the link below:<br>[review-link]<br><br>Best regards,<br>The [site-name] Team', 'bp-member-reviews' );
					if ( isset( $bupr_admin_general['review_email_message'] ) && ! empty( $bupr_admin_general['review_email_message'] ) ) {
						$bupr_message = str_replace(
							array( '[user-name]', '[reviewer-name]', '[review-link]', '[site-name]' ),
							array( $bupr_reciever_name, $user_name, $review_link, $site_name ),
							$bupr_admin_general['review_email_message']
						);
					} else {
						$bupr_message = str_replace(
							array( '[user-name]', '[reviewer-name]', '[review-link]', '[site-name]' ),
							array( $bupr_reciever_name, $user_name, $review_link, $site_name ),
							$message
						);
					}
					$bupr_header = array( 'Content-Type: text/html; charset=UTF-8' );
					wp_mail( $bupr_to, $bupr_subject, nl2br( $bupr_message ), $bupr_header );
				}
			}
		}

		/**
		 * Sanitize options array recursively.
		 *
		 * @param array $options The options to sanitize.
		 * @return array Sanitized options.
		 */
		public function bupr_sanitize_options( $options ) {
			if ( ! is_array( $options ) ) {
				return array();
			}

			$sanitized = array();
			foreach ( $options as $key => $value ) {
				$key = sanitize_key( $key );
				if ( is_array( $value ) ) {
					$sanitized[ $key ] = $this->bupr_sanitize_options( $value );
				} elseif ( is_bool( $value ) ) {
					$sanitized[ $key ] = (bool) $value;
				} elseif ( is_numeric( $value ) ) {
					$sanitized[ $key ] = is_float( $value ) ? (float) $value : (int) $value;
				} else {
					$sanitized[ $key ] = sanitize_text_field( $value );
				}
			}

			return $sanitized;
		}

		/**
		 * Add flag column to reviews list table.
		 *
		 * @since 3.8.0
		 * @param array $columns The existing columns.
		 * @return array Modified columns.
		 */
		public function bupr_add_flag_column( $columns ) {
			$new_columns = array();
			foreach ( $columns as $key => $value ) {
				$new_columns[ $key ] = $value;
				if ( 'title' === $key ) {
					$new_columns['bupr_flags'] = __( 'Reports', 'bp-member-reviews' );
				}
			}
			return $new_columns;
		}

		/**
		 * Display flag column content.
		 *
		 * @since 3.8.0
		 * @param string $column  The column name.
		 * @param int    $post_id The post ID.
		 */
		public function bupr_flag_column_content( $column, $post_id ) {
			if ( 'bupr_flags' !== $column ) {
				return;
			}

			$flag_count  = get_post_meta( $post_id, 'bupr_flag_count', true );
			$flag_status = get_post_meta( $post_id, 'bupr_flag_status', true );

			if ( empty( $flag_count ) || 0 === (int) $flag_count ) {
				echo '—';
				return;
			}

			$status_class = 'flagged' === $flag_status ? 'bupr-status-flagged' : '';
			$status_label = '';
			if ( 'resolved' === $flag_status ) {
				$status_label = ' <span class="bupr-status-resolved">(' . esc_html__( 'Resolved', 'bp-member-reviews' ) . ')</span>';
			} elseif ( 'dismissed' === $flag_status ) {
				$status_label = ' <span class="bupr-status-dismissed">(' . esc_html__( 'Dismissed', 'bp-member-reviews' ) . ')</span>';
			}

			printf(
				'<span class="bupr-flag-count %s">%d %s</span>%s',
				esc_attr( $status_class ),
				(int) $flag_count,
				esc_html( _n( 'report', 'reports', (int) $flag_count, 'bp-member-reviews' ) ),
				wp_kses_post( $status_label )
			);
		}

		/**
		 * Add flagged filter link to views.
		 *
		 * @since 3.8.0
		 * @param array $views The existing views.
		 * @return array Modified views.
		 */
		public function bupr_add_flagged_filter_link( $views ) {
			$flagged_count = $this->bupr_get_flagged_reviews_count();

			if ( $flagged_count > 0 ) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Checking query param for view highlight only.
				$current          = isset( $_GET['bupr_flagged'] ) && '1' === sanitize_text_field( wp_unslash( $_GET['bupr_flagged'] ) ) ? 'current' : '';
				$views['flagged'] = sprintf(
					'<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
					esc_url(
						add_query_arg(
							array(
								'post_type'    => 'review',
								'bupr_flagged' => '1',
							),
							admin_url( 'edit.php' )
						)
					),
					esc_attr( $current ),
					esc_html__( 'Reported', 'bp-member-reviews' ),
					(int) $flagged_count
				);
			}

			return $views;
		}

		/**
		 * Get count of flagged reviews.
		 *
		 * @since 3.8.0
		 * @return int The count of flagged reviews.
		 */
		private function bupr_get_flagged_reviews_count() {
			global $wpdb;
			return (int) $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(DISTINCT p.ID) FROM {$wpdb->posts} p
					INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
					WHERE p.post_type = %s
					AND p.post_status IN ('publish', 'draft')
					AND pm.meta_key = 'bupr_flag_status'
					AND pm.meta_value = 'flagged'",
					'review'
				)
			);
		}

		/**
		 * Filter reviews to show only flagged ones.
		 *
		 * @since 3.8.0
		 * @param WP_Query $query The query object.
		 */
		public function bupr_filter_flagged_reviews( $query ) {
			if ( ! is_admin() || ! $query->is_main_query() ) {
				return;
			}

			$screen = get_current_screen();
			if ( ! $screen || 'edit-review' !== $screen->id ) {
				return;
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Query filter based on URL param, no state change.
			if ( isset( $_GET['bupr_flagged'] ) && '1' === sanitize_text_field( wp_unslash( $_GET['bupr_flagged'] ) ) ) {
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Necessary for flagged filter functionality.
				$query->set(
					'meta_query',
					array(
						array(
							'key'     => 'bupr_flag_status',
							'value'   => 'flagged',
							'compare' => '=',
						),
					)
				);
			}
		}

		/**
		 * Add meta box for flag details.
		 *
		 * @since 3.8.0
		 */
		public function bupr_add_flag_meta_box() {
			add_meta_box(
				'bupr_flag_details',
				__( 'Report Details', 'bp-member-reviews' ),
				array( $this, 'bupr_render_flag_meta_box' ),
				'review',
				'side',
				'default'
			);
		}

		/**
		 * Render the flag meta box content.
		 *
		 * @since 3.8.0
		 */
		public function bupr_render_flag_meta_box() {
			global $post;

			$flags       = get_post_meta( $post->ID, 'bupr_flags', true );
			$flag_status = get_post_meta( $post->ID, 'bupr_flag_status', true );

			if ( empty( $flags ) || ! is_array( $flags ) ) {
				echo '<p>' . esc_html__( 'No reports for this review.', 'bp-member-reviews' ) . '</p>';
				return;
			}

			echo '<div class="bupr-flag-reports">';
			echo '<p><strong>' . esc_html__( 'Status:', 'bp-member-reviews' ) . '</strong> ';
			switch ( $flag_status ) {
				case 'flagged':
					echo '<span style="color: #d63638;">' . esc_html__( 'Pending Review', 'bp-member-reviews' ) . '</span>';
					break;
				case 'resolved':
					echo '<span style="color: #00a32a;">' . esc_html__( 'Resolved', 'bp-member-reviews' ) . '</span>';
					break;
				case 'dismissed':
					echo '<span style="color: #787c82;">' . esc_html__( 'Dismissed', 'bp-member-reviews' ) . '</span>';
					break;
				default:
					echo esc_html__( 'None', 'bp-member-reviews' );
			}
			echo '</p>';

			echo '<p><strong>' . esc_html__( 'Total Reports:', 'bp-member-reviews' ) . '</strong> ' . count( $flags ) . '</p>';

			echo '<ul style="margin-left: 15px;">';
			foreach ( $flags as $flag ) {
				$reporter      = get_userdata( $flag['reporter_id'] );
				$reporter_name = $reporter ? $reporter->display_name : __( 'Unknown', 'bp-member-reviews' );
				$date          = isset( $flag['date'] ) ? date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $flag['date'] ) : '';

				echo '<li style="margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px solid #ddd;">';
				echo '<strong>' . esc_html( $flag['reason_label'] ) . '</strong><br>';
				echo '<small>' . esc_html__( 'By:', 'bp-member-reviews' ) . ' ' . esc_html( $reporter_name ) . '</small>';
				if ( $date ) {
					echo '<br><small>' . esc_html( $date ) . '</small>';
				}
				echo '</li>';
			}
			echo '</ul>';

			if ( 'flagged' === $flag_status ) {
				echo '<div style="margin-top: 15px;">';
				$dismiss_url = wp_nonce_url(
					add_query_arg(
						array(
							'action'    => 'bupr_dismiss_flag',
							'review_id' => $post->ID,
						),
						admin_url( 'admin-post.php' )
					),
					'bupr_dismiss_flag_' . $post->ID
				);
				$resolve_url = wp_nonce_url(
					add_query_arg(
						array(
							'action'    => 'bupr_resolve_flag',
							'review_id' => $post->ID,
						),
						admin_url( 'admin-post.php' )
					),
					'bupr_resolve_flag_' . $post->ID
				);
				echo '<a href="' . esc_url( $dismiss_url ) . '" class="button">' . esc_html__( 'Dismiss Reports', 'bp-member-reviews' ) . '</a> ';
				echo '<a href="' . esc_url( $resolve_url ) . '" class="button button-primary">' . esc_html__( 'Mark Resolved', 'bp-member-reviews' ) . '</a>';
				echo '</div>';
			}

			echo '</div>';
		}

		/**
		 * Add bulk actions for flagged reviews.
		 *
		 * @since 3.8.0
		 * @param array $actions Existing bulk actions.
		 * @return array Modified bulk actions.
		 */
		public function bupr_add_flag_bulk_actions( $actions ) {
			$actions['bupr_dismiss_flags'] = __( 'Dismiss Reports', 'bp-member-reviews' );
			$actions['bupr_resolve_flags'] = __( 'Resolve Reports', 'bp-member-reviews' );
			return $actions;
		}

		/**
		 * Handle bulk actions for flagged reviews.
		 *
		 * @since 3.8.0
		 * @param string $redirect_to The redirect URL.
		 * @param string $doaction    The action being taken.
		 * @param array  $post_ids    The posts IDs.
		 * @return string Modified redirect URL.
		 */
		public function bupr_handle_flag_bulk_actions( $redirect_to, $doaction, $post_ids ) {
			if ( 'bupr_dismiss_flags' !== $doaction && 'bupr_resolve_flags' !== $doaction ) {
				return $redirect_to;
			}

			$new_status = 'bupr_dismiss_flags' === $doaction ? 'dismissed' : 'resolved';
			$updated    = 0;

			foreach ( $post_ids as $post_id ) {
				$current_status = get_post_meta( $post_id, 'bupr_flag_status', true );
				if ( 'flagged' === $current_status ) {
					update_post_meta( $post_id, 'bupr_flag_status', $new_status );
					++$updated;
				}
			}

			return add_query_arg( 'bupr_flags_updated', $updated, $redirect_to );
		}

		/**
		 * Display admin notice for bulk action result.
		 *
		 * @since 3.8.0
		 */
		public function bupr_flag_bulk_action_notice() {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce not needed for displaying notices.
			if ( ! empty( $_GET['bupr_flags_updated'] ) ) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce not needed for displaying notices.
				$count = absint( wp_unslash( $_GET['bupr_flags_updated'] ) );
				printf(
					'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
					sprintf(
						/* translators: %d: Number of reviews updated */
						esc_html( _n( '%d review report status updated.', '%d reviews report statuses updated.', $count, 'bp-member-reviews' ) ),
						intval( $count )
					)
				);
			}

			// Report dismissed notice.
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce not needed for displaying notices.
			if ( ! empty( $_GET['bupr_flag_dismissed'] ) ) {
				printf(
					'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
					esc_html__( 'Review reports have been dismissed.', 'bp-member-reviews' )
				);
			}

			// Report resolved notice.
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce not needed for displaying notices.
			if ( ! empty( $_GET['bupr_flag_resolved'] ) ) {
				printf(
					'<div class="notice notice-success is-dismissible"><p>%s</p></div>',
					esc_html__( 'Review reports have been marked as resolved.', 'bp-member-reviews' )
				);
			}
		}

		/**
		 * Handle dismiss flag action from admin-post.
		 *
		 * @since 3.8.0
		 */
		public function bupr_handle_dismiss_flag() {
			// Verify nonce.
			$review_id = isset( $_GET['review_id'] ) ? absint( wp_unslash( $_GET['review_id'] ) ) : 0;
			$nonce     = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';
			if ( 0 === $review_id || ! wp_verify_nonce( $nonce, 'bupr_dismiss_flag_' . $review_id ) ) {
				wp_die( esc_html__( 'Security check failed.', 'bp-member-reviews' ) );
			}

			// Check user capabilities.
			if ( ! current_user_can( 'edit_post', $review_id ) ) {
				wp_die( esc_html__( 'You do not have permission to perform this action.', 'bp-member-reviews' ) );
			}

			// Verify the post exists and is a review.
			$review = get_post( $review_id );
			if ( ! $review || 'review' !== $review->post_type ) {
				wp_die( esc_html__( 'Invalid review.', 'bp-member-reviews' ) );
			}

			// Update flag status to dismissed.
			update_post_meta( $review_id, 'bupr_flag_status', 'dismissed' );

			// Redirect back to the review edit page.
			$redirect_url = add_query_arg(
				array(
					'post'                => $review_id,
					'action'              => 'edit',
					'bupr_flag_dismissed' => 1,
				),
				admin_url( 'post.php' )
			);

			wp_safe_redirect( $redirect_url );
			exit;
		}

		/**
		 * Handle resolve flag action from admin-post.
		 *
		 * @since 3.8.0
		 */
		public function bupr_handle_resolve_flag() {
			// Verify nonce.
			$review_id = isset( $_GET['review_id'] ) ? absint( wp_unslash( $_GET['review_id'] ) ) : 0;
			$nonce     = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';
			if ( 0 === $review_id || ! wp_verify_nonce( $nonce, 'bupr_resolve_flag_' . $review_id ) ) {
				wp_die( esc_html__( 'Security check failed.', 'bp-member-reviews' ) );
			}

			// Check user capabilities.
			if ( ! current_user_can( 'edit_post', $review_id ) ) {
				wp_die( esc_html__( 'You do not have permission to perform this action.', 'bp-member-reviews' ) );
			}

			// Verify the post exists and is a review.
			$review = get_post( $review_id );
			if ( ! $review || 'review' !== $review->post_type ) {
				wp_die( esc_html__( 'Invalid review.', 'bp-member-reviews' ) );
			}

			// Update flag status to resolved.
			update_post_meta( $review_id, 'bupr_flag_status', 'resolved' );

			// Redirect back to the review edit page.
			$redirect_url = add_query_arg(
				array(
					'post'               => $review_id,
					'action'             => 'edit',
					'bupr_flag_resolved' => 1,
				),
				admin_url( 'post.php' )
			);

			wp_safe_redirect( $redirect_url );
			exit;
		}
	}
	new BUPR_Admin();
}
