# BP Member Reviews 3.8.1 - QA handoff

Date: 2026-10-06 · Board: [BuddyPress Member Reviews](https://app.basecamp.com/5798509/buckets/37595471/card_tables/7416147525)

All 17 cards in **Ready for Testing** are fixed on branch `3.8.1`. Verify each against the steps below, then run section 3 (security fixes found in the browser audit that have no card yet). Every card also has a comment with the root cause and the commit.

## 1. Setup

| Item | Value |
|---|---|
| Branch | `3.8.1` on `wbcomdesigns/buddypress-member-review` (version 3.8.1) |
| Build | `npm install && npx grunt dist` → `dist/buddypress-member-review-3.8.1.zip`, or check out the branch directly |
| Requires | WordPress 7.x, BuddyPress 14.x (Members + Activity components), PHP 8.1+ |
| Themes to cover | Reign, BuddyX, BuddyX Pro (each light + dark), Twenty Twenty-Five (unknown-theme check) |
| Viewports | 1440, 1024, 390 |
| Mail | Read real emails (Mailpit on Local), not the email log |
| Debug | `WP_DEBUG` + `WP_DEBUG_LOG` on; `wp-content/debug.log` must stay free of `buddypress-member-review` lines |

Personas you need (create them if your site lacks them):

| Persona | Role | Used for |
|---|---|---|
| Admin | Administrator | Settings, approving, moderating reports |
| Member A | Subscriber | The member being reviewed (profile owner, replies) |
| Member B | Subscriber | Reviewer and reporter |
| Member C | Subscriber | Second reporter |
| Contributor | Contributor | Must NOT be able to moderate |
| Author | Author | Must NOT be able to moderate their own review's reports |

Dark mode: use the theme's own dark toggle (BuddyX/Reign set `html[data-bx-mode="dark"]`). Do not test with the OS dark setting; the plugin deliberately ignores it.

## 2. The 17 Ready for Testing cards

| Card | What to do | Expected |
|---|---|---|
| [10375057560](https://app.basecamp.com/5798509/buckets/37595471/card_tables/cards/10375057560) Hidden review stays public in activity + REST | Activity Stream ON, threshold 2. Member B and C report a published review. Check /activity/ logged out and in, and `/wp-json/buddypress/v1/activity`. Also admin Unpublish and Trash a review. | The review's activity entry is gone in every case. |
| [10375058313](https://app.basecamp.com/5798509/buckets/37595471/card_tables/cards/10375058313) Re-approve posts duplicate activity | Approve a pending review, edit it as the reviewer, auto-hide it, re-approve it. | Exactly one activity entry for that review at every step while published. |
| [10375059257](https://app.basecamp.com/5798509/buckets/37595471/card_tables/cards/10375059257) Blank threshold disables auto-hide | General tab: clear Auto-Hide Threshold, Save, reload. Also try `-3`. | Field shows 5. `0` still saves as 0 (auto-hide off). |
| [10359736539](https://app.basecamp.com/5798509/buckets/37595471/card_tables/cards/10359736539) General fields misaligned | Auto Approve OFF, open General at 1440 / 1024 / 390. | Every row is a sibling, controls on one right rail, no large gaps, no horizontal scroll. |
| [10359731262](https://app.basecamp.com/5798509/buckets/37595471/card_tables/cards/10359731262) Blank space left of Email editor | Same screen. | Both email editors use the full row width on desktop. |
| [10359759580](https://app.basecamp.com/5798509/buckets/37595471/card_tables/cards/10359759580) Checkboxes → toggles | General + Criteria tabs. Flip each switch, Save, reload. | Real toggle switches; state persists; Add Criteria / Remove buttons match the screen's button style. |
| [10359894913](https://app.basecamp.com/5798509/buckets/37595471/card_tables/cards/10359894913) Criteria builder visible when disabled | Criteria tab with Multiple Criteria OFF, fresh load; then toggle ON and OFF. | Builder and Add button hidden when OFF, shown when ON, without saving. |
| [10359722182](https://app.basecamp.com/5798509/buckets/37595471/card_tables/cards/10359722182) Overview missing Turn on links | Turn off Anonymous, Owner Replies, Reporting, Email, BP notifications; open Overview. | Every Off row has a Turn on link to the General tab. |
| [10359898223](https://app.basecamp.com/5798509/buckets/37595471/card_tables/cards/10359898223) Delete vs Remove labels | Criteria tab: compare saved rows with a row added by Add Criteria. | Both say Remove; new rows have the drag handle too. |
| [10364835434](https://app.basecamp.com/5798509/buckets/37595471/card_tables/cards/10364835434) Rating Color missing in widget | Set Rating Color to red, put the Member Review widget in a sidebar, open a normal page (not BuddyPress). | Widget stars are red. |
| [10360404600](https://app.basecamp.com/5798509/buckets/37595471/card_tables/cards/10360404600) Dark mode ignores Reign palette | Reign dark mode, open a member's Reviews tab. | Review cards use Reign's dark blue-grey (`#1a2028`), not neutral grey. BuddyX Pro already matched; no change expected there. |
| [10375051109](https://app.basecamp.com/5798509/buckets/37595471/card_tables/cards/10375051109) Report / Edit text hidden on hover | BuddyX and BuddyX Pro, light and dark: hover Report and Edit Review, open Report and hover the ×. | Text and × stay readable on hover in all four combinations. |
| [10114978928](https://app.basecamp.com/5798509/buckets/37595471/card_tables/cards/10114978928) Admin dark mode unreadable header | Users > Profile, pick the WordPress "Midnight"/dark admin colour scheme, open the settings. | Panel stays light; header text readable. |
| [10375111353](https://app.basecamp.com/5798509/buckets/37595471/card_tables/cards/10375111353) Show Review Button dropped on save | Uncheck Show Review Button, Save; check it again, Save. | Off hides Add Review in profile headers; On shows it again. (Unchecked now saves as `no`.) |
| [10375231738](https://app.basecamp.com/5798509/buckets/37595471/card_tables/cards/10375231738) Add Review on own profile | Visit your own profile as a member. | No Add Review button. Hardening only; no visible change on real profiles. |
| [10375110414](https://app.basecamp.com/5798509/buckets/37595471/card_tables/cards/10375110414) Auto-hidden vs pending in admin | Auto-hide a review (threshold 2, two reports). Open Reviews list and Overview. Then approve it. | "Auto-hidden (1)" view, red Auto-hidden badge with the hide date on hover, Overview "1 hidden automatically" and Pending excludes it. After approval all three clear. |
| [10375123786](https://app.basecamp.com/5798509/buckets/37595471/card_tables/cards/10375123786) Docs: phantom shortcode params | Read `docs/website/shortcodes/shortcodes.md` and `developer-guide/templates.md`. | Only `title`, `total_member`, `type`, `avatar`; override path `your-theme/buddypress-member-review/`. A template copied there is used. |

## 3. Audit fixes with no card yet (run as the wrong role)

These were found by the full browser audit and are fixed on the same branch. Each check posts to the endpoint directly, because hiding a button is not a gate. Use the browser console on a page the role can open; the front nonce is in the page's localized object (`review_nonce`), the admin one in `bupr_admin_ajax_object.nonce`.

| # | Check | Expected |
|---|---|---|
| A1 | Contributor opens wp-admin Posts, POSTs `bupr_approve_review` for a pending review. | `permission_error`; review stays pending. The Approve link is not shown to them. |
| A2 | Reviewer Roles = Administrator only. Member B POSTs `allow_bupr_member_review_update` for Member A. | "You are not allowed to review this member."; no review created. |
| A3 | Member B edits their own pending review, and their own auto-hidden review (`bupr_update_review`). | Text changes, status stays pending/hidden, no activity entry appears. |
| A4 | Logged out, open `/members/<A>/reviews/view/<pending review id>/`. | "Review not found". A published review still opens; a published review under another member's URL does not. |
| A5 | Post an anonymous review; open its single view; inspect the avatar. | No reviewer username, no `user-<id>-avatar` class; alt reads "Profile Photo". |
| A6 | Update Review OFF. Member B calls `bupr_update_review` / `bupr_edit_review`. | `update_disabled`. Admin can still edit. |
| A7 | Author-role user who wrote a reported review opens the Dismiss / Resolve link from the Report Details box. | Refused. Admin can dismiss. |
| A8 | Threshold 2: two reports auto-hide a review; admin clicks Dismiss in Report Details; then one new report. | Dismiss republishes the review (Auto-hidden badge gone, one activity entry back); the new report does not hide it again. |
| A9 | Top members widget / `[bupr_display_top_members]`. | Display names, never usernames; no 0-review members; "1 review" singular. |

Behaviour fixes from the same audit, checked through the normal UI:

| # | Check | Expected |
|---|---|---|
| B1 | Submit a review with no text and no stars; with some stars and no text. | Refused with "Please rate every criterion or write a review."; what you typed stays in the form. |
| B2 | Submit with some stars and text. | Saved; each rating shows under its own criterion. |
| B3 | Submit successfully. | Confirmation stays on screen (it used to vanish in about 2 seconds). |
| B4 | Report a review, reload. | "Reported" shows instead of the Report button. |
| B5 | Logged out, open the Add Review tab, click "log in". | After login you land back on the Add Review form. |
| B6 | Toggle Auto Approve without saving. | Approval Email rows hide and show immediately. |
| B7 | Write two paragraphs in Email Message (Code tab), Save, trigger a review. | Paragraphs kept in the setting and in the email. |
| B8 | Anonymous Reviews OFF, submit with the anonymous flag forced on. | Email shows the reviewer's real name. |
| B9 | Owner reply on a pending review (POST `bupr_add_owner_reply`). | Refused; replies work on published reviews. |
| B10 | Force-delete a published review (Trash, then Delete Permanently). | Member's rating and count update; its activity entry is gone. |
| B11 | Twenty Twenty-Five: Add Review form, edit and report dialogs at 1440 / 1024 / 390. | No horizontal scroll; dialogs fit the screen. |
| B12 | BuddyX / BuddyX Pro report dialog. | Submit red, Cancel neutral (not a second red button). |
| B13 | Load the shortcode page, a profile Reviews tab, and the admin bar with WP_DEBUG on. | No BuddyPress deprecation notices from this plugin in debug.log. |

## 4. Out of scope - please do not bounce

- **Reviews menu shows the pending count twice.** BP Group Reviews uses the same `review` post type and adds its own badge. Fix belongs in that plugin.
- **Reign puts Add Review in the profile "…" menu.** That is Reign's header layout for extra actions, not ours.
- **Reviewer is not notified of an owner reply.** Feature request; moving to Suggestions.
- **Empty stars use Rating Color.** Outline (empty) and solid (filled) stars share the colour the owner picked; the shape tells them apart. By design.
- **Twenty Twenty-Five at 390px** scrolls 7px sideways because of BuddyPress's own profile header avatar, not this plugin.
- **RTL rating text order** ("7 ) 3.67/5 ( reviews") on a site with no Arabic translation. Deferred.
- **Duplicate element IDs** (edit button, criteria rows). Deferred; renaming risks breaking Pro and theme selectors.
- **Pro card 9562240497** (Pro should require the free plugin) is a Pro change, assigned to Varun.

## 5. Reporting back

- Pass: comment on the card with theme, viewport, role and what you saw, then move it to Done.
- Fail: comment with the exact steps, role, theme, viewport and screenshot, then move it back to Bugs.
- Section 3 failures: open a new card in Bugs titled with the check id (for example "A4: pending review readable by visitors").
- Repeatable regression rows live in `docs/qa/AGENT_SMOKE_RUNBOOK.md` (D1-D13).
