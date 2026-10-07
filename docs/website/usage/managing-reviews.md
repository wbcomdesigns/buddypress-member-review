# Managing Reviews in the Admin

Every review is saved in WordPress as an item of type "review". You manage them from the **Reviews** menu, which works like the list of posts, with a few extras for moderation.

Settings are separate: they live at **WB Plugins > Member Reviews**.

## Open the list

Go to **Reviews > All Reviews** in the admin menu.

### Columns

| Column | What it shows |
|---|---|
| **Title** | A sentence such as "Sam received a review". The title is made by the plugin. |
| **Reports** | A dash if no one reported it. Otherwise the number of reports, plus "Resolved", "Dismissed" or "Auto-hidden" when that applies. |
| **Author** | The member who wrote the review. This shows the real writer, even for anonymous reviews. |
| **Reviews Category** | The type of review. Profile reviews use "BP Member". |
| **Date** | When it was written. |

### Statuses

| Status | Meaning |
|---|---|
| **Published** | Visible on the profile and counted in the member's rating. |
| **Draft** | Not visible. Either waiting for your approval, or hidden because of reports (marked **Auto-hidden**). |
| **Trash** | Removed from the site. You can restore it or delete it for good. |

### Find the review you need

Above the list you see the status links (**All**, **Published**, **Draft**, **Trash**) and these extras:

| Link | Shows |
|---|---|
| **Reported** (with a count) | Reviews with open reports, whether or not they are hidden. Appears only when there are some. |
| **Auto-hidden** (with a count) | Reviews hidden because they reached the report threshold. Appears only when there are some. |

You can also search, and filter by month.

## What you can do to a review

Hover over a review's title to see its actions:

| Action | What it does |
|---|---|
| **View Review** | Opens the review on the member's profile. |
| **Approve** | Publishes a draft review. Shown on drafts only. Needs the Editor role or higher. |
| **Trash** | Moves it to the trash. |

Quick Edit and Edit links are removed on purpose. To open a review, click its title.

When a review is trashed or deleted, the member's rating and review count update, and its activity stream entry and notifications are removed.

## Open a single review

Click the title. You can:

- Edit the written text.
- See the stars given, in the **Member Ratings** box. This box is for reading. It does not change the stars.
- Read the reports in the **Report Details** box, then click **Dismiss Reports** or **Mark Resolved**. See [Handle a reported review](../how-to/handle-a-reported-review.md).
- Use the normal **Publish**, **Move to Trash** and status controls.

## Bulk actions

Tick one or more reviews and choose from **Bulk actions**:

- **Move to Trash**
- **Dismiss Reports**
- **Resolve Reports**

The two report actions change the report status only. They do not publish a hidden review.

## The counts at the top of the settings screen

**WB Plugins > Member Reviews > Overview** shows Published Reviews, Pending Approval, Members Reviewed and Reported. The numbers count reviews by status as described above.

## Tips

- You cannot add a review from the admin. Reviews are written by members from the front of your site.
- If you also use BuddyPress Group Reviews, its reviews share this list. Use the **Reviews Category** column to tell them apart.
- Who can do what: Administrators and Editors can approve, dismiss, resolve and delete. Contributors and Authors cannot approve or moderate reports.
