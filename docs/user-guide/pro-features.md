# Pro Features Guide

BuddyPress Member Reviews Pro extends the free plugin with advanced review modes, verification, and API access.

## Feature Overview

| Feature | What It Does |
|---------|--------------|
| Review Modes | Switch between Ratings, Testimonials, or Endorsements |
| Verified Badges | Mark authentic reviews with trust signals |
| Flexible Criteria | Add thumbs up/down and text fields |
| REST API | Full API for mobile apps |
| Endorsement Mode | LinkedIn-style skill endorsements |

## Review Modes

Pro offers three distinct review modes. Choose one based on your community type.

### Ratings Mode (Default)

Standard star-based reviews with criteria.

**Best for:**
- Service marketplaces
- Professional networks
- Freelancer platforms

**Features:**
- Multiple rating criteria
- Aggregate ratings
- Stars display everywhere

### Testimonials Mode

Text-only reviews without star ratings.

**Best for:**
- Course platforms
- Membership sites
- Communities focused on qualitative feedback

**Features:**
- No star ratings on reviews
- "Testimonials" label instead of "Reviews"
- Focus on written feedback
- Aggregate ratings hidden

### Endorsements Mode

LinkedIn-style skill endorsements.

**Best for:**
- Professional networks
- Skill-based communities
- Career platforms

**Features:**
- Skill-based endorsement buttons
- "Endorse [Name]" on profile headers
- Pending requests system
- Skills list display

## Setting Up Review Modes

1. Install and activate the Pro addon
2. Go to **Settings > Member Reviews > Pro**
3. Select your preferred mode
4. Save changes

**Note:** Changing modes doesn't delete existing reviews. They remain in the database but display differently.

## Verified Badges

Add trust signals to authentic reviews.

### Verification Types

| Type | How It Works |
|------|--------------|
| Admin Verified | Administrator manually marks a review |
| Tenure Verified | Auto-verify reviews from long-term members |
| Transaction Verified | Link to purchases (future feature) |

### Setting Up Verified Badges

1. Go to **Reviews** in WordPress admin
2. Edit a review
3. Check "Mark as Verified"
4. Save

### Auto-Verification by Tenure

1. Go to **Settings > Member Reviews > Pro**
2. Set "Auto-verify tenure" (e.g., 6 months)
3. Reviews from members older than this are auto-verified

### Badge Display

Verified badges appear next to the review date with a checkmark icon and tooltip.

## Flexible Criteria

Go beyond star ratings with thumbs and text fields.

### Criteria Types

| Type | Display | Use Case |
|------|---------|----------|
| Stars | 1-5 star rating | Quality, satisfaction |
| Thumbs | Up/Down buttons | Would recommend, would hire again |
| Text | Short text input | Project type, one word description |

### Creating Flexible Criteria

1. Go to **Settings > Member Reviews > Criteria**
2. Pro replaces the default builder with an enhanced version
3. Click **Add Criteria**
4. Enter a label
5. Select type (Stars, Thumbs, or Text)
6. Set weight for aggregate calculation
7. Toggle "Required" as needed
8. Drag to reorder
9. Click **Save Criteria**

### Weights

Weights affect aggregate rating calculation:
- Stars: Contribute to average based on weight
- Thumbs: Convert to 5 (up) or 1 (down) with weight
- Text: No contribution to aggregate (weight ignored)

**Example weights:**
- Quality (Stars, weight 2): Counts double
- Communication (Stars, weight 1): Normal weight
- Would Recommend (Thumbs, weight 0.5): Half weight

## REST API

Full CRUD API for reviews.

### Endpoints

```
GET    /wp-json/bupr/v1/reviews
POST   /wp-json/bupr/v1/reviews
GET    /wp-json/bupr/v1/reviews/{id}
PATCH  /wp-json/bupr/v1/reviews/{id}
DELETE /wp-json/bupr/v1/reviews/{id}
GET    /wp-json/bupr/v1/members/{id}/reviews
GET    /wp-json/bupr/v1/members/{id}/rating
```

### Authentication

Use WordPress Application Passwords or JWT for external access.

### Use Cases

- Mobile app integration
- Headless WordPress sites
- External dashboards
- Third-party integrations

See the full [REST API documentation](../developer-guide/rest-api.md).

## Endorsement Mode Details

### How Endorsements Work

1. **Visit a profile:** See "Endorse [Name]" button in header
2. **Select skills:** Choose from predefined skill list
3. **Add message:** Optional note with endorsement
4. **Submit:** Endorsement appears on their profile

### Skill Management

Admins can customize available skills:

1. Go to **Settings > Member Reviews > Pro**
2. Find "Endorsement Skills" section
3. Enable/disable default skills
4. Add custom skills

### Default Skills

- Leadership
- Communication
- Problem Solving
- Teamwork
- Creativity
- Technical Skills
- Project Management
- Time Management
- Adaptability
- Attention to Detail

### Pending Requests

Profile owners see pending endorsements in a separate tab:

1. **Pending Requests** subnav appears when requests exist
2. Review each request with skills and message
3. Click **Approve** or **Decline**
4. Approved endorsements join the main list

## Installation

### Requirements

- BuddyPress Member Reviews (free) v3.7.0 or higher
- Valid Pro license for updates

### Installation Steps

1. Purchase Pro from Wbcom Designs
2. Download the Pro addon ZIP
3. Go to **Plugins > Add New > Upload Plugin**
4. Upload and activate
5. Enter your license key in **Settings > Member Reviews > License**

### License Management

- License is required for updates, not for features
- Features work immediately after activation
- Expired licenses still work, just no updates

## Migration

### From Free to Pro

- Existing reviews are preserved
- Star ratings continue working
- Simply activate Pro and configure

### Between Modes

Switching modes:
- Existing reviews remain in database
- Display adapts to new mode
- No data is lost or deleted

## Pro Support

Pro license includes priority support:

- Direct email support
- Faster response times
- Feature request consideration

Contact: support@wbcomdesigns.com
