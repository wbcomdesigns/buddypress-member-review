# Welcome Email Sequence

Onboarding sequence for new plugin users.

---

## Email 1: Welcome + Quick Start

**Trigger:** Plugin activation
**Delay:** Immediate
**Subject Lines (A/B):**
- A: "Member Reviews is active — here's what to do next"
- B: "Your BuddyPress community just got trust signals"

### Body

Hi,

You just activated BuddyPress Member Reviews. Here's how to get the most out of it:

**Quick Setup (2 minutes):**

1. **Go to Settings > Member Reviews > General**
   Set who can leave reviews and whether to require moderation.

2. **Add your criteria (Criteria tab)**
   Default criteria work fine, or add your own: "Reliability", "Expertise", "Response Time" — whatever fits your community.

3. **Check Display settings**
   Pick your star color and decide whether to show ratings in the member directory.

That's it. Your members can now review each other.

**Want to see it in action?**
Visit any member profile, click the Reviews tab, and submit a test review.

**Questions?**
Reply to this email or check out our documentation: https://docs.wbcomdesigns.com/docs/buddypress-member-review/

— The Wbcom Designs Team

---

## Email 2: Feature Tips

**Trigger:** 3 days after Email 1
**Subject Lines (A/B):**
- A: "3 features you might have missed"
- B: "Get more from Member Reviews"

### Body

Hi,

You've had BuddyPress Member Reviews installed for a few days. Here are three features that new users often overlook:

**1. Owner Replies**
Profile owners can respond to reviews they receive. This builds trust — they can thank positive reviewers or address concerns professionally.

Enable in: Settings > Member Reviews > General > Enable Owner Replies

**2. Report System**
Members can report inappropriate reviews. After a configurable threshold (default: 5 reports), reviews are auto-hidden for your review.

Configure in: Settings > Member Reviews > General > Report Threshold

**3. Top Members Shortcode**
Showcase your highest-rated members anywhere:

```
[bupr_display_top_members total_member="10"]
```

Add this to your homepage, sidebar, or a dedicated "Top Contributors" page.

---

**How's it going?**
Hit reply and let us know how Member Reviews is working for your community. We read every response.

— Wbcom Designs

---

## Email 3: Pro Introduction

**Trigger:** 7 days after Email 1
**Subject Lines (A/B):**
- A: "When star ratings don't fit your community"
- B: "Testimonials, Endorsements, and Verified Badges"

### Body

Hi,

Star ratings work great for marketplaces. But not every community is Yelp.

**Course platforms** need student success stories — not 4.5 stars.
**Professional networks** need skill endorsements — not numerical ratings.

That's why we built BuddyPress Member Reviews Pro.

**Three Review Modes:**

| Mode | What Members See | Best For |
|------|-----------------|----------|
| Ratings | Star ratings + criteria | Marketplaces, service directories |
| Testimonials | Text-only feedback | Courses, coaching, memberships |
| Endorsements | Skill badges | Professional networks, expert directories |

**Plus:**
- **Verified Badges** — Mark authentic reviews with trust signals
- **Flexible Criteria** — Stars, thumbs up/down, or text fields
- **REST API** — Build mobile apps or connect external tools

**Pricing starts at $49/year** for a single site.

Check it out: https://wbcomdesigns.com/downloads/buddypress-member-reviews-pro

---

**Still happy with Free?**
That's fine. The free version is fully functional. Pro is there when you need more flexibility.

— Wbcom Designs

---

## Email 4: Success Check-in

**Trigger:** 14 days after Email 1
**Subject Lines (A/B):**
- A: "How's Member Reviews working for you?"
- B: "Quick question about your community"

### Body

Hi,

It's been two weeks since you installed BuddyPress Member Reviews.

**Quick questions:**

1. Are members using the review system?
2. Is there anything confusing or missing?
3. Would a brief call help you get more value from it?

Reply with any feedback — I personally read every response.

If everything's working well, feel free to ignore this email. But if you're stuck on something, I'm here to help.

— Varun
Wbcom Designs

P.S. If you're finding the plugin useful, a quick review on WordPress.org helps other community managers find us: https://wordpress.org/support/plugin/bp-user-profile-reviews/reviews/#new-post

---

## Sequence Flow

```
Day 0: Email 1 (Welcome + Quick Start)
  ↓
Day 3: Email 2 (Feature Tips)
  ↓
Day 7: Email 3 (Pro Introduction)
  ↓
Day 14: Email 4 (Success Check-in)
```

---

## Segmentation Notes

**Skip Email 3 (Pro Introduction) if:**
- User already purchased Pro
- User clicked "Not interested" on previous Pro emails

**Add to Upgrade Sequence if:**
- User clicked Pro link in Email 3
- User has been active for 30+ days with 50+ reviews

