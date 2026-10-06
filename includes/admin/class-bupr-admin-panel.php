<?php
/**
 * BUPR Admin: menu, settings registration, enqueue, page rendering.
 *
 * Replaces the legacy Wbcom wrapper chrome (admin/wbcom/) with the
 * card-panel admin pattern. Follows the wp-plugin-development skill
 * Part 6 and references/wbcom-wrapper-migration.md playbook.
 *
 * Data contract is preserved byte-for-byte: the same three option
 * groups / option names are registered, sanitized by the legacy
 * BUPR_Admin::bupr_sanitize_options(), and merged with the
 * sentinel-guarded pattern so conditionally-rendered keys (approval
 * emails, criteria fields) are never wiped on save.
 *
 * @package BuddyPress_Member_Reviews
 * @since   3.8.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'BUPR_Admin_Panel' ) ) {

	/**
	 * Class BUPR_Admin_Panel
	 *
	 * @since 3.8.0
	 */
	class BUPR_Admin_Panel {

		/**
		 * Menu slug — kept identical to the legacy primary settings page so
		 * bookmarks, the plugin action link, and Pro tab filters still work.
		 */
		const MENU_SLUG = 'bp-member-review-settings';

		/**
		 * Settings option groups / names (unchanged from the legacy admin).
		 */
		const OPTION_GENERAL  = 'bupr_admin_general_options';
		const OPTION_CRITERIA = 'bupr_admin_settings';
		const OPTION_DISPLAY  = 'bupr_admin_display_options';

		/**
		 * Sidebar tabs rendered inside the single admin page.
		 *
		 * The legacy tab keys (bupr-general, bupr-criteria, bupr-display) are
		 * preserved so the Pro addon's `bupr_admin_settings_tabs` filter keeps
		 * matching.
		 *
		 * ---------------------------------------------------------------------
		 * REMOVED TAB: `bupr-pro-features` ("Add-ons") — removed 2026-07-17.
		 * ---------------------------------------------------------------------
		 * Removed because the Pro addon is not released, so on every free site
		 * the tab rendered an empty surface with nothing in it. READ THIS BEFORE
		 * SHIPPING PRO — restoring Pro's settings needs BOTH halves back:
		 *
		 * 1. This tab entry. The slug `bupr-pro-features` is a PUBLIC CONTRACT:
		 *    it appears in `?tab=bupr-pro-features` URLs and in the legacy tab
		 *    map the Pro addon filters via `bupr_admin_settings_tabs`. Restore
		 *    the slug verbatim; do not rename it. Also restore its entry in the
		 *    `$view_map` in render_page() or the tab falls back to Overview.
		 *
		 * 2. The view + template that carried Pro's MOUNT POINT, both deleted
		 *    with this tab:
		 *      - includes/admin/views/settings-pro-features.php
		 *      - admin/tab-templates/bupr-setting-pro-features-tab.php
		 *    The template fired `do_action( 'bupr_admin_settings_pro_content' )`
		 *    — the action Pro hooks (unconditionally) to render its settings.
		 *    Pro renders FORM CONTENTS ONLY and depends on free supplying the
		 *    surrounding wrapper markup, so any replacement surface must nest
		 *    the do_action() inside exactly this structure:
		 *
		 *      <div class="wbcom-tab-content">
		 *        <div class="wbcom-admin-title-section"> <h3>...</h3> </div>
		 *        <div class="wbcom-admin-option-wrap wbcom-admin-option-wrap-view">
		 *          <?php do_action( 'bupr_admin_settings_pro_content' ); ?>
		 *        </div>
		 *      </div>
		 *
		 *    Restoring the do_action() without those wrappers renders Pro's
		 *    fields unstyled and outside the card chrome.
		 *
		 * 3. A DANGLING LINK is left behind on purpose, and it points at this
		 *    tab. admin/tab-templates/bupr-setting-criteria-tab.php builds a
		 *    notice ending "...switch to Star Ratings mode in the [Add-ons]
		 *    tab", linking to `?tab=bupr-pro-features`. It is unreachable in
		 *    free - the whole block is gated on class_exists( 'BUPR_Pro_Features' ),
		 *    a Pro class - so it costs a free site nothing today and was left
		 *    rather than churn Pro-facing copy while Pro is unreleased. But the
		 *    moment Pro ships, that link is live and lands on a tab that no
		 *    longer exists. Restore this tab and it works again; decide not to,
		 *    and that notice needs rewriting to point somewhere real.
		 *
		 * Git history: the deleted files are recoverable from the commit that
		 * removed them. See CLAUDE.md "Recent changes" and audit/manifest.json
		 * (`removed_contracts`) for the same record.
		 * ---------------------------------------------------------------------
		 *
		 * @return array<string, array{label:string, icon:string, group:string}>
		 */
		public static function get_tabs() {
			$tabs = array(
				'overview'      => array(
					'label' => __( 'Overview', 'bp-member-reviews' ),
					'icon'  => 'dashicons-chart-bar',
					'group' => 'main',
				),
				'bupr-general'  => array(
					'label' => __( 'General', 'bp-member-reviews' ),
					'icon'  => 'dashicons-admin-generic',
					'group' => 'settings',
				),
				'bupr-criteria' => array(
					'label' => __( 'Criteria', 'bp-member-reviews' ),
					'icon'  => 'dashicons-star-filled',
					'group' => 'settings',
				),
				'bupr-display'  => array(
					'label' => __( 'Display', 'bp-member-reviews' ),
					'icon'  => 'dashicons-admin-appearance',
					'group' => 'settings',
				),
			);

			/*
			 * Back-compat with the Pro addon, which hooks the legacy
			 * `bupr_admin_settings_tabs` filter (a tab_key => label map) to
			 * REMOVE the free Criteria tab when it ships its own criteria
			 * builder. We pass the legacy-shaped map through that filter and
			 * drop any tab the Pro addon removed, preserving its behaviour
			 * without forcing a Pro release.
			 */
			$legacy_map = array();
			foreach ( $tabs as $slug => $tab ) {
				$legacy_map[ $slug ] = $tab['label'];
			}
			$filtered_map = apply_filters( 'bupr_admin_settings_tabs', $legacy_map );
			if ( is_array( $filtered_map ) ) {
				foreach ( array_keys( $tabs ) as $slug ) {
					if ( ! array_key_exists( $slug, $filtered_map ) ) {
						unset( $tabs[ $slug ] );
					}
				}
			}

			/*
			 * Discover — ecosystem cross-promotion (read-only display view).
			 * Added AFTER the legacy `bupr_admin_settings_tabs` round-trip so the
			 * Pro addon's tab-removal filter can never accidentally drop it.
			 */
			$tabs['discover'] = array(
				'label' => __( 'Discover', 'bp-member-reviews' ),
				'icon'  => 'dashicons-products',
				'group' => 'discover',
			);

			/**
			 * Filter the card-panel settings tabs (rich descriptors).
			 *
			 * @since 3.8.0
			 *
			 * @param array $tabs Tab descriptors keyed by slug.
			 */
			return apply_filters( 'bupr_admin_panel_tabs', $tabs );
		}

		/**
		 * Bootstrap hooks.
		 */
		public function register() {
			add_action( 'admin_menu', array( $this, 'add_menu' ) );
			// Priority 999 reclaims the shared hub landing render when a
			// legacy wbcom-wrapper plugin registered wbcomplugins first.
			add_action( 'admin_menu', array( $this, 'takeover_hub_landing' ), 999 );
			add_action( 'admin_init', array( $this, 'register_settings' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
			add_action( 'in_admin_header', array( $this, 'suppress_foreign_notices' ), 1 );
		}

		/**
		 * Attach as a single submenu under the shared WB Plugins hub.
		 */
		public function add_menu() {
			$cap = 'manage_options';

			// First Wbcom plugin to load creates the shared WB Plugins hub.
			if ( empty( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) ) {
				add_menu_page(
					esc_html__( 'WB Plugins', 'bp-member-reviews' ),
					esc_html__( 'WB Plugins', 'bp-member-reviews' ),
					$cap,
					'wbcomplugins',
					array( $this, 'render_hub' ),
					'dashicons-lightbulb',
					59
				);
			}

			add_submenu_page(
				'wbcomplugins',
				'BuddyPress Member Reviews',
				esc_html__( 'Member Reviews', 'bp-member-reviews' ),
				$cap,
				self::MENU_SLUG,
				array( $this, 'render_page' )
			);
		}

		/**
		 * Force the shared hub's landing page to render our card-panel
		 * dashboard, overriding whatever a legacy wbcom-wrapper plugin may
		 * have registered. See references/wbcom-wrapper-migration.md Part 15/16.
		 */
		public function takeover_hub_landing() {
			global $admin_page_hooks;
			if ( empty( $admin_page_hooks['wbcomplugins'] ) ) {
				return;
			}
			remove_all_actions( 'toplevel_page_wbcomplugins' );
			add_action( 'toplevel_page_wbcomplugins', array( $this, 'render_hub' ) );
		}

		/**
		 * Register the three settings option groups.
		 *
		 * Each group keeps its legacy sanitize_callback so the option row
		 * shape is byte-identical to the pre-migration form. The criteria
		 * group additionally keeps the `pre_update_option_bupr_admin_settings`
		 * filter (registered in BUPR_Admin) which converts the criteria
		 * builder rows into `profile_rating_fields`.
		 */
		public function register_settings() {
			register_setting(
				self::OPTION_GENERAL,
				self::OPTION_GENERAL,
				array(
					'type'              => 'array',
					'sanitize_callback' => array( $this, 'sanitize_general' ),
				)
			);
			register_setting(
				self::OPTION_CRITERIA,
				self::OPTION_CRITERIA,
				array(
					'type'              => 'array',
					'sanitize_callback' => array( $this, 'sanitize_criteria' ),
				)
			);
			register_setting(
				self::OPTION_DISPLAY,
				self::OPTION_DISPLAY,
				array(
					'type'              => 'array',
					'sanitize_callback' => array( $this, 'sanitize_display' ),
				)
			);
		}

		/**
		 * Run the legacy recursive sanitizer on raw input.
		 *
		 * Delegates to BUPR_Admin::bupr_sanitize_options() so the value
		 * coercion is identical to the legacy admin. Falls back to a local
		 * copy if the legacy class is somehow unavailable.
		 *
		 * @param mixed $input Raw form input.
		 * @return array Sanitized input (not yet merged with stored option).
		 */
		private function run_legacy_sanitizer( $input ) {
			if ( ! is_array( $input ) ) {
				return array();
			}
			if ( class_exists( 'BUPR_Admin' ) ) {
				$legacy = new BUPR_Admin();
				return $legacy->bupr_sanitize_options( $input );
			}
			// Defensive fallback (legacy class always loads in practice).
			$sanitized = array();
			foreach ( $input as $key => $value ) {
				$key = sanitize_key( $key );
				if ( is_array( $value ) ) {
					$sanitized[ $key ] = $this->run_legacy_sanitizer( $value );
				} else {
					$sanitized[ $key ] = sanitize_text_field( $value );
				}
			}
			return $sanitized;
		}

		/**
		 * Sanitize the General option group.
		 *
		 * Each settings group lives on exactly ONE tab in both the legacy and
		 * the card-panel admin, so the General form rebuilds the whole group
		 * from input — identical to the pre-migration behaviour (unchecked
		 * toggles correctly persist as their "off"/default value).
		 *
		 * The ONE exception is the approval-email pair: if a save arrives
		 * without them (the General tab has always posted them since 3.8.1,
		 * but this sanitizer also runs for programmatic update_option()
		 * calls), they are restored from the stored option instead of wiped.
		 * This is the single sentinel-guarded carve-out (Playbook 7.1) this
		 * plugin needs; everything else is a full per-group rebuild.
		 *
		 * @param mixed $input Raw input.
		 * @return array
		 */
		public function sanitize_general( $input ) {
			$sanitized = $this->run_legacy_sanitizer( $input );

			$stored = get_option( self::OPTION_GENERAL, array() );
			$stored = is_array( $stored ) ? $stored : array();

			// Approval-email keys: keep the stored value when a save omits them.
			$conditional_keys = array( 'review_approve_email_subject', 'review_approve_email_message' );
			foreach ( $conditional_keys as $cond_key ) {
				if ( ! array_key_exists( $cond_key, $sanitized ) && array_key_exists( $cond_key, $stored ) ) {
					$sanitized[ $cond_key ] = $stored[ $cond_key ];
				}
			}

			// The two email bodies come from a rich-text editor: keep paragraphs and safe
			// HTML. The generic sanitizer runs sanitize_text_field(), which flattened every
			// saved message to one line with its formatting stripped.
			foreach ( array( 'review_email_message', 'review_approve_email_message' ) as $message_key ) {
				if ( is_array( $input ) && isset( $input[ $message_key ] ) && is_string( $input[ $message_key ] ) ) {
					$sanitized[ $message_key ] = wp_kses_post( $input[ $message_key ] );
				}
			}

			// Numeric fields: a blank or junk value falls back to the documented default
			// instead of being stored verbatim (a blank threshold silently disabled auto-hide).
			// 0 stays meaningful for the threshold ("never auto-hide").
			$numeric_defaults = array(
				'bupr_flag_threshold'      => array( 5, 0 ),
				'profile_reviews_per_page' => array( 5, 1 ),
			);
			foreach ( $numeric_defaults as $num_key => list( $default, $min ) ) {
				if ( ! array_key_exists( $num_key, $sanitized ) ) {
					continue;
				}
				$raw                   = $sanitized[ $num_key ];
				$sanitized[ $num_key ] = is_numeric( $raw ) && (int) $raw >= $min ? (int) $raw : $default;
			}

			return $sanitized;
		}

		/**
		 * Sanitize the Criteria option group.
		 *
		 * The criteria builder posts `rating_field_name[]` /
		 * `rating_field_name_display[]`; the
		 * `pre_update_option_bupr_admin_settings` filter (BUPR_Admin) then
		 * converts those into `profile_rating_fields` AFTER this sanitizer.
		 * Full rebuild from input matches the legacy behaviour exactly.
		 *
		 * @param mixed $input Raw input.
		 * @return array
		 */
		public function sanitize_criteria( $input ) {
			return $this->run_legacy_sanitizer( $input );
		}

		/**
		 * Sanitize the Display option group.
		 *
		 * @param mixed $input Raw input.
		 * @return array
		 */
		public function sanitize_display( $input ) {
			return $this->run_legacy_sanitizer( $input );
		}

		/**
		 * Enqueue admin assets only on our screen + the shared hub landing.
		 *
		 * @param string $hook_suffix Current admin page hook.
		 */
		public function enqueue_assets( $hook_suffix ) {
			unset( $hook_suffix );
			$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
			if ( ! $screen || ! $this->is_our_screen( $screen ) ) {
				return;
			}

			$version = defined( 'BUPR_PLUGIN_VERSION' ) ? BUPR_PLUGIN_VERSION : '1.0.0';

			wp_enqueue_style(
				'bupr-admin-panel',
				BUPR_PLUGIN_URL . 'assets/css/admin.css',
				array(),
				$version
			);

			wp_enqueue_script(
				'bupr-admin-panel',
				BUPR_PLUGIN_URL . 'assets/js/admin.js',
				array( 'jquery' ),
				$version,
				true
			);

			wp_localize_script(
				'bupr-admin-panel',
				'buprAdminPanel',
				array(
					'i18n' => array(
						'saved'           => __( 'Settings saved.', 'bp-member-reviews' ),
						'confirmDanger'   => __( 'Are you sure? This cannot be undone.', 'bp-member-reviews' ),
						'confirmContinue' => __( 'Continue', 'bp-member-reviews' ),
						'confirmCancel'   => __( 'Cancel', 'bp-member-reviews' ),
					),
				)
			);
		}

		/**
		 * Suppress 3rd-party admin notices on our screen only.
		 */
		public function suppress_foreign_notices() {
			$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
			if ( ! $screen || ! $this->is_our_screen( $screen ) ) {
				return;
			}
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
		}

		/**
		 * True if the current screen is the plugin's admin page OR the
		 * shared hub landing (our callback owns the hub render too).
		 *
		 * @param WP_Screen $screen Current screen.
		 * @return bool
		 */
		private function is_our_screen( $screen ) {
			if ( empty( $screen->id ) ) {
				return false;
			}
			return (bool) preg_match( '/_page_' . preg_quote( self::MENU_SLUG, '/' ) . '$/', $screen->id )
				|| 'toplevel_page_wbcomplugins' === $screen->id;
		}

		/**
		 * Render the shared WB Plugins hub landing page.
		 */
		public function render_hub() {
			$view = BUPR_PLUGIN_PATH . 'includes/admin/views/hub.php';
			if ( file_exists( $view ) ) {
				include $view;
			}
		}

		/**
		 * Render the single admin page. Routes to the active tab.
		 */
		public function render_page() {
			$bupr_tabs = self::get_tabs();
			$tab_slugs = array_keys( $bupr_tabs );

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Read-only tab routing.
			$active = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : $tab_slugs[0];
			if ( ! isset( $bupr_tabs[ $active ] ) ) {
				$active = $tab_slugs[0];
			}

			$page_url = admin_url( 'admin.php?page=' . self::MENU_SLUG );

			/*
			 * Map tab slug -> view partial + the option group it submits.
			 *
			 * The `bupr-pro-features` entry was removed with its tab on
			 * 2026-07-17; see the removal record in get_tabs() above before
			 * restoring a surface for the Pro addon.
			 */
			$view_map = array(
				'overview'      => array(
					'view'  => 'overview',
					'group' => '',
				),
				'bupr-general'  => array(
					'view'  => 'settings-general',
					'group' => self::OPTION_GENERAL,
				),
				'bupr-criteria' => array(
					'view'  => 'settings-criteria',
					'group' => self::OPTION_CRITERIA,
				),
				'bupr-display'  => array(
					'view'  => 'settings-display',
					'group' => self::OPTION_DISPLAY,
				),
				'discover'      => array(
					'view'  => 'discover',
					'group' => '',
				),
			);

			$entry = isset( $view_map[ $active ] ) ? $view_map[ $active ] : $view_map['overview'];
			$view  = $entry['view'];

			// Settings tabs render their own <form> (criteria/pro/member-type
			// tabs conditionally swap forms), so the shell does NOT wrap a
			// form. We pass the active group only as informational context.
			$settings_form_group = $entry['group'];

			$view_path = BUPR_PLUGIN_PATH . 'includes/admin/views/' . $view . '.php';
			$shell     = BUPR_PLUGIN_PATH . 'includes/admin/views/shell.php';

			include $shell;
		}
	}
}
