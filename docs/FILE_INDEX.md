# BuddyPress Member Reviews - Complete File Index

**Plugin Version:** 3.7.0
**Last Updated:** 2026-01-26
**Total Files:** 82 files (excluding node_modules, vendor)

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
| `bupr-admin.php` | Admin settings, CPT registration, menu pages |
| `class-bupr-admin-feedback.php` | Plugin deactivation feedback modal |

### Tab Templates (`admin/tab-templates/`)

| File | Purpose |
|------|---------|
| `bupr-setting-general-tab.php` | General settings (auto-approve, notifications, flagging) |
| `bupr-setting-display-tab.php` | Display options (star colors, labels, positions) |
| `bupr-setting-criteria-tab.php` | Rating criteria builder |
| `bupr-setting-shortcode-tab.php` | Shortcode generator and reference |
| `bupr-setting-support-tab.php` | Support links and resources |
| `bupr-setting-pro-features-tab.php` | Pro version feature preview |
| `bupr-member-types-criteria-tab.php` | Member type specific criteria (Pro) |
| `bupr-welcome-page.php` | Welcome/activation page |

### Admin Assets

| File | Purpose |
|------|---------|
| `assets/css/bupr-admin.css` | Admin panel styling |
| `assets/css/min/bupr-admin.min.css` | Minified admin CSS |
| `assets/css/rtl/bupr-admin.rtl.css` | RTL support |
| `assets/js/bupr-admin.js` | Admin JavaScript (criteria builder, settings) |
| `assets/js/bupr-color-picker.js` | Color picker integration |
| `assets/js/min/bupr-admin.min.js` | Minified admin JS |
| `assets/js/min/bupr-color-picker.min.js` | Minified color picker |

### Wbcom Wrapper (`admin/wbcom/`)

| File | Purpose |
|------|---------|
| `wbcom-admin-settings.php` | Wbcom SDK integration |
| `templates/wbcom-license-page.php` | License activation page |
| `templates/wbcom-plugins-page.php` | Wbcom plugins showcase |
| `templates/wbcom-support-page.php` | Support page template |
| `assets/css/wbcom-admin-setting.css` | Wbcom admin styling |
| `assets/js/wbcom-admin-setting.js` | Wbcom admin JavaScript |

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
| `bupr-shortcodes.php` | `[bupr_review]`, `[bupr_display_top_members]` shortcodes |

### Templates (`includes/templates/`)

| File | Purpose |
|------|---------|
| `bupr-reviews-tab-template.php` | Review list display on profile |
| `bupr-single-review-template.php` | Individual review card layout |
| `bupr-edit-review-form.php` | Edit review modal form |

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
| `css/bupr-front.css` | Main frontend styling |
| `css/bupr-reviews.css` | Review display styling |
| `css/min/bupr-front.min.css` | Minified frontend CSS |
| `css/min/bupr-reviews.min.css` | Minified reviews CSS |
| `css/rtl/bupr-front.rtl.css` | RTL frontend support |
| `css/rtl/bupr-reviews.rtl.css` | RTL reviews support |
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
| `bp-member-reviews-de_DE.l10n.php` | German translation |
| `bp-member-reviews-fr_FR.l10n.php` | French translation |

---

## Documentation (`docs/`)

### User Guides

| File | Purpose |
|------|---------|
| `README.md` | Documentation index |
| `FILE_INDEX.md` | This file - complete file listing |
| `user-guide/getting-started.md` | Installation and setup guide |
| `user-guide/pro-features.md` | Pro addon feature documentation |
| `user-guide/faq.md` | Frequently asked questions |

### Admin Guides

| File | Purpose |
|------|---------|
| `admin-guide/settings.md` | Settings reference |
| `admin-guide/managing-reviews.md` | Review moderation guide |
| `admin-guide/shortcodes.md` | Shortcode usage |
| `admin-guide/widgets.md` | Widget configuration |

### Developer Guides

| File | Purpose |
|------|---------|
| `developer-guide/hooks.md` | Actions and filters reference |
| `developer-guide/templates.md` | Template override guide |
| `developer-guide/database.md` | Meta field reference |
| `developer-guide/rest-api.md` | REST API documentation (Pro) |

### Architecture

| File | Purpose |
|------|---------|
| `architecture/PLUGIN_ARCHITECTURE.md` | Complete technical reference |
| `architecture/FINAL_REPORT.md` | Documentation completeness report |
| `architecture/manifest/*.txt` | Component indexes (classes, hooks, etc.) |

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
