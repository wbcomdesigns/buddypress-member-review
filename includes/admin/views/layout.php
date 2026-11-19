<?php
/**
 * Admin page shell: page header, sidebar nav, body slot.
 *
 * Receives from BUPR_Admin_Panel::render_page():
 *
 * @var array  $bupr_tabs           Tab registry keyed by slug.
 * @var string $active              Active tab slug.
 * @var string $page_url            Base URL (admin.php?page=bp-member-review-settings).
 * @var string $view                View slug (e.g. 'overview', 'settings-general').
 * @var string $view_path           Absolute path to the partial.
 * @var string $settings_form_group register_setting() group for the active settings tab ('' for non-settings tabs).
 *
 * @package BuddyPress_Member_Reviews
 * @since   3.8.0
 */

defined( 'ABSPATH' ) || exit;

$bupr_version = defined( 'BUPR_PLUGIN_VERSION' ) ? BUPR_PLUGIN_VERSION : '';
?>
<div class="wrap bupr-admin">

	<header class="bupr-page-header">
		<div class="bupr-page-header__title">
			<span class="dashicons dashicons-testimonial" aria-hidden="true"></span>
			<div>
				<h1><?php echo esc_html( 'BuddyPress ' . BUPR_Admin_Panel::product_name() ); ?></h1>
				<p class="bupr-page-header__subtitle"><?php esc_html_e( 'Let members review and rate each other on their BuddyPress profiles, with criteria, moderation, owner replies, and reporting.', 'bp-member-reviews' ); ?></p>
			</div>
		</div>
		<div class="bupr-page-header__actions">
			<?php if ( $bupr_version ) : ?>
				<span class="bupr-version-pill">v<?php echo esc_html( $bupr_version ); ?></span>
			<?php endif; ?>
		</div>
	</header>

	<?php
	/*
	 * Without this marker, core's common.js re-parents every .notice to sit
	 * right after the first <h1> it finds, which slots the "Settings saved"
	 * banner between our title and its subtitle instead of below the header.
	 */
	?>
	<hr class="wp-header-end">

	<div class="bupr-settings-layout">

		<aside class="bupr-settings-sidebar">
			<div class="bupr-settings-sidebar-brand">
				<span class="bupr-settings-brand-icon" aria-hidden="true">
					<span class="dashicons dashicons-testimonial"></span>
				</span>
				<div class="bupr-settings-brand-text">
					<p class="bupr-settings-brand-name"><?php echo esc_html( BUPR_Admin_Panel::product_name() ); ?></p>
					<p class="bupr-settings-brand-sub"><?php esc_html_e( 'Plugin', 'bp-member-reviews' ); ?></p>
				</div>
			</div>
			<nav class="bupr-settings-sidebar-nav" aria-label="<?php esc_attr_e( 'Member Reviews navigation', 'bp-member-reviews' ); ?>">
				<?php
				$bupr_printed_groups = array();
				$bupr_group_labels   = array(
					'settings' => esc_html__( 'Settings', 'bp-member-reviews' ),
					'discover' => esc_html__( 'More Tools', 'bp-member-reviews' ),
				);
				foreach ( $bupr_tabs as $bupr_slug => $bupr_tab ) {
					$bupr_group = isset( $bupr_tab['group'] ) ? $bupr_tab['group'] : 'main';
					if ( 'main' !== $bupr_group && ! in_array( $bupr_group, $bupr_printed_groups, true ) ) {
						echo '<div class="bupr-snav-divider" role="separator"></div>';
						if ( isset( $bupr_group_labels[ $bupr_group ] ) ) {
							echo '<p class="bupr-snav-section-label">' . esc_html( $bupr_group_labels[ $bupr_group ] ) . '</p>';
						}
						$bupr_printed_groups[] = $bupr_group;
					}
					$bupr_classes  = 'bupr-snav-link';
					$bupr_classes .= $active === $bupr_slug ? ' bupr-snav-link--active' : '';
					echo '<a href="' . esc_url( $page_url . '&tab=' . $bupr_slug ) . '" class="' . esc_attr( $bupr_classes ) . '">';
					echo '<span class="dashicons ' . esc_attr( $bupr_tab['icon'] ) . '" aria-hidden="true"></span>';
					echo esc_html( $bupr_tab['label'] );
					echo '</a>';
				}
				?>

				<div class="bupr-snav-divider" role="separator"></div>
				<p class="bupr-snav-section-label"><?php esc_html_e( 'Resources', 'bp-member-reviews' ); ?></p>
				<a href="https://docs.wbcomdesigns.com/docs/buddypress-member-review/" class="bupr-snav-link" target="_blank" rel="noopener noreferrer">
					<span class="dashicons dashicons-book" aria-hidden="true"></span>
					<?php esc_html_e( 'Documentation', 'bp-member-reviews' ); ?>
					<span class="dashicons dashicons-external bupr-snav-link__ext" aria-hidden="true"></span>
					<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'bp-member-reviews' ); ?></span>
				</a>
			</nav>
		</aside>

		<div class="bupr-settings-main">
			<?php
			// Render settings notices inside the content column so the banner
			// aligns with the panel chrome instead of spanning full width.
			settings_errors();

			/*
			 * Each tab partial (and the legacy tab template it includes) owns
			 * its own <form method="post" action="options.php"> with
			 * settings_fields() + submit_button(). The shell therefore does
			 * NOT wrap a form — it only provides the chrome + body slot.
			 */
			if ( file_exists( $view_path ) ) {
				include $view_path;
			}
			?>
		</div>

	</div>
</div>
