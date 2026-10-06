# Features Overview

Complete feature breakdown for BuddyPress Member Reviews (Free and Pro).

---

## Core Features (Free)

### Star Ratings

Let members rate each other with familiar 5-star ratings.

**What It Does:**
Each review includes a 1-5 star rating that appears on profiles and in the member directory.

**The Benefit:**
Visitors instantly see who's well-regarded — no need to read every review.

**Why It Matters:**
- Aggregate ratings calculate automatically
- Profile headers show average rating at a glance
- Members can filter/sort by rating in directories

---

### Multiple Rating Criteria

Don't settle for a single rating. Rate on what actually matters.

**What It Does:**
Create custom criteria like Communication, Quality, Reliability, or anything specific to your community.

**The Benefit:**
Reviewers give specific feedback, not vague scores — so you can identify what makes members good (or not).

**Why It Matters:**
- "4 stars overall" doesn't tell you much
- "5 stars Communication, 3 stars Timeliness" tells you exactly what to expect
- Criteria are fully customizable in settings

---

### Written Reviews

Stars tell part of the story. Words tell the rest.

**What It Does:**
Optional text field for reviewers to explain their rating with specific experiences.

**The Benefit:**
Context makes ratings meaningful — a 4-star review with "great work, just slow to respond" is more useful than 4 anonymous stars.

**Why It Matters:**
- Reviews appear on the member's profile tab
- Reviews can post to BuddyPress activity stream
- Searchable for finding specific feedback

---

### Owner Replies

Let members respond to reviews about them.

**What It Does:**
Profile owners can post a single reply to each review they receive.

**The Benefit:**
Members can thank positive reviewers, clarify misunderstandings, or address concerns professionally — right where others will see it.

**Why It Matters:**
- Shows the member is engaged and responsive
- Provides context for negative reviews
- Builds dialogue and community trust

---

### Report System

Let your community help moderate.

**What It Does:**
Members can report inappropriate reviews. Reviews auto-hide after reaching a threshold you set.

**The Benefit:**
You don't have to catch everything — your community flags problems, and automation handles the rest.

**Why It Matters:**
- Configurable threshold (e.g., auto-hide after 5 reports)
- Reported reviews go to admin queue
- Reduces your moderation workload

---

### Moderation Queue

Control what gets published.

**What It Does:**
Optionally require admin approval before reviews appear publicly.

**The Benefit:**
No surprise inappropriate content — you review before it's live.

**Why It Matters:**
- Dashboard shows all pending reviews
- Bulk approve/reject actions
- Edit reviews before approving if needed

---

### Email Notifications

Keep members informed automatically.

**What It Does:**
Send emails when someone receives a review, when their review is approved, or when someone replies.

**The Benefit:**
Members don't have to check constantly — they're notified and can respond quickly.

**Why It Matters:**
- Configurable notification types
- Works with BuddyPress notification system
- Customizable email templates

---

### Top Members Shortcode

Showcase your highest-rated members anywhere.

**What It Does:**
Display a grid or list of top-rated members using `[bupr_display_top_members]`.

**The Benefit:**
Homepage, sidebar, dedicated page — highlight contributors wherever it makes sense.

**Why It Matters:**
```
[bupr_display_top_members total_member="10" type="top rated"]
```
- Top rated or most reviewed
- Choose how many members to show
- Show or hide avatars

---

### Template Overrides

Customize how reviews look in your theme.

**What It Does:**
Copy template files to your theme folder and modify the HTML/CSS.

**The Benefit:**
Reviews match your site design exactly — not stuck with plugin defaults.

**Why It Matters:**
- Copy to `your-theme/buddypress-member-review/`
- Full control over markup
- Updates don't overwrite your changes

---

## Pro Features

### Testimonials Mode

When star ratings don't fit your community.

**What It Does:**
Removes star ratings entirely. Members submit text-only testimonials — success stories in their own words.

**The Benefit:**
Course platforms, coaching sites, and membership communities need stories, not scores. "4.5 stars" doesn't capture what a course changed for someone.

**Why It Matters:**
- Tab label changes to "Testimonials"
- Profile header hides rating display
- Review form shows only text field
- Existing rating data is preserved (reappears if you switch back)

**Best For:** Online courses, coaching programs, membership communities

---

### Endorsements Mode

LinkedIn-style skill vouching.

**What It Does:**
Instead of star ratings, members vouch for each other's skills. Skills appear as badges with endorsement counts.

**The Benefit:**
Professional networks can't rate colleagues "4.5 stars" — that's awkward. Skill endorsements are appropriate and familiar.

**Why It Matters:**
- "Endorse" button on profile headers
- Skill selection modal with admin-defined skills
- Badges show: `[Leadership (12)] [WordPress (8)]`
- Pending endorsement requests for approval

**Best For:** Professional networks, expert directories, alumni networks

---

### Verified Badges

Trust signals for authentic reviews.

**What It Does:**
Mark reviews as verified with a visible checkmark badge. Verify manually or auto-verify reviews from long-term members.

**The Benefit:**
Anyone can write a review. Verified badges prove the reviewer is legitimate — a real customer, colleague, or community member.

**Why It Matters:**
- Admin can verify from dashboard
- Auto-verify by membership tenure (e.g., 30+ days)
- Badge displays inline with review
- Bulk verify/unverify actions

---

### Flexible Criteria Builder

Rate on anything, in any format.

**What It Does:**
Create rating criteria with different input types: stars (1-5), thumbs (up/down), or text (short answer).

**The Benefit:**
"Would you recommend this person?" works better as thumbs up/down than 1-5 stars. Now you can choose.

**Why It Matters:**
- Visual criteria builder in admin
- Weighted averages (Quality counts 2x, Speed counts 1x)
- Mix types within one review form
- Drag-and-drop reordering

---

### REST API

Connect your community to everything.

**What It Does:**
Full CRUD API for reviews. Create, read, update, delete reviews programmatically.

**The Benefit:**
Build mobile apps, sync with external dashboards, or integrate with third-party services — all using standard REST endpoints.

**Why It Matters:**
| Endpoint | Purpose |
|----------|---------|
| `GET /bupr/v1/reviews` | List all reviews |
| `GET /bupr/v1/reviews/{id}` | Single review |
| `POST /bupr/v1/reviews` | Create review |
| `PATCH /bupr/v1/reviews/{id}` | Update review |
| `DELETE /bupr/v1/reviews/{id}` | Delete review |
| `GET /bupr/v1/members/{id}/rating` | Member's aggregate rating |

---

### Priority Support

Direct access to the team that built it.

**What It Does:**
Skip the WordPress.org forums. Email support directly for faster response.

**The Benefit:**
When you're running a business on this plugin, you need answers fast — not forum responses in 24-48 hours.

**Why It Matters:**
- Same-day response (business hours)
- Direct email to development team
- Feature requests prioritized

---

## Feature Comparison Table

| Feature | Free | Pro |
|---------|:----:|:---:|
| Star ratings (1-5) | ✓ | ✓ |
| Multiple criteria | ✓ | ✓ |
| Written reviews | ✓ | ✓ |
| Anonymous option | ✓ | ✓ |
| Owner replies | ✓ | ✓ |
| Report system | ✓ | ✓ |
| Auto-hide threshold | ✓ | ✓ |
| Moderation queue | ✓ | ✓ |
| Email notifications | ✓ | ✓ |
| BuddyPress notifications | ✓ | ✓ |
| Activity stream posting | ✓ | ✓ |
| Top Members shortcode | ✓ | ✓ |
| Template overrides | ✓ | ✓ |
| Member directory integration | ✓ | ✓ |
| **Testimonials Mode** | — | ✓ |
| **Endorsements Mode** | — | ✓ |
| **Verified Badges** | — | ✓ |
| **Flexible Criteria** | — | ✓ |
| **REST API** | — | ✓ |
| **Priority Support** | — | ✓ |

---

## Compatibility

| Platform | Supported |
|----------|:---------:|
| WordPress 6.0+ | ✓ |
| BuddyPress 12.0+ | ✓ |
| BuddyBoss Platform | ✓ |
| BuddyBoss Theme | ✓ |
| Youzify | ✓ |
| PHP 7.4 - 8.2+ | ✓ |

---

## Get Started

**Free:** https://wordpress.org/plugins/bp-user-profile-reviews/

**Pro:** https://wbcomdesigns.com/downloads/buddypress-member-reviews-pro

**Documentation:** https://docs.wbcomdesigns.com/docs/buddypress-member-review/
