# Settings Reference

Every setting lives at **WB Plugins > Member Reviews**. The screen has a sidebar with these tabs: Overview, General, Criteria, Display, and Discover. Overview is a dashboard, and Discover lists other free Wbcom tools. Settings are on the General, Criteria, and Display tabs.

## General tab

Field labels below match the plugin. Defaults are what a fresh install ships with.

| Setting | Default | What it does |
|---|---|---|
| Multiple Reviews | On | Allow a member to submit more than one review of the same member. |
| Show Review Button | On | Show the Add Review button on member profile headers. |
| Auto Approve Reviews | On | Publish reviews immediately. Turn off to hold each new review as a draft until an admin approves it. |
| Member Directory Ratings | On | Show member ratings on the members directory. |
| Email Notifications | On | Email the reviewed member when they receive a review. |
| Email Subject | "You Have Received a New Review on [site-name]" | Subject line for the review email. |
| Email Message | Preset text | Body of the review email. |
| Approval Email Subject | "A New Review Requires Your Approval on [site-name]" | Admin alert subject. Shown only when Auto Approve is off. |
| Approval Email Message | Preset text | Admin alert body. Shown only when Auto Approve is off. |
| BuddyPress Notifications | On | Send a BuddyPress notification when a member receives a review. |
| Update Review | On | Let members edit their own reviews after posting. |
| Reviews Per Page | 5 | How many reviews per page on a profile before pagination. |
| Reviewer Roles | All roles | User roles allowed to submit reviews. |
| Reviewee Roles | All roles | User roles whose members can be reviewed. |
| Anonymous Reviews | Off | Let members submit reviews anonymously. |
| Activity Stream | Off | Post each new review to the BuddyPress activity stream. |
| Owner Replies | On | Let profile owners reply once to each review they receive. |
| Review Reporting | On | Let members report inappropriate reviews. |
| Auto-Hide Threshold | 5 | Hide a review after this many reports. Set to 0 to disable. |

### Email placeholders

The review email supports `[user-name]`, `[reviewer-name]`, `[review-link]`, `[site-name]`, and `[site-admin]`. The approval email supports `[review-aproval-link]`, `[site-admin]`, and `[site-name]`.

## Criteria tab

- **Allow Multiple Criteria** (default off): when on, reviewers rate each named criterion separately; when off, they give a single overall rating.
- The criteria rows list your rating fields. Two are seeded on activation, "Member Response" and "Member Skills". Add, rename, remove, and reorder them. A criterion's stored key is an identifier that stays fixed even when you rename its label, so existing reviews are not broken by a rename.

See [Star Ratings and Criteria](../features/star-ratings-and-criteria.md).

## Display tab

- **Review label (singular)**: the singular word used across the plugin, for example "Review".
- **Review label (plural)**: the plural word, for example "Reviews". The plural label also drives the profile tab name and its URL slug, so set it before members share links.
- **Star color**: the filled-star color. Default `#FFC400`.

## Saving

Each tab saves independently. Click Save on a tab before switching to another, or unsaved changes on that tab are lost.
