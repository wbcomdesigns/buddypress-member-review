# Troubleshooting

Fixes for the issues that come up most often. Settings referenced here live at **WB Plugins > Member Reviews**.

## I cannot find the settings screen

The settings are not under Settings. They live at **WB Plugins > Member Reviews**, on the shared WB Plugins menu. The Reviews list (the actual review posts) is a separate top-level **Reviews** menu.

## The Reviews tab does not appear on profiles

Check these:

- BuddyPress 6.0+ or BuddyBoss 1.8+ is active with the Members component enabled.
- The profile you are viewing belongs to a role listed in **Reviewee Roles** on the General tab.
- The `bupr_show_profile_review_tab` filter is not returning false from custom code.
- Flush permalinks by visiting **Settings > Permalinks** and saving, since the tab slug is derived from the plural review label.

## A new review is not showing on the profile

- If **Auto Approve Reviews** is off, the review is a draft until an admin approves it at **Reviews > All Reviews**.
- The review may have passed the report threshold and been auto-hidden; check the Reported view.
- Clear any page or object cache.

## The review count or rating looks wrong

The cached count and aggregate recalculate when a review is approved, edited, trashed, or changes status, and a daily background job reconciles them. If numbers still look off, confirm no reviews are stuck in Draft or Trash, and confirm WP-Cron is running on the site (a very low-traffic site may need a real cron trigger).

## Members cannot edit their reviews

Editing requires the **Update Review** setting to be on, and members can only edit their own reviews. The edit form is AJAX-driven, so it needs JavaScript. Check the browser console for script errors and for theme or plugin conflicts.

## The report or reply button does nothing

The report, reply, and edit flows all run through AJAX and require JavaScript. If they are inert:

- Look for JavaScript errors in the browser console, usually from another plugin or the theme.
- Confirm **Review Reporting** and **Owner Replies** are enabled on the General tab.
- Only logged-in members can report, and only the profile owner can reply.

## Email notifications are not arriving

- Confirm **Email Notifications** is on (General tab).
- Test general WordPress email with an SMTP plugin; most email issues are host-level, not plugin-level.
- Check the recipient's spam folder.
- Enable `WP_DEBUG_LOG` and review `wp-content/debug.log`.

BuddyPress notifications and email are independent switches, so one can be on while the other is off.

## Directory ratings are missing

Enable **Member Directory Ratings** on the General tab. Ratings only render for members who have at least one published review, and the directory must be a BuddyPress or BuddyBoss members directory.

## Stars look wrong or unstyled

- The plugin bundles Font Awesome for its icons; a theme or plugin that also loads a different Font Awesome version can conflict.
- Set the star color on the **Display** tab.
- Dark mode follows the theme's explicit dark signal (BuddyX or Reign), not the operating system setting.

## Translations are not applying

The text domain is `bp-member-reviews` and the POT file is `bp-member-reviews.pot` in the plugin's `languages` folder. Place `.mo` files there named `bp-member-reviews-{locale}.mo`, or use a translation plugin. Confirm the site locale matches the file name.

## Still stuck

Gather your WordPress, BuddyPress or BuddyBoss, and PHP versions, your active theme, and any `debug.log` entries, then contact [Wbcom Designs support](https://wbcomdesigns.com/support/).
