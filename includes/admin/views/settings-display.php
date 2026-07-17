<?php
/**
 * Settings tab: Display.
 *
 * Wraps the existing Display tab template (ported 1:1).
 *
 * @package BuddyPress_Member_Reviews
 * @since   3.8.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="bupr-card bupr-card--legacy">
	<div class="bupr-card__body">
		<?php require BUPR_PLUGIN_PATH . 'admin/tab-templates/bupr-setting-display-tab.php'; ?>
	</div>
</div>

<?php
/*
 * Shortcode reference. Kept here (rather than on its own tab) so the one
 * shortcode this plugin ships is discoverable next to the display settings it
 * obeys. Attributes and defaults mirror the `shortcode_atts()` call in
 * includes/bupr-shortcodes.php — keep the two in sync.
 */
?>
<div class="bupr-card">
	<div class="bupr-card__head">
		<p class="bupr-card__title"><?php esc_html_e( 'Top Members Shortcode', 'bp-member-reviews' ); ?></p>
		<p class="bupr-card__desc"><?php esc_html_e( 'Paste this shortcode into any post, page, or block to list your highest-rated or most-reviewed members.', 'bp-member-reviews' ); ?></p>
	</div>
	<table class="form-table">
		<tr>
			<th scope="row"><?php esc_html_e( 'Shortcode', 'bp-member-reviews' ); ?></th>
			<td><code>[bupr_display_top_members]</code></td>
		</tr>
		<tr>
			<th scope="row"><code>title</code></th>
			<td>
				<code>[bupr_display_top_members title="Top Rated Members"]</code><br />
				<span class="description"><?php esc_html_e( 'Heading shown above the list. Empty by default.', 'bp-member-reviews' ); ?></span>
			</td>
		</tr>
		<tr>
			<th scope="row"><code>total_member</code></th>
			<td>
				<code>[bupr_display_top_members total_member="5"]</code><br />
				<span class="description"><?php esc_html_e( 'How many members to list. Defaults to 5.', 'bp-member-reviews' ); ?></span>
			</td>
		</tr>
		<tr>
			<th scope="row"><code>type</code></th>
			<td>
				<code>[bupr_display_top_members type="top rated"]</code><br />
				<code>[bupr_display_top_members type="top reviewed"]</code><br />
				<span class="description"><?php esc_html_e( 'Order by highest average rating ("top rated", the default) or by number of reviews received ("top reviewed").', 'bp-member-reviews' ); ?></span>
			</td>
		</tr>
		<tr>
			<th scope="row"><code>avatar</code></th>
			<td>
				<code>[bupr_display_top_members avatar="hide"]</code><br />
				<span class="description"><?php esc_html_e( 'Show or hide member avatars. Defaults to show.', 'bp-member-reviews' ); ?></span>
			</td>
		</tr>
	</table>
</div>
