# Ratings Mode (Default)

The default review mode — traditional 5-star ratings with customizable criteria. Members rate each other on specific aspects and write optional text reviews.

![Profile header with rating](../images/frontend-profile-header.png)

## When to Use Ratings Mode

**Best for:** Freelancer marketplaces, service directories, e-commerce communities, any site where quantified feedback matters.

| Scenario | Why Ratings Work |
|----------|-----------------|
| Freelancer marketplace | Clients compare providers by score |
| Service directory | Quick quality signal for visitors |
| Tutor platform | Students evaluate teaching quality |
| Rental/booking site | Trust score for listings |

## How Ratings Display

### Member Profile Header
- Aggregate star rating (e.g., 4.5/5)
- Total review count

### Reviews Tab
Each review shows:
- Star ratings per criterion (if multi-criteria enabled)
- Overall score
- Written text review
- Reviewer name and avatar
- Date and optional verified badge

### Member Directory
- Star rating beside each member (if enabled in settings)
- Helps visitors quickly identify top members

## Setting Up Rating Criteria

![Criteria settings](../images/admin-settings-criteria.png)

### Single Criterion (Default)
Members see one "Overall Rating" with 1-5 stars.

### Multiple Criteria
Enable in **Settings > Member Reviews > Criteria** tab. Add criteria like:

**Service Provider:**
- Quality of Work
- Communication
- Timeliness
- Value for Money

**Professional Network:**
- Expertise
- Collaboration
- Responsiveness

**Tutor/Instructor:**
- Knowledge
- Teaching Style
- Patience
- Preparation

### How Aggregate Rating Is Calculated

For each review, the plugin averages all criteria ratings to get a review score. Then it averages all review scores to get the member's aggregate rating.

**Example:**
- Review 1: Communication (5), Quality (4), Timeliness (5) = 4.67 average
- Review 2: Communication (4), Quality (5), Timeliness (4) = 4.33 average
- Member aggregate: (4.67 + 4.33) / 2 = 4.50

## Submitting a Review

![Add review form](../images/frontend-add-review-form.png)

1. Visit another member's profile
2. Click the **Reviews** tab or **Add Review** button in header
3. Rate each criterion using stars (1-5)
4. Write an optional text review
5. Submit

## Pro Enhancement: Flexible Criteria

With the Pro addon, Ratings Mode gains additional criteria types:

| Type | Display | Use Case |
|------|---------|----------|
| **Stars** (Free) | 1-5 star rating | Quality, satisfaction |
| **Thumbs** (Pro) | Up/Down buttons | Would recommend, would hire again |
| **Text** (Pro) | Short text input | Project type, one-word description |

Criteria can also have **weights** — if "Quality" matters more than "Speed," weight it higher for accurate aggregate ratings.

See [Flexible Criteria](../features/flexible-criteria.md) for full details.

## Switching Modes

You can switch from Ratings to Testimonials or Endorsements mode anytime via **Settings > Member Reviews > Pro**. Existing review data is preserved — star ratings stop displaying but aren't deleted. Switch back and ratings reappear.
