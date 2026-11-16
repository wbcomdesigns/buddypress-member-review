# BuddyPress Member Review - Plugin Architecture

## Overview

**Plugin Name:** Wbcom Designs - BuddyPress Member Reviews
**Version:** 3.8.3
**Text Domain:** bp-member-reviews
**WordPress.org Slug:** bp-user-profile-reviews
**Minimum Requirements:**
- WordPress 5.0+
- BuddyPress 12.0+ or BuddyBoss 1.8+
- PHP 7.4+

## Purpose

Enables registered BuddyPress/BuddyBoss community members to post reviews on other members' profiles with star ratings and text reviews.

---

## File Structure

```
buddypress-member-review/
├── buddypress-member-review.php     # Main plugin file
├── admin/
│   ├── bupr-admin.php               # Admin class, CPT registration
│   ├── class-bupr-admin-feedback.php # User feedback collection
│   ├── assets/
│   │   ├── css/                     # Admin styles
│   │   └── js/                      # Admin scripts
│   ├── tab-templates/               # Settings tab templates
│   └── wbcom/                       # Wbcom shared admin framework
├── includes/
│   ├── class-buprglobals.php        # Global settings class
│   ├── bupr-scripts.php             # Asset enqueue
│   ├── bupr-filters.php             # Core filters and hooks
│   ├── bupr-shortcodes.php          # Shortcode class
│   ├── bupr-ajax.php                # AJAX handlers
│   ├── bupr-notification.php        # BP notification integration
│   ├── bupr-general-functions.php   # Helper functions
│   ├── templates/                   # Frontend templates
│   └── widgets/                     # WordPress widgets
├── assets/
│   ├── css/                         # Frontend styles
│   └── js/                          # Frontend scripts
└── languages/                       # Translation files
```

---

## Core Components

### 1. Custom Post Type: `review`

**Registration:** `admin/bupr-admin.php:353`

```php
register_post_type('review', array(
    'labels'       => [...],
    'public'       => false,
    'show_ui'      => true,
    'supports'     => array('title', 'editor', 'author'),
    'taxonomies'   => array('review_category'),
    'capabilities' => array(
        'create_posts' => 'do_not_allow',
    ),
));
```

**Taxonomy:** `review_category` (hierarchical)
**Default Term:** "BP Member"

### 2. User Meta Keys

| Key | Type | Description |
|-----|------|-------------|
| `bupr_review_count` | int | Number of reviews received |
| `bupr_aggregate_rating` | float | Average rating (1-5) |
| `bupr_last_calculated` | timestamp | Last cache update |

### 3. Post Meta Keys

| Key | Type | Description |
|-----|------|-------------|
| `linked_bp_member` | int | User ID being reviewed |
| `profile_star_rating` | array | Rating criteria values |
| `bupr_anonymous_review_post` | string | 'yes' if anonymous |

---

## AJAX Handlers

| Action | Method | File:Line | Purpose |
|--------|--------|-----------|---------|
| `bupr_approve_review` | POST | bupr-ajax.php:37 | Approve pending review |
| `allow_bupr_member_review_update` | POST | bupr-ajax.php:40 | Submit new review |
| `bupr_edit_review` | POST | bupr-ajax.php:46 | Load review for editing |
| `bupr_update_review` | POST | bupr-ajax.php:47 | Save edited review |

---

## Action Hooks (Custom)

| Hook | Location | Parameters |
|------|----------|------------|
| `bupr_member_review_after_review_insert` | bupr-ajax.php:287 | $review_id, $member_id |
| `bupr_sent_review_notification` | bupr-ajax.php:414 | $member_id, $review_id |
| `bupr_before_member_review_list` | templates | - |
| `bupr_after_member_review_list` | templates | - |
| `bupr_member_review_form` | shortcodes | - |
| `bupr_recalculation_completed` | general-functions | $user_id |

---

## Filter Hooks (Custom)

| Filter | Default | Purpose |
|--------|---------|---------|
| `bupr_show_profile_review_tab` | true | Control tab visibility |
| `bupr_review_tab_name` | 'Reviews' | Tab label |
| `bupr_member_query_args` | array | Modify review query |
| `bupr_shortcode_output` | HTML | Filter shortcode output |
| `bupr_allow_activity_posting` | true | Control activity creation |
| `bupr_change_avatar_image` | - | Custom avatar for reviews |
| `bupr_hide_username_in_activity` | false | Anonymous in activity |
| `bupr_default_subnav_slug` | 'view' | Default sub-tab |

---

## Shortcodes

### `[bupr_display_top_members]`

**Attributes:**
- `title` - Section heading
- `total_member` - Number to display (default: 5)
- `type` - 'top rated' or 'most reviewed'
- `avatar` - 'show' or 'hide'

**Output:** Grid of top-rated or most-reviewed members with avatars and ratings.

---

## Widgets

### 1. Member Reviews Display Widget
**Class:** `bupr_members_review_setting`
**File:** `includes/widgets/display-review.php`

### 2. Single Member Rating Widget
**Class:** `bupr_single_member_rating_widget`
**File:** `includes/widgets/member-rating.php`

---

## Templates (Overridable)

Copy to theme: `your-theme/buddypress-member-review/`

| Template | Purpose |
|----------|---------|
| `bupr-reviews-tab-template.php` | Review list on profile |
| `bupr-single-review-template.php` | Single review display |
| `bupr-edit-review-form.php` | Edit modal content |

---

## Cron Jobs

| Hook | Schedule | Purpose |
|------|----------|---------|
| `bupr_cron_recalculate_user_reviews_batch` | daily | Recalculate all user ratings |

---

## Admin Pages

| Menu | Slug | Parent |
|------|------|--------|
| WB Plugins | wbcomplugins | - |
| Member Reviews | bp-member-review-settings | wbcomplugins |

### Settings Tabs

1. **Welcome** - Introduction
2. **General** - Core settings (auto-approve, email, roles)
3. **Criteria** - Rating field configuration
4. **Shortcode** - Usage documentation
5. **Display** - Labels and colors

---

## BuddyPress Integration

### Profile Tab
**Slug:** configurable via settings
**Sub-tabs:** 'view' (list), 'add-review' (form)

### Notifications
**Component:** `bupr_member_review`
**Actions:** New review received

### Activity
Creates activity entries when reviews are published (configurable).

---

## Data Flow

```
User submits review form
    ↓
bupr-front.js validates & collects data
    ↓
AJAX: allow_bupr_member_review_update
    ↓
bupr-ajax.php validates:
  - Nonce, login, permissions
  - Not self-review
  - Multi-review check
    ↓
wp_insert_post (status: publish or draft)
    ↓
Set post meta (ratings, member link, anonymous)
    ↓
do_action('bupr_member_review_after_review_insert')
    ↓
bupr_handle_review_submission()
  - Recalculate aggregate rating
  - Update user meta
  - Clear caches
    ↓
Send notifications (BP + email)
    ↓
Create activity entry (if enabled)
    ↓
Return JSON success
```

---

## Cache Strategy

**User meta caching:**
- `bupr_review_count` - stored on review submit
- `bupr_aggregate_rating` - recalculated on changes

**Cache invalidation hooks:**
- `before_delete_post`
- `trashed_post`
- `untrashed_post`

**Transients:** None currently used (opportunity for improvement)

---

## Security

- Nonce verification on all AJAX handlers
- `current_user_can()` checks for admin actions
- Input sanitization: `sanitize_text_field()`, `absint()`, `wp_kses_post()`
- Output escaping: `esc_html()`, `esc_attr()`, `esc_url()`

---

## Known Limitations

1. No REST API endpoints
2. No CLI commands
3. No database tables (uses post meta)
4. Limited to one shortcode
5. No user-facing delete functionality
6. Page reload after edit (not AJAX-only)

---

## Extension Points

**For Developers:**

```php
// Modify review tab visibility
add_filter('bupr_show_profile_review_tab', function($show, $user_id) {
    // Custom logic
    return $show;
}, 10, 2);

// Modify shortcode output
add_filter('bupr_shortcode_output', function($html, $atts) {
    // Custom HTML
    return $html;
}, 10, 2);

// After review submission
add_action('bupr_member_review_after_review_insert', function($review_id, $member_id) {
    // Custom integration
}, 10, 2);
```

---

## Performance Considerations

1. **Aggregate ratings cached** in user meta (good)
2. **Cron-based batch recalculation** (good)
3. **N+1 queries in shortcode** (needs improvement)
4. **No object caching integration** (opportunity)
5. **Direct DB queries for complex lookups** (acceptable)
