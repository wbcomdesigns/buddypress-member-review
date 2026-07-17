=== Wbcom Designs - BuddyPress Member Reviews ===
Contributors: wbcomdesigns, vapvarun
Donate link: https://wbcomdesigns.com/donate/
Tags: buddypress, members, reviews, ratings, community
Requires at least: 6.1
Tested up to: 7.0
Stable tag: 3.8.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: bp-member-reviews
Domain Path: /languages

Let members review and rate each other on their profiles. Build trust with star ratings, custom criteria, moderation, and directory ratings.

== Description ==

**BuddyPress Member Reviews** gives your community a reputation layer. Registered members post reviews on other members' profiles, rate them against criteria you define, and the plugin rolls those scores into an aggregate rating shown on the profile and on the members directory.

It needs BuddyPress 6.0+ or BuddyBoss Platform 1.8+ with the Members component active. Everything is controlled from one settings screen: who can review whom, whether reviews are auto-approved or moderated, what the criteria are called, and how the whole thing is labelled.

= What you get =

**Reviews and ratings on member profiles**
* A Reviews tab on every member profile, with per-criteria star ratings and an aggregate score.
* Rate members against criteria you define. Two are seeded on activation, "Member Response" and "Member Skills", and you can rename, remove, or add your own.
* Members can edit their own reviews after posting, when the "Allow update" setting is on.
* Profile owners can reply to reviews left about them, once per review.
* Self-reviews are blocked, so scores stay honest.
* Optional anonymous reviews for members who want to give feedback privately.

**Ratings on the members directory**
* Show each member's star rating right on the members directory, on by default.
* An "Add Review" button on directory entries so members can review without opening the profile first, also on by default.
* Both are individually switchable in General settings.

**Moderation you control**
* Auto Approve Reviews is ON by default, so reviews go live immediately. Turn it OFF to hold every new review as a draft until an admin approves it.
* Members can report inappropriate reviews.
* Reviews auto-hide once they pass a report threshold you set.
* A Report Details meta box on each review in the admin, with dismiss and resolve actions plus a flagged-reviews filter and bulk actions on the Reviews list.
* Reviewer and reviewee role gating: choose which roles may leave reviews and which roles may receive them.

**Notifications**
* BuddyPress notifications when a member receives a review.
* Email notifications for new reviews, and pending-approval alerts for admins.
* Both can be switched off independently.

**Activity stream**
* Publish reviews to the BuddyPress activity stream so the community sees new feedback as it happens. Off by default, switch it on in General settings.

**Display anywhere**
* Two widgets: "BP Member Review Widget" (top rated or most reviewed members) and "BP Displayed Member Rating Widget" (the rating of the member whose profile is being viewed).
* The `[bupr_display_top_members]` shortcode for a top-rated or top-reviewed list on any page or post.
* Customize the review labels and star color, including the singular and plural label used for the profile tab.

= Perfect For =

* Freelance and service marketplaces where members hire each other
* Coaching, mentoring, and tutoring communities
* Membership sites that need visible trust signals
* Any BuddyPress or BuddyBoss community where reputation matters

= Premium Support =

Our dedicated support team is ready to help you get the most out of this plugin. Whether you need help with setup, customization, or troubleshooting, we're here to assist.

= Documentation =

* **[Documentation and Support](https://docs.wbcomdesigns.com/)** - Setup walkthrough, settings reference, and usage guides

= Translations =

* English (default)
* German (de_DE) and French (fr_FR) translations included
* Ready for translation in your language with included POT file
* WPML compatible (wpml-config.xml included)
* RTL language support included

= Links =

* [Plugin Homepage](https://wbcomdesigns.com/downloads/buddypress-member-reviews/)
* [Documentation](https://docs.wbcomdesigns.com/)
* [Support](https://wbcomdesigns.com/support/)
* [Request Features](https://wbcomdesigns.com/contact/)

= Compatibility =

* WordPress 6.1 and higher
* PHP 7.4 and higher (8.0+ recommended)
* BuddyPress 6.0+ or BuddyBoss Platform 1.8+ required, with the Members component active
* Works with the block editor and classic editor. The two widgets are classic WP_Widget classes, so on a block theme add them through the Legacy Widget block.
* Tested with popular themes including BuddyX, Reign, BuddyBoss Theme and Youzify

= What's New in 3.8.0 =

The admin settings screen is rebuilt on the Wbcom card-panel UI with sidebar navigation, an overview dashboard, and a Discover tab. On the front end, the review styles now run on one design-token system across light and dark, the star rating input is a keyboard-operable ARIA radiogroup, and directional CSS has moved to logical properties for correct right-to-left rendering. This release also closes a security gap: the review update endpoint now enforces an ownership and capability check, so members can only edit their own reviews.

== More Free Tools from Wbcom Designs ==

Member Reviews gives your community its reputation layer, the trust signals members earn from each other. These other free tools from Wbcom Designs give you the rest of the stack that reputation is built on, from the theme and the network itself to forums, media, events, gamification, directories, jobs, and courses.

* **[BuddyX](https://wbcomdesigns.com/downloads/buddyx-theme/)** - A free, fast community theme for BuddyPress, BuddyBoss and PeepSo with a modern layout and dark mode.
* **[BuddyNext](https://wbcomdesigns.com/downloads/buddynext/)** - Stand up a complete WordPress community with activity streams, member spaces, profiles, direct messaging, and built-in moderation.
* **[Jetonomy](https://wbcomdesigns.com/downloads/jetonomy/)** - Add forums, question-and-answer boards, and idea spaces that stay tidy through trust-based auto-moderation even past 100,000 topics.
* **[Mediaverse](https://wbcomdesigns.com/downloads/mediaverse/)** - Let members build photo and video albums, react, follow each other, and message privately while AI moderation keeps things clean.
* **[Eventonomy](https://wbcomdesigns.com/downloads/eventonomy/)** - Run community events with RSVPs, calendars, and front-end submissions.
* **[WB Gamification](https://wbcomdesigns.com/downloads/wordpress-gamification-plugin/)** - Reward members with points, badges, and leaderboards to keep engagement high.
* **[Listora](https://wbcomdesigns.com/downloads/listora/)** - Publish searchable directories across ten listing types with reviews, maps, and member-submitted entries from the front end.
* **[WP Career Board](https://wbcomdesigns.com/downloads/wp-career-board/)** - Add a job board with front-end listings, applications, and employer profiles.
* **[Learnomy](https://wbcomdesigns.com/downloads/learnomy/)** - Build and sell online courses, auto-grade quizzes, collect payments, and award certificates when learners finish.

== Installation ==

= Automatic Installation (Recommended) =

1. Log in to your WordPress admin dashboard
2. Navigate to Plugins > Add New
3. Search for "BuddyPress Member Reviews"
4. Click "Install Now" on the Wbcom Designs - BuddyPress Member Reviews plugin
5. After installation, click "Activate"
6. Go to **WB Plugins > Member Reviews** to configure the plugin

= Manual Installation =

1. Download the plugin ZIP file
2. Log in to your WordPress admin dashboard
3. Navigate to Plugins > Add New > Upload Plugin
4. Choose the downloaded ZIP file and click "Install Now"
5. After installation, click "Activate Plugin"
6. Configure settings at **WB Plugins > Member Reviews**

= Post-Installation Setup =

After activating the plugin, we recommend:

1. Visit **WB Plugins > Member Reviews > General**. Reviews are auto-approved out of the box: if you want to read every review before it goes live, turn **Auto Approve Reviews** off now.
2. On the same tab, set who may leave reviews and who may receive them, and switch email and BuddyPress notifications on or off. Directory ratings and the directory "Add Review" button are on by default here too.
3. Open the **Criteria** tab. Two criteria are seeded on activation, "Member Response" and "Member Skills". Rename them or add your own to fit your community.
4. Open the **Display** tab to set the review labels and star color. The plural label also drives the profile tab and its URL slug, so pick it before members start sharing links.
5. Visit any member profile and use the Reviews tab to post a test review, then check it appears on the members directory.
6. See the [documentation](https://docs.wbcomdesigns.com/) for detailed instructions.

= Requirements =

* WordPress 6.1 or higher
* PHP 7.4 or higher
* BuddyPress 6.0+ or BuddyBoss Platform 1.8+, with the Members component active

== Frequently Asked Questions ==

= Is BuddyPress required? =

Yes. The plugin needs BuddyPress 6.0 or higher, or BuddyBoss Platform 1.8 or higher, with the Members component active. Reviews live on member profiles, so there is no standalone mode.

= How do members submit reviews? =

Members open another member's profile and click the Reviews tab, then use the "Add Review" button. If directory ratings are enabled, they can also start a review straight from the members directory.

Note that "Reviews" is just the default label. If you rename the plural label under Display settings, the tab and its URL slug change to match.

= Can members review themselves? =

No. The plugin prevents self-reviews to ensure honest feedback.

= How do I add custom rating criteria? =

Go to **WB Plugins > Member Reviews > Criteria**. Two criteria ship as defaults, "Member Response" and "Member Skills". Add, rename, or remove them freely, for example "Communication" or "Reliability".

= Can I require approval before reviews appear? =

Yes, but note the default is the other way round. **Auto Approve Reviews** is ON by default, so out of the box reviews publish immediately with no moderation. To review every submission first, go to **WB Plugins > Member Reviews > General** and turn **Auto Approve Reviews** off. New reviews then stay as drafts until you approve them from the Reviews screen.

= Can members edit a review after posting it? =

Yes, when the "Allow update" setting is enabled. Members can only edit their own reviews.

= Can I control who reviews whom? =

Yes. General settings has two role selectors: one for the roles allowed to leave reviews, and one for the roles whose members can be reviewed.

= Do reviews post to the activity stream? =

They can. Enable the review activity setting in General settings and each new review publishes to the BuddyPress activity stream. It is off by default.

= How do notifications work? =

Members get a BuddyPress notification when they receive a review, and an email if email notifications are enabled. Admins get a pending-approval alert when moderation is on. Notifications and emails are separate switches, so you can run one without the other.

= What happens when a review is reported? =

Reported reviews are flagged in the admin. You can filter the Reviews list to flagged items only, open a review to read its Report Details meta box, then dismiss or resolve the report, individually or in bulk. Reviews auto-hide once they pass the report threshold you set.

= Does it work with BuddyBoss? =

Yes. BuddyBoss Platform 1.8 and higher is supported, and the plugin is tested with the BuddyBoss Theme.

= How do I display top-rated members? =

Two ways. Add the **BP Member Review Widget** to a widget area for a top-rated or most-reviewed list, or use the `[bupr_display_top_members]` shortcode on any page or post. There is also a **BP Displayed Member Rating Widget** that shows the rating of the member whose profile is being viewed.

On a block theme, add either widget through the Legacy Widget block.

= What attributes does the shortcode accept? =

`[bupr_display_top_members]` accepts four attributes:

* `title` - heading shown above the list. Defaults to empty.
* `total_member` - how many members to list. Defaults to 5.
* `type` - `top rated` (by aggregate rating) or `top reviewed` (by review count). Defaults to `top rated`.
* `avatar` - `show` or `hide` member avatars. Defaults to `show`.

For example: `[bupr_display_top_members title="Most Reviewed" type="top reviewed" total_member="10" avatar="hide"]`

= Can members reply to reviews about them? =

Yes. Profile owners can reply to each review once. Enable this in General settings.

= Is the plugin translation ready? =

Yes. German and French translations ship with the plugin, and a POT file is included so you can translate into any language. The plugin is WPML compatible and includes RTL support.

= Where can I get support? =

We offer comprehensive support through multiple channels:
* [Documentation](https://docs.wbcomdesigns.com/) - Free guides for every Wbcom plugin
* [Support](https://wbcomdesigns.com/support/) - Get help from our team
* [Contact us](https://wbcomdesigns.com/contact/) - Report bugs and request features

= Will this work with my theme? =

The plugin has been tested with popular BuddyPress and BuddyBoss themes including BuddyX, Reign, BuddyBoss Theme, and Youzify. It follows BuddyPress template standards, so it should work with any properly coded BuddyPress-compatible theme.

= How often is the plugin updated? =

We regularly update the plugin to ensure compatibility with the latest versions of WordPress, BuddyPress, and BuddyBoss. Security patches are released promptly when needed.

== Screenshots ==

1. Member Reviews tab with per-criteria star ratings and the aggregate profile score
2. Reviews tab in BuddyX dark mode
3. Add Review form with the keyboard-operable star rating widget and criteria
4. Reviews tab on mobile - rows, criteria, and buttons stack cleanly
5. Admin General Settings - moderation, notifications, roles, and reporting
6. Admin Discover tab - more free tools from Wbcom Designs

== Changelog ==

= 3.8.0 - July 2026 =

Admin redesign plus a full front-end UX, accessibility, and dark-mode pass.

* New      - Added Spanish, Italian and Portuguese (Brazil) translations, and completed the existing German and French ones.
* New      - Rebuilt the admin settings screen with the Wbcom card-panel UI: sidebar navigation, an overview dashboard, and a Discover tab. Same settings, clearer layout.
* Improve  - Front-end review styles now run on one design-token system in both light and dark, so themes and site owners can recolor any surface from a single variable.
* Improve  - Consolidated the review stylesheets into one file and removed the dead legacy modal system, duplicate rules, and unused selectors.
* Improve  - Normalized responsive breakpoints and added disabled, active, and keyboard focus states to interactive controls.
* Improve  - Star rating input is now a keyboard-operable ARIA radiogroup (arrow keys, Enter/Space) with accessible labels and higher-contrast colors.
* Improve  - Dark mode is keyed only off the theme's explicit dark signal (BuddyX/Reign), never the OS setting, and the modal close button now follows the theme color instead of a hardcoded black icon.
* Improve  - Converted directional CSS to logical properties for correct right-to-left rendering.
* Improve  - Replaced native browser alerts on the review front end with accessible toast notifications.
* Fix      - JavaScript translations could never load in production: the translation file lookup used the minified script path, which never matches the file the build produces.
* Fix      - The review count and average-rating tooltip were assembled from separate fragments, which cannot be translated correctly in every language. Each is now a single translatable phrase, and a stray bracket in the zero-review case is gone.
* Fix      - Several admin and front-end strings had no translatable source, including the criteria row builder and the reply and report messages.
* Fix      - Localized the admin review-approval progress label ("Approving...") so it translates on non-English sites.
* Fix      - The review-recalculation cron is scheduled once on a single daily interval (removed a conflicting double registration).
* Security - The review update endpoint now enforces an ownership/capability check, so members can only edit their own reviews.

= 3.7.1 =
* Fixed: WordPress Coding Standards compliance across all PHP files
* Fixed: Removed deprecated load_plugin_textdomain call
* Fixed: Plugin Check compatibility improvements

= 3.7.0 =
* New: Owner Replies - profile owners can respond to reviews once
* New: Report System - users can report inappropriate reviews
* New: Auto-hide reviews after configurable report threshold
* New: Admin settings for owner replies, reporting, and auto-hide
* Improved: Changed "Flag" to "Report" terminology
* Improved: Admin UI with clearer labels
* Improved: Security hardening
* Fixed: PHP Warning for undefined array key
* Fixed: Template consistency across all modes

= 3.6.0 =
* New: UI improvements for member reviews on directory page
* New: Filter hook for review tab extensibility
* Improved: Script optimization
* Improved: BuddyBoss Theme compatibility
* Fixed: Rating stars visibility with BuddyBoss
* Fixed: Star rating reset on review edit
* Fixed: Email subject and message editing
* Fixed: Text domain loading for translations

= 3.5.0 =
* Added: German (DE) and French (FR) translations
* Updated: Backend labels and strings
* Fixed: Fatal error with BuddyPress Classic
* Fixed: PHPCS compliance issues
* Removed: Unused files for lighter footprint

= 3.4.0 =
* Added: Pending approval notifications for admins
* Added: Meta box for member ratings on edit page
* Added: Conditional script/style loading
* Fixed: String translation issues
* Fixed: BuddyBoss and Youzify compatibility
* Fixed: Notification link and mark-as-read
* Updated: Backend settings UI
* Improved: Performance with minified assets

= 3.3.1 =
* Fixed: Multi-user role issues
* Added: Youzify support
* Added: Member star rating in activity
* Fixed: Review submission with disabled components
* Fixed: Average ratings with single criteria

= 3.3.0 =
* Fixed: Anonymous review avatar and URL
* Fixed: Shortcode form in BuddyBoss
* Fixed: Pagination for reviews and notifications
* Improved: Mobile responsive UI
* Improved: Security enhancements
* Updated: BuddyPress, BuddyBoss, Youzify integration

== Upgrade Notice ==

= 3.8.0 =
Security update: the review update endpoint now enforces an ownership check, so members can only edit their own reviews. Also includes the rebuilt admin settings screen and a front-end accessibility, dark-mode and RTL pass. Recommended for all users.

= 3.7.0 =
New features: Owner Replies and Report System. Security improvements included. Recommended update for all users.
