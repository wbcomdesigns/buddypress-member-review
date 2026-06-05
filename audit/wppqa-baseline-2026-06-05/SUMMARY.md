# wppqa Baseline — buddypress-member-review @ 3.7.1

Date: 2026-06-05 | Branch: 3.7.1 | Tool: wp-plugin-qa MCP

## Per-check results

| Check | Passed | Failed | Skipped | Notes |
|---|---|---|---|---|
| wppqa_check_plugin_dev_rules | 2 | 7 | 0 | 6 high errors + 1 medium warning |
| wppqa_check_rest_js_contract | 0 | 0 | 1 | Skipped — no REST routes in plugin |
| wppqa_check_wiring_completeness | 0 | 0 | 1 | Skipped — no includes/admin/ + templates/ shape it inspects |

Release-readiness gate: NOT release-ready while high errors stand (failed > 0).

## Findings (triaged)

### REAL — action required

1. **alert() x3** — `assets/js/bupr-front.js:442,466,472` (owner-reply flow).
   admin-ux Rule 10 bans native alert(). Replace with toast/inline message.
   Severity: high (UX rule), low (functional).

2. **Nonce without capability — `includes/bupr-ajax.php:666` (bupr_update_review)**.
   THIS IS A REAL AUTH GAP. The handler verifies the `review-nonce` then calls
   `wp_update_post()` with no ownership or `edit_post`/`edit_others_posts` check.
   Any logged-in user holding the front nonce can update any review by ID.
   (Contrast: the sibling `bupr_edit_review` at :538 DOES check ownership/cap.)
   Severity: high. Tracked in manifest static_analysis.missing_handlers.

### FALSE POSITIVE (per nopriv-public-endpoint quirk)

3. **Nonce without capability — `includes/bupr-ajax.php:156` (wp_allow_bupr_my_member)**.
   This is the PUBLIC review-submission endpoint, registered for both
   `wp_ajax_` and `wp_ajax_nopriv_`. Nonce-only is correct by design; self-review
   is blocked by user-ID comparison. wppqa does not recognize nopriv endpoints
   and false-positives 'nonce-no-cap'. NOT a bug.

### LOW / BY-DESIGN

4. **Nonce without capability — `includes/bupr-ajax.php:906` (bupr_flag_review)**.
   Intentional: gated by `is_user_logged_in()` + per-user dedupe + the
   `enable_flagging` setting. Any member may report once. Acceptable; no per-action
   cap needed for a community-report feature.

5. **Nonce without capability — `admin/wbcom/wbcom-admin-settings.php:44` (wbcom_addons_cards)**.
   Shared-admin helper; only echoes back a sanitized display string, no state
   change. Low risk. Lives in the OLD Wbcom wrapper (shared across plugins).

6. **5 distinct breakpoints (1068/420/480/767/768px)** — frontend-responsive Rule 1
   (max 3). Component-local fixes; consolidate to 640/1024/1440. Medium, cosmetic.

## Manifest reconciliation
Manifest static_analysis records findings #1, #2, #5 and the nopriv false positive
(#3) so downstream audit prose does not contradict this baseline.
