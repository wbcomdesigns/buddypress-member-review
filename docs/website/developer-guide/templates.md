# Template Overrides

The front-end review templates can be overridden from your theme, so your customizations survive plugin updates. Do not edit files inside the plugin directory.

## Templates you can override

| Template | Purpose |
|---|---|
| `bupr-reviews-tab-template.php` | The Reviews tab on a member profile (the review list). |
| `bupr-single-review-template.php` | A single review's markup. |
| `bupr-edit-review-form.php` | The edit-review form. |

The defaults ship in `includes/templates/` inside the plugin.

## How overriding works

The plugin loads templates through `bupr_get_template()`, which resolves the path with `bupr_locate_template()`. The lookup order is:

1. `wp-content/themes/your-theme/buddypress-member-review/<template>.php`
2. the plugin default in `includes/templates/<template>.php`

So to override a template, copy it into a `buddypress-member-review/` folder in your (child) theme and edit the copy.

### Example

Copy:

```
wp-content/plugins/buddypress-member-review/includes/templates/bupr-reviews-tab-template.php
```

to:

```
wp-content/themes/your-child-theme/buddypress-member-review/bupr-reviews-tab-template.php
```

Clear any page cache and reload a member profile to see your version.

## Filtering the resolved path

If you need to relocate templates, filter the resolved path instead of copying:

```php
add_filter( 'bupr_locate_template', function ( $template, $template_name, $template_path, $default_path ) {
    // Return a different absolute path for a given $template_name.
    return $template;
}, 10, 4 );
```

## Extending without overriding

Prefer the template hooks over copying a whole template when you only need to add markup:

```php
add_action( 'bupr_before_member_review_list', function () {
    echo '<div class="my-notice">Community review guidelines apply.</div>';
} );

add_action( 'bupr_after_review_content', function ( $review_id ) {
    // Append custom markup after a review's content.
} );
```

Available template hooks: `bupr_before_member_review_list`, `bupr_after_member_review_list`, `bupr_after_review_date` (`$review_id`), `bupr_after_review_content` (`$review_id`), `bupr_after_rating_fields`, and the `bupr_reviewer_name_html` filter (`$html`, `$review_id`). See [Hooks and Filters](hooks-and-filters.md).

## Notes

- Always escape output with `esc_html()`, `esc_attr()`, or `wp_kses_post()`.
- Use a child theme so your overrides survive theme updates.
- The edit, reply, and report flows are AJAX-driven and require JavaScript.
