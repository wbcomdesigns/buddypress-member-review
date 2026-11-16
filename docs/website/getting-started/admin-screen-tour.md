# Admin Screen Tour

Everything you set up for member reviews lives in two places in the WordPress admin. This page shows you what is on each screen so you know where to go.

## WB Plugins > Member Reviews (settings)

**WB Plugins** is a shared menu. Every Wbcom Designs plugin on your site adds its own page under it, so it may contain other plugins too. Click **Member Reviews** to open this plugin's screen.

The screen has a sidebar on the left with these items:

| Tab | What you do there |
|---|---|
| **Overview** | See how many reviews you have and which features are on. No settings to save. |
| **General** | Approval, emails, notifications, who can review whom, replies, reporting. This is the main settings tab. |
| **Criteria** | Choose what members rate (for example "Communication") or use one overall rating. |
| **Display** | Change the word "Review", the star color, and look up the shortcode. |
| **Add-ons** | Settings for an add-on such as Member Reviews Pro. This tab only appears when an add-on is installed and active. |
| **Discover** | A read-only list of other free Wbcom Designs plugins. No settings. |

The sidebar also has a **Documentation** link under **Resources**. It opens the online documentation in a new tab.

If you click **WB Plugins** itself (the parent menu), you see a hub page that lists every Wbcom plugin installed on your site, each with an **Open settings** link.

### The Overview tab

The top of the Overview tab shows four numbers:

| Number | Meaning |
|---|---|
| **Published Reviews** | Reviews that are live on profiles. |
| **Pending Approval** | Reviews waiting for you to approve them. Reviews hidden by reports are not counted here. |
| **Members Reviewed** | Members who have at least one published review. |
| **Reported** | Reviews that members have reported and that you have not yet dismissed or resolved. Under it you may see a link such as "2 hidden automatically". |

When at least one review is waiting on a report, a warning appears at the top with a **Review now** link that opens the reported reviews.

Below the numbers, **Current Configuration** shows, in plain words, what happens right now: whether reviews publish automatically, and whether email notifications, BuddyPress notifications, anonymous reviews, owner replies and review reporting are on. Next to every feature that is off there is a **Turn on** link that takes you to the General tab.

**Quick Actions** has three buttons: **Manage Reviews**, **General Settings** and **Rating Criteria**.

### The General, Criteria and Display tabs

Each of these tabs is one form with a **Save Changes** button at the bottom. Flipping a switch (an on and off toggle) changes nothing until you click **Save Changes**. Read each setting in the [Settings Reference](../usage/settings-reference.md).

## Reviews (the list of reviews)

**Reviews** is its own menu in the left-hand WordPress menu. It is the list of every review on your site, written by any member.

- **All Reviews** is the list. Columns: Title, Reports, Author, Reviews Category, Date.
- You can open a review, read it and its reports, approve it, or move it to the trash.
- The **Add New Review** button is switched off on purpose. Reviews can only be written by members from the front of your site.
- When reviews wait for approval, a small number badge appears next to the **Reviews** menu item.

See [Managing Reviews in the Admin](../usage/managing-reviews.md).

## Who can see these screens

| Screen | Who can open it |
|---|---|
| **WB Plugins > Member Reviews** | Administrators (anyone who can manage site options). |
| **Reviews** list | Administrators and Editors. |
| Approve a review, dismiss or resolve reports | Administrators and Editors only. Contributors and Authors cannot. |
