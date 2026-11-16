# Installation and Setup

BuddyPress Member Reviews lets the members of your community review and rate each other. Reviews appear on the reviewed member's profile, and each member gets an average star rating that shows on their profile and (optionally) in the members directory.

This page gets the plugin installed and working. For a quick path to a working review system, follow [Let members review each other](../how-to/let-members-review-each-other.md).

## What you need first

| Requirement | Minimum |
|---|---|
| WordPress | 6.5 or higher |
| PHP | 8.1 or higher |
| Community plugin | BuddyPress 12.0 or higher, or BuddyBoss Platform 1.8 or higher |
| BuddyPress component | The **Members** component must be active |

Reviews live on member profiles, so the plugin cannot work without BuddyPress or BuddyBoss. If BuddyPress is missing, the plugin deactivates itself and shows a red notice. If the Members component is switched off, you get the same kind of notice and the plugin deactivates.

## Install the plugin

### From the WordPress admin

1. Go to **Plugins > Add New**.
2. Search for **BuddyPress Member Reviews**.
3. Click **Install Now**, then **Activate**.

### By uploading a ZIP file

1. Go to **Plugins > Add New > Upload Plugin**.
2. Choose the plugin ZIP file and click **Install Now**.
3. Click **Activate Plugin**.

## What happens when you activate

- You are taken straight to the settings screen at **WB Plugins > Member Reviews**. This redirect does not happen on multisite, on bulk activation, or when you activate with WP-CLI.
- Two rating criteria are created for you: "Member Response" and "Member Skills".
- Reviews are published immediately (no approval step) until you change that setting.
- A **Reviews** tab appears on every member profile.

You can come back to the settings at any time from **WB Plugins > Member Reviews**, or from the **Settings** link under the plugin name on the **Plugins** page.

## Find your way around the admin

There are two places in the WordPress menu:

- **WB Plugins > Member Reviews** is where you change settings. See [Admin Screen Tour](admin-screen-tour.md).
- **Reviews** (its own top-level menu, with a speech-bubble icon) is the list of every review. You approve, hide and delete reviews there. See [Managing Reviews in the Admin](../usage/managing-reviews.md).

## Set it up

1. Open **WB Plugins > Member Reviews**.
2. Open the **General** tab. Decide whether reviews go live straight away or wait for your approval, and who can review whom. Click **Save Changes**.
3. Open the **Criteria** tab. Rename or add the things members rate (for example "Communication"). Click **Save Changes**.
4. Open the **Display** tab. Choose the words and the star color. Click **Save Changes**.
5. Test it with two accounts. See [Let members review each other](../how-to/let-members-review-each-other.md).

Each tab has its own **Save Changes** button and saves on its own. Save a tab before you move to another one.

## Next steps

- [Admin Screen Tour](admin-screen-tour.md)
- [Features Overview](../features/overview.md)
- [How-to guides](../how-to/let-members-review-each-other.md)
- [Settings Reference](../usage/settings-reference.md)
