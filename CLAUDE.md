# Plugin: BuddyPress Member Reviews

## Quick Reference
- **Main File**: buddypress-member-review.php
- **Version**: 3.7.0
- **Text Domain**: bp-member-reviews
- **WordPress.org Slug**: bp-user-profile-reviews
- **Prefix**: bupr_

## Architecture Docs
Full documentation in `docs/architecture/`:
- [PLUGIN_ARCHITECTURE.md](docs/architecture/PLUGIN_ARCHITECTURE.md) - Complete reference
- [manifest/](docs/architecture/manifest/) - Index files

## Key Entry Points

### Main Classes/Files
- **Main plugin**: `buddypress-member-review.php` - Initialization, activation hooks
- **Admin**: `admin/bupr-admin.php` - Settings, CPT registration
- **AJAX**: `includes/bupr-ajax.php` - All AJAX handlers
- **Filters**: `includes/bupr-filters.php` - Profile tabs, display logic
- **Shortcodes**: `includes/bupr-shortcodes.php` - `[bupr_display_top_members]`

### Frontend Flow
1. `assets/js/bupr-front.js` - Review submission, editing, star ratings
2. `includes/templates/bupr-reviews-tab-template.php` - Review list
3. `includes/templates/bupr-single-review-template.php` - Single review

## Custom Post Type
- **CPT**: `review` (registered at `admin/bupr-admin.php:353`)
- **Taxonomy**: `review_category`

## Hooks (Most Used)

### Actions
```php
do_action('bupr_member_review_after_review_insert', $review_id, $member_id);
do_action('bupr_sent_review_notification', $member_id, $review_id);
do_action('bupr_before_member_review_list');
do_action('bupr_after_member_review_list');
```

### Filters
```php
apply_filters('bupr_show_profile_review_tab', true);
apply_filters('bupr_review_tab_name', 'Reviews');
apply_filters('bupr_shortcode_output', $html, $atts);
apply_filters('bupr_allow_activity_posting', true);
```

## AJAX Handlers
| Action | Purpose |
|--------|---------|
| `allow_bupr_member_review_update` | Submit new review |
| `bupr_approve_review` | Admin approve review |
| `bupr_edit_review` | Load review for editing |
| `bupr_update_review` | Save edited review |

## User Meta
- `bupr_review_count` - Number of reviews received
- `bupr_aggregate_rating` - Average rating (1-5)

## Post Meta
- `linked_bp_member` - User ID being reviewed
- `profile_star_rating` - Array of rating values
- `bupr_anonymous_review_post` - 'yes' if anonymous

## Templates (Overridable)
Copy to `your-theme/review/`:
- `bupr-reviews-tab-template.php`
- `bupr-single-review-template.php`
- `bupr-edit-review-form.php`

## Recent Changes

| Date | Type | Description | Files |
|------|------|-------------|-------|
| 2026-01-23 | fix | Accessibility and data reset fixes | bupr-shortcodes.php, bupr-ajax.php, bupr-filters.php |
| 2026-01-23 | fix | Security and coding standards for v3.7.0 | Multiple files |
| 2026-01-23 | docs | Added architecture documentation | docs/architecture/ |

## Known Issues / TODOs

### Critical (from code review)
1. **Race condition in rating calculation** - `bupr-ajax.php:284-287` - Recalculation only fires if ratings exist
2. **Double review bypass** - Users can submit while review is in draft status
3. **Anonymous user ID leakage** - Activity stores real user_id for anonymous reviews

### High Priority
1. **Silent email failures** - wp_mail() return not checked in multiple locations
2. **Missing cache invalidation** - Post status transitions don't trigger recalc
3. **No user-facing delete** - Users cannot remove their own reviews

### Moderate
1. **N+1 queries in shortcode** - Performance issue with many members
2. **Page reload after edit** - Should be AJAX-only
3. **Deprecated FILTER_SANITIZE_FULL_SPECIAL_CHARS** - PHP 8.1+ warnings

## Development Notes

### Running Tests
```bash
# No automated tests currently exist
# Manual testing required
```

### Common Tasks

**Add new rating criteria:**
Settings > Member Reviews > Criteria Tab

**Override template:**
Copy `includes/templates/*.php` to `theme/review/`

**Disable activity posting:**
```php
add_filter('bupr_allow_activity_posting', '__return_false');
```

**Hide reviews tab for certain users:**
```php
add_filter('bupr_show_profile_review_tab', function($show, $user_id) {
    // Your logic here
    return $show;
}, 10, 2);
```

## Build Commands
```bash
# Grunt for JS minification (if configured)
npm install
grunt
```

## File Counts
- PHP files: ~25
- JS files: 5
- CSS files: 4
- Templates: 6
