# Agent Smoke Runbook - BuddyPress Member Reviews

**Audience:** a browser-capable agent (Playwright MCP + WP-CLI) or a human QA person with the same access.

C rows are **customer contracts**: verify the promise from the UI AND the server side (DB / option / post meta), reading the plugin code to find selectors and handlers. D rows are repros of past incidents; follow them literally. Judge the **experience** as the site owner and the member would, not "page loaded".

## Global preconditions

- Plugin dir: `wp-content/plugins/buddypress-member-review` (WP root = three levels up)
- Site: `https://wbcom-free.local` (Local by Flywheel; BuddyPress Nouveau)
- Auto-login: `?autologin=1` (admin) or `?autologin=<login>`; log out first via `/wp-login.php?action=logout` + click the confirm link
- Version constant: `BUPR_PLUGIN_VERSION`
- Personas: see `docs/qa/qa-config.json` `personas`
- Admin screens: `wp-admin/admin.php?page=bp-member-review-settings&tab=bupr-overview|bupr-general|bupr-criteria|bupr-display|bupr-discover`, list `wp-admin/edit.php?post_type=review`
- Front: `/members/<login>/reviews/`, `/members/<login>/reviews/add-review/`, `/activity/`, members directory `/members/`
- Mail: Local Mailpit (`wp eval 'echo ini_get("sendmail_path");'` gives the SMTP port; the web/API port is next to it, `GET /api/v1/messages`)
- Screenshots: `~/Documents/work-artifacts/screenshots/YYYY-MM/` only, never the site root

## Output contract

Write exactly one file: `docs/qa/.last-smoke-pass.json` with the shape in the wp-plugin-smoke skill (`mode`, `release_version`, `ran_at`, `sections`, `failures`, `hard_fails`, `untested`, `coverage`, `debug_log_issues`, `manual_required`, `layer1_delta`). Every row not run goes in `untested` with a reason.

## Fixture cleanup (before and after the walk)

Test data uses the prefix `QA-SMOKE`. Track created IDs and delete **by ID only** (never by title search).

```bash
wp post list --post_type=review --post_status=any --s="QA-SMOKE" --fields=ID,post_title   # inspect first
wp post delete <ids> --force
```

Restore every option you change (record the original with `wp option get ... --format=json` first).

## Debug log protocol

Enable `WP_DEBUG`, `WP_DEBUG_LOG`, `WP_DEBUG_DISPLAY=false`; baseline `wp-content/debug.log` and `../../logs/php/error.log` byte counts; diff after every section. Any new fatal/warning/notice from this plugin is a failure.

---

## A - Activation

- **A1** Deactivate + reactivate the plugin: no fatal, no PHP notice, redirect lands on the settings screen once, `bupr_admin_general_options` keeps its values.
- **A2** With BuddyPress deactivated the plugin shows an admin notice and does not fatal. Re-activate BuddyPress after.

## C - Core contracts

### Admin (as administrator, 1440 / 1024 / 390)

- **C1 Overview**: stats tiles match DB counts (published, pending = drafts minus auto-hidden, members reviewed, reported). Every disabled feature row has a working "Turn on" link. Quick Actions links resolve.
- **C2 General save**: toggle every switch off and on, change subject/message/reviews-per-page/threshold, Save, reload. Every value persists in `bupr_admin_general_options`; unchecked switches store `no`. Blank threshold stores 5. Toggles render as switches. No row is nested inside another; controls share one right rail; no horizontal scroll at 390; no large empty gaps.
- **C3 Auto-approve conditional fields**: with Auto Approve OFF the Approval Email Subject/Message rows render; ON they hide, and saving with them hidden keeps the stored approval subject/message.
- **C4 Criteria**: Multiple Criteria OFF on fresh load hides the builder + Add button; ON shows them; Add appends a row identical to saved rows (handle, Remove button); Remove deletes it; drag reorders; Save persists order and names in `bupr_admin_settings`.
- **C5 Display**: change review labels and Rating Color; Save; front-end tab label and star colour follow (profile header, reviews tab, members directory, widget).
- **C6 Reviews list**: Reported and Auto-hidden views appear only when non-zero and filter correctly; Approve row action publishes a draft as admin; Report Details meta box Dismiss / Resolve work; Bulk actions work. As `qa_contrib` the Approve link is absent and the approve AJAX endpoint is refused.

### Front end (Reign first, then BuddyX + BuddyX Pro light/dark, Twenty Twenty-Five)

- **C7 Submit review** (member-other on member-owner's profile): Add Review button visible in the header, form validates (stars required per criterion, text), submits, success message; with auto-approve OFF it lands as draft and admin gets the approval email; ON it publishes, appears on the Reviews tab, aggregate rating updates in header and directory, member gets the email (Mailpit) and a BP notification.
- **C8 Rules**: cannot review self (no button on own profile; endpoint refuses); Multiple Reviews OFF refuses a second review; Reviewer Roles / Reviewee Roles restrictions hide the button AND the endpoint refuses; Anonymous ON shows "An anonymous user" everywhere (tab, activity, email) and OFF ignores the anonymous flag.
- **C9 Edit**: reviewer edits own review via Edit Review modal; change persists; other members get no Edit button and the endpoint refuses another member's review (PR-3).
- **C10 Owner reply**: member-owner replies once; reply shows; a second reply is refused; other members see no reply form; Owner Replies OFF removes the form and the endpoint refuses.
- **C11 Report + auto-hide** (threshold 2): two different members report; reporter sees confirmation; same member cannot report twice; at threshold the review leaves the profile tab, the activity stream (logged out + in) and `GET /wp-json/buddypress/v1/activity`; admin sees the Auto-hidden badge + view + Overview count; re-approving restores exactly one activity entry and clears the badge (PR-4).
- **C12 Activity**: Activity Stream ON posts one entry per published review; editing updates it (no duplicate); OFF posts none.
- **C13 Widgets + shortcode**: Member Review widget on a non-BP page (Reign right sidebar, /sample-page/) loads styles and uses Rating Color; `[bupr_display_top_members]` with `title`, `total_member`, `type`, `avatar` honours each attribute.
- **C14 Logged-out visitor**: can read the Reviews tab; sees no Add/Edit/Report/Reply controls; every AJAX endpoint refuses (PR-1).
- **C15 Pagination**: with more reviews than Reviews Per Page the tab paginates and the pager works.

### Presentation

- **C16** Every front surface (header button, tab, form, edit modal, report modal, reply, directory rating, widget) at 1440 / 1024 / 390 on Reign, BuddyX, BuddyX Pro (light + dark via `html[data-bx-mode="dark"]`) and Twenty Twenty-Five: buttons readable at rest and hover (compare computed `color` vs `backgroundColor`), modals fit the viewport, no overlap, no horizontal scroll.
- **C17** RTL: switch site language to Arabic (or `?rtl` via `wp option update WPLANG ar`), check admin settings and the reviews tab mirror correctly; restore.

## D - Regression guards (3.8.1)

- **D1** Contributor (`qa_contrib`) on `wp-admin/edit.php` reads `bupr_admin_ajax_object.nonce` and POSTs `action=bupr_approve_review` for a draft review: must return `permission_error`, review stays draft.
- **D2** Reviewer Roles = [administrator]: a subscriber POSTing `allow_bupr_member_review_update` with a valid `review-nonce` gets "You are not allowed to review this member."; no review row created.
- **D3** General tab with auto-approve OFF: `.wbcom-settings-section-wrap` count 19, nested 0.
- **D4** Save General with Show Review Button unchecked: option key `bupr_hide_review_button` = `no`.
- **D5** Report button hover under BuddyX dark: text colour differs from background colour.
- **D6** WP "Dark" admin colour scheme (Users > Profile): settings header text readable (panel stays light).

## Failure protocol

Record failures with `origin: from` (our plugin) or `for` (theme / BuddyPress / environment) and continue; never fix. For each, draft a Basecamp card: title, Where (URL + role + theme + viewport), Expected, Actual (computed values / DB rows), file:line if known. The calling session re-verifies before filing.
