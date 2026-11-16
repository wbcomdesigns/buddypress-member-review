# Troubleshooting

Find the problem you see, then read the likely cause and the fix. Settings are at **WB Plugins > Member Reviews**.

## The plugin turned itself off

- **Cause:** BuddyPress or BuddyBoss is not active, or the BuddyPress **Members** component is switched off. The plugin shows a red notice and deactivates.
- **Fix:** Activate BuddyPress (or BuddyBoss), turn on the Members component at **Settings > BuddyPress > Components**, then activate this plugin again. You need BuddyPress 12.0+ or BuddyBoss 1.8+.

## I cannot find the settings

- **Cause:** They are not under Settings.
- **Fix:** Go to **WB Plugins > Member Reviews**, or use the **Settings** link under the plugin name on the **Plugins** page. The list of reviews is a different menu named **Reviews**.

## The Reviews tab is missing on profiles

- **Cause 1:** BuddyPress is not active, or the Members component is off.
- **Cause 2:** Some custom code hides it with the `bupr_show_profile_review_tab` filter.
- **Fix:** Check both. The tab shows on every profile, for logged-in and logged-out visitors, and does not depend on roles.

## I cannot see "Add Review"

Work down this list.

| Cause | Fix |
|---|---|
| You are on your own profile. The tab is hidden there on purpose. | Open someone else's profile. |
| Your role is not in **Reviewer Roles**. | Add the role on the **General** tab. |
| The other member's role is not in **Reviewee Roles**. | Add the role on the **General** tab. |
| You only see **Add Review** in the tab, not the header. | Switch on **Show Review Button** on the **General** tab. |
| You already reviewed this member and **Multiple Reviews** is off. | The form shows "You already posted a review for this member." Switch **Multiple Reviews** on to allow more. |
| You are logged out. | You see "You must log in to add a review." Log in and you come back to the form. |

If a member has more than one role, any of their roles counts, for both the header button and the tab under **Reviews**.

## A review is refused when I press Submit

| Message | Meaning |
|---|---|
| "Please rate every criterion or write a review." / "Please either select a rating or write a review." | Rate every criterion, or write some text. |
| "You cannot review your own profile." | Self-reviews are blocked. |
| "You are not allowed to review this member." | The reviewer or the reviewed member has a role that the role settings do not allow. |
| "You already posted a review for this member." | **Multiple Reviews** is off and a review already exists. |
| "You must be logged in to submit a review." | The login expired. Log in again. |
| "Security verification failed." | The page is out of date, often from a page cache. Reload the page. If it keeps happening, stop your cache plugin from caching member profile pages. |

A refused review keeps what the member typed.

## A new review is not showing on the profile

| Cause | Fix |
|---|---|
| **Auto Approve Reviews** is off. | Open **Reviews**, find the draft and click **Approve**. |
| Reports hid it (it shows **Auto-hidden**). | Open it and click **Dismiss Reports** to bring it back. See [Handle a reported review](../how-to/handle-a-reported-review.md). |
| It is in the trash. | Restore it from **Trash**. |
| It was written about a different member. | A review only shows on the profile of the member it was written about. |
| More reviews than **Reviews Per Page**. | Use the page links under the list. |
| Cached page. | Clear the page and object cache. |

## A reported review has vanished

- **Cause:** It reached **Auto-Hide Threshold** (5 reports by default) and was hidden. It is not deleted.
- **Fix:** Go to **Reviews** and click **Auto-hidden** above the list. Open the review. Click **Dismiss Reports** to publish it again, or leave it hidden. To stop auto-hiding, set **Auto-Hide Threshold** to 0.

## The review count or rating looks wrong

| What you see | Reason |
|---|---|
| The rating ignores a review. | The review is pending, hidden or trashed, or it has no stars (text only). Only published reviews with stars add to the average. |
| An old criterion name still shows. | Reviews keep the name they were written with. This is expected. |
| The top members list is out of date. | The list is saved for about 15 minutes. Wait and reload. |
| The number is stuck after a change. | The count updates when a review is published, edited, hidden, trashed or deleted. A daily background job also double-checks it. If your site has little traffic, make sure WordPress scheduled tasks (WP-Cron) can run. |

## Emails are not arriving

1. Check **Email Notifications** is on (**General** tab).
2. Remember which email is which:
   - The reviewed member gets an email when a review is **published**.
   - You get an email only when a review **needs approval** (**Auto Approve Reviews** off). It goes to the **Administration Email Address** in **Settings > General**.
   - Reports and replies send no email.
3. Look in spam folders.
4. Test WordPress email on your site with an SMTP plugin. Most missing emails are a hosting problem, not a plugin problem.
5. Edit your message text and make sure the placeholders (such as `[review-link]`) are still in square brackets.

If emails work but notification bubbles do not, check **BuddyPress Notifications** and that the BuddyPress Notifications component is active.

## There is no Edit Review button, or editing fails

- **Update Review** must be on. When it is off, members see "Editing reviews is turned off on this site."
- The button shows only on the member's own reviews, and only on published reviews.
- Editing needs JavaScript. Look for errors in the browser console.

## The Report or Reply button is missing

- **Report** needs **Review Reporting** on, a logged-in member, and a review written by someone else. After reporting, it changes to "Reported".
- **Reply to this review** needs **Owner Replies** on, you must be the member who received the review, the review must be published, and you must not have replied yet.
- **Report** is on the **Reviews** tab list. **Reply to this review** is on the list and on the single review page.

## Buttons do nothing

Edit, Report, Reply and Approve work through AJAX. If they do nothing:

- Check the browser console for JavaScript errors, often from a theme or another plugin.
- Turn off minify or combine options in your cache plugin for this plugin's scripts.
- Reload the page, in case an old cached page holds an expired security code.

## Stars are the wrong color or look broken

- Set the color at **WB Plugins > Member Reviews > Display > Rating Color**, save, and clear caches.
- Another plugin or theme may load a different Font Awesome. Try switching it off to test.
- The plugin loads its styles only on member profiles, the members directory, activity pages, pages that contain the shortcode in their content, and pages with the **BP Member Review Widget**. If you place the shortcode somewhere else (for example in a theme file), the styles may not load. A developer can return `true` from the `bupr_should_load_assets` filter on those pages.

## Ratings do not show in the members directory

- **Member Directory Ratings** must be on.
- Members without any published review show nothing.
- The directory must be the BuddyPress or BuddyBoss members directory.

## The shortcode or widget shows nothing

- "No members have been reviewed yet." means no member has a published review yet. For the top rated order, members also need at least one star rating.
- Check the shortcode: `[bupr_display_top_members]`, with straight quote marks.
- The **BP Displayed Member Rating Widget** only shows on member profile pages.

## The Anonymous tick box is missing

Switch on **Anonymous Reviews** on the **General** tab and save. Clear page caches.

## Approve, Dismiss or Resolve is missing or refused

These need the Editor role or higher. Contributors and Authors cannot approve reviews or moderate reports. The **Approve** link shows on drafts only.

## The Pending Approval badge is missing

The number next to **Reviews** in the menu shows only while **Auto Approve Reviews** is off.

## The profile tab address gives "page not found"

The address comes from **Reviews Label (Plural)** on the **Display** tab. If you changed it, old links no longer work. Use the new address shown on the member's profile.

## I cannot save the Display tab

**Review Label** cannot contain numbers. Remove digits and the **Save Changes** button works again.

## Translations are not applying

German, French, Spanish, Italian and Portuguese (Brazil) files ship with the plugin. Check your site language at **Settings > General > Site Language** matches. For another language, use the `bp-member-reviews.pot` file in the plugin's `languages` folder with a translation plugin.

## Still stuck

Collect your WordPress, PHP, BuddyPress or BuddyBoss and plugin versions, your theme, and any lines from `wp-content/debug.log` (turn on `WP_DEBUG_LOG` first). Then contact [Wbcom Designs support](https://wbcomdesigns.com/support/).
