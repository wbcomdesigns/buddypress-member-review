# Plugin: BuddyPress Member Reviews

## Quick Reference
- **Main File**: buddypress-member-review.php
- **Version**: 3.7.0
- **Text Domain**: bp-member-reviews
- **WordPress.org Slug**: bp-user-profile-reviews
- **Prefix**: bupr_

## Download Links
- **Wbcom Designs**: https://wbcomdesigns.com/downloads/buddypress-member-reviews/
- **WordPress.org**: https://wordpress.org/plugins/bp-user-profile-reviews/
- **Documentation**: https://docs.wbcomdesigns.com/docs/buddypress-member-review/
- **Support Forum**: https://wordpress.org/support/plugin/bp-user-profile-reviews/

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
apply_filters('bupr_global_settings', $bupr); // Modify global settings at runtime (used by Pro addon)
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
| `bupr_add_owner_reply` | Profile owner reply to review |
| `bupr_flag_review` | Flag a review as inappropriate |

## User Meta
- `bupr_review_count` - Number of reviews received
- `bupr_aggregate_rating` - Average rating (1-5)

## Post Meta
- `linked_bp_member` - User ID being reviewed
- `profile_star_rating` - Array of rating values
- `bupr_anonymous_review_post` - 'yes' if anonymous
- `bupr_owner_reply` - Profile owner's reply content
- `bupr_owner_reply_date` - Timestamp of owner reply
- `bupr_flags` - Array of flag reports [{reporter_id, reason, reason_label, date}]
- `bupr_flag_status` - 'none'|'flagged'|'resolved'|'dismissed'
- `bupr_flag_count` - Number of flags
- `bupr_auto_hidden` - true if review was auto-hidden due to flag threshold
- `bupr_auto_hidden_date` - Timestamp of auto-hide

## Admin Settings (General Tab)
New settings for free features:
- `bupr_enable_owner_replies` - 'yes'|'no' - Enable profile owners to reply (default: yes)
- `bupr_enable_flagging` - 'yes'|'no' - Enable review flagging (default: yes)
- `bupr_flag_threshold` - int - Auto-hide reviews after X flags (default: 5, 0 = disabled)

## Templates (Overridable)
Copy to `your-theme/review/`:
- `bupr-reviews-tab-template.php`
- `bupr-single-review-template.php`
- `bupr-edit-review-form.php`

## Recent Changes

| Date | Type | Description | Files |
|------|------|-------------|-------|
| 2026-01-26 | docs | Add missing user flows: Edit Review modal, Report modal, Owner Reply inline form | getting-started.md |
| 2026-01-26 | docs | Create standalone feature guides: testimonials.md, endorsements.md, verified-reviews.md | user-guide/ |
| 2026-01-26 | docs | Rewrite docs with value-first approach (problems solved, use cases, impacts) | All docs |
| 2026-01-26 | docs | Embed screenshots in all documentation files for feature showcase | getting-started.md, settings.md, pro-features.md, managing-reviews.md, README.md |
| 2026-01-26 | docs | Update all docs to use "Report" terminology instead of "Flag" | faq.md, settings.md, managing-reviews.md, README.md |
| 2026-01-26 | ui | Change "Flag" terminology to "Report" for consistency across all modes | bupr-reviews-tab-template.php, bupr-front.css, bupr-front.js, bupr-setting-general-tab.php, bupr-admin.php |
| 2026-01-26 | ui | Standardize admin labels to Title Case, clearer descriptions | bupr-setting-general-tab.php, bupr-setting-display-tab.php, bupr-setting-criteria-tab.php |
| 2026-01-26 | docs | QA audit: Fill PRO documentation gaps (admin management, REST API schema, endorsement UX) | pro-features.md, rest-api.md |
| 2026-01-26 | fix | PHP Warning "Undefined array key 0" when Pro stores ratings with label keys | bupr-reviews-tab-template.php:167-176 |
| 2026-01-24 | fix | Template consistency: `.rating-sec` now always wraps date, stars conditional inside | bupr-reviews-tab-template.php:124-176 |
| 2026-01-24 | fix | Pro Features preview now shows only actual Pro features (removed 5 non-existent options) | bupr-setting-pro-features-tab.php |
| 2026-01-24 | fix | Added `bupr_admin_settings_criteria_content` hook for Pro Flexible Criteria builder | bupr-setting-criteria-tab.php:20-35 |
| 2026-01-24 | fix | Testimonial Mode: Hide rating section completely when `active_rating_fields` is empty | bupr-shortcodes.php:361-417 |
| 2026-01-24 | fix | Testimonial Mode: Add missing nonce to AJAX call when no rating fields (critical security fix) | bupr-front.js:195 |
| 2026-01-24 | fix | Added `bupr_global_settings` filter for Pro addon to modify settings at runtime | class-buprglobals.php |
| 2026-01-24 | fix | Profile header respects `dir_view_ratings` setting (for Testimonial Mode) | bupr-filters.php:572-575 |
| 2026-01-23 | feature | Admin controls for owner replies, flagging, auto-hide threshold | class-buprglobals.php, bupr-setting-general-tab.php |
| 2026-01-23 | feature | Pro Features comparison tab in admin | bupr-admin.php, bupr-setting-pro-features-tab.php |
| 2026-01-23 | feature | Owner Replies - profile owner can respond to reviews | bupr-ajax.php, templates, JS, CSS |
| 2026-01-23 | feature | Flagging System - users can report inappropriate reviews | bupr-ajax.php, bupr-admin.php, templates, JS, CSS |
| 2026-01-23 | feature | Pro Addon Plugin created | ../buddypress-member-review-pro/ |
| 2026-01-23 | fix | Accessibility and data reset fixes | bupr-shortcodes.php, bupr-ajax.php, bupr-filters.php |
| 2026-01-23 | fix | Security and coding standards for v3.7.0 | Multiple files |
| 2026-01-23 | docs | Added architecture documentation | docs/architecture/ |

## Pro Addon Plugin

A separate Pro addon is available at `../buddypress-member-review-pro/`:

### Pro Features
- **Review Modes**: Ratings (default), Testimonials, or Endorsements
- **Verified Badges**: Trust signals for authentic reviews
- **REST API**: Full CRUD API at `/wp-json/bupr/v1/`
- **Flexible Criteria**: Dynamic rating criteria builder
- **Endorsement Mode**: LinkedIn-style skill endorsements

### Pro Files
```
buddypress-member-review-pro/
├── buddypress-member-review-pro.php  # Main plugin
├── edd-license/                       # EDD license handling
├── includes/
│   ├── class-bupr-pro-features.php   # Feature gate
│   ├── class-bupr-rest-controller.php # REST API
│   ├── class-bupr-verified-badges.php # Verified badges
│   ├── class-bupr-testimonial-mode.php # Testimonial mode
│   ├── class-bupr-endorsement-mode.php # Endorsements
│   └── class-bupr-flexible-criteria.php # Criteria builder
└── admin/
    └── class-bupr-pro-admin.php       # Pro admin settings
```

### Pro Feature Checks
```php
// Check if Pro is active
if (BUPR_Pro_Features::is_pro_active()) { ... }

// Check specific feature
if (BUPR_Pro_Features::feature_enabled('rest_api')) { ... }

// Get current review mode
$mode = BUPR_Pro_Features::get_review_mode(); // 'ratings'|'testimonials'|'endorsements'
```

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
