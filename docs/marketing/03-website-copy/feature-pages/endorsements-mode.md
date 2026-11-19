# Endorsements Mode

**Pro Feature** — LinkedIn-style skill vouching for professional networks.

---

## The Problem

You can't rate colleagues with stars.

"I give my coworker 4.5 stars" is awkward. Professional networks need a different model — one that's familiar, appropriate, and actually useful.

Self-reported skills aren't trustworthy either. Anyone can claim "Expert in Project Management" on their profile.

---

## The Solution

Endorsements Mode replaces star ratings with skill vouching.

Members endorse each other's skills. Skills appear as badges with endorsement counts:

```
[Leadership (12)] [WordPress (8)] [Project Management (5)] [Communication (3)]
```

If 12 colleagues have vouched for your leadership, that means something.

---

## How It Works

1. Enable Endorsements Mode in Pro settings
2. "Endorse" button appears on member profile headers
3. Clicking opens a skill selection modal
4. Endorser selects skills they can vouch for
5. Skills appear on the profile with endorsement counts

**No star ratings.** No awkward "how many stars is this person?" decisions.

---

## The Endorsement Flow

**Step 1: Visit a colleague's profile**
```
┌─────────────────────────────────────────────────────────────┐
│  [Avatar]  Sarah Johnson           [Endorse Sarah]          │
│            Senior Developer                                 │
└─────────────────────────────────────────────────────────────┘
```

**Step 2: Select skills to endorse**
```
┌─────────────────────────────────────────────────────────────┐
│  Endorse Sarah's Skills                                     │
│                                                             │
│  ☑ Leadership        ☐ Project Management                  │
│  ☑ WordPress         ☑ Communication                       │
│  ☐ Design            ☐ Marketing                           │
│                                                             │
│  Add a message (optional):                                  │
│  ┌─────────────────────────────────────────────────────┐   │
│  │ Sarah led our team through a complex migration...   │   │
│  └─────────────────────────────────────────────────────┘   │
│                                                             │
│                              [Cancel]  [Submit Endorsement] │
└─────────────────────────────────────────────────────────────┘
```

**Step 3: Skills display on profile**
```
┌─────────────────────────────────────────────────────────────┐
│  Sarah's Skills                                             │
│                                                             │
│  [Leadership (12)] [WordPress (8)] [Communication (5)]      │
│  [PHP (4)] [Project Management (3)]                         │
└─────────────────────────────────────────────────────────────┘
```

---

## Best For

### Professional Networks
Colleagues vouch for each other's skills. More appropriate than ratings, more credible than self-claims.

### Expert Directories
Find experts by skill. "Show me everyone endorsed for WordPress" is a useful search.

### Alumni Networks
University and professional association members endorse each other based on real experience.

### Industry Associations
Members build visible credibility within their professional community.

---

## Custom Skills

Define the skills that matter to your community.

**Default skills included:**
- Leadership
- Communication
- Problem Solving
- Technical Skills
- Project Management
- Teamwork
- Creativity
- Time Management
- Adaptability
- Mentoring

**Add your own:**
- Industry-specific skills
- Role-specific competencies
- Certifications or qualifications
- Any skill relevant to your network

Manage skills in **Settings > Member Reviews > Pro**.

---

## Endorsement Requests (Optional)

Members can request endorsements from colleagues:

1. Member clicks "Request Endorsement" on their own profile
2. Selects skills they want endorsed
3. Request goes to the other member's pending queue
4. Colleague approves or declines

This encourages participation without making it feel forced.

---

## What Changes in Endorsements Mode

| Element | Ratings Mode | Endorsements Mode |
|---------|--------------|-------------------|
| Tab label | "Reviews" | "Endorsements" |
| Header button | "Add Review" | "Endorse [Name]" |
| Form | Stars + Text | Skill checkboxes + Text |
| Profile display | Star average | Skill badges with counts |
| Aggregate | 1-5 rating | Skill-specific counts |

---

## Why Endorsements Work

**Familiar pattern:** LinkedIn popularized skill endorsements. Users know what to do.

**Low friction:** Selecting checkboxes is faster than writing a review. More people participate.

**Specific credibility:** "Endorsed by 12 people for Leadership" is more meaningful than "4.5 stars."

**Searchable:** Build member directories filtered by endorsed skills.

---

## Enabling Endorsements Mode

1. Go to **Settings > Member Reviews > Pro**
2. Under Review Mode, select **Endorsements**
3. Customize skill list (optional)
4. Save changes

The change applies immediately.

---

## Works With Other Pro Features

- **Verified Badges:** Mark endorsements from verified colleagues
- **REST API:** Query members by endorsed skills, build skill-based directories
- **Flexible Criteria:** Endorsements mode has its own form, but you can still track other data points

---

## Get Endorsements Mode

Included in all Pro licenses:

| Plan | Sites | Price/Year |
|------|-------|------------|
| Personal | 1 | $49 |
| Professional | 5 | $99 |
| Agency | Unlimited | $129 |

**Purchase Pro:** https://wbcomdesigns.com/downloads/buddypress-member-reviews-pro

**Requires:** BuddyPress Member Reviews (free) v3.8.3+
