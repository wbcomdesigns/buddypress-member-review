# Flexible Criteria Builder

**Pro Feature** — Rate on anything, in any format.

---

## The Problem

Not everything fits a 1-5 star scale.

"Would you recommend this person?" — Yes or No makes more sense than 1-5.

"What would you improve?" — A text answer, not stars.

"Rate their communication" — OK, stars work here.

The free version gives you star criteria only. Pro lets you mix formats.

---

## The Solution

Create rating criteria with the input type that fits:

| Type | Input | Best For |
|------|-------|----------|
| **Stars** | 1-5 selection | Quality ratings, overall scores |
| **Thumbs** | Up/Down buttons | Yes/No questions, quick feedback |
| **Text** | Short text field | Open-ended questions, suggestions |

Mix them in one review form. Members rate on stars, vote thumbs, and write short answers — all in one submission.

---

## How It Works

1. Go to **Settings > Member Reviews > Criteria Tab**
2. Pro replaces the free criteria builder with the flexible version
3. Add criteria with different types
4. Set weights for aggregate calculation
5. Drag to reorder
6. Save

---

## Example: Marketplace Criteria

```
┌─────────────────────────────────────────────────────────────┐
│  Product Quality              ★★★★★          (Weight: 2x)   │
│  Shipping Speed               ★★★★☆          (Weight: 1x)   │
│  Communication                ★★★★★          (Weight: 1x)   │
│  Would Buy Again?             👍             (Thumbs)       │
│  Suggestions for Seller:      [Text input]   (Text)        │
└─────────────────────────────────────────────────────────────┘
```

The aggregate rating uses weighted star criteria. Thumbs and text display separately.

---

## Example: Course Platform Criteria

```
┌─────────────────────────────────────────────────────────────┐
│  Content Quality              ★★★★★          (Weight: 2x)   │
│  Instructor Clarity           ★★★★☆          (Weight: 1x)   │
│  Would Recommend?             👍             (Thumbs)       │
│  Most Valuable Lesson:        [Text input]   (Text)        │
│  What Would You Improve?      [Text input]   (Text)        │
└─────────────────────────────────────────────────────────────┘
```

Collect both quantitative ratings and qualitative feedback in one form.

---

## Weighted Averages

Not all criteria matter equally. Weights let you prioritize.

**Example:**
- Product Quality: Weight 2
- Shipping Speed: Weight 1
- Communication: Weight 1

If someone rates 5, 4, 5:
- Unweighted average: (5 + 4 + 5) / 3 = 4.67
- Weighted average: (5×2 + 4×1 + 5×1) / 4 = 4.75

Quality matters more, so it counts more.

---

## The Criteria Builder

Visual admin interface for managing criteria:

```
┌─────────────────────────────────────────────────────────────┐
│  Rating Criteria                                            │
│                                                             │
│  ≡  Communication    [Stars ▼]   Weight: [1]   [🗑]         │
│  ≡  Quality          [Stars ▼]   Weight: [2]   [🗑]         │
│  ≡  Would Recommend  [Thumbs ▼]  Weight: [—]   [🗑]         │
│  ≡  Suggestions      [Text ▼]    Weight: [—]   [🗑]         │
│                                                             │
│  [+ Add Criteria]                                           │
│                                                             │
│  [Save Criteria]  [Reset to Defaults]                       │
└─────────────────────────────────────────────────────────────┘
```

- Drag ≡ handle to reorder
- Select type from dropdown
- Set weight (stars only)
- Delete with trash icon

---

## Input Types Explained

### Stars (1-5)

Standard star rating. Click to select 1-5 stars.

**Use for:** Quality, satisfaction, performance scores

**Aggregate:** Yes — stars calculate into the overall average

---

### Thumbs (Up/Down)

Binary choice. Thumbs up or thumbs down.

**Use for:** Yes/No questions, recommendations, simple feedback

**Aggregate:** Displayed as percentage (e.g., "92% would recommend")

---

### Text (Short Answer)

Text input field. 280 character limit.

**Use for:** Open-ended feedback, suggestions, specific comments

**Aggregate:** N/A — displayed as individual responses

---

## Free vs Pro Criteria

| Capability | Free | Pro |
|------------|:----:|:---:|
| Star criteria | ✓ | ✓ |
| Thumbs criteria | — | ✓ |
| Text criteria | — | ✓ |
| Weighted averages | — | ✓ |
| Drag-and-drop reorder | — | ✓ |
| Multiple types in one form | — | ✓ |

---

## Compatibility Notes

### Mode Compatibility

Flexible Criteria only works in **Ratings Mode**.

- Testimonials Mode: No criteria (text-only by design)
- Endorsements Mode: Skills replace criteria

### Backward Compatibility

- Existing star criteria automatically migrate to Pro format
- Existing reviews continue to display correctly
- No data loss when enabling Pro

---

## Example Use Cases

### Freelancer Marketplace
```
Quality (Stars, 2x) | Timeliness (Stars, 1x) | Communication (Stars, 1x) | Hire Again? (Thumbs) | Notes (Text)
```

### Service Directory
```
Service Quality (Stars, 2x) | Value for Money (Stars, 1x) | Would Recommend? (Thumbs) | Best Part (Text)
```

### Gaming Community
```
Skill Level (Stars) | Teamwork (Stars) | Fun to Play With (Stars) | Toxic? (Thumbs Down = Toxic)
```

---

## Enabling Flexible Criteria

1. Install and activate Pro
2. Go to **Settings > Member Reviews > Criteria Tab**
3. Pro builder replaces free builder automatically
4. Add/modify criteria as needed
5. Save

---

## Get Flexible Criteria

Included in all Pro licenses:

| Plan | Sites | Price/Year |
|------|-------|------------|
| Single | 1 | $69 |
| 5-License | 5 | $129 |
| Starter | 25 | $199 |
| Developer | Unlimited | $299 |

**Purchase Pro:** https://wbcomdesigns.com/downloads/buddypress-member-reviews-pro

**Requires:** BuddyPress Member Reviews (free) v3.7.0+
