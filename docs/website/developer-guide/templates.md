# Template Overrides

You can override the front-end review templates from your theme, so your changes survive plugin updates. Never edit files inside the plugin folder.

## Templates you can override

| Template | What it draws |
|---|---|
| `bupr-reviews-tab-template.php` | The **Reviews** tab list on a member profile, including the Report dialog and the owner reply form. |
| `bupr-single-review-template.php` | One full review (opened from "read more..." or a review link). |
| `bupr-edit-review-form.php` | The pop-up shell for editing a review. |

The defaults are in `includes/templates/` inside the plugin.

The review form itself (the stars and the **Submit Review** button) is built in PHP by the plugin, not in a template file. To change it, use the hooks below, such as `bupr_after_rating_fields`.

## How the lookup works

The plugin loads templates with `bupr_get_template()`, which asks `bupr_locate_template()` for the file. It checks, in this order:

1. `your-child-theme/buddypress-member-review/<template>.php`
2. `your-parent-theme/buddypress-member-review/<template>.php`
3. `your-theme/<template>.php` (the theme root, with the same file name)
4. The plugin default, `includes/templates/<template>.php`

The first file that exists wins.

### Example

Copy:

```
wp-content/plugins/buddypress-member-review/includes/templates/bupr-reviews-tab-template.php
```

to:

```
wp-content/themes/your-child-theme/buddypress-member-review/bupr-reviews-tab-template.php
```

Edit the copy, then reload a member profile. Clear any page cache first.

## Keep these when you override

- **`bupr-reviews-tab-template.php`**: keep the `do_action( 'bupr_after_member_review_list' )` line. The edit-review pop-up is added through it, so without it **Edit Review** stops working. Keep the Report dialog markup (`.bupr-flag-modal`) and the reply markup (`.bupr-reply-*`) if you want those features to keep working, because the plugin's JavaScript looks for those class names.
- **`bupr-single-review-template.php`**: it already hides pending and report-hidden reviews from everyone except the writer and moderators. Keep that check if you rewrite the file.
- Anonymous reviews: the templates replace the reviewer's name and picture when the review is marked anonymous. Keep that logic when you copy them.

## Filter the template path

To move a template somewhere else without copying it:

```php
add_filter( 'bupr_locate_template', function ( $template, $template_name, $template_path, $default_path ) {
	if ( 'bupr-single-review-template.php' === $template_name ) {
		return WP_CONTENT_DIR . '/my-reviews/single.php';
	}
	return $template;
}, 10, 4 );
```

## Add markup without overriding

Hooks are safer than copying a whole file:

```php
add_action( 'bupr_before_member_review_list', function () {
	echo '<p class="my-note">Be kind. Reviews follow our community rules.</p>';
} );

add_action( 'bupr_after_review_content', function ( $review_id ) {
	// Append markup after each review's text.
} );
```

See [Hooks and Filters](hooks-and-filters.md) for the full list.

## Good practice

- Escape everything you print: `esc_html()`, `esc_attr()`, `wp_kses_post()`.
- Use a child theme so updates to your theme do not remove your overrides.
- The edit, reply and report actions use AJAX, so they need JavaScript.
- After a plugin update, compare your copy with the new default to pick up fixes.
