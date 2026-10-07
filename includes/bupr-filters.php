<?php // phpcs:disable WordPress.Files.FileName.InvalidClassFileName
/**
 * Class to serve filter Calls.
 *
 * @since    1.0.0
 * @author   Wbcom Designs
 * @package BuddyPress_Member_Reviews
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'BUPR_Custom_Hooks' ) ) {

	/**
	 * Class to add custom hooks for this plugin
	 *
	 * @since    1.0.0
	 * @author   Wbcom Designs
	 */
	class BUPR_Custom_Hooks {

		/**
		 * Constructor.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function __construct() {

			// Init hooks with proper conditional checks.
			$this->init_hooks();
		}

			/**
			 * Initialize hooks based on BuddyPress theme and configuration
			 */
		private function init_hooks() {

			if ( function_exists( 'buddypress' ) && ! buddypress()->buddyboss ) {
				add_action( 'bp_setup_nav', array( $this, 'bupr_member_profile_reviews_tab' ), 11 );
			}
			if ( function_exists( 'buddypress' ) && buddypress()->buddyboss ) {
				add_action( 'bp_before_member_in_header_meta', array( $this, 'bupr_member_average_rating' ) );
				add_action( 'wp', array( $this, 'bupr_member_profile_reviews_tab' ), 11 );
			} else {
				add_action( 'bp_before_member_header_meta', array( $this, 'bupr_member_average_rating' ) );
			}
			add_action( 'youzify_after_profile_header_user_meta', array( $this, 'bupr_member_average_rating' ) );
			add_action( 'bp_setup_admin_bar', array( $this, 'bupr_setup_admin_bar' ), 10 );

			add_action( 'init', array( $this, 'bupr_add_bp_member_reviews_taxonomy_term' ) );
			add_filter( 'post_row_actions', array( $this, 'bupr_bp_member_reviews_row_actions' ), 10, 2 );
			add_filter( 'bulk_actions-edit-review', array( $this, 'bupr_remove_edit_bulk_actions' ), 10, 1 );
			// Commented out: add_action for bp_screens review tab display.
			add_action( 'bp_member_header_actions', array( $this, 'bupr_add_review_button_on_member_header' ) );
			add_action( 'youzify_after_profile_header_user_meta', array( $this, 'bupr_add_review_button_on_member_header' ) );
			add_action( 'bp_activity_after_save', array( $this, 'bupr_add_activity_meta' ) );
			add_filter( 'bp_get_activity_action', array( $this, 'bupr_hide_username_in_activity' ), 10, 2 );
			add_filter( 'bp_get_activity_user_link', array( $this, 'bupr_change_user_link' ) );
			add_filter( 'bp_get_activity_avatar', array( $this, 'bupr_change_avatar_image' ) );

			/*
			 * Add review link at member's directory if option admin setting is enabled.
			 */

				// Directory integration with theme compatibility.
			$this->setup_directory_hooks();
			add_action( 'init', array( $this, 'bupr_set_default_rating_criteria' ) );
			add_action( 'bupr_after_member_review_list', array( $this, 'bupr_edit_review_form_modal' ) );

			if ( in_array( 'bp-rewrites/class-bp-rewrites.php', (array) get_option( 'active_plugins' ), true ) ) {
				add_filter( 'bp_nouveau_get_nav_link', array( $this, 'bupr_nouveau_link_fix' ), 10, 2 );
			}

			add_action( 'bupr_member_review_after_review_insert', array( $this, 'bupr_create_review_activity' ), 10, 2 );

			add_action( 'bp_get_activity_content_body', array( $this, 'bupr_added_activity_star_rating' ), 10, 2 );
		}

		/**
		 * Set up directory integration hooks based on active theme.
		 */
		private function setup_directory_hooks() {
			// Skip if BuddyPress is not active.
			if ( ! function_exists( 'buddypress' ) ) {
				return;
			}

			// Check if BuddyBoss is active first.
			if ( buddypress()->buddyboss ) {
				// Use BuddyBoss specific action.
				add_action( 'bp_member_members_list_item', array( $this, 'bupr_rating_directory' ), 50 );
			} else {
				// Check the theme package ID to distinguish between BuddyPress Legacy and Nouveau.
				$theme_package = bp_get_option( '_bp_theme_package_id' );

				if ( 'nouveau' === $theme_package ) {
					// If BuddyPress Nouveau is active, use the bp_directory_members_item_meta hook.
					add_action( 'bp_directory_members_item_meta', array( $this, 'bupr_rating_directory' ), 50 );
				} else {
					// If BuddyPress Legacy is active, use the bp_directory_members_item hook.
					add_action( 'bp_directory_members_item', array( $this, 'bupr_rating_directory' ), 50 );
				}
			}
		}

		/**
		 * Fix nav item links for BP Rewrites compatibility.
		 *
		 * @param string $link     The URL for the nav item.
		 * @param object $nav_item The nav item object.
		 * @return string The URL for the nav item.
		 */
		public function bupr_nouveau_link_fix( $link, $nav_item ) {
			$bp_nouveau = bp_nouveau();
			$nav_item   = $bp_nouveau->current_nav_item;

			$link = '#';
			if ( ! empty( $nav_item->link ) ) {
				$link = $nav_item->link;
			}

			if ( 'personal' === $bp_nouveau->displayed_nav && ! empty( $nav_item->primary ) ) {
				if ( is_user_logged_in() ) {
					$link = str_replace( bupr_member_url( bp_loggedin_user_id() ), bupr_member_url( bp_displayed_user_id() ), $link );
				} else {
					$link = trailingslashit( bupr_member_url( bp_displayed_user_id() ) . $link );
				}
			}
			return $link;
		}

		/**
		 * To add default criteria review settings.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function bupr_set_default_rating_criteria() {
			$bupr_admin_settings = get_option( 'bupr_admin_settings', true );
			if ( empty( $bupr_admin_settings ) || ! is_array( $bupr_admin_settings ) ) {
				/*
				 * Untranslated keys on purpose - these are identifiers, not
				 * labels. This runtime seeder is the one that actually fires on
				 * most installs (the activation hook only covers a clean
				 * activate), so translating here froze the option into the
				 * locale of whichever request happened to seed it.
				 * bupr_get_criteria_label() translates at render time instead.
				 */
				$default_admin_criteria = array(
					'profile_multi_rating_allowed' => '1',
					'profile_rating_fields'        => bupr_get_default_rating_fields(),
				);
				update_option( 'bupr_admin_settings', $default_admin_criteria );
			}
		}

		/**
		 * Display average rating in the BuddyPress directory.
		 *
		 * @since 1.0.0
		 */
		public function bupr_rating_directory() {
			global $members_template, $bupr;
			// Check if we are on the members directory and if the setting is enabled.
			if ( ! bp_is_members_directory() || 'yes' !== $bupr['dir_view_ratings'] ) {
				return;
			}
			$user_id = $members_template->member->id;
			// Get cached rating data.
			$bupr_avg_rating    = get_user_meta( $user_id, 'bupr_aggregate_rating', true );
			$bupr_reviews_count = get_user_meta( $user_id, 'bupr_review_count', true );

			// If no cached data, skip display.
			if ( empty( $bupr_avg_rating ) && empty( $bupr_reviews_count ) ) {
				return;
			}

			// Convert to proper numeric values.
			$bupr_avg_rating    = (float) $bupr_avg_rating;
			$bupr_reviews_count = (int) $bupr_reviews_count;

			// Only display if we have reviews.
			if ( $bupr_reviews_count > 0 ) {
				$this->render_rating_stars( $bupr_avg_rating, $bupr_reviews_count, $user_id, 'directory' );
			} else {
				// Display just the review count if no rating.
				$review_string = sprintf(
					/* translators: %s: Number of reviews. */
					_n( '%s review', '%s reviews', $bupr_reviews_count, 'bp-member-reviews' ),
					number_format_i18n( $bupr_reviews_count )
				);
				echo '<div><span class="bupr-directory-rating-text">' .
					esc_html( $review_string ) .
					'</span></div>';
			}
		}


		/**
		 * Render rating stars with proper HTML.
		 *
		 * @param float  $rating  Average rating value.
		 * @param int    $count   Number of reviews.
		 * @param int    $user_id User ID for the rating display.
		 * @param string $context Where this is being displayed (profile, directory, etc).
		 */
		private function render_rating_stars( $rating, $count, $user_id, $context = 'profile' ) {
			// Calculate star display data.
			$stars_on   = floor( $rating );
			$stars_half = ( $rating - $stars_on >= 0.5 ) ? 1 : 0;
			$stars_off  = 5 - ( $stars_on + $stars_half );

			// Format rating to 2 decimal places.
			$rating = number_format( $rating, 2 );

			// Generate CSS class based on context.
			$wrapper_class = 'bupr-' . $context . '-review-wrapper';
			$stars_class   = 'bupr-' . $context . '-review-stars';
			$tooltip_class = 'bupr-' . $context . '-tooltip';

			// Whole-phrase strings so translators control word order and plural rules.
			$tooltip_text = sprintf(
				/* translators: 1: Average rating value (e.g. "4.50"). 2: Number of ratings. */
				_n( 'Average rating: %1$s based on %2$s rating', 'Average rating: %1$s based on %2$s ratings', $count, 'bp-member-reviews' ),
				$rating,
				number_format_i18n( $count )
			);

			$rating_text = sprintf(
				/* translators: 1: Average rating value out of 5 (e.g. "4.50"). 2: Number of reviews. */
				_n( '%1$s/5 ( %2$s review )', '%1$s/5 ( %2$s reviews )', $count, 'bp-member-reviews' ),
				$rating,
				number_format_i18n( $count )
			);

			// Start output buffering for better performance.
			ob_start();
			?>
			<div class="<?php echo esc_attr( $wrapper_class ); ?>">
				<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
					<span itemprop="ratingValue" content="<?php echo esc_attr( $rating ); ?>"></span>
					<span itemprop="bestRating" content="5"></span>
					<span itemprop="ratingCount" content="<?php echo esc_attr( $count ); ?>"></span>
					<span itemprop="reviewCount" content="<?php echo esc_attr( $count ); ?>"></span>
					<span itemprop="itemReviewed" content="Person"></span>
					<span itemprop="name" content="
					<?php
					// phpcs:ignore Squiz.Commenting.InlineComment.NotEmpty -- Multiline version check logic.
					if ( function_exists( 'bp_get_version' ) && version_compare( bp_get_version(), '12.0.0', '>=' ) ) {
						echo esc_attr( bp_members_get_user_slug( 'profile' === $context ? bp_displayed_user_id() : $user_id ) );
					} else {
						echo esc_attr( bp_core_get_username( 'profile' === $context ? bp_displayed_user_id() : $user_id ) );
					}
					?>
					"></span>
					<span itemprop="url" content="<?php echo esc_url( bp_core_get_userlink( 'profile' === $context ? bp_displayed_user_id() : $user_id, false, true ) ); ?>"></span>

					<div class="<?php echo esc_attr( $stars_class ); ?> <?php echo esc_attr( $tooltip_class ); ?>"
						data-tooltip="<?php echo esc_attr( $tooltip_text ); ?>">
						<?php
						// Display the stars.
						for ( $i = 1; $i <= $stars_on; $i++ ) {
							echo '<span class="fas fa-star bupr-star-rate"></span>';
						}
						for ( $i = 1; $i <= $stars_half; $i++ ) {
							echo '<span class="fas fa-star-half-alt bupr-star-rate"></span>';
						}
						for ( $i = 1; $i <= $stars_off; $i++ ) {
							echo '<span class="far fa-star bupr-star-rate"></span>';
						}
						?>
						<span class="bupr-<?php echo esc_attr( $context ); ?>-rating-text">
							<?php echo esc_html( $rating_text ); ?>
						</span>
					</div>
				</div>
			</div>
			<?php

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output is escaped in buffer.
			echo ob_get_clean();
		}




		/**
		 * Actions performed to add a review button on member header.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function bupr_add_review_button_on_member_header() {
			global $bupr;
			if ( empty( $bupr['hide_review_button'] ) || 'yes' !== $bupr['hide_review_button'] || ! is_user_logged_in() ) {
				return;
			}
			$member_id = bp_displayed_user_id();
			// Never on own profile (self-review is refused), and only when the roles
			// settings allow it: the same check the Add Review tab and the submit use.
			if ( ! $member_id || bp_loggedin_user_id() === $member_id || ! bupr_roles_allow_review( bp_loggedin_user_id(), $member_id ) ) {
				return;
			}
			$review_url = bupr_member_url( $member_id ) . bupr_profile_review_tab_plural_slug() . '/add-' . bupr_profile_review_tab_singular_slug();
			$tag        = 'nouveau' === bp_get_option( '_bp_theme_package_id' ) ? 'li' : 'div';
			?>
			<<?php echo esc_html( $tag ); ?> id="bupr-add-review-btn" class="generic-button">
				<a href="<?php echo esc_url( $review_url ); ?>" class="button add-review" show="form">
					<?php
					/* translators: %s: Review label. */
					printf( esc_html__( 'Add %s', 'bp-member-reviews' ), esc_html( $bupr['review_label'] ) );
					?>
				</a>
			</<?php echo esc_html( $tag ); ?>>
			<?php
		}

		/**
		 * Setup Reviews link in admin bar.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @param    array $wp_admin_nav Member Review add menu array.
		 * @author   Wbcom Designs
		 */
		public function bupr_setup_admin_bar( $wp_admin_nav = array() ) {
			global $wp_admin_bar;
			global $bupr;
			$reviews_count = bupr_get_member_review_count( get_current_user_id() );

			$profile_menu_slug = isset( $bupr['review_label_plural'] ) ? sanitize_title( $bupr['review_label_plural'] ) : esc_html( 'reviews' );

			$base_url = bupr_member_url( bp_loggedin_user_id() ) . $profile_menu_slug;
			if ( is_user_logged_in() ) {
				$wp_admin_bar->add_menu(
					array(
						'parent' => 'my-account-buddypress',
						'id'     => 'my-account-' . $profile_menu_slug,
						'title'  => $profile_menu_slug . ' <span class="count">' . $reviews_count . '</span>',
						'href'   => trailingslashit( $base_url ),
					)
				);
			}
		}

		/**
		 * Display average rating on a BuddyPress member's profile.
		 *
		 * @since 1.0.0
		 */
		public function bupr_member_average_rating() {

			global $bupr;
			// Only proceed on user profiles.
			if ( ! bp_is_user() ) {
				return;
			}

			// Check if rating display is enabled (can be disabled by Testimonial mode).
			if ( isset( $bupr['dir_view_ratings'] ) && 'yes' !== $bupr['dir_view_ratings'] ) {
				return;
			}

			$user_id = bp_displayed_user_id();

			// Get cached rating data.
			$bupr_avg_rating    = get_user_meta( $user_id, 'bupr_aggregate_rating', true );
			$bupr_reviews_count = get_user_meta( $user_id, 'bupr_review_count', true );

			// Calculate if necessary or use cached values.
			if ( '' === $bupr_avg_rating || '' === $bupr_reviews_count ) {
				$rating_data        = $this->calculate_user_rating( $user_id );
				$bupr_avg_rating    = $rating_data['average'];
				$bupr_reviews_count = $rating_data['count'];

				// Cache the values.
				update_user_meta( $user_id, 'bupr_aggregate_rating', $bupr_avg_rating );
				update_user_meta( $user_id, 'bupr_review_count', $bupr_reviews_count );
			} elseif ( (int) $bupr_reviews_count > 0 && ( '' === (string) $bupr_avg_rating || (float) $bupr_avg_rating <= 0 )
				&& false === get_transient( 'bupr_drift_checked_' . $user_id ) ) {
				// Defensive guard against drifted meta (positive count but an
				// empty/zero aggregate) - e.g. orphaned meta left by review
				// deletions on pre-3.8 installs, which renders "0.00/5 (1
				// review)" with no reviews. Lazily rebuild from the real posts.
				// Throttled per user so a member with a legitimately unrated
				// review does not recalc on every profile view.
				$rating_data        = $this->calculate_user_rating( $user_id );
				$bupr_avg_rating    = $rating_data['average'];
				$bupr_reviews_count = $rating_data['count'];

				update_user_meta( $user_id, 'bupr_aggregate_rating', $bupr_avg_rating );
				update_user_meta( $user_id, 'bupr_review_count', $bupr_reviews_count );

				set_transient( 'bupr_drift_checked_' . $user_id, 1, DAY_IN_SECONDS );
			}

			// Display the rating if there are reviews.
			if ( $bupr_reviews_count > 0 ) {
				$this->render_rating_stars( $bupr_avg_rating, $bupr_reviews_count, $user_id, 'member' );
			} else {
				$review_string = sprintf(
					/* translators: %s: Number of reviews. */
					_n( '%s review', '%s reviews', $bupr_reviews_count, 'bp-member-reviews' ),
					number_format_i18n( $bupr_reviews_count )
				);
				echo '<div><span class="bupr-member-rating-text">' .
					esc_html( $review_string ) .
					'</span></div>';
			}
		}

		/**
		 * Calculate user rating with optimized database queries.
		 *
		 * @param int $user_id User ID to calculate rating for.
		 * @return array Rating data with average and count.
		 */
		private function calculate_user_rating( $user_id ) {
			global $wpdb, $bupr;

			// Validate and sanitize input.
			$user_id = absint( $user_id );
			if ( ! $user_id ) {
				return array(
					'average' => 0,
					'count'   => 0,
				);
			}
			// Use direct SQL for better performance.
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
			$reviews = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT p.ID
                FROM {$wpdb->posts} p
                INNER JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
                WHERE p.post_type = 'review'
                AND p.post_status = 'publish'
                AND pm.meta_key = 'linked_bp_member'
                AND pm.meta_value = %d",
					$user_id
				)
			);

			$total_rating       = 0;
			$review_count       = count( $reviews );
			$total_review_count = 0;

			if ( $review_count > 0 ) {
				// Get all review IDs.
				$review_ids = wp_list_pluck( $reviews, 'ID' );

				// Get all ratings in a single query.
				$review_ratings = array();
				foreach ( $review_ids as $review_id ) {
					$ratings = get_post_meta( $review_id, 'profile_star_rating', true );
					if ( ! empty( $ratings ) && is_array( $ratings ) ) {
						$review_ratings[] = $ratings;
					}
				}

				// Calculate average rating.
				foreach ( $review_ratings as $ratings ) {
					$rate        = 0;
					$field_count = 0;

					foreach ( $ratings as $field => $value ) {
						if ( 0 === (int) $value ) {
							continue;
						}
						$rate += $value;
						++$field_count;
					}

					if ( $field_count > 0 ) {
						$total_rating += $rate / $field_count;
						++$total_review_count;
					}
				}
			}

			$average = ( $total_review_count > 0 ) ? $total_rating / $total_review_count : 0;

			return array(
				'average' => $average,
				'count'   => $review_count,
			);
		}



		/**
		 * Actions performed to remove edit from bulk options.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @param    array $actions Actions array.
		 * @author   Wbcom Designs
		 * @return   array Modified actions array.
		 */
		public function bupr_remove_edit_bulk_actions( $actions ) {
			unset( $actions['edit'] );
			return $actions;
		}

		/**
		 * Actions performed to hide row actions.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @param    array  $actions Actions array.
		 * @param    object $post    Post object.
		 * @author   Wbcom Designs
		 * @return   array Modified actions array.
		 */
		public function bupr_bp_member_reviews_row_actions( $actions, $post ) {
			global $bp;
			global $bupr;
			if ( 'review' === $post->post_type ) {
				unset( $actions['edit'] );
				unset( $actions['view'] );
				unset( $actions['inline hide-if-no-js'] );
				$review_term = isset( wp_get_object_terms( $post->ID, 'review_category' )[0]->name ) ? wp_get_object_terms( $post->ID, 'review_category' )[0]->name : '';
				if ( 'BP Member' === $review_term ) {
					// Add a link to view the review.
					$review_title     = $post->post_title;
					$linked_bp_member = get_post_meta( $post->ID, 'linked_bp_member', true );

					$review_url = bp_core_get_userlink( $linked_bp_member, false, true ) . sanitize_title( $bupr['review_label_plural'] ) . '/view/' . $post->ID;
					/* translators: %s: Review label. */
					$actions['view_review'] = '<a href="' . $review_url . '" title="' . $review_title . '">' . sprintf( esc_html__( 'View %s', 'bp-member-reviews' ), esc_html( $bupr['review_label'] ) ) . '</a>';

					// Add Approve Link for draft reviews.
					if ( 'draft' === $post->post_status && current_user_can( 'edit_others_posts' ) ) {
						$actions['approve_review'] = '<a href="javascript:void(0);" title="' . $review_title . '" class="bupr-approve-review" data-rid="' . $post->ID . '">' . esc_html__( 'Approve', 'bp-member-reviews' ) . '</a>';
					}
				}
			}
			return $actions;
		}

		/**
		 * Action performed to add taxonomy term for group reviews.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function bupr_add_bp_member_reviews_taxonomy_term() {
			$termexists = term_exists( 'BP Member', 'review_category' );
			if ( 0 === $termexists || null === $termexists ) {
				wp_insert_term( 'BP Member', 'review_category' );
			}
		}

		/**
		 * Action performed to add a tab for member profile reviews.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function bupr_member_profile_reviews_tab() {
			global $bp, $bupr;

			$bp_pages = bp_core_get_directory_pages();
			add_filter( 'site_url', 'bupr_site_url', 99 );
			$member_slug = ( isset( $bp_pages->members ) && isset( $bp_pages->members->slug ) ) ? $bp_pages->members->slug : 'members';

			// Count member's reviews (cached aggregate, not a full query per page view).
			$bupr_notification = '<span class="count">' . bupr_get_member_review_count( bp_displayed_user_id() ) . '</span>';

			$name = bp_get_displayed_user_username();

			if ( apply_filters( 'bupr_show_profile_review_tab', true ) ) {
				$tab_args = array(
					'name'                    => bupr_profile_review_tab_name() . ' ' . $bupr_notification, // BP cuts one char before the count span.
					'slug'                    => bupr_profile_review_tab_plural_slug(),
					'screen_function'         => array( $this, 'bupr_reviews_tab_function_to_show_screen' ),
					'position'                => 75,
					'default_subnav_slug'     => bupr_default_review_view_slug(),
					'show_for_displayed_user' => true,
				);
				bp_core_new_nav_item( $tab_args );
			}

			$parent_slug = bupr_profile_review_tab_plural_slug();

			// Add subnav to view a review.
			bp_core_new_subnav_item(
				array(
					'name'            => bupr_profile_review_tab_name(),
					'slug'            => bupr_default_review_view_slug(),
					'parent_url'      => $bp->loggedin_user->domain . $parent_slug . '/',
					'parent_slug'     => $parent_slug,
					'screen_function' => array( $this, 'bupr_view_review_tab_function_to_show_screen' ),
					'position'        => 100,
					'link'            => site_url() . "/$member_slug/$name/$parent_slug/",
				)
			);

			// Add subnav to add a review.
			// Never on own profile: the profile form has no member picker and self-review is refused (bupr-ajax.php).
			// Logged-in viewers get it only when the roles settings allow the review, the same check as the header
			// button and the submit; logged-out visitors see it and the form asks them to log in.
			$member_id = bp_displayed_user_id();
			if ( bp_loggedin_user_id() !== $member_id && ( ! is_user_logged_in() || bupr_roles_allow_review( bp_loggedin_user_id(), $member_id ) ) ) {
				bp_core_new_subnav_item(
					array(
						/* translators: %1$s: Review label. */
						'name'            => sprintf( esc_html__( 'Add %1$s', 'bp-member-reviews' ), esc_html( bupr_profile_review_singular_tab_name() ) ),
						'slug'            => 'add-' . bupr_profile_review_tab_singular_slug(),
						'parent_url'      => $bp->loggedin_user->domain . $parent_slug . '/',
						'parent_slug'     => $parent_slug,
						'screen_function' => array( $this, 'bupr_reviews_form_tab_function_to_show_screen' ),
						'position'        => 200,
						'link'            => site_url() . "/$member_slug/$name/$parent_slug/" . 'add-' . bupr_profile_review_tab_singular_slug(),
					)
				);
			}
			remove_filter( 'site_url', 'bupr_site_url', 99 );
		}

		/**
		 * Action performed to show screen of reviews listing tab.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function bupr_reviews_tab_function_to_show_screen() {
			add_action( 'bp_template_content', array( $this, 'bupr_reviews_tab_function_to_show_content' ) );
			bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
		}

		/**
		 * Action performed to show screen of reviews form tab.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function bupr_reviews_form_tab_function_to_show_screen() {
			add_action( 'bp_template_content', array( $this, 'bupr_reviews_form_to_show_content' ) );
			bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
		}

		/**
		 * Actions performed to show the content of reviews list tab
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function bupr_reviews_tab_function_to_show_content() {
			bupr_get_template( 'bupr-reviews-tab-template.php' );
		}

		/**
		 * Action performed to show the content of add review tab
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function bupr_reviews_form_to_show_content() {
			global $bupr;
			?>
			<div class="bupr-bp-member-review-no-popup-add-block">
			<?php
			if ( is_user_logged_in() ) {
				do_action( 'bupr_member_review_form' );
			} else {
				$bp_template_option = bp_get_option( '_bp_theme_package_id' );
				if ( 'nouveau' === $bp_template_option ) {
					?>
						<div id="message" class="info bp-feedback bp-messages bp-template-notice">
							<span class="bp-icon" aria-hidden="true"></span>
				<?php } else { ?>
							<div id="message" class="info">
							<?php } ?>
							<p>
							<?php
							printf(
								wp_kses(
									/* translators: %1$s: login URL; %2$s: review label. */
									__( 'You must <a href="%1$s">log in</a> to add a %2$s.', 'bp-member-reviews' ),
									array( 'a' => array( 'href' => array() ) )
								),
								// Come back to this form after logging in.
								esc_url( wp_login_url( bp_get_requested_url() ) ),
								esc_html( strtolower( $bupr['review_label'] ) )
							);
							?>
						</p></div>
						<?php
			}
			?>
					</div>
			<?php
		}

		/**
		 * Action performed to show screen of single review view tab.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function bupr_view_review_tab_function_to_show_screen() {
			add_action( 'bp_template_content', array( $this, 'bupr_view_review_tab_function_to_show_content' ) );
			bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
		}

		/**
		 * Action performed to show the content of reviews list tab.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function bupr_view_review_tab_function_to_show_content() {
			bupr_get_template( 'bupr-single-review-template.php' );
		}

		/**
		 * Display edit review form modal.
		 *
		 * @since    1.0.0
		 * @access   public
		 */
		public function bupr_edit_review_form_modal() {
			if ( is_user_logged_in() ) {
				bupr_get_template( 'bupr-edit-review-form.php' );
			}
		}

		/**
		 * Create activity on new review creation.
		 *
		 * @since 2.8.1
		 * @access public
		 * @author Wbcom Designs
		 *
		 * @param int $review_id        The ID of the review post.
		 * @param int $reviewed_user_id The ID of the user being reviewed.
		 */
		public function bupr_create_review_activity( $review_id, $reviewed_user_id ) {
			global $bupr, $bp;

			if ( isset( $bupr['review_activity'] ) && 'yes' !== $bupr['review_activity'] ) {
				return;
			}

			// Apply filter to allow disabling activity creation.
			$allow_activity = apply_filters( 'bupr_allow_activity_posting', true, $review_id, $reviewed_user_id );

			// If the filter returns false, do not proceed with activity creation.
			if ( false === $allow_activity ) {
				return;
			}

			// Only a published review belongs in the activity stream (an edit to a pending
			// or report-hidden review fires this same hook).
			$review = get_post( $review_id );
			if ( ! empty( $review ) && 'publish' === $review->post_status ) {
				// Get the reviewer's (author's) and reviewed user's profile URLs.
				$reviewer_user_id   = $review->post_author;
				$reviewer_user_link = bp_core_get_userlink( $reviewer_user_id );
				$reviewed_user_link = bp_core_get_userlink( $reviewed_user_id );

				// Get the review content.
				$review_content = $review->post_content;
				// Check for anonymous review.
				$is_anonymous = get_post_meta( $review_id, 'bupr_anonymous_review_post', true ) === 'yes';
				if ( $is_anonymous ) {
					$reviewer_user_link = esc_html__( 'An anonymous user', 'bp-member-reviews' );
				}

				// Construct the action text (reviewer to reviewed user).
				$action = sprintf(
					apply_filters(
						'bupr_member_review_activity_action',
						/* translators: %1$s: Review user link; %2$s: review label; %3$s: reviewed user link. */
						__( '%1$s posted a new %2$s to %3$s', 'bp-member-reviews' )
					),
					$reviewer_user_link,
					strtolower( esc_html( $bupr['review_label'] ) ),
					$reviewed_user_link
				);

				// Ensure the content is safe.
				$escaped_content = wp_kses_post( $review_content );

				if ( function_exists( 'bp_activity_add' ) ) {
					// For anonymous reviews, use the reviewed user as activity author to protect anonymity.
					// This prevents the real reviewer's ID from being exposed in database queries.
					$activity_user_id = $is_anonymous ? $reviewed_user_id : $reviewer_user_id;

					// One entry per review: an edit or re-approval updates the existing
					// entry in place (keeping its date) instead of posting a duplicate.
					$existing_id   = bupr_get_review_activity_id( $review_id );
					$recorded_time = $existing_id ? ( new BP_Activity_Activity( $existing_id ) )->date_recorded : bp_core_current_time();

					// Add activity to BuddyPress.
					$activity_id = bp_activity_add(
						array(
							'id'                => $existing_id,
							'recorded_time'     => $recorded_time,
							'action'            => $action,
							'content'           => ! empty( $escaped_content ) ? $escaped_content : 'Ratings',
							'component'         => $bp->members->id,
							'type'              => bupr_review_activity_type(),
							'user_id'           => $activity_user_id,
							'item_id'           => $review_id,
							'secondary_item_id' => $reviewed_user_id,
							'hide_sitewide'     => false,
							'is_spam'           => false,
							'privacy'           => 'public',
						)
					);

					if ( ! $activity_id && defined( 'WP_DEBUG' ) && WP_DEBUG ) {
						error_log( 'BUPR: Failed to create activity for review ID: ' . $review_id );
					}
				}
			}
		}

		/**
		 * Added member star rating in activity.
		 *
		 * @param string $activity_content Activity Content.
		 * @param object $activity         Activity Object.
		 * @return string Modified activity content.
		 */
		public function bupr_added_activity_star_rating( $activity_content, $activity ) {
			$post_id              = $activity->item_id;
			$review_rating_fields = get_option( 'bupr_admin_settings', true );
			$review_ratings       = get_post_meta( $post_id, 'profile_star_rating', false );
			$review_start         = '';
			if ( ! empty( $review_ratings[0] ) && is_array( $review_ratings[0] ) ) {
				$review_start .= '<div class="bupr-multi-review">';
				foreach ( $review_ratings[0] as $rating_field_name => $ratings ) {
					if ( empty( $ratings ) ) {
						continue;
					}
					$review_start .= '<div class="multi-review">';
					if ( ! empty( $rating_field_name ) ) {
						$review_start .= '<div class="bupr-col-6">' . esc_html( bupr_get_criteria_label( $rating_field_name ) ) . '</div>';
					}
					$review_start .= '<div class="bupr-col-6">';

					// Star rating display.
					$stars_on  = $ratings;
					$stars_off = 5 - $stars_on;
					for ( $i = 1; $i <= $stars_on; $i++ ) {
						$review_start .= '<span class="fas fa-star stars bupr-star-rate"></span>';

					}
					for ( $i = 1; $i <= $stars_off; $i++ ) {
						$review_start .= '<span class="far fa-star stars bupr-star-rate"></span>';
					}
					$review_start .= '</div>';
					$review_start .= '</div>';
				}
				$review_start .= '</div>';
			}
			return $activity_content . $review_start;
		}

		/**
		 * Saved the user name as anonymous if meta in review.
		 *
		 * @since    3.2.2
		 * @access   public
		 * @author   Wbcom Designs
		 *
		 * @param object $activity Activity object.
		 */
		public function bupr_add_activity_meta( $activity ) {
			$is_anonymous   = get_post_meta( $activity->item_id, 'bupr_anonymous_review_post', true );
			$anonymous_user = ( 'yes' === $is_anonymous ) ? esc_html__( 'An anonymous user', 'bp-member-reviews' ) : '';
			if ( isset( $activity->id ) && ! empty( $anonymous_user ) ) {
				bp_activity_add_meta( $activity->id, 'bupr_user_string', $anonymous_user );
			}
		}

		/**
		 * Hide the user name if anonymous text available.
		 *
		 * @since    3.2.2
		 * @access   public
		 * @author   Wbcom Designs
		 *
		 * @param string $action   The activity action text.
		 * @param object $activity Activity object.
		 * @return string Modified action text.
		 */
		public function bupr_hide_username_in_activity( $action, $activity ) {
			$bupr_user_string = bp_activity_get_meta( $activity->id, 'bupr_user_string' );
			$user_id          = $activity->user_id;
			if ( ! empty( $bupr_user_string ) ) {
				$action = str_replace( bp_core_get_userlink( $user_id ), $bupr_user_string, $action );
			}
			return apply_filters( 'bupr_hide_username_in_activity', $action );
		}

		/**
		 * Change the user url if the activity is of anonymous user.
		 *
		 * @since    3.2.2
		 * @access   public
		 * @author   Wbcom Designs
		 *
		 * @param string $user_link_activity User profile link.
		 * @return string Modified user link.
		 */
		public function bupr_change_user_link( $user_link_activity ) {
			global $activities_template;
			$activity     = $activities_template->activity;
			$is_anonymous = get_post_meta( $activity->item_id, 'bupr_anonymous_review_post', true );
			if ( 'yes' === $is_anonymous ) {
				$user_link_activity = '#';
			}
			return apply_filters( 'bupr_change_user_link', $user_link_activity );
		}

		/**
		 * Change the user gravatar if the activity is of anonymous user.
		 *
		 * @since    3.2.2
		 * @access   public
		 * @author   Wbcom Designs
		 *
		 * @param string $gravatar_image Gravatar image HTML.
		 * @return string Modified gravatar image HTML.
		 */
		public function bupr_change_avatar_image( $gravatar_image ) {
			global $activities_template;

			if ( isset( $activities_template->activity ) && isset( $activities_template->activity->item_id ) ) {
				$activity     = $activities_template->activity;
				$is_anonymous = get_post_meta( $activity->item_id, 'bupr_anonymous_review_post', true );

				if ( 'yes' === $is_anonymous ) {

					$size           = apply_filters( 'bupr_default_avatar_size', 150 );
					$default_avatar = apply_filters( 'bupr_change_default_avatar', 'mystery' );  // Could also use identicon, monsterid, retro, etc.
					$gravatar_url   = 'https://www.gravatar.com/avatar/?s=' . $size . '&d=' . $default_avatar;

					$gravatar_image = '<img src="' . esc_url( $gravatar_url ) . '" class="avatar photo" alt="' . esc_attr__( 'Custom Default Gravatar', 'bp-member-reviews' ) . '" />';
				}
			}

			return apply_filters( 'bupr_change_avatar_image', $gravatar_image );
		}
	}
			new BUPR_Custom_Hooks();
}
