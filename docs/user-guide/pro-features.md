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
| Admin Management | Enhanced dashboard for review oversight |

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

---

## Verified Badges

Add trust signals to authentic reviews.

### Verification Types

| Type | How It Works |
|------|--------------|
| Admin Verified | Administrator manually marks a review |
| Tenure Verified | Auto-verify reviews from long-term members |
| Transaction Verified | Link to purchases (future feature) |

### Mode-Aware Badge Labels

Verified badges display context-aware text based on the active review mode:

| Review Mode | Badge Text | Tooltip |
|-------------|------------|---------|
| Ratings | "Verified Review" | ✓ Verified Review |
| Testimonials | "Verified Testimonial" | ✓ Verified Testimonial |
| Endorsements | "Verified Endorsement" | ✓ Verified Endorsement |

### Setting Up Verified Badges

1. Go to **Reviews** in WordPress admin
2. Edit a review
3. Check "Mark as Verified"
4. Save

### Auto-Verification by Tenure

1. Go to **Settings > Member Reviews > Pro**
2. Set "Auto-verify tenure" (e.g., 6 months)
3. Reviews from members older than this are auto-verified

### Badge Display Location

Verified badges appear inline after the review date:

```
Posted on January 26, 2026 ✓ Verified Review
```

The badge includes:
- Checkmark icon (✓)
- Mode-aware label text
- Hover tooltip with verification details

---

## Admin Review Management

Pro adds a comprehensive review management dashboard.

### Accessing the Dashboard

Go to **Reviews > Review Management** in WordPress admin.

### Dashboard Tabs

| Tab | Purpose |
|-----|---------|
| Overview | Stats dashboard with review counts |
| Verified Reviews | Filter and manage verified reviews |
| Flagged Reviews | Handle flagged reviews with reasons |
| Settings | Quick access to Pro settings |

### Stats Dashboard

The Overview tab displays:
- Total reviews (published, pending, draft)
- Verified review count
- Flagged review count
- Average rating

### Review Row Actions

Each review in the list has these actions:

| Action | What It Does |
|--------|--------------|
| Verify | Mark review as verified |
| Unverify | Remove verification |
| Feature | Pin review (future display) |
| Unfeature | Remove featured status |
| Edit | Open edit screen |
| Trash | Move to trash |

### Bulk Actions

Select multiple reviews and:
- **Mark as Verified** - Add verification to selected
- **Remove Verification** - Clear verification from selected

### Review Edit Meta Boxes

When editing a single review, Pro adds:

**Verification Status Box:**
- Checkbox: Mark as Verified
- Display: Verification type, date, verified by

**Review Details Box:**
- Reviewer name and profile link
- Member reviewed
- All rating values per criterion
- Flag count and status (if flagged)

---

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

### Weights and Aggregate Calculation

Weights determine how each criterion affects the aggregate rating.

**Calculation Formula:**
```
Aggregate = Σ(rating × weight) / Σ(weights)
```

**How Each Type Contributes:**

| Type | Rating Value | Contribution |
|------|--------------|--------------|
| Stars | 1-5 as submitted | value × weight |
| Thumbs Up | Converts to 5 | 5 × weight |
| Thumbs Down | Converts to 1 | 1 × weight |
| Text | N/A | Excluded (weight ignored) |

**Example Calculation:**

| Criterion | Type | Value | Weight | Contribution |
|-----------|------|-------|--------|--------------|
| Quality | Stars | 4 | 2.0 | 4 × 2 = 8 |
| Communication | Stars | 5 | 1.0 | 5 × 1 = 5 |
| Would Recommend | Thumbs | Up (5) | 0.5 | 5 × 0.5 = 2.5 |
| Project Type | Text | "Website" | 1.0 | Excluded |

**Aggregate = (8 + 5 + 2.5) / (2 + 1 + 0.5) = 15.5 / 3.5 = 4.43**

### Custom Criteria in Edit Review Modal

When editing a review with custom criteria:

1. Stars show the same star selector as the form
2. Thumbs show Up/Down toggle buttons
3. Text shows a text input field

Values are pre-populated from the original submission and can be modified.

---

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

### Admin Endpoints

```
POST   /wp-json/bupr/v1/reviews/{id}/verify
POST   /wp-json/bupr/v1/reviews/{id}/approve
POST   /wp-json/bupr/v1/reviews/{id}/feature
POST   /wp-json/bupr/v1/reviews/{id}/dismiss-flag
```

### Query Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `member_id` | int | Filter by reviewed member |
| `author_id` | int | Filter by reviewer |
| `verified` | bool | Filter by verification status |
| `flagged` | bool | Filter by flag status |
| `min_rating` | float | Minimum aggregate rating |
| `date_before` | string | Reviews before date (ISO 8601) |
| `date_after` | string | Reviews after date (ISO 8601) |
| `per_page` | int | Results per page (default: 10) |
| `page` | int | Page number |

### Response Schema

```json
{
  "id": 123,
  "member_id": 45,
  "author_id": 67,
  "content": "Great to work with!",
  "ratings": {
    "quality": 5,
    "communication": 4,
    "would_recommend": "up"
  },
  "aggregate_rating": 4.5,
  "date": "2026-01-26T10:30:00",
  "status": "publish",
  "verified": true,
  "verified_type": "admin",
  "verified_date": "2026-01-26T11:00:00",
  "verified_by": 1,
  "featured": false,
  "flag_status": "none",
  "flag_count": 0,
  "anonymous": false,
  "reply": {
    "content": "Thank you for your review!",
    "date": "2026-01-26T12:00:00"
  },
  "_links": {
    "self": "/wp-json/bupr/v1/reviews/123",
    "member": "/wp-json/bupr/v1/members/45",
    "author": "/wp-json/wp/v2/users/67"
  }
}
```

### Authentication

Use WordPress Application Passwords or JWT for external access.

### Use Cases

- Mobile app integration
- Headless WordPress sites
- External dashboards
- Third-party integrations

See the full [REST API documentation](../developer-guide/rest-api.md).

---

## Endorsement Mode Details

### How Endorsements Work

**Important:** The "Endorse [Name]" button only appears when Endorsements mode is active in Pro settings. It won't show in Ratings or Testimonials mode.

#### Giving an Endorsement

1. **Visit a profile:** See "Endorse [Name]" button in profile header (only on other users' profiles)
2. **Click button:** Opens skills selection modal
3. **Select skills:** Check skills you want to endorse (checkboxes)
4. **Add message (optional):** Write a note explaining your endorsement
5. **Submit:** Click "Submit Endorsement"
6. **Confirmation:** Success message shown, button disappears (endorsement visible in Endorsements tab)

#### Skills Selection Modal

The modal displays:
- Member's name at the top
- Grid of available skills (checkboxes)
- Optional message textarea
- Cancel and Submit buttons

**Modal Behavior:**
- Lightbox overlay (darkened background)
- Centered on screen
- Click outside to close
- Escape key to close
- Skills are alphabetically sorted

#### Requesting an Endorsement

Members can request endorsements on their own profile:

1. **Own profile:** See "Request Endorsement" button in header
2. **Click button:** Opens request modal
3. **Select skills:** Choose skills you want endorsed
4. **Add message:** Explain why you're requesting
5. **Submit:** Request sent to selected member

### Skill Management

Admins can customize available skills:

1. Go to **Settings > Member Reviews > Pro**
2. Find "Endorsement Skills" section
3. Enable/disable default skills using checkboxes
4. Add custom skills (one per line) in the text area
5. Save changes

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

### Pending Requests Tab

Profile owners manage pending endorsements via a dedicated subnav tab.

**Tab Visibility:**
- Only appears when pending requests exist
- Hidden when no pending requests
- Shows count badge: "Pending Requests (3)"

**Tab Location:**
- Under the main "Endorsements" tab as a subnav item
- Note: "Add Endorsement" subnav is hidden since endorsements use the header button flow

**Request Display:**
Each pending request shows:
- Requester's avatar and name
- Skills they want endorsed
- Their message (if provided)
- Request date
- Approve/Decline buttons

**Actions:**
- **Approve:** Endorsement is published, skills added to list
- **Decline:** Request removed, no endorsement created

### Endorsed Skills Display

Approved endorsements display as skill tags:

```
[Leadership] [Communication] [Problem Solving]
```

Each tag shows:
- Skill name
- Endorsement count (if multiple)
- Hover shows endorsers

---

## Installation

### Requirements

- BuddyPress Member Reviews (free) v3.7.0 or higher
- Valid Pro license for updates

### Installation Steps

1. Purchase Pro from [Wbcom Designs](https://wbcomdesigns.com/downloads/buddypress-member-reviews-pro)
2. Download the Pro addon ZIP
3. Go to **Plugins > Add New > Upload Plugin**
4. Upload and activate
5. Enter your license key in **Settings > Member Reviews > License**

### License Management

- License is required for updates, not for features
- Features work immediately after activation
- Expired licenses still work, just no updates

### Feature Gate

Pro features work based on plugin activation, not license:

```php
// Check if Pro addon is active
if ( class_exists( 'BUPR_Pro_Features' ) ) {
    // Pro features available
}

// Check license (for updates only)
if ( BUPR_Pro_Features::is_license_valid() ) {
    // Updates available
}
```

---

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

---

## Pro Support

Pro license includes priority support:

- Direct email support
- Faster response times
- Feature request consideration

Contact: support@wbcomdesigns.com
