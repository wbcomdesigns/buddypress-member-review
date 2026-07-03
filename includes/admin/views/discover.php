<?php
/**
 * Discover partial: ecosystem cross-promotion (read-only display view).
 *
 * Rendered inside shell.php. This is a pure presentation view — product
 * cards with outbound links to other free Wbcom Designs tools. No forms,
 * no settings, no options, no AJAX.
 *
 * @package BuddyPress_Member_Reviews
 * @since   3.8.0
 */

defined( 'ABSPATH' ) || exit;

$bupr_ecosystem_img = BUPR_PLUGIN_URL . 'assets/images/ecosystem/';

/*
 * Each product: brand mark (shipped in assets/images/ecosystem/), name,
 * a short admin-specific blurb (worded differently from readme.txt), and
 * the wbcomdesigns.com download URL.
 */
$bupr_ecosystem = array(
	array(
		'name' => __( 'BuddyNext', 'bp-member-reviews' ),
		'logo' => 'buddynext.svg',
		'desc' => __( 'Launch a full social network on WordPress with feeds, member spaces, profiles, and private messaging.', 'bp-member-reviews' ),
		'url'  => 'https://wbcomdesigns.com/downloads/buddynext/',
	),
	array(
		'name' => __( 'Jetonomy', 'bp-member-reviews' ),
		'logo' => 'jetonomy.svg',
		'desc' => __( 'Run discussion forums and Q&A boards that police themselves with trust levels and scale to huge topic counts.', 'bp-member-reviews' ),
		'url'  => 'https://wbcomdesigns.com/downloads/jetonomy/',
	),
	array(
		'name' => __( 'Mediaverse', 'bp-member-reviews' ),
		'logo' => 'mediaverse.svg',
		'desc' => __( 'Give members a place to share photos and video, react, follow each other, and chat one to one.', 'bp-member-reviews' ),
		'url'  => 'https://wbcomdesigns.com/downloads/mediaverse/',
	),
	array(
		'name' => __( 'Listora', 'bp-member-reviews' ),
		'logo' => 'listora.svg',
		'desc' => __( 'Build any kind of searchable directory with multiple listing types, ratings, maps, and self-serve submissions.', 'bp-member-reviews' ),
		'url'  => 'https://wbcomdesigns.com/downloads/listora/',
	),
	array(
		'name' => __( 'Learnomy', 'bp-member-reviews' ),
		'logo' => 'learnomy.svg',
		'desc' => __( 'Publish and sell online courses, grade quizzes automatically, and hand learners a certificate at the end.', 'bp-member-reviews' ),
		'url'  => 'https://wbcomdesigns.com/downloads/learnomy/',
	),
);
?>

<div class="bupr-card">
	<div class="bupr-card__head">
		<p class="bupr-card__title"><?php esc_html_e( 'More Free Tools from Wbcom Designs', 'bp-member-reviews' ); ?></p>
		<p class="bupr-card__desc"><?php esc_html_e( 'Member Reviews adds the reputation layer to your community. These free plugins from Wbcom Designs give you the rest of the stack — the network itself, forums, media, directories, and courses.', 'bp-member-reviews' ); ?></p>
	</div>
	<div class="bupr-card__body">
		<div class="bupr-discover-grid">
			<?php foreach ( $bupr_ecosystem as $bupr_product ) : ?>
				<div class="bupr-discover-card">
					<span class="bupr-discover-card__logo" aria-hidden="true">
						<img src="<?php echo esc_url( $bupr_ecosystem_img . $bupr_product['logo'] ); ?>" alt="<?php echo esc_attr( $bupr_product['name'] ); ?>" width="52" height="52" loading="lazy" />
					</span>
					<h3 class="bupr-discover-card__title"><?php echo esc_html( $bupr_product['name'] ); ?></h3>
					<p class="bupr-discover-card__desc"><?php echo esc_html( $bupr_product['desc'] ); ?></p>
					<a class="bupr-btn bupr-btn-secondary bupr-discover-card__cta" href="<?php echo esc_url( $bupr_product['url'] ); ?>" target="_blank" rel="noopener">
						<?php esc_html_e( 'Get it free', 'bp-member-reviews' ); ?>
						<span class="dashicons dashicons-external" aria-hidden="true"></span>
						<span class="screen-reader-text">
							<?php
							/* translators: %s: product name. */
							echo esc_html( sprintf( __( '%s (opens in a new tab)', 'bp-member-reviews' ), $bupr_product['name'] ) );
							?>
						</span>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
