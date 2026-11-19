# BuddyPress Member Reviews - Complete File Index

**Plugin Version:** 3.8.3
**Last Updated:** 2026-11-16

---

## Root Files

| File | Purpose |
|------|---------|
| `buddypress-member-review.php` | Main plugin file, activation hooks, constants |
| `index.php` | Security placeholder |
| `uninstall.php` | Cleanup on plugin deletion |
| `readme.txt` | WordPress.org readme with changelog |
| `CLAUDE.md` | AI assistant reference documentation |
| `gruntfile.js` | Build automation |
| `package.json` | NPM dependencies |
| `package-lock.json` | Dependency lock file |

---

## Admin (`admin/`)

### Core Admin Files

| File | Purpose |
|------|---------|
| `bupr-admin.php` | Review post type, admin list columns, approve and report actions |
| `class-bupr-admin-feedback.php` | Plugin deactivation feedback modal |

### Tab Templates (`admin/tab-templates/`)

| File | Purpose |
|------|---------|
| `bupr-setting-general-tab.php` | General settings (auto-approve, notifications, flagging) |
| `bupr-setting-display-tab.php` | Display options (star colors, labels, positions) |
| `bupr-setting-criteria-tab.php` | Rating criteria builder |
| `bupr-setting-pro-features-tab.php` | Add-ons tab wrapper (shown only when an add-on such as Pro is active) |

### Admin Assets

| File | Purpose |
|------|---------|
| `admin/assets/css/bupr-admin.css` | Review list and edit screen styling |
| `admin/assets/js/bupr-admin.js` | Review list actions and criteria rows |
| `admin/assets/js/bupr-color-picker.js` | Color picker integration |
| `admin/assets/*/min/` | Minified copies |
| `assets/css/admin.css` | Settings panel styling (card panel) |
| `assets/js/admin.js` | Settings panel JavaScript |

### Settings Panel (`includes/admin/`)

The settings screen at WB Plugins > Member Reviews (3.8.0+). The legacy `admin/wbcom/` wrapper was removed in 3.8.0.

| File | Purpose |
|------|---------|
| `class-bupr-admin-panel.php` | Registers the menu page and its tabs, saves each tab |
| `views/layout.php` | Page shell with the sidebar |
| `views/hub.php` | WB Plugins hub page |
| `views/overview.php` | Overview tab |
| `views/settings-general.php` | General tab |
| `views/settings-criteria.php` | Criteria tab |
| `views/settings-display.php` | Display tab |
| `views/settings-pro-features.php` | Add-ons tab |
| `views/discover.php` | Discover tab |

---

## Includes (`includes/`)

### Core PHP Files

| File | Purpose |
|------|---------|
| `class-buprglobals.php` | Global settings class, option management |
| `bupr-ajax.php` | All AJAX handlers (submit, approve, edit, flag, reply) |
| `bupr-filters.php` | BuddyPress integration (tabs, header ratings) |
| `bupr-general-functions.php` | Helper functions (rating calculation, display) |
| `bupr-notification.php` | Email and BP notification handlers |
| `bupr-scripts.php` | Frontend asset enqueuing |
| `bupr-shortcodes.php` | `[bupr_display_top_members]` shortcode |

### Templates (`includes/templates/`)

| File | Purpose |
|------|---------|
| `bupr-reviews-tab-template.php` | Review list display on profile |
| `bupr-single-review-template.php` | Individual review card layout |
| `bupr-edit-review-form.php` | Edit review modal form |
| `bupr-owner-reply.php` | Profile owner reply box and reply |

### Widgets (`includes/widgets/`)

| File | Purpose |
|------|---------|
| `display-review.php` | Display Review widget |
| `member-rating.php` | BP Displayed Member Rating widget |

---

## Frontend Assets (`assets/`)

### CSS

| File | Purpose |
|------|---------|
| `css/bupr-front.css` | Frontend styling (review styles merged in 3.8.0) |
| `css/min/bupr-front.min.css` | Minified frontend CSS |
| `css/rtl/bupr-front.rtl.css` | RTL frontend support |
| `css/fontawesome/all.min.css` | FontAwesome icons |

### JavaScript

| File | Purpose |
|------|---------|
| `js/bupr-front.js` | Frontend functionality (star ratings, AJAX, modals) |
| `js/min/bupr-front.min.js` | Minified frontend JS |

---

## Languages (`languages/`)

| File | Purpose |
|------|---------|
| `bp-member-reviews-*.po` / `.mo` / `.l10n.php` | Translations (de_DE, es_ES, fr_FR, it_IT, pt_BR) and the `.pot` template |

---

## Documentation (`docs/`)

| Path | Purpose |
|------|---------|
| `README.md` | Documentation index |
| `FILE_INDEX.md` | This file |
| `website/` | Customer documentation (getting started, how-to, features, shortcodes, FAQ, troubleshooting, developer guide), ordered by `website/docs_config.json` |
| `architecture/PLUGIN_ARCHITECTURE.md` | Technical reference |
| `architecture/manifest/*.txt` | Component indexes (classes, hooks, etc.) |
| `marketing/` | Marketing copy |
| `qa/` | QA notes |

---

## Key Entry Points

### Initialization Flow

```
buddypress-member-review.php
├── Define constants (BUPR_PLUGIN_VERSION, BUPR_PLUGIN_URL, etc.)
├── Load dependencies
│   ├── includes/class-buprglobals.php (settings)
│   ├── includes/bupr-filters.php (BP integration)
│   ├── includes/bupr-ajax.php (AJAX handlers)
│   ├── includes/bupr-shortcodes.php
│   ├── includes/bupr-scripts.php
│   └── includes/bupr-notification.php
└── Admin hooks
    └── admin/bupr-admin.php
```

### AJAX Handlers

| Action | Handler | File |
|--------|---------|------|
| `allow_bupr_member_review_update` | Submit new review | bupr-ajax.php |
| `bupr_approve_review` | Approve pending review | bupr-ajax.php |
| `bupr_edit_review` | Load review for editing | bupr-ajax.php |
| `bupr_update_review` | Save edited review | bupr-ajax.php |
| `bupr_add_owner_reply` | Profile owner reply | bupr-ajax.php |
| `bupr_flag_review` | Flag inappropriate review | bupr-ajax.php |

### Template Hierarchy

```
Theme Override Location: {theme}/review/
├── bupr-reviews-tab-template.php
├── bupr-single-review-template.php
└── bupr-edit-review-form.php
```

---

## Pro Addon Integration Points

The FREE plugin provides these hooks for Pro addon integration:

| Hook Type | Hook Name | Purpose |
|-----------|-----------|---------|
| Filter | `bupr_global_settings` | Modify global settings at runtime |
| Action | `bupr_admin_settings_criteria_content` | Add Pro criteria builder |
| Action | `bupr_after_review_date` | Insert verified badges |
| Filter | `bupr_review_tab_name` | Change tab label for modes |
| Filter | `bupr_show_profile_review_tab` | Control tab visibility |

---

## File Statistics

| Category | Count |
|----------|-------|
| PHP Files | 35 |
| JavaScript Files | 8 |
| CSS Files | 12 |
| Documentation | 16 |
| Configuration | 4 |
| Language | 2 |
| **Total** | **82** |
