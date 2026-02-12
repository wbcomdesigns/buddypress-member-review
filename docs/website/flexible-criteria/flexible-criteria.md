# Flexible Criteria Builder

**PRO Feature** - Requires BuddyPress Member Reviews Pro addon

## What Is Flexible Criteria?

Flexible Criteria Builder lets you create custom rating dimensions beyond simple star ratings. Collect structured feedback across multiple categories with weighted importance scoring.

![Criteria Builder Interface](../images/pro-criteria-builder.png)

## Beyond Basic Star Ratings

The free version provides a single overall rating. Pro adds:

- Multiple rating criteria per review
- Different input types (Stars, Thumbs, Text)
- Weighted importance per criterion
- Aggregate weighted ratings
- Category-specific feedback

This gives you richer data and helps reviewers provide more specific, actionable feedback.

## Criteria Types

### Stars (1-5 Rating)

Traditional 5-star rating scale for quantitative feedback.

**Best for:** Quality, professionalism, communication, skill level, value

**Display:** Interactive star selector with current rating highlighted

### Thumbs (Up/Down)

Binary recommendation choice for simple yes/no questions.

**Best for:** "Would recommend", "Would hire again", "Met expectations"

**Display:** Two thumb buttons (up/down) with one selected

### Text (Short Input)

Free-form text field for qualitative feedback.

**Best for:** "Biggest strength", "Area for improvement", "Project summary"

**Display:** Single-line text input field

## Weighted Ratings

Assign importance weights to each criterion. Weights affect the calculated aggregate rating, letting you prioritize what matters most.

**Weight Scale:** 0.1 (least important) to 2.0 (most important)

**Calculation:**

```
Weighted Average = Σ(rating × weight) / Σ(weight)
```

**Example:**

- Communication: 5 stars, weight 1.5 = 7.5
- Timeliness: 3 stars, weight 0.5 = 1.5
- Quality: 4 stars, weight 2.0 = 8.0
- Total: (7.5 + 1.5 + 8.0) / (1.5 + 0.5 + 2.0) = 4.25 stars

## Building Custom Criteria

### Access the Builder

1. Go to **Settings → Member Reviews → Pro**
2. Scroll to **Flexible Criteria** section
3. Click **Manage Criteria**

### Add a Criterion

1. Click **Add Criterion**
2. Fill in details:
   - **Label:** Display name (e.g., "Communication")
   - **Type:** Stars, Thumbs, or Text
   - **Weight:** Importance multiplier (0.1-2.0)
   - **Required:** Whether reviewers must fill this field
3. Click **Save Criterion**

### Reorder Criteria

Drag and drop criteria to change display order. Order affects both the review form and display.

### Remove a Criterion

1. Hover over criterion
2. Click **Delete** icon
3. Confirm removal

**Warning:** Removing a criterion doesn't delete existing data, but that criterion won't appear in new reviews or display templates.

## Example Configurations

### Freelancer Platform

**Goal:** Evaluate project delivery and collaboration

**Criteria:**
- Quality of Work (Stars, weight 2.0, required)
- Communication (Stars, weight 1.5, required)
- Timeliness (Stars, weight 1.5, required)
- Would Hire Again (Thumbs, weight 1.0, required)
- Project Highlights (Text, weight 0, optional)

### Professional Network

**Goal:** Capture endorsement specifics

**Criteria:**
- Expertise Level (Stars, weight 2.0, required)
- Reliability (Stars, weight 1.5, required)
- Team Collaboration (Stars, weight 1.0, optional)
- Recommend for (Text, weight 0, optional)

### Course Platform

**Goal:** Evaluate instructor effectiveness

**Criteria:**
- Knowledge (Stars, weight 2.0, required)
- Teaching Clarity (Stars, weight 1.5, required)
- Responsiveness (Stars, weight 1.0, required)
- Would Recommend (Thumbs, weight 1.0, required)
- Most Valuable Lesson (Text, weight 0, optional)

### Community Forum

**Goal:** Track helpfulness

**Criteria:**
- Helpfulness (Stars, weight 2.0, required)
- Friendliness (Stars, weight 1.0, required)
- Knowledge (Stars, weight 1.5, required)
- Thanks for (Text, weight 0, optional)

## Criteria Sync & Backward Compatibility

### When Pro Is Active

Reviews display using Pro's custom criteria configuration. All criteria appear in the review form and on the frontend.

### When Pro Is Deactivated

The free version takes over automatically:

- Custom criteria data is preserved in the database
- Free version displays only the overall rating
- Review form returns to simple 5-star input
- No data is lost

### When Pro Is Reactivated

All custom criteria data returns immediately. Reviews show full criteria details again.

This ensures smooth transitions and prevents data loss if you change licensing.

## Review Form Display

### Frontend Edit Modal

When members create or edit reviews, custom criteria appear as form fields:

1. Overall rating (required)
2. Each custom criterion in configured order
3. Review text
4. Anonymous option

Star criteria show interactive star selectors. Thumbs criteria show up/down buttons. Text criteria show input fields.

### Required Field Validation

If a criterion is marked required, members cannot submit the review without completing it. Validation messages appear inline.

## Aggregate Rating Calculation

### Weighted Average

The member's aggregate rating uses the weighted average formula across all reviews:

1. For each review, calculate weighted score
2. Sum all weighted scores
3. Divide by sum of weights
4. Round to nearest 0.1

### Display Locations

Weighted aggregate ratings appear:

- Member profile header
- Member directory cards
- Review widgets
- REST API responses

### Hover Details

Hovering over aggregate ratings shows:

- Total review count
- Breakdown by criterion (if applicable)
- Verification count

## Technical Details

- Criteria stored as plugin options
- Review criterion data stored as post meta
- Weight calculation uses PHP number precision
- Filterable via `bupr_pro_criteria_config` hook
- AJAX validation for required fields

## Support

Need help with flexible criteria?

- Check our [Knowledge Base](https://docs.wbcomdesigns.com)
- Contact support at support@wbcomdesigns.com
