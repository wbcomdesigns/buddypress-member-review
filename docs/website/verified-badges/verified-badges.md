# Verified Badges

**PRO Feature** - Requires BuddyPress Member Reviews Pro addon

## What Are Verified Badges?

Verified badges are trust signals that mark authentic reviews on your BuddyPress community. A small green checkmark appears next to verified reviews, helping visitors distinguish genuine feedback from unverified claims.

![Verified Badge Display](../images/pro-verified-badge.png)

## The Problem

Anyone can write anything online. Without verification, your members face:

- Skepticism about review authenticity
- Reduced trust in your community
- Lower conversion rates for professional profiles
- Difficulty distinguishing real experiences from promotional content

Verified badges solve this by providing visual proof that a review meets your community's authenticity standards.

## Verification Types

### Admin Verified

Manual verification by site administrators. Use this when you have external proof of a relationship or transaction between members.

**Best for:** Reviews you've personally validated, flagged genuine reviews, special cases

### Tenure Verified

Automatic verification based on member registration date. Long-term members automatically get verified status for their reviews.

**Best for:** Community trust signals, rewarding established members, scaling verification

### Transaction Verified

**Coming soon.** Automatic verification when a documented transaction exists between reviewer and reviewed member.

## Mode-Aware Badge Labels

Badge labels automatically adapt to your review mode:

- **Review Mode:** "Verified Review"
- **Testimonial Mode:** "Verified Testimonial"
- **Endorsement Mode:** "Verified Endorsement"

This ensures consistent language throughout your community.

## Display Location

Verified badges appear inline after the review date:

```
Posted 2 weeks ago • Verified Review ✓
```

The green checkmark provides instant visual recognition while maintaining clean, professional formatting.

## How to Enable

1. Go to **Settings → Member Reviews → Pro**
2. Scroll to **Verified Badges** section
3. Check **Enable Verified Badges**
4. Configure verification methods:
   - **Manual Verification:** Always available
   - **Auto-Verify by Tenure:** Set minimum months (0-120)
5. Click **Save Changes**

## Manual Verification

### Single Review

1. Go to **Member Reviews → All Reviews** in admin
2. Hover over a review
3. Click **Verify** or **Unverify**

### Bulk Verification

1. Go to **Member Reviews → All Reviews**
2. Check multiple reviews
3. Select **Verify** or **Unverify** from bulk actions dropdown
4. Click **Apply**

## Auto-Verification by Tenure

Set a minimum member age threshold. Reviews written by members who've been registered longer than this period are automatically verified.

**Configuration:**

1. Go to **Settings → Member Reviews → Pro → Verified Badges**
2. Set **Auto-Verify Members After (Months)**
3. Enter months (e.g., 6 for members active 6+ months)
4. Save changes

**How It Works:**

- Plugin checks member registration date when displaying reviews
- If `current_date - registration_date >= threshold`, badge appears
- Updates automatically as members pass the threshold
- No database changes needed - purely display logic

## Use Cases

### Freelancer Marketplace

Verify reviews from completed projects. Verified badges help build trust with your audience by distinguishing genuine feedback from unverified claims.

**Setup:** Enable admin verification, manually verify reviews after project completion

### Professional Network

Auto-verify reviews from established members. Shows reviews come from experienced community members, not new accounts.

**Setup:** Set 6-month tenure threshold for automatic verification

### Course Platform

Verify reviews from course purchasers. Verified badges help establish authenticity of course feedback.

**Setup:** Use admin verification after enrollment confirmation (transaction verification coming soon)

## Admin Features

### Verified Filter

Filter reviews by verification status:

1. Go to **Member Reviews → All Reviews**
2. Use **Verified Status** dropdown
3. Options: All, Verified, Unverified

### Bulk Operations

Verify or unverify multiple reviews simultaneously:

- Select reviews using checkboxes
- Choose bulk action
- Apply to all selected reviews

### Verification Indicator

Admin list view shows verification status in a dedicated column for quick scanning.

## Technical Details

- Verification status stored as review meta
- Tenure verification calculated on-the-fly (no cron jobs)
- Badge HTML includes microdata for SEO
- Filterable via `bupr_pro_is_review_verified` hook

## Support

Need help with verified badges?

- Check our [Knowledge Base](https://docs.wbcomdesigns.com)
- Contact support at support@wbcomdesigns.com
