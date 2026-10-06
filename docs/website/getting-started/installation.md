# Installation and Setup

BuddyPress Member Reviews adds a reputation layer to your community. Registered members post reviews on other members' profiles, rate them against criteria you define, and the plugin rolls those scores into an aggregate rating shown on the profile and on the members directory.

## Requirements

- WordPress 6.5 or higher
- PHP 8.0 or higher
- BuddyPress 6.0 or higher, or BuddyBoss Platform 1.8 or higher, with the Members component active

Reviews live on member profiles, so there is no standalone mode. One of BuddyPress or BuddyBoss must be active.

## Install the plugin

### From the WordPress admin

1. Go to **Plugins > Add New**.
2. Search for "BuddyPress Member Reviews".
3. Click **Install Now** on the Wbcom Designs listing, then **Activate**.

### Manual upload

1. Download the plugin ZIP.
2. Go to **Plugins > Add New > Upload Plugin**.
3. Choose the ZIP file, click **Install Now**, then **Activate**.

After activation you are redirected to the settings screen. You can return any time at **WB Plugins > Member Reviews**.

## First-run configuration

All settings live under **WB Plugins > Member Reviews**, which opens a card-panel screen with sidebar tabs: Overview, General, Criteria, Display, and Discover.

### Step 1: General

On the **General** tab:

- Reviews are auto-approved out of the box. If you want to read every review before it goes live, turn **Auto Approve Reviews** off. New reviews then stay as drafts until an admin approves them.
- Choose which roles may leave reviews and which roles may receive them.
- Switch email notifications and BuddyPress notifications on or off. Both default to on.
- Directory ratings and the directory Add Review button are on by default here too.

### Step 2: Criteria

On the **Criteria** tab, two criteria are seeded on activation: "Member Response" and "Member Skills". Rename them, remove them, or add your own to fit your community. Enable **Allow Multiple Criteria** to rate several aspects, or leave it off for a single overall rating.

### Step 3: Display

On the **Display** tab, set the singular and plural review labels and the star color. The plural label also drives the profile tab and its URL slug, so pick it before members start sharing links.

### Step 4: Test it

Visit any member profile, open the Reviews tab, and post a test review. Confirm it appears on the profile and, if directory ratings are on, on the members directory.

## What next

- [Features Overview](../features/overview.md)
- [Submitting and Editing Reviews](../usage/submitting-reviews.md)
- [Settings Reference](../usage/settings-reference.md)
