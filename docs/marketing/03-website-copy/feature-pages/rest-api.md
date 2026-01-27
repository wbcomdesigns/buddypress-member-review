# REST API

**Pro Feature** — Full CRUD API for reviews and member ratings.

---

## The Problem

Your community data is locked inside WordPress.

You want to:
- Build a mobile app that shows reviews
- Display ratings on external pages
- Sync review data with a CRM
- Create a custom dashboard

Without an API, you'd need custom database queries or screen scraping.

---

## The Solution

Full REST API with standard WordPress authentication.

Create, read, update, delete reviews. Get member ratings. Filter and paginate results. All through documented endpoints.

---

## Quick Start

### Get All Reviews

```bash
GET /wp-json/bupr/v1/reviews
```

Response:
```json
{
  "reviews": [
    {
      "id": 123,
      "member_id": 45,
      "reviewer_id": 67,
      "rating": 4.5,
      "content": "Great to work with...",
      "criteria": {
        "Communication": 5,
        "Quality": 4
      },
      "verified": true,
      "date": "2026-01-15T10:30:00",
      "status": "publish"
    }
  ],
  "total": 150,
  "pages": 15
}
```

### Get Member's Rating

```bash
GET /wp-json/bupr/v1/members/45/rating
```

Response:
```json
{
  "member_id": 45,
  "average_rating": 4.7,
  "review_count": 23,
  "criteria_averages": {
    "Communication": 4.9,
    "Quality": 4.5
  }
}
```

---

## Endpoint Reference

### Reviews

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/bupr/v1/reviews` | List reviews (filterable) |
| `GET` | `/bupr/v1/reviews/{id}` | Get single review |
| `POST` | `/bupr/v1/reviews` | Create new review |
| `PATCH` | `/bupr/v1/reviews/{id}` | Update review |
| `DELETE` | `/bupr/v1/reviews/{id}` | Delete review |

### Members

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/bupr/v1/members/{id}/reviews` | Reviews for a member |
| `GET` | `/bupr/v1/members/{id}/rating` | Aggregate rating |

### Review Actions

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/bupr/v1/reviews/{id}/reply` | Add owner reply |
| `POST` | `/bupr/v1/reviews/{id}/report` | Report review |

---

## Filtering & Pagination

### List Reviews with Filters

```bash
GET /wp-json/bupr/v1/reviews?member_id=45&status=publish&per_page=10&page=2
```

**Available filters:**
- `member_id` — Reviews for specific member
- `reviewer_id` — Reviews by specific reviewer
- `status` — publish, pending, draft
- `verified` — true/false
- `min_rating` — Minimum star rating
- `max_rating` — Maximum star rating
- `after` — Reviews after date (ISO 8601)
- `before` — Reviews before date (ISO 8601)

**Pagination:**
- `per_page` — Results per page (default: 10, max: 100)
- `page` — Page number

---

## Creating Reviews

```bash
POST /wp-json/bupr/v1/reviews
Content-Type: application/json
Authorization: Bearer {token}

{
  "member_id": 45,
  "content": "Excellent work on our project...",
  "criteria": {
    "Communication": 5,
    "Quality": 5,
    "Timeliness": 4
  },
  "anonymous": false
}
```

Response:
```json
{
  "id": 124,
  "member_id": 45,
  "reviewer_id": 67,
  "rating": 4.67,
  "content": "Excellent work on our project...",
  "status": "pending",
  "created": "2026-01-27T14:22:00"
}
```

**Note:** Reviews respect your moderation settings. If admin approval is required, status will be "pending" until approved.

---

## Authentication

### Public Endpoints (No Auth)
- `GET /reviews` — List public reviews
- `GET /reviews/{id}` — Single public review
- `GET /members/{id}/reviews` — Member's public reviews
- `GET /members/{id}/rating` — Member's rating

### Protected Endpoints (Auth Required)
- `POST /reviews` — Create review
- `PATCH /reviews/{id}` — Update review
- `DELETE /reviews/{id}` — Delete review
- `POST /reviews/{id}/reply` — Add owner reply
- `POST /reviews/{id}/report` — Report review

### Auth Methods

Standard WordPress REST authentication:
- **Application Passwords** (WordPress 5.6+)
- **Cookie auth** (for logged-in AJAX requests)
- **OAuth 1.0a** (with plugin)
- **JWT** (with plugin)

---

## Use Cases

### Mobile App

Build a native iOS/Android app showing member profiles with reviews.

```javascript
// Fetch member rating for profile screen
const response = await fetch(
  `${siteUrl}/wp-json/bupr/v1/members/${memberId}/rating`
);
const rating = await response.json();

// Display: ★★★★☆ (4.7) - 23 reviews
```

### External Dashboard

Aggregate review metrics for admin dashboard outside WordPress.

```javascript
// Get all reviews this month
const reviews = await fetch(
  `${siteUrl}/wp-json/bupr/v1/reviews?after=2026-01-01&per_page=100`
);

// Calculate metrics
const avgRating = reviews.reduce((sum, r) => sum + r.rating, 0) / reviews.length;
const verifiedCount = reviews.filter(r => r.verified).length;
```

### CRM Integration

Sync high-rated members to your CRM as leads.

```javascript
// Find members with 4.5+ rating and 5+ reviews
const topMembers = await fetch(
  `${siteUrl}/wp-json/bupr/v1/reviews?min_rating=4.5`
);

// Group by member, filter by review count, push to CRM
```

### Third-Party Display

Show reviews on external marketing sites.

```javascript
// Embed latest reviews on landing page
const latestReviews = await fetch(
  `${siteUrl}/wp-json/bupr/v1/reviews?status=publish&verified=true&per_page=5`
);
```

---

## Rate Limiting

Standard WordPress REST rate limits apply. No additional restrictions from the plugin.

For high-volume applications, implement caching on your end.

---

## Error Handling

Standard HTTP status codes:

| Code | Meaning |
|------|---------|
| 200 | Success |
| 201 | Created |
| 400 | Bad request (validation failed) |
| 401 | Unauthorized (auth required) |
| 403 | Forbidden (insufficient permissions) |
| 404 | Not found |
| 429 | Too many requests |

Error response format:
```json
{
  "code": "rest_cannot_create",
  "message": "You cannot review yourself.",
  "data": {
    "status": 403
  }
}
```

---

## Full Documentation

Complete API reference with request/response examples:
https://docs.wbcomdesigns.com/docs/buddypress-member-review/developer-guide/rest-api/

---

## Get REST API Access

Included in all Pro licenses:

| Plan | Sites | Price/Year |
|------|-------|------------|
| Single | 1 | $69 |
| 5-License | 5 | $129 |
| Starter | 25 | $199 |
| Developer | Unlimited | $299 |

**Purchase Pro:** https://wbcomdesigns.com/downloads/buddypress-member-reviews-pro

**Requires:** BuddyPress Member Reviews (free) v3.7.0+
