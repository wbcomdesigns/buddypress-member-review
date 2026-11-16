# Verified Badges

**Pro Feature** — Trust signals for authentic reviews.

---

## The Problem

Anyone can write a review. How do visitors know it's real?

Fake reviews plague every platform. Without verification:
- Competitors post negative reviews
- Friends post inflated positive reviews
- Random accounts appear credible
- Visitors can't tell authentic from fake

---

## The Solution

Verified badges mark reviews from confirmed, legitimate sources.

```
┌─────────────────────────────────────────────────────────────┐
│  [Avatar]  John Smith  ✓ Verified                          │
│            January 15, 2026                                 │
│  ★★★★★                                                     │
│                                                             │
│  "Excellent work on our website redesign. Delivered on      │
│   time and the results exceeded expectations."              │
└─────────────────────────────────────────────────────────────┘
```

That checkmark tells visitors: this review is authentic.

---

## Two Verification Methods

### Manual Verification

Admins mark specific reviews as verified.

**Use when:**
- You can confirm the reviewer is a real customer
- The review comes from a known community member
- You've verified the transaction or relationship

**How to verify:**
1. Go to Reviews in admin
2. Find the review
3. Click "Verify" in row actions
4. Badge appears instantly

Bulk verify multiple reviews at once.

---

### Auto-Verify by Tenure

Automatically verify reviews from long-term members.

**Settings:**
- Set minimum membership duration (e.g., 30 days, 90 days, 1 year)
- Reviews from members who joined before that threshold get auto-verified

**Why this works:**
- Fake accounts are usually new
- Long-term members have reputation to protect
- Reduces your manual verification workload

**Configure in:** Settings > Member Reviews > Pro > Auto-Verify Tenure

---

## What Gets Verified

Verification applies to:
- **Ratings Mode:** Verified review badge
- **Testimonials Mode:** Verified testimonial badge
- **Endorsements Mode:** Verified endorsement badge

The badge language adapts to your mode.

---

## Where Badges Display

- Review list on member profiles
- Individual review pages
- Activity stream posts
- REST API responses (verified field)

The badge is visible anywhere reviews appear.

---

## Badge Appearance

The verified badge appears inline after the reviewer name:

```
John Smith ✓ Verified • January 15, 2026
```

Clean, unobtrusive, but immediately visible.

---

## Admin Management

### Verification Dashboard

See all verified reviews at a glance:
- Filter by verification status
- Sort by verification date
- Track who verified what

### Bulk Actions

- Mark as Verified (multiple reviews)
- Remove Verification (multiple reviews)

### Per-Review Controls

Row actions on each review:
- Verify
- Unverify

---

## Why Verification Matters

### For Marketplaces

Buyers trust reviews from verified purchasers. "Verified Buyer" badges are standard on Amazon, eBay, and every major marketplace.

### For Course Platforms

"Verified Student" confirms the reviewer actually took the course. Not a competitor, not a friend who never enrolled.

### For Professional Networks

"Verified Colleague" means they actually worked together. The endorsement carries weight.

---

## Verification Strategies

### Conservative Approach
Only manually verify. Every badge is a personal stamp of approval.

**Best for:** Small communities where you know members personally.

### Balanced Approach
Auto-verify 90-day+ members. Manually verify newer members.

**Best for:** Growing communities where tenure indicates legitimacy.

### Open Approach
Auto-verify all reviews after 30 days. Badge shows "Established Member."

**Best for:** Large communities where any long-term member is credible.

---

## No Verification = No Badge

Unverified reviews still display normally. They just don't get the badge.

Visitors learn: reviews with badges are confirmed. Reviews without may still be legitimate, but aren't formally verified.

---

## Enabling Verified Badges

1. Go to **Settings > Member Reviews > Pro**
2. Enable **Verified Badges** toggle
3. (Optional) Set auto-verify tenure
4. Save changes

Start verifying reviews from your admin dashboard.

---

## Works With All Modes

| Mode | Badge Text |
|------|------------|
| Ratings | ✓ Verified |
| Testimonials | ✓ Verified |
| Endorsements | ✓ Verified |

---

## Get Verified Badges

Included in all Pro licenses:

| Plan | Sites | Price/Year |
|------|-------|------------|
| Personal | 1 | $49 |
| Professional | 5 | $99 |
| Agency | Unlimited | $129 |

**Purchase Pro:** https://wbcomdesigns.com/downloads/buddypress-member-reviews-pro

**Requires:** BuddyPress Member Reviews (free) v3.8.3+
