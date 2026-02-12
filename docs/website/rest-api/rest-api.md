# REST API Reference

**PRO Feature** - Requires BuddyPress Member Reviews Pro addon

## Overview

The Member Reviews REST API provides programmatic access to review data, ratings, and moderation features. Build mobile apps, external dashboards, or headless WordPress sites with full review functionality.

**Base URL:** `/wp-json/bupr/v1/`

## Authentication

The API supports three authentication methods:

### Cookie Authentication

Use WordPress cookie authentication for logged-in sessions. Requires nonce for write operations.

**Best for:** Admin interfaces, same-origin requests

**Setup:** Include `X-WP-Nonce` header with REST API nonce

### Application Passwords

Create application-specific passwords for API access without exposing main credentials.

**Best for:** Mobile apps, third-party integrations

**Setup:**

1. Go to **Users → Profile**
2. Scroll to **Application Passwords**
3. Enter app name and click **Add New**
4. Use Basic Auth with username and generated password

### JWT/OAuth (Third-Party Plugins)

Install JWT or OAuth plugins for token-based authentication.

**Best for:** Multi-user applications, distributed systems

**Setup:** Install a JWT/OAuth plugin compatible with WordPress REST API

## Endpoints

### GET /reviews

List all reviews with filtering and pagination.

**Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `page` | integer | 1 | Page number |
| `per_page` | integer | 10 | Reviews per page (max: 100) |
| `member_id` | integer | - | Filter by reviewed member ID |
| `author_id` | integer | - | Filter by review author ID |
| `status` | string | `approved` | Filter by status: `approved`, `pending`, `spam` |
| `verified` | boolean | - | Filter by verification status |
| `min_rating` | float | - | Minimum rating (1.0-5.0) |
| `date_start` | string | - | Start date (ISO 8601: `2024-01-01`) |
| `date_end` | string | - | End date (ISO 8601: `2024-12-31`) |
| `orderby` | string | `date` | Sort by: `date`, `rating`, `random` |
| `order` | string | `desc` | Sort direction: `asc`, `desc` |

**Example Request:**

```bash
curl -X GET "https://example.com/wp-json/bupr/v1/reviews?member_id=5&verified=true&per_page=20" \
  -H "Authorization: Basic base64(username:app_password)"
```

**Example Response:**

```json
{
  "reviews": [
    {
      "id": 123,
      "member_id": 5,
      "member_name": "John Doe",
      "author_id": 42,
      "author_name": "Jane Smith",
      "rating": 4.5,
      "title": "Excellent collaboration",
      "content": "John was professional and delivered quality work.",
      "criteria": [
        {
          "label": "Communication",
          "type": "stars",
          "value": 5,
          "weight": 1.5
        },
        {
          "label": "Quality",
          "type": "stars",
          "value": 4,
          "weight": 2.0
        }
      ],
      "is_verified": true,
      "verification_type": "admin",
      "is_featured": false,
      "is_anonymous": false,
      "status": "approved",
      "date_created": "2024-01-15T10:30:00",
      "date_modified": "2024-01-15T10:30:00",
      "_links": {
        "self": "https://example.com/wp-json/bupr/v1/reviews/123",
        "member": "https://example.com/wp-json/bupr/v1/members/5",
        "author": "https://example.com/wp-json/wp/v2/users/42"
      }
    }
  ],
  "pagination": {
    "total": 45,
    "pages": 3,
    "page": 1,
    "per_page": 20
  }
}
```

### GET /reviews/{id}

Retrieve a single review with full details.

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `id` | integer | Review ID (required) |

**Example Request:**

```bash
curl -X GET "https://example.com/wp-json/bupr/v1/reviews/123" \
  -H "Authorization: Basic base64(username:app_password)"
```

**Example Response:**

```json
{
  "id": 123,
  "member_id": 5,
  "member_name": "John Doe",
  "member_avatar": "https://example.com/avatar.jpg",
  "author_id": 42,
  "author_name": "Jane Smith",
  "author_avatar": "https://example.com/author.jpg",
  "rating": 4.5,
  "title": "Excellent collaboration",
  "content": "John was professional and delivered quality work on time.",
  "criteria": [
    {
      "label": "Communication",
      "type": "stars",
      "value": 5,
      "weight": 1.5
    }
  ],
  "is_verified": true,
  "verification_type": "admin",
  "is_featured": false,
  "is_anonymous": false,
  "status": "approved",
  "date_created": "2024-01-15T10:30:00",
  "date_modified": "2024-01-15T10:30:00"
}
```

### POST /reviews

Create a new review.

**Permissions:** User must be logged in and have permission to review the target member.

**Request Body:**

```json
{
  "member_id": 5,
  "rating": 4.5,
  "title": "Great experience",
  "content": "Highly professional and skilled.",
  "criteria": [
    {
      "label": "Communication",
      "value": 5
    },
    {
      "label": "Quality",
      "value": 4
    }
  ],
  "is_anonymous": false
}
```

**Example Request:**

```bash
curl -X POST "https://example.com/wp-json/bupr/v1/reviews" \
  -H "Authorization: Basic base64(username:app_password)" \
  -H "Content-Type: application/json" \
  -d '{
    "member_id": 5,
    "rating": 4.5,
    "title": "Great experience",
    "content": "Highly professional and skilled."
  }'
```

**Example Response:**

```json
{
  "id": 124,
  "status": "pending",
  "message": "Review submitted successfully. Awaiting approval."
}
```

### PATCH /reviews/{id}

Update an existing review.

**Permissions:** Review author or administrator.

**Request Body:**

```json
{
  "rating": 5.0,
  "title": "Updated title",
  "content": "Updated review content.",
  "criteria": [
    {
      "label": "Communication",
      "value": 5
    }
  ]
}
```

**Example Request:**

```bash
curl -X PATCH "https://example.com/wp-json/bupr/v1/reviews/123" \
  -H "Authorization: Basic base64(username:app_password)" \
  -H "Content-Type: application/json" \
  -d '{
    "rating": 5.0,
    "title": "Updated title"
  }'
```

**Example Response:**

```json
{
  "id": 123,
  "status": "updated",
  "message": "Review updated successfully."
}
```

### DELETE /reviews/{id}

Delete a review permanently.

**Permissions:** Review author or administrator.

**Example Request:**

```bash
curl -X DELETE "https://example.com/wp-json/bupr/v1/reviews/123" \
  -H "Authorization: Basic base64(username:app_password)"
```

**Example Response:**

```json
{
  "id": 123,
  "status": "deleted",
  "message": "Review deleted successfully."
}
```

### GET /members/{id}/reviews

Get all reviews for a specific member.

**Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `id` | integer | - | Member ID (required) |
| `page` | integer | 1 | Page number |
| `per_page` | integer | 10 | Reviews per page |
| `status` | string | `approved` | Filter by status |

**Example Request:**

```bash
curl -X GET "https://example.com/wp-json/bupr/v1/members/5/reviews?per_page=5" \
  -H "Authorization: Basic base64(username:app_password)"
```

**Example Response:**

```json
{
  "member_id": 5,
  "member_name": "John Doe",
  "reviews": [
    {
      "id": 123,
      "rating": 4.5,
      "title": "Excellent collaboration",
      "author_name": "Jane Smith",
      "date_created": "2024-01-15T10:30:00"
    }
  ],
  "pagination": {
    "total": 12,
    "pages": 3,
    "page": 1,
    "per_page": 5
  }
}
```

### GET /members/{id}/rating

Get aggregate rating statistics for a member.

**Example Request:**

```bash
curl -X GET "https://example.com/wp-json/bupr/v1/members/5/rating" \
  -H "Authorization: Basic base64(username:app_password)"
```

**Example Response:**

```json
{
  "member_id": 5,
  "member_name": "John Doe",
  "aggregate_rating": 4.3,
  "total_reviews": 12,
  "verified_reviews": 8,
  "rating_distribution": {
    "5": 6,
    "4": 4,
    "3": 1,
    "2": 1,
    "1": 0
  },
  "criteria_averages": [
    {
      "label": "Communication",
      "average": 4.5
    },
    {
      "label": "Quality",
      "average": 4.2
    }
  ]
}
```

### POST /reviews/{id}/verify

Verify a review (admin only).

**Permissions:** Administrator or moderator.

**Example Request:**

```bash
curl -X POST "https://example.com/wp-json/bupr/v1/reviews/123/verify" \
  -H "Authorization: Basic base64(username:app_password)"
```

**Example Response:**

```json
{
  "id": 123,
  "is_verified": true,
  "verification_type": "admin",
  "message": "Review verified successfully."
}
```

**Unverify:**

```bash
curl -X DELETE "https://example.com/wp-json/bupr/v1/reviews/123/verify" \
  -H "Authorization: Basic base64(username:app_password)"
```

### POST /reviews/{id}/approve

Approve a pending review (admin only).

**Permissions:** Administrator or moderator.

**Example Request:**

```bash
curl -X POST "https://example.com/wp-json/bupr/v1/reviews/123/approve" \
  -H "Authorization: Basic base64(username:app_password)"
```

**Example Response:**

```json
{
  "id": 123,
  "status": "approved",
  "message": "Review approved successfully."
}
```

**Unapprove (set to pending):**

```bash
curl -X POST "https://example.com/wp-json/bupr/v1/reviews/123/unapprove" \
  -H "Authorization: Basic base64(username:app_password)"
```

### POST /reviews/{id}/feature

Feature or unfeature a review (admin only).

**Permissions:** Administrator.

**Example Request:**

```bash
curl -X POST "https://example.com/wp-json/bupr/v1/reviews/123/feature" \
  -H "Authorization: Basic base64(username:app_password)"
```

**Example Response:**

```json
{
  "id": 123,
  "is_featured": true,
  "message": "Review featured successfully."
}
```

**Unfeature:**

```bash
curl -X DELETE "https://example.com/wp-json/bupr/v1/reviews/123/feature" \
  -H "Authorization: Basic base64(username:app_password)"
```

## Response Format

All responses follow a consistent JSON structure:

**Success Response:**

```json
{
  "data": {},
  "status": "success"
}
```

**Error Response:**

```json
{
  "code": "error_code",
  "message": "Human-readable error message",
  "status": 400
}
```

## Error Handling

Common error codes:

| Code | Status | Description |
|------|--------|-------------|
| `rest_forbidden` | 401 | Authentication required |
| `rest_forbidden_context` | 403 | Insufficient permissions |
| `rest_invalid_param` | 400 | Invalid request parameter |
| `bupr_review_not_found` | 404 | Review ID does not exist |
| `bupr_member_not_found` | 404 | Member ID does not exist |
| `bupr_cannot_review` | 403 | User cannot review this member |
| `bupr_already_reviewed` | 409 | User already reviewed this member |
| `bupr_invalid_rating` | 400 | Rating outside 1-5 range |

**Example Error Response:**

```json
{
  "code": "bupr_already_reviewed",
  "message": "You have already reviewed this member.",
  "data": {
    "status": 409,
    "existing_review_id": 123
  }
}
```

## Rate Limiting

Default rate limits apply:

- **Authenticated users:** 1000 requests/hour
- **Unauthenticated users:** 100 requests/hour

Rate limit headers included in responses:

```
X-RateLimit-Limit: 1000
X-RateLimit-Remaining: 995
X-RateLimit-Reset: 1642516800
```

## Use Cases

### Mobile App

Build native iOS/Android apps with review functionality:

- Display member profiles with ratings
- Submit new reviews from mobile
- Push notifications for review approvals
- Offline support with API caching

### External Dashboard

Create custom analytics dashboards:

- Aggregate rating trends over time
- Export review data to CSV/Excel
- Generate PDF reports
- Integration with BI tools

### Headless WordPress

Build decoupled frontends using React/Vue/Next.js:

- Fetch reviews via API
- Client-side filtering and sorting
- Real-time updates via webhooks
- Progressive web app support

### Third-Party Integrations

Connect reviews to external platforms:

- Sync reviews to CRM systems
- Post reviews to social media
- Email digest automation
- Zapier/Make.com workflows

## Technical Details

- Implements WordPress REST API standards
- Supports JSON and form-encoded requests
- CORS enabled for cross-origin requests
- Schema validation for all inputs
- Respects WordPress privacy settings

## Support

Need help with the REST API?

- Check our [Knowledge Base](https://docs.wbcomdesigns.com)
- Contact support at support@wbcomdesigns.com
