# BuddyPress Member Reviews (free) - capabilities

Buyer-level answer to "can it do X?", verified against code at 3.8.1. Status: `YES` / `PARTIAL` / `NO`. Pro-only features are listed as `NO` here (this file covers the free plugin).

## Reviewing members

| Question | Status | Delivered by |
|---|---|---|
| Can members rate and review other members on their BuddyPress profile? | YES | Profile Reviews tab + submit handler, `includes/bupr-ajax.php:154` |
| Can a review rate several criteria (e.g. Communication, Skills)? | YES | Multiple Criteria setting + criteria builder, `includes/bupr-filters.php:201`, `admin/tab-templates/bupr-setting-criteria-tab.php` |
| Can members post anonymously? | YES | Anonymous Reviews setting, `includes/class-buprglobals.php:73` |
| Are members stopped from reviewing themselves? | YES | Server check in the submit handler, `includes/bupr-ajax.php:154` |
| Can the site limit each member to one review per person? | YES | Multiple Reviews setting, enforced server-side, `includes/bupr-ajax.php:266` |
| Can the site limit who may review, and who may be reviewed, by role? | YES | Reviewer / Reviewee Roles, enforced server-side, `includes/bupr-general-functions.php:701` |
| Can members edit their own reviews? | YES | Update Review setting + edit endpoints, `includes/bupr-ajax.php:708` |
| Can the reviewed member reply? | YES | One owner reply per review, `includes/bupr-ajax.php:845` |
| Is the reviewer notified of an owner reply? | YES | BuddyPress notification when BuddyPress notifications are on, `includes/bupr-notification.php:289` (3.8.1+) |

## Moderation

| Question | Status | Delivered by |
|---|---|---|
| Can reviews wait for admin approval? | YES | Auto Approve off + Approve row action (Editor and up), `includes/bupr-ajax.php:78` |
| Can members report inappropriate reviews? | YES | Report dialog + handler, `includes/bupr-ajax.php:979` |
| Are reported reviews hidden automatically? | YES | Auto-Hide Threshold (default 5 reports), same handler |
| Can admins see which reviews were hidden by reports? | YES | Auto-hidden view, badge and Overview count, `includes/bupr-general-functions.php:745` |
| Can admins dismiss or resolve reports? | YES | Report Details box (Editor and up), `admin/bupr-admin.php:784`, `:840` |
| Profanity filter, URL blocking, reviewer bans? | NO | Declined for the free plugin |

## Notifications and community

| Question | Status | Delivered by |
|---|---|---|
| Does the reviewed member get an email? | YES | Configurable subject/body, `includes/bupr-ajax.php:449` |
| Does the admin get an approval email? | YES | Approval email (when Auto Approve is off) |
| BuddyPress notifications? | YES | `includes/bupr-notification.php:147` |
| Are reviews posted to the activity stream? | YES | Activity Stream setting, one entry per published review, `includes/bupr-filters.php:1003` |

## Display

| Question | Status | Delivered by |
|---|---|---|
| Star rating in profile header and member directory? | YES | Member Directory Ratings setting, `includes/class-buprglobals.php:65` |
| Top-rated / most-reviewed members list? | YES | `[bupr_display_top_members]`, `includes/bupr-shortcodes.php:30`, plus the Member Review widget |
| Rating widget for the displayed member? | YES | `includes/widgets/member-rating.php` |
| Rating rich snippets (schema.org microdata)? | YES | `includes/bupr-filters.php:291` |
| Custom labels and star colour? | YES | Display tab, colour applied at `includes/bupr-scripts.php:155` |
| Theme template overrides? | YES | `your-theme/buddypress-member-review/`, `includes/bupr-general-functions.php:29` |
| Dark mode on BuddyX / BuddyX Pro / Reign? | YES | Theme's explicit dark mode only (never the OS setting), `assets/css/bupr-front.css` |

## Platform

| Question | Status | Delivered by |
|---|---|---|
| REST API? | NO | AJAX only |
| Gutenberg blocks? | NO | Shortcode and widgets |
| Verified badges, endorsements, testimonials, flexible criteria? | NO | Member Review Pro |
| Translation-ready? | YES | Text domain `bp-member-reviews`; de, fr, es, it, pt_BR shipped |
