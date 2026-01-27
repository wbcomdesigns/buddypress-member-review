# FAQ Content

Frequently asked questions for website and documentation.

---

## General Questions

### What is BuddyPress Member Reviews?

A WordPress plugin that adds member-to-member reviews to BuddyPress communities. Members can rate and review each other's profiles with star ratings, written feedback, and trust signals.

---

### Does it work with BuddyBoss?

Yes. Fully compatible with BuddyBoss Platform and BuddyBoss Theme. Also works with Youzify.

---

### Can members review themselves?

No. The review form doesn't appear on your own profile. This prevents fake self-reviews.

---

### Do I need BuddyPress installed?

Yes. This plugin extends BuddyPress (or BuddyBoss Platform). Without BuddyPress, member profiles don't exist — so there's nothing to review.

---

### What happens when I deactivate the plugin?

Reviews are stored as a custom post type. They remain in the database but stop displaying. Reactivate and everything returns. Uninstalling via WordPress deletes all data permanently.

---

## Free Version Questions

### Is the free version really free?

Yes. Download from WordPress.org, use on unlimited sites, no purchase required. The free version is fully functional — star ratings, moderation, notifications, shortcodes — everything listed in the free features section.

---

### What's the difference between Free and Pro?

Free: Star ratings, multiple criteria, owner replies, reporting, moderation, notifications, shortcodes.

Pro adds: Testimonials Mode (text-only reviews), Endorsements Mode (skill vouching), Verified Badges, Flexible Criteria Builder (thumbs, text types), REST API, and priority support.

Most communities don't need Pro. It's for specific use cases like courses (testimonials), professional networks (endorsements), or mobile app integrations (API).

---

### Will free users get updates?

Yes. Updates come through WordPress.org like any other plugin.

---

## Pro Version Questions

### Do I need the free plugin to use Pro?

Yes. Pro is an addon that requires the free base plugin (v3.7.0+). Install free first, then install Pro.

---

### What happens if my Pro license expires?

Features continue working. You just stop receiving updates and priority support. Pro features are not disabled when a license expires.

---

### Can I switch between review modes?

Yes. Go to Settings > Member Reviews > Pro and change the mode. Your existing data is preserved — ratings hide in Testimonials mode but reappear if you switch back.

---

### Do all Pro licenses include all features?

Yes. Single, 5-License, Starter, and Developer all include the same features. The difference is how many sites you can activate.

---

### Is there a trial or demo?

No trial. We offer a 14-day refund policy. If Pro doesn't work for your needs, contact support within 14 days for a refund.

---

## Review Management Questions

### How do I moderate reviews?

Go to Reviews in your WordPress admin. Pending reviews appear there. You can approve, edit, or delete any review. Enable "Admin Approval Required" in settings to require approval before reviews publish.

---

### Can members edit their reviews?

Yes, if you enable it. Settings > Member Reviews > General > Allow Review Editing. Members can update their own reviews. Edits can optionally require re-approval.

---

### Can members delete their reviews?

Not from the frontend. Only admins can delete reviews. Members can edit their reviews if editing is enabled.

---

### What happens to reported reviews?

Reports accumulate. When a review reaches your threshold (e.g., 5 reports), it auto-hides and moves to moderation. You can then approve (unhide), delete, or dismiss the reports.

---

### Can profile owners respond to reviews?

Yes, with Owner Replies (enabled by default). Profile owners can post one reply per review. Replies appear publicly below the review.

---

## Technical Questions

### Does it affect site performance?

Minimal impact. Reviews load asynchronously on profiles. No extra database queries on pages without reviews. Scripts and styles only load where needed.

---

### Can I customize the review templates?

Yes. Copy templates from `plugin/includes/templates/` to `your-theme/review/` and modify. Your changes persist through updates.

---

### Does it integrate with the activity stream?

Yes. New reviews can post to BuddyPress activity, showing in feeds and on profiles. Disable in settings if you prefer reviews-only.

---

### Does it work with multisite?

Yes. Network activate or activate per-site. Reviews are site-specific.

---

### What languages are supported?

Fully translatable with included POT file. Translations available for German (DE) and French (FR). WPML compatible.

---

## API Questions (Pro)

### What can I do with the REST API?

Create, read, update, delete reviews programmatically. Get member ratings, list reviews with filters, create reviews from external sources. Build mobile apps or sync with external systems.

---

### Is authentication required?

Depends on the operation. Reading public reviews: no auth needed. Creating/updating/deleting: requires authentication (logged-in user with appropriate permissions).

---

### Where's the API documentation?

Full endpoint reference in the developer documentation: https://docs.wbcomdesigns.com/docs/buddypress-member-review/

---

## Pricing Questions

### How much does Pro cost?

| Plan | Sites | Price/Year |
|------|-------|------------|
| Personal | 1 | $49 |
| Professional | 5 | $99 |
| Agency | Unlimited | $129 |

---

### Is it a one-time purchase or subscription?

Annual subscription. Includes updates and support for one year. Renew to continue receiving updates.

---

### What payment methods do you accept?

Credit cards (Visa, Mastercard, Amex) and PayPal through Easy Digital Downloads.

---

### Do you offer refunds?

14-day refund policy. If Pro doesn't meet your needs, contact support within 14 days.

---

## Support Questions

### How do I get support?

**Free users:** WordPress.org support forum
**Pro users:** Direct email to support@wbcomdesigns.com

---

### What's the response time?

Forum: 24-48 hours typically
Pro email: Same business day

---

### Can you help with customization?

Basic customization questions are covered by support. Custom development work (new features, integrations) is available as a separate service — contact us for a quote.

---

## Migration Questions

### Can I import reviews from another plugin?

No automatic importer currently. Contact support to discuss manual migration options for large sites.

---

### What if I switch themes?

Reviews are stored in the database, independent of themes. Switching themes doesn't affect your reviews. You may need to adjust template overrides if you were using them.

---

### Can I export my reviews?

Reviews are a custom post type. Export via WordPress Tools > Export, selecting the "review" post type. Or use the REST API (Pro) to extract data programmatically.
