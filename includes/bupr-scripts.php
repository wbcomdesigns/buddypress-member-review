<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Class to add custom scripts and styles.
 *
 * @since    1.0.0
 * @author   Wbcom Designs
 * @package BuddyPress_Member_Reviews
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'BUPRScriptsStyles' ) ) {
	/**
	 * Class to add custom scripts and styles.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @author   Wbcom Designs
	 */
	class BUPRScriptsStyles {

		/**
		 * Constructor.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function __construct() {

			// Add Scripts only on reviews tab.
			add_action( 'wp_enqueue_scripts', array( $this, 'bupr_public_enqueue_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'bupr_public_enqueue_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'bupr_admin_enqueue_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'bupr_admin_enqueue_styles' ) );
			add_filter( 'load_script_textdomain_relative_path', array( $this, 'bupr_filter_script_translation_relative_path' ), 10, 2 );
		}

		/**
		 * Point JED lookups at the unminified script path.
		 *
		 * Outside SCRIPT_DEBUG the scripts are served from assets/js/min/ and
		 * admin/assets/js/min/. Core strips a trailing ".min.js" when it hashes the
		 * path for the JED filename, but it does NOT strip the "/min/" directory
		 * segment - so it looks for a hash of "assets/js/min/bupr-front.js" while
		 * wp i18n make-json keys the file on "assets/js/bupr-front.js". The two
		 * never match and every wp.i18n string silently falls back to English in
		 * production.
		 *
		 * Core documents `$relative` as `string|false` (false when the relative
		 * path could not be determined - see wp-includes/l10n.php), so the
		 * is_string() guard below is load-bearing, not defensive padding.
		 *
		 * @since   3.8.0
		 * @param   string|false $relative Relative path core will hash, or false if undeterminable.
		 * @param   string       $src      Full script URL.
		 * @return  string|false
		 */
		public function bupr_filter_script_translation_relative_path( $relative, $src ) {
			if ( ! is_string( $relative ) || false === strpos( $src, 'buddypress-member-review/' ) ) {
				return $relative;
			}
			// "assets/js/min/bupr-front.js" -> "assets/js/bupr-front.js".
			return preg_replace( '#/min/([^/]+)$#', '/$1', $relative );
		}

		/**
		 * Determine if scripts should be loaded on current page.
		 *
		 * @access   private
		 * @return   boolean  Whether scripts should be loaded.
		 */
		private function should_load_frontend_assets() {
			// Don't load on admin or login pages.
			if ( is_admin() || wp_doing_ajax() ) {
				return false;
			}

			// Skip if BuddyPress is not active.
			if ( ! function_exists( 'buddypress' ) || ! function_exists( 'bp_is_active' ) ) {
				return false;
			}

			// Always load on these BuddyPress pages.
			$load_on_pages = array(
				'bp_is_members_directory', // Members directory.
				'bp_is_user',              // User profiles.
				'bp_is_user_profile',      // User profile specific page.
				'bp_is_activity_component', // Activity pages (for review activities).
			);

			foreach ( $load_on_pages as $conditional ) {
				if ( function_exists( $conditional ) && call_user_func( $conditional ) ) {
					return true;
				}
			}

			// The Member Review widget renders in any sidebar, not only on BuddyPress pages.
			if ( is_active_widget( false, false, 'bupr_members_review_setting', true ) ) {
				return true;
			}

			// Check for shortcodes in current post content.
			global $post;
			if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'bupr_display_top_members' ) ) {
				return true;
			}

			// Allow other plugins to add their own conditions.
			return apply_filters( 'bupr_should_load_assets', false );
		}

		/**
		 * Actions performed for enqueuing styles for site front
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function bupr_public_enqueue_styles() {
			if ( ! $this->should_load_frontend_assets() ) {
				return;
			}

			if ( ! bp_is_groups_directory() ) {
				global $bupr;

				if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
					$extension = is_rtl() ? '.rtl.css' : '.css';
					$path      = is_rtl() ? '/rtl' : '';
				} else {
					$extension = is_rtl() ? '.rtl.css' : '.min.css';
					$path      = is_rtl() ? '/rtl' : '/min';
				}
				// Determine file paths based on debug mode and RTL support.
				// Legacy bupr-reviews.css was merged into bupr-front.css in the 3.8.0 consolidation; no longer enqueued.
				wp_enqueue_style( 'bupr-front-css', BUPR_PLUGIN_URL . 'assets/css' . $path . '/bupr-front' . $extension, array(), BUPR_PLUGIN_VERSION, 'all' );
				bupr_enqueue_star_font();
				if ( isset( $bupr['rating_color'] ) && ! empty( $bupr['rating_color'] ) ) {
					$bupr_star_color = $bupr['rating_color'];
					$custom_css      = ".bupr-star-rate { color: {$bupr_star_color} !important; }";
					wp_add_inline_style( 'bupr-front-css', $custom_css );
				}

				// Enqueue dashicons for flag button.
				wp_enqueue_style( 'dashicons' );
			}
		}


		/**
		 * Actions performed for enqueuing scripts for site front
		 *
		 * @since    3.3.2
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function bupr_public_enqueue_scripts() {

			// Only load assets when needed.
			if ( ! $this->should_load_frontend_assets() ) {
				return;
			}

			if ( ! bp_is_groups_directory() ) {

				global $bupr;

				if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
					$extension = '.js';
					$path      = '';
				} else {
					$extension = '.min.js';
					$path      = '/min';
				}
				wp_register_script( 'bupr-front-js', BUPR_PLUGIN_URL . 'assets/js' . $path . '/bupr-front' . $extension, array( 'jquery' ), BUPR_PLUGIN_VERSION, true );
				wp_enqueue_script( 'bupr-front-js' );
				$title_review   = bupr_profile_review_tab_singular_slug();
				$cur_name       = bp_get_displayed_user_fullname();
				$reviews_titles = array(
					'cur_username'       => $cur_name,
					'review_title'       => $title_review,
					'review_nonce'       => wp_create_nonce( 'review-nonce' ),
					/* translators: 1: Reviewed member's display name. 2: Singular review label (e.g. "review"). */
					'review_title_format' => esc_html__( '%1$s received a %2$s', 'bp-member-reviews' ),
					'search_placeholder' => esc_html__( 'Enter Keywords....', 'bp-member-reviews' ),
					'reply_empty'        => esc_html__( 'Please enter a reply.', 'bp-member-reviews' ),
					'reply_failed'       => esc_html__( 'Failed to add reply.', 'bp-member-reviews' ),
					'flag_select_reason' => esc_html__( 'Please select a reason.', 'bp-member-reviews' ),
					'reported'           => esc_html__( 'Reported', 'bp-member-reviews' ),
					'generic_error'      => esc_html__( 'An error occurred. Please try again.', 'bp-member-reviews' ),
				);
				wp_localize_script( 'bupr-front-js', 'mail_title', $reviews_titles );
				wp_localize_script(
					'bupr-front-js',
					'bupr_a11y',
					array(
						/* translators: %d: star position (1-5) in the rating group. */
						'rate_label'  => esc_html__( 'Rate %d out of 5', 'bp-member-reviews' ),
						'group_label' => esc_html__( 'Rating', 'bp-member-reviews' ),
					)
				);
				wp_set_script_translations( 'bupr-front-js', 'bp-member-reviews', BUPR_PLUGIN_PATH . 'languages' );
			}
		}

		/**
		 * Actions performed for enqueuing scripts for admin page
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 * @param string $hook The current admin page hook.
		 */
		public function bupr_admin_enqueue_scripts( $hook ) {

			if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
				$extension = '.js';
				$path      = '';
			} else {
				$extension = '.min.js';
				$path      = '/min';
			}
			$bupr_pages = array(
				'toplevel_page_bp-member-review-settings',
				'reviews_page_bp-member-review-settings',
				'edit.php', // For post type review.
			);

			if ( function_exists( 'get_current_screen' ) ) {
				$screen           = get_current_screen();
				$is_review_page   = isset( $screen->post_type ) && 'review' === $screen->post_type;
				$is_settings_page = isset( $_GET['page'] ) && ( 'bp-member-review-settings' === $_GET['page'] || 'wbcomplugins' === $_GET['page'] );

				if ( ! $is_review_page && ! $is_settings_page && ! in_array( $hook, $bupr_pages, true ) ) {
					return;
				}
				wp_enqueue_script( 'jquery' );
				wp_enqueue_script( 'jquery-ui-sortable' );

				if ( ! wp_script_is( 'wbcom-select2-js', 'enqueued' ) ) {
					wp_enqueue_script( 'wbcom-select2-js', BUPR_PLUGIN_URL . 'admin/assets/js/vendor/select2.min.js', array( 'jquery' ), BUPR_PLUGIN_VERSION, false );
				}
				wp_register_script( 'bupr-js-admin', BUPR_PLUGIN_URL . 'admin/assets/js' . $path . '/bupr-admin' . $extension, array( 'jquery' ), BUPR_PLUGIN_VERSION, false );
				wp_enqueue_script( 'bupr-js-admin' );

				wp_localize_script(
					'bupr-js-admin',
					'bupr_admin_ajax_object',
					array(
						'ajaxurl'           => admin_url( 'admin-ajax.php' ),
						'success_msz'       => esc_html__( 'Already Installed & Activated', 'bp-member-reviews' ),
						'error_msz'         => esc_html__( 'There was a problem performing the action.', 'bp-member-reviews' ),
						'number_validation' => esc_html__( '* Number input is not allowed *', 'bp-member-reviews' ),
						'approving_text'    => esc_html__( 'Approving…', 'bp-member-reviews' ),
						'nonce'             => wp_create_nonce( 'bupr_member_review_ajax' ),
						// Strings for the JS-generated criteria row (mirrors admin/tab-templates/bupr-setting-criteria-tab.php).
						'criteria_placeholder'  => esc_attr__( 'Add Review criteria eg. Member Response. ', 'bp-member-reviews' ),
						'criteria_aria_label'   => esc_attr__( 'Review criteria name', 'bp-member-reviews' ),
						'criteria_remove'       => esc_attr__( 'Remove', 'bp-member-reviews' ),
						'criteria_remove_aria'  => esc_attr__( 'Remove this review criteria', 'bp-member-reviews' ),
					)
				);

				if ( $is_settings_page ) {
					/* add wp color picker */
					wp_enqueue_script( 'bupr-color-picker', BUPR_PLUGIN_URL . 'admin/assets/js' . $path . '/bupr-color-picker' . $extension, array( 'wp-color-picker' ), BUPR_PLUGIN_VERSION, false );
				}
				wp_set_script_translations( 'bupr-js-admin', 'bp-member-reviews', BUPR_PLUGIN_PATH . 'languages' );
			}
		}

		/**
		 * Actions performed for enqueuing styles for admin page
		 *
		 * @since    3.3.2
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function bupr_admin_enqueue_styles() {
			global $typenow, $bupr;
			if ( function_exists( 'get_current_screen' ) ) {
				$screen           = get_current_screen();
				$is_review_page   = isset( $screen->post_type ) && 'review' === $screen->post_type;
				$is_settings_page = isset( $_GET['page'] ) && ( 'bp-member-review-settings' === $_GET['page'] || 'wbcomplugins' === $_GET['page'] );
				// Only load on relevant pages.
				if ( ! $is_review_page && ! $is_settings_page ) {
					return;
				}

				if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
					$extension = is_rtl() ? '.rtl.css' : '.css';
					$path      = is_rtl() ? '/rtl' : '';
				} else {
					$extension = is_rtl() ? '.rtl.css' : '.min.css';
					$path      = is_rtl() ? '/rtl' : '/min';
				}

				bupr_enqueue_star_font();
				if ( ! wp_style_is( 'wbcom-select2-css', 'enqueued' ) ) {
					wp_enqueue_style( 'wbcom-select2-css', BUPR_PLUGIN_URL . 'admin/assets/css/vendor/select2.css', array(), BUPR_PLUGIN_VERSION, 'all' );
				}
				wp_enqueue_style( 'bupr-css-admin', BUPR_PLUGIN_URL . 'admin/assets/css' . $path . '/bupr-admin' . $extension, array(), BUPR_PLUGIN_VERSION, 'all' );
				if ( ! wp_style_is( 'wp-color-picker', 'enqueued' ) && $is_settings_page ) {
					wp_enqueue_style( 'wp-color-picker' );
				}
			}

			if ( $is_review_page ) {

				wp_enqueue_style( 'bupr-front-css', BUPR_PLUGIN_URL . 'assets/css' . $path . '/bupr-front' . $extension, array(), BUPR_PLUGIN_VERSION, 'all' );
				if ( isset( $bupr['rating_color'] ) && ! empty( $bupr['rating_color'] ) ) {
					$bupr_star_color = $bupr['rating_color'];
					$custom_css      = ".bupr-star-rate { color: {$bupr_star_color} !important; }";
					wp_add_inline_style( 'bupr-front-css', $custom_css );
				}
			}
		}
	}
	new BUPRScriptsStyles();
}
