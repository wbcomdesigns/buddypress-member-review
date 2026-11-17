<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * Member Rating Widget.
 *
 * @package BuddyPress_Member_Reviews
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'widgets_init', 'bupr_member_rating_widget' );

/**
 * Register the member rating widget.
 *
 * @return void
 */
function bupr_member_rating_widget() {
	register_widget( 'bupr_single_member_rating_widget' );
}

/**
 * Widget class for displaying member ratings on single member profiles.
 *
 * @since 1.0.0
 */
// phpcs:ignore PEAR.NamingConventions.ValidClassName.Invalid, PEAR.NamingConventions.ValidClassName.StartWithCapital, Universal.Files.SeparateFunctionsFromOO.Mixed, Squiz.Commenting.ClassComment.Missing -- Legacy class name, cannot rename without breaking existing widget instances.
class bupr_single_member_rating_widget extends WP_Widget {

	/**
	 * Constructor to set up widget options.
	 */
	public function __construct() {
		$widget_ops  = array(
			'classname'   => 'bupr_single_member_rating_widget buddypress',
			'description' => esc_html__( 'Display ratings for the displayed member.', 'bp-member-reviews' ),
		);
		$control_ops = array(
			'width'   => 280,
			'height'  => 350,
			'id_base' => 'bupr_single_member_rating_widget',
		);
		parent::__construct( 'bupr_single_member_rating_widget', esc_html__( 'BP Displayed Member Rating Widget', 'bp-member-reviews' ), $widget_ops, $control_ops );
	}

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current widget instance.
	 * @return void
	 */
	public function widget( $args, $instance ) {
		// Extract widget wrapper variables explicitly instead of using extract().
		$before_widget = $args['before_widget'];
		$after_widget  = $args['after_widget'];
		$before_title  = $args['before_title'];
		$after_title   = $args['after_title'];

		// Check if it's a user profile being viewed.
		if ( ! bp_is_user() ) {
			return;
		}

		$user_id = bp_displayed_user_id(); // Get the displayed user ID.
		// `title` is the standard key; `bupr_title` is read for widgets saved before 3.8.3.
		/* translators: %s: Review label; */
		$bupr_title     = apply_filters( 'widget_title', $instance['title'] ?? ( $instance['bupr_title'] ?? sprintf( __( "%s's Ratings", 'bp-member-reviews' ), bp_get_displayed_user_fullname() ) ), $instance, $this->id_base );
		$rating_limit   = isset( $instance['rating_limit'] ) ? $instance['rating_limit'] : 5;
		$rating_default = isset( $instance['rating_default'] ) ? $instance['rating_default'] : 'latest';

		// Get the reviews for the displayed user.
		$bupr_args = array(
			'post_type'      => 'review',
			'post_status'    => 'publish',
			// shortcut: newest 100 member ratings; Highest/Lowest sort within them (the rating is serialized meta, not SQL-sortable).
			'posts_per_page' => 100,
			'no_found_rows'  => true,
			'meta_query'     => array(
				array(
					'key'     => 'linked_bp_member',
					'value'   => $user_id,
					'compare' => '=',
				),
			),
		);

		$reviews            = get_posts( $bupr_args );
		$final_review_arr   = array();
		$final_review_obj   = array();
		$bupr_reviews_count = count( $reviews );

		// Process each review to get the star rating.
		if ( 0 !== $bupr_reviews_count ) {
			foreach ( $reviews as $review ) {
				$review_ratings = get_post_meta( $review->ID, 'profile_star_rating', true );
				if ( is_array( $review_ratings ) && ! empty( $review_ratings ) ) {
					$total_rating = array_sum( $review_ratings );
					$rating_count = count( $review_ratings );
					// Skip if no valid ratings to prevent division by zero.
					if ( 0 === $total_rating || 0 === $rating_count ) {
						continue;
					}
					$final_review_arr[ $review->ID ] = round( (int) $total_rating / $rating_count, 2 ); // Ensure rating precision.
					$final_review_obj[ $review->ID ] = $review;
				}
			}
		}

		echo $before_widget; // phpcs:ignore.
		if ( '' !== (string) $bupr_title ) {
			echo $before_title . esc_html( $bupr_title ) . $after_title; // phpcs:ignore.
		}
		// Sorting Logic for Reviews (highest, lowest, latest).
		if ( ! empty( $final_review_arr ) ) {
			if ( 'highest' === $rating_default ) {
				arsort( $final_review_arr );
			} elseif ( 'lowest' === $rating_default ) {
				asort( $final_review_arr );
			}

			cache_users( wp_list_pluck( $final_review_obj, 'post_author' ) );

			// Display the sorted reviews.
			echo '<ul class="item-list" id="bp-member-rating">';
			$bupr_user_count = 0;
			foreach ( $final_review_arr as $bupr_key => $bupr_value ) {
				if ( (int) $rating_limit > 0 && $bupr_user_count >= (int) $rating_limit ) { // Saved as a string; blank or 0 means no limit.
					break;
				} else {
					$author_id             = $final_review_obj[ $bupr_key ]->post_author;
					$members_profile       = bp_core_get_userlink( $author_id );
					$user_anonymous_review = bupr_is_anonymous_review( $bupr_key );

					echo '<li class="vcard"><div class="item-avatar">';
					// An anonymous review gets the default avatar, never the reviewer's own.
					echo get_avatar( $user_anonymous_review ? 0 : $author_id, 50 );
					echo '</div><div class="item"><div class="item-title">';
					echo ( ! $user_anonymous_review ) ? wp_kses_post( $members_profile ) : esc_html__( 'Anonymous', 'bp-member-reviews' );
					echo '</div><div class="item-meta">';

					/**
					 * Filter whether to show star ratings in widget.
					 * Pro addon sets this to false for Testimonial/Endorsement modes.
					 *
					 * @since 3.8.0
					 * @param bool $show_stars Whether to display star ratings. Default true.
					 */
					$show_stars = apply_filters( 'bupr_widget_show_stars', true );

					if ( $show_stars ) {
						// Display the star ratings (fixed 5 stars with actual rating filled).
						$bupr_avg_rating = $bupr_value;
						$stars_on        = floor( $bupr_avg_rating );
						$stars_half      = ( $bupr_avg_rating - $stars_on >= 0.5 ) ? 1 : 0;
						$stars_off       = 5 - ( $stars_on + $stars_half );

						// Filled stars.
						for ( $i = 1; $i <= $stars_on; $i++ ) {
							echo '<span class="fas fa-star bupr-star-rate"></span>';
						}
						// Half star.
						if ( $stars_half ) {
							echo '<span class="fas fa-star-half-alt bupr-star-rate"></span>';
						}
						// Empty stars.
						for ( $i = 1; $i <= $stars_off; $i++ ) {
							echo '<span class="far fa-star bupr-star-rate"></span>';
						}

						// Display rating (X/5).
						echo '</div><span class="bupr-meta">';
						/* translators: %1$s: average rating */
						printf( esc_html__( '%1$s/5', 'bp-member-reviews' ), esc_html( $bupr_value ) );
						echo '</span>';
					} else {
						echo '</div>';
					}
					echo '</div></li>';

					++$bupr_user_count;
				}
			}
			echo '</ul>';
		} else {
			echo '<p class="bupr-widget-empty">' . esc_html__( 'The member has not received a rating yet.', 'bp-member-reviews' ) . '</p>';
		}

		echo $after_widget; // phpcs:ignore.
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param array $new_instance New settings for this instance.
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                   = $old_instance;
		$instance['title']          = wp_strip_all_tags( $new_instance['title'] ?? '' );
		$instance['rating_limit']   = $new_instance['rating_limit'];
		$instance['rating_default'] = $new_instance['rating_default'];
		unset( $instance['bupr_title'] ); // Legacy key, replaced by `title`.
		return $instance;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @param array $instance Current settings.
	 * @return void
	 */
	public function form( $instance ) {
		$defaults       = array(
			'title'          => $instance['bupr_title'] ?? esc_html__( 'Member Ratings', 'bp-member-reviews' ),
			'rating_limit'   => 5,
			'rating_default' => 'latest',
		);
		$instance       = wp_parse_args( (array) $instance, $defaults );
		$title          = esc_attr( $instance['title'] );
		$rating_limit   = esc_attr( $instance['rating_limit'] );
		$rating_default = esc_attr( $instance['rating_default'] );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Enter Title:', 'bp-member-reviews' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'rating_limit' ) ); ?>"><?php esc_html_e( 'Number of Ratings to Show:', 'bp-member-reviews' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'rating_limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'rating_limit' ) ); ?>" type="number" value="<?php echo esc_attr( $rating_limit ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'rating_default' ) ); ?>"><?php esc_html_e( 'Default Sorting:', 'bp-member-reviews' ); ?></label>
			<select id="<?php echo esc_attr( $this->get_field_id( 'rating_default' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'rating_default' ) ); ?>" class="widefat">
				<option value="latest" <?php selected( $rating_default, 'latest' ); ?>><?php esc_html_e( 'Latest', 'bp-member-reviews' ); ?></option>
				<option value="highest" <?php selected( $rating_default, 'highest' ); ?>><?php esc_html_e( 'Highest', 'bp-member-reviews' ); ?></option>
				<option value="lowest"  <?php selected( $rating_default, 'lowest' ); ?>><?php esc_html_e( 'Lowest', 'bp-member-reviews' ); ?></option>
			</select>
		</p>
		<?php
	}
}
