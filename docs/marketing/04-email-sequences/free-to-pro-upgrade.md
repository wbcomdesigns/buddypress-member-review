# Free to Pro Upgrade Sequence

Email sequence for converting free users to Pro.

---

## Trigger Criteria

Send this sequence when:
- User has been active for 30+ days
- Site has 50+ reviews submitted
- User has visited Pro page or clicked Pro links

---

## Email 1: Use Case Discovery

**Subject Lines (A/B):**
- A: "Quick question about your community type"
- B: "Ratings, testimonials, or endorsements?"

### Body

Hi,

I noticed your community has been using BuddyPress Member Reviews actively. Nice work building that trust system.

I'm curious — what type of community are you running?

**A) Marketplace / Service Directory**
Members sell services or products to each other

**B) Course Platform / Coaching Site**
Students learn from instructors

**C) Professional Network**
Members connect for career opportunities

**D) General Interest Community**
Members share a hobby or interest

Hit reply with A, B, C, or D. I'll send you specific tips for maximizing reviews in your type of community.

— Varun, Wbcom Designs

---

## Email 2A: Marketplace Response

**Trigger:** User replied "A" (Marketplace)
**Subject:** "Verified badges build buyer confidence"

### Body

Hi,

Thanks for sharing. Marketplace communities like yours depend on trust — buyers need to know sellers are legitimate before spending money.

**Two Pro features that help marketplaces:**

**1. Verified Badges**
Mark reviews from confirmed buyers. When visitors see that verified checkmark, they know the review is authentic.

You can:
- Manually verify after confirming a transaction
- Auto-verify reviews from long-term members (trust tenure)

**2. Flexible Criteria**
Instead of generic ratings, create marketplace-specific criteria:
- "Product Quality" → Stars (1-5)
- "Shipping Speed" → Stars (1-5)
- "Would Buy Again?" → Thumbs up/down

Weighted averages let you prioritize what matters most.

**Marketplace owners report:**
- 40% fewer support tickets after implementing verified reviews
- Higher seller engagement when they can build visible reputation

---

Interested? Pro starts at $49/year: https://wbcomdesigns.com/downloads/buddypress-member-reviews-pro

Questions? Reply here.

— Varun

---

## Email 2B: Course Platform Response

**Trigger:** User replied "B" (Course)
**Subject:** "Testimonials work better than stars for courses"

### Body

Hi,

Thanks for sharing. Course platforms have a unique challenge — star ratings feel transactional when you're talking about learning and personal growth.

"4.5 stars" doesn't capture what a course actually changed for someone.

**Pro includes Testimonials Mode:**

Switch to Testimonials and star ratings disappear. Students submit text-only feedback — success stories in their own words.

Example testimonial:
> "Sarah's marketing course helped me land my first three clients within a month. The Facebook Ads module alone was worth the price."

That's more persuasive than "5 stars."

**Course creators report:**
- 2x higher conversion on pages with testimonials vs. star ratings
- More detailed feedback that helps improve courses

---

Try it: $49/year for a single site
https://wbcomdesigns.com/downloads/buddypress-member-reviews-pro

— Varun

---

## Email 2C: Professional Network Response

**Trigger:** User replied "C" (Professional)
**Subject:** "LinkedIn-style endorsements for your network"

### Body

Hi,

Professional networks have the hardest trust problem. You can't rate a colleague 4.5 stars — that's awkward.

**Pro includes Endorsements Mode:**

Instead of star ratings, members vouch for each other's skills:

```
[Leadership (12)] [WordPress (8)] [Project Management (5)]
```

Skills are defined by you — industry-specific, role-specific, whatever fits.

**How it works:**
1. Member visits colleague's profile
2. Clicks "Endorse"
3. Selects skills they can vouch for
4. Skills appear as badges with endorsement counts

It's familiar (LinkedIn pioneered it) and appropriate for professional contexts.

**Network founders report:**
- 3x more profile engagement with skill endorsements
- Members actively building portfolios on your platform

---

Upgrade: $49/year
https://wbcomdesigns.com/downloads/buddypress-member-reviews-pro

— Varun

---

## Email 2D: General Community Response

**Trigger:** User replied "D" (General)
**Subject:** "Make your top contributors famous"

### Body

Hi,

General interest communities often struggle with recognition — how do you highlight your best members without making it feel like a competition?

**Two features that help:**

**1. Top Members Shortcode (Free)**
You already have this. Use it on your homepage:
```
[bupr_display_top_members max="5"]
```

**2. Verified Badges (Pro)**
Mark reviews from your most trusted members. Gives longtime contributors extra credibility.

**For hobby communities specifically:**
The default Ratings mode usually works well. Stars are familiar, easy to understand, and encourage participation.

Pro features are there when you need them — but Free might be all you need.

---

If you want to explore Pro: $49/year
https://wbcomdesigns.com/downloads/buddypress-member-reviews-pro

Or just keep using Free — it's fully functional.

— Varun

---

## Email 3: Final Offer

**Trigger:** 5 days after Email 2 (any variant)
**Send only if:** User hasn't purchased Pro
**Subject Lines (A/B):**
- A: "Last chance: 20% off Member Reviews Pro"
- B: "Upgrade before Friday — save $14"

### Body

Hi,

This is the last email in this sequence. If Pro isn't right for you, no worries — I won't email about it again.

But if you've been considering upgrading, I wanted to offer a deal:

**20% off any Pro license through Friday**

Use code: UPGRADE20

| Plan | Regular | With Code |
|------|---------|-----------|
| Personal (1 site) | $49 | $39 |
| Professional (5 sites) | $99 | $79 |
| Agency (unlimited) | $129 | $103 |

**What you get:**
- Three review modes (Ratings, Testimonials, Endorsements)
- Verified badges for trust signals
- Flexible criteria builder
- Full REST API
- Priority email support

---

Grab it: https://wbcomdesigns.com/downloads/buddypress-member-reviews-pro

Discount code: **UPGRADE20**
Expires: [Friday date]

— Varun, Wbcom Designs

---

## Sequence Flow

```
Day 0: Email 1 (Use Case Discovery)
  ↓
Wait for reply (max 3 days)
  ↓
Day 1-3: Email 2A/B/C/D (Based on response)
  ↓
  If no response: Email 2D (General) after 3 days
  ↓
Day 8: Email 3 (Final Offer)
```

---

## Notes

- Track which variant (A/B/C/D) converts best
- Personalize based on reply content
- Remove from sequence immediately on purchase

