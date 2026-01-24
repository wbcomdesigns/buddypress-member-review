<?php
/**
 * Reviews tab template.
 *
 * @package BuddyPress_Member_Reviews
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

global $bp,$post;
global $allowedtags,$allowedposttags;
global $bupr;

$bupr_review_succes = false;
$current_user       = wp_get_current_user();
$member_id          = $current_user->ID;

// Gather all the bp member reviews.
$args = array(
	'post_type'      => 'review',
	'post_status'    => 'publish',
	'posts_per_page' => $bupr['reviews_per_page'],
	'paged'          => get_query_var( 'page', 1 ),
	'category'       => 'bp-member',
	'meta_query'     => array(
		array(
			'key'     => 'linked_bp_member',
			'value'   => bp_displayed_user_id(),
			'compare' => '=',
		),
	),
);

$reviews = new WP_Query( $args );
?>

<?php do_action( 'bupr_before_member_review_list' ); ?>

<div class="bupr-bp-member-reviews-block">
	
	<!-- MODAL FOR USER LOGIN -->
	<input type="hidden" id="reviews_pluginurl" value="<?php echo esc_attr( BUPR_PLUGIN_URL ); ?>">
	<input type="hidden" class="member-rating-limit" value="<?php echo esc_attr( $bupr['reviews_per_page'] ); ?>">

	<div class="bp-member-reviews">
		<div id="bp-member-reviews-list" cellspacing="0">
			<div id="request-review-list" class="item-list">
				<?php
				if ( $reviews->have_posts() ) {
					while ( $reviews->have_posts() ) :
						$reviews->the_post();	
						$anonymous_post_review = get_post_meta( $post->ID, 'bupr_anonymous_review_post', true );
						$member_review_ratings = get_post_meta( $post->ID, 'profile_star_rating', false );
						$author_id             = get_post_field( 'post_author', get_the_ID() );

						if ( ! empty( $bupr['active_rating_fields'] ) ) {
							$member_review_rating_fields = $bupr['active_rating_fields'];
						}
						$bupr_rating_criteria = array();
						if ( ! empty( $member_review_rating_fields ) ) {
							foreach ( $member_review_rating_fields as $bupr_keys => $bupr_fields ) {
									$bupr_rating_criteria[] = $bupr_keys;
							}
						}
						?>
						<div class="bupr-row">
							<?php if ( ! empty( $bupr['allow_update'] ) && 'yes' === $bupr['allow_update'] ) : ?>
								<?php if ( bp_loggedin_user_id() == $author_id ) : ?>
									<div id="bupr-reiew-edit" class="bupr-edit-review-button-wrapper" data-review="<?php echo esc_attr( $post->ID ); ?>">
										<button type="button" class="bupr-edit-review-button" id="bupr-review-edit-btn" data-review="<?php echo esc_attr( $post->ID ); ?>" >
										<?php
										/* translators: %s: */
										echo sprintf( esc_html__( 'Edit %s', 'bp-member-reviews' ), esc_html( bupr_profile_review_singular_tab_name() ) );
										?>
										</button>
									</div>
								<?php endif; ?>
							<?php endif; ?>
							<div class="bupr-members-profiles">
								<div class="item-avatar">
									<?php
									$author = $reviews->post->post_author;
									if ( 'yes' === $anonymous_post_review ) {
										if ( function_exists( 'buddypress' ) && ! isset( buddypress()->buddyboss ) ) {
											$avatar_url = bp_core_avatar_default(
												$type   = 'local',
												array(
													'height' => 96,
													'width' => 96,
													'html' => true,
												)
											);
										} elseif ( function_exists( 'buddypress' ) && isset( buddypress()->buddyboss ) ) {
											$size = bp_core_avatar_full_width();
											$avatar_url = 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mm&amp;s=' . $size;
										}
										echo "<img src='" . esc_url( $avatar_url ) . "' class='avatar avatar-96 photo' alt='Profile Photo' width='96' height='96'></img>";
									} else {

										echo bp_core_fetch_avatar( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
											array(
												'item_id' => $author,
												'height'  => 96,
												'width'   => 96,
											)
										);
									}
									?>
								</div>
								<div class="reviewer">
									<h4>
										<?php
										if ( 'yes' === $anonymous_post_review ) {
											$reviewer_name_html = esc_html__( 'anonymous', 'bp-member-reviews' );
										} else {
											$reviewer_name_html = wp_kses( bp_core_get_userlink( $author ), $allowedtags );
										}
										/**
										 * Filter the reviewer name HTML.
										 * Used by Pro addon to add verified badges.
										 *
										 * @since 3.8.0
										 * @param string $reviewer_name_html The reviewer name HTML.
										 * @param int    $review_id          The review post ID.
										 */
										echo apply_filters( 'bupr_reviewer_name_html', $reviewer_name_html, get_the_ID() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										?>
									</h4>
								</div>
							</div>

							<div class="bupr-members-content">
								<div class="rating-sec">
								<?php
								// Only show star ratings if there are active rating fields and ratings exist.
								if ( ! empty( $member_review_ratings ) && ! empty( $bupr['active_rating_fields'] ) ) {
									$member_review_ratings_new = array();
									foreach ( $bupr_rating_criteria as $bupr_rating_each_criteria ) {
										if ( array_key_exists( $bupr_rating_each_criteria, $member_review_ratings[0] ) ) {
											$member_review_ratings_new[ $bupr_rating_each_criteria ] = $member_review_ratings[0][ $bupr_rating_each_criteria ];
										}
									}
									if ( ! empty( $member_review_ratings_new ) ) {
										$aggregate = array_sum( $member_review_ratings_new ) / count( $member_review_ratings_new );
										if ( $aggregate ) {
											echo "<div class='rating-star'>";
											for ( $i = 0; $i < 5; $i++ ) {
												if ( floor( $aggregate ) - $i >= 1 ) {
													?>
													<span class="fas fa-star bupr-star-rate"></span>
													<?php
												} elseif ( $aggregate - $i > 0 ) {
													?>
													<span class="fa fa-star-half-o bupr-star-rate"></span>
													<?php
												} else {
													?>
													<span class="far fa-star stars bupr-star-rate"></span>
													<?php
												}
											}
											echo '</div>';
											echo '<span class="rating-num">' . esc_html( number_format( $aggregate, 2 ) ) . '</span>';
										}
									} else {
										foreach ( $member_review_ratings as $member_review_rating ) {

											if ( 0 == $member_review_rating[0] ) {
												continue;
											}

											if ( isset( $member_review_rating[0] ) && $member_review_rating[0] !== null ) {
												echo '<span class="rating-num">' . esc_html( number_format( $member_review_rating[0], 2 ) ) . '</span>';
											}
										}
									}
								}
								?>
								<span class="posted-on list">
									<time class="entry-date published updated">
										<?php echo esc_html( date_i18n( 'M Y', strtotime( $reviews->post->post_date ) ) ); ?>
									</time>
								</span>
								</div>
								<?php
								$user_id = bp_displayed_user_id();
								$url     = bp_core_get_userlink( $user_id, false, true ) . bupr_profile_review_tab_plural_slug() . '/' . bupr_default_review_view_slug() . '/' . get_the_id();
								?>
								<div class="bupr-review-description">
									<div class="bupr-full-description">
										<?php
										$trimcontent = get_the_content();
										if ( ! empty( $trimcontent ) ) {
											$len = strlen( $trimcontent );
											if ( $len > 150 ) {
												$shortexcerpt = substr( $trimcontent, 0, 150 );
												?>
												<div class="description">
													<?php echo wp_kses_post( wpautop( $shortexcerpt ) ); ?>
													<a href="<?php echo esc_url( $url ); ?> " class="bupr-read-more">
														<i><?php esc_html_e( 'read more...', 'bp-member-reviews' ); ?></i>
													</a>
												</div>
											<?php } else { ?>
												<div class="description"><?php echo wp_kses_post( wpautop( $trimcontent ) ); ?></div>
												<?php
											}
										}
										if ( ! empty( $member_review_rating_fields ) && ! empty( $member_review_ratings[0] ) ) {

											foreach ( $member_review_ratings[0] as $field => $bupr_value ) {
												
												if( ! $bupr_value ) {
													continue;
												}

												if ( $field === 0 ) {
													$field = '';
												}
												if ( $member_review_rating_fields ) {
													echo '<div class="multi-review inline-content"><div class="bupr-col-4 bupr-criteria-label">' . esc_attr( $field ) . '</div>';
													/*** Star rating Ratings */
													$stars_on  = $bupr_value;
													$stars_off = 5 - $stars_on;
													echo '<div class="bupr-col-4 ">';
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
													echo '</div>';
												} else {
													echo '<div class="multi-review inline-content">';
													/*** Star rating Ratings */
													$stars_on  = $bupr_value;
													$stars_off = 5 - $stars_on;
													echo '<div class="bupr-col-4 ">';
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
													echo '</div>';
												}
											}
										}

										/**
										 * Fires after review content is displayed in the list.
										 * Used by Pro addon to display custom criteria (thumbs/text).
										 *
										 * @since 3.8.0
										 * @param int $review_id The review post ID.
										 */
										do_action( 'bupr_after_review_content', get_the_ID() );
										?>
									</div>
								</div>
								<?php
								// Owner Reply Section - only show if enabled in settings.
								$owner_replies_enabled = isset( $bupr['enable_owner_replies'] ) ? $bupr['enable_owner_replies'] : 'yes';
								if ( 'yes' === $owner_replies_enabled ) :
									$owner_reply      = get_post_meta( get_the_ID(), 'bupr_owner_reply', true );
									$owner_reply_date = get_post_meta( get_the_ID(), 'bupr_owner_reply_date', true );
									$linked_member_id = get_post_meta( get_the_ID(), 'linked_bp_member', true );
									$current_user_id  = get_current_user_id();
									$can_reply        = ( (int) $linked_member_id === $current_user_id ) && empty( $owner_reply );
								?>
								<div class="bupr-reply-section" data-review="<?php echo esc_attr( get_the_ID() ); ?>">
									<?php if ( ! empty( $owner_reply ) ) : ?>
										<?php
										$owner_data   = get_userdata( $linked_member_id );
										$owner_name   = $owner_data ? $owner_data->display_name : __( 'Profile Owner', 'bp-member-reviews' );
										$owner_avatar = bp_core_fetch_avatar(
											array(
												'item_id' => $linked_member_id,
												'object'  => 'user',
												'html'    => false,
											)
										);
										?>
										<div class="bupr-owner-reply">
											<div class="bupr-reply-header">
												<img src="<?php echo esc_url( $owner_avatar ); ?>" class="avatar avatar-32" alt="<?php echo esc_attr( $owner_name ); ?>" width="32" height="32">
												<span class="bupr-reply-author"><?php echo esc_html( $owner_name ); ?></span>
												<span class="bupr-reply-badge"><?php esc_html_e( 'Owner Response', 'bp-member-reviews' ); ?></span>
												<?php if ( $owner_reply_date ) : ?>
													<span class="bupr-reply-date"><?php echo esc_html( date_i18n( get_option( 'date_format' ), $owner_reply_date ) ); ?></span>
												<?php endif; ?>
											</div>
											<div class="bupr-reply-content"><?php echo wp_kses_post( wpautop( $owner_reply ) ); ?></div>
										</div>
									<?php elseif ( $can_reply ) : ?>
										<div class="bupr-reply-form-wrapper">
											<button type="button" class="bupr-show-reply-form button" data-review="<?php echo esc_attr( get_the_ID() ); ?>">
												<?php esc_html_e( 'Reply to this review', 'bp-member-reviews' ); ?>
											</button>
											<div class="bupr-reply-form" style="display: none;">
												<textarea class="bupr-reply-textarea" rows="3" placeholder="<?php esc_attr_e( 'Write your response...', 'bp-member-reviews' ); ?>"></textarea>
												<div class="bupr-reply-actions">
													<button type="button" class="bupr-submit-reply button" data-review="<?php echo esc_attr( get_the_ID() ); ?>">
														<?php esc_html_e( 'Submit Reply', 'bp-member-reviews' ); ?>
													</button>
													<button type="button" class="bupr-cancel-reply button">
														<?php esc_html_e( 'Cancel', 'bp-member-reviews' ); ?>
													</button>
													<span class="bupr-reply-spinner" style="display: none;"></span>
												</div>
											</div>
										</div>
									<?php endif; ?>
								</div>
								<?php endif; // End owner replies enabled check. ?>
							</div>

							<?php
							// Flag Section - only show if enabled in settings.
							$flagging_enabled = isset( $bupr['enable_flagging'] ) ? $bupr['enable_flagging'] : 'yes';
							if ( 'yes' === $flagging_enabled && is_user_logged_in() && get_current_user_id() !== (int) $author_id ) :
							?>
								<div class="bupr-flag-section">
									<button type="button" class="bupr-flag-button" data-review="<?php echo esc_attr( get_the_ID() ); ?>" title="<?php esc_attr_e( 'Report this review', 'bp-member-reviews' ); ?>">
										<span class="dashicons dashicons-flag"></span>
									</button>
								</div>
							<?php endif; // End flagging enabled check. ?>
						</div>
						<?php
						endwhile;

						$total_pages = $reviews->max_num_pages;
					if ( $total_pages > 1 ) {
						?>
						<div class="bupr-pagination">
						<?php
						/*** Posts pagination ***/
						echo "<div class='bupr-posts-pagination'>";
						echo wp_kses(
							paginate_links(
								array(
									'base'    => add_query_arg( 'page', '%#%' ),
									'format'  => '',
									'current' => max( 1, get_query_var( 'page' ) ),
									'total'   => $reviews->max_num_pages,
								)
							),
							$allowedposttags
						);
							echo '</div>';
						?>
						</div>
						<?php
					}
						wp_reset_postdata();
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
							/* translators: %s: */
							echo sprintf( esc_html__( 'Unfortunately, there are no %1$s found.', 'bp-member-reviews' ), esc_html( strtolower( $bupr['review_label'] ) ) );
							?>

						</p>
					</div>
							<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
					<?php do_action( 'bupr_after_member_review_list' ); ?>

<!-- Flag Review Modal -->
<div class="bupr-flag-modal" style="display: none;">
	<div class="bupr-flag-modal-content">
		<div class="bupr-flag-modal-header">
			<h3><?php esc_html_e( 'Report Review', 'bp-member-reviews' ); ?></h3>
			<button type="button" class="bupr-flag-modal-close">&times;</button>
		</div>
		<div class="bupr-flag-modal-body">
			<p><?php esc_html_e( 'Please select a reason for reporting this review:', 'bp-member-reviews' ); ?></p>
			<input type="hidden" id="bupr-flag-review-id" value="">
			<div class="bupr-flag-reasons">
				<label class="bupr-flag-reason-option">
					<input type="radio" name="bupr_flag_reason" value="spam">
					<?php esc_html_e( 'Spam or fake review', 'bp-member-reviews' ); ?>
				</label>
				<label class="bupr-flag-reason-option">
					<input type="radio" name="bupr_flag_reason" value="harassment">
					<?php esc_html_e( 'Harassment or abuse', 'bp-member-reviews' ); ?>
				</label>
				<label class="bupr-flag-reason-option">
					<input type="radio" name="bupr_flag_reason" value="inappropriate">
					<?php esc_html_e( 'Inappropriate content', 'bp-member-reviews' ); ?>
				</label>
				<label class="bupr-flag-reason-option">
					<input type="radio" name="bupr_flag_reason" value="conflict">
					<?php esc_html_e( 'Conflict of interest', 'bp-member-reviews' ); ?>
				</label>
			</div>
			<div class="bupr-flag-error" style="display: none; color: red; margin-top: 10px;"></div>
		</div>
		<div class="bupr-flag-modal-footer">
			<button type="button" class="bupr-flag-submit button"><?php esc_html_e( 'Submit Report', 'bp-member-reviews' ); ?></button>
			<button type="button" class="bupr-flag-cancel button"><?php esc_html_e( 'Cancel', 'bp-member-reviews' ); ?></button>
			<span class="bupr-flag-spinner" style="display: none;"></span>
		</div>
	</div>
</div>
