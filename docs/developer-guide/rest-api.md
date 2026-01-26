# REST API

Full REST API for BuddyPress Member Reviews. **This feature requires the Pro addon.**

## Base URL

```
/wp-json/bupr/v1/
```

## Authentication

The API uses WordPress REST API authentication:

- **Cookie Auth**: For logged-in users on your site
- **Application Passwords**: For external applications (WordPress 5.6+)
- **JWT/OAuth**: Via third-party plugins

## Endpoints

### List Reviews

```
GET /bupr/v1/reviews
```

**Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `page` | int | 1 | Page number |
| `per_page` | int | 10 | Reviews per page (max 100) |
| `member_id` | int | - | Filter by reviewed member |
| `author_id` | int | - | Filter by reviewer |
| `status` | string | publish | Post status |
| `orderby` | string | date | Sort field (date, rating) |
| `order` | string | desc | Sort direction (asc, desc) |

**Response:**

```json
{
    "reviews": [
        {
            "id": 123,
            "title": "Great experience",
            "content": "Working with John was fantastic...",
            "author": {
                "id": 45,
                "name": "Jane Smith",
                "avatar": "https://..."
            },
            "member": {
                "id": 67,
                "name": "John Doe",
                "avatar": "https://..."
            },
            "ratings": {
                "communication": 5,
                "quality": 4,
                "timeliness": 5
            },
            "average_rating": 4.67,
            "date": "2024-01-15T10:30:00",
            "is_anonymous": false,
            "has_reply": true,
            "reply": {
                "content": "Thank you for the kind words!",
                "date": "2024-01-16T09:15:00"
            },
            "verified": true,
            "verified_type": "admin"
        }
    ],
    "total": 45,
    "pages": 5
}
```

### Get Single Review

```
GET /bupr/v1/reviews/{id}
```

**Response:** Same structure as single item in list.

### Create Review

```
POST /bupr/v1/reviews
```

**Required permissions:** Logged in, role allowed to write reviews.

**Body:**

```json
{
    "member_id": 67,
    "title": "Great experience",
    "content": "Working with John was fantastic...",
    "ratings": {
        "communication": 5,
        "quality": 4,
        "timeliness": 5
    },
    "anonymous": false
}
```

**Response:** Created review object with 201 status.

### Update Review

```
PATCH /bupr/v1/reviews/{id}
```

**Required permissions:** Review author or admin.

**Body:** Same as create (partial updates allowed).

### Delete Review

```
DELETE /bupr/v1/reviews/{id}
```

**Required permissions:** Review author or admin.

**Response:** 204 No Content on success.

### Get Member Reviews

```
GET /bupr/v1/members/{id}/reviews
```

**Parameters:** Same as list reviews.

**Response:** Same as list reviews, filtered to specific member.

### Get Member Rating

```
GET /bupr/v1/members/{id}/rating
```

**Response:**

```json
{
    "member_id": 67,
    "aggregate_rating": 4.5,
    "review_count": 23,
    "rating_breakdown": {
        "5": 15,
        "4": 5,
        "3": 2,
        "2": 1,
        "1": 0
    }
}
```

### Add Reply

```
POST /bupr/v1/reviews/{id}/reply
```

**Required permissions:** Must be the reviewed member.

**Body:**

```json
{
    "content": "Thank you for your feedback!"
}
```

### Flag Review

```
POST /bupr/v1/reviews/{id}/flag
```

**Required permissions:** Logged in, not the review author.

**Body:**

```json
{
    "reason": "spam"
}
```

**Valid reasons:** `spam`, `harassment`, `inappropriate`, `conflict`

## Error Responses

```json
{
    "code": "rest_forbidden",
    "message": "You do not have permission to do this.",
    "data": {
        "status": 403
    }
}
```

**Common error codes:**

| Code | Status | Description |
|------|--------|-------------|
| `rest_forbidden` | 403 | Permission denied |
| `rest_review_not_found` | 404 | Review doesn't exist |
| `rest_invalid_param` | 400 | Invalid parameter value |
| `rest_already_reviewed` | 400 | User already reviewed this member |
| `rest_cannot_review_self` | 400 | Cannot review yourself |

## Rate Limiting

No built-in rate limiting. Consider using a plugin like "Limit API Requests" for production.

## Code Examples

### JavaScript (Fetch)

```javascript
// Get reviews for a member
fetch('/wp-json/bupr/v1/members/67/reviews', {
    headers: {
        'X-WP-Nonce': wpApiSettings.nonce
    }
})
.then(response => response.json())
.then(data => console.log(data));
```

### JavaScript (jQuery)

```javascript
$.ajax({
    url: '/wp-json/bupr/v1/reviews',
    method: 'POST',
    beforeSend: function(xhr) {
        xhr.setRequestHeader('X-WP-Nonce', wpApiSettings.nonce);
    },
    data: JSON.stringify({
        member_id: 67,
        title: 'Great experience',
        content: 'Amazing work!',
        ratings: { quality: 5 }
    }),
    contentType: 'application/json',
    success: function(response) {
        console.log('Review created:', response);
    }
});
```

### PHP (wp_remote)

```php
$response = wp_remote_get(
    rest_url( 'bupr/v1/members/67/rating' ),
    array(
        'headers' => array(
            'Authorization' => 'Basic ' . base64_encode( 'user:app_password' ),
        ),
    )
);

$data = json_decode( wp_remote_retrieve_body( $response ), true );
```

### cURL

```bash
# Get member rating
curl -X GET "https://yoursite.com/wp-json/bupr/v1/members/67/rating" \
     -u "username:application_password"

# Create review
curl -X POST "https://yoursite.com/wp-json/bupr/v1/reviews" \
     -u "username:application_password" \
     -H "Content-Type: application/json" \
     -d '{"member_id":67,"title":"Great!","content":"Loved working together.","ratings":{"quality":5}}'
```

## Mobile App Integration

For mobile apps using the REST API:

1. **Authentication:** Use Application Passwords or JWT
2. **Caching:** Cache member ratings, invalidate on new review
3. **Offline:** Queue reviews locally, sync when online
4. **Images:** Avatar URLs are included in responses

## Webhooks

The REST API doesn't include webhooks, but you can trigger external services using the action hooks:

```php
add_action( 'bupr_member_review_after_review_insert', function( $review_id, $member_id ) {
    wp_remote_post( 'https://your-webhook.com/reviews', array(
        'body' => array(
            'review_id' => $review_id,
            'member_id' => $member_id,
            'site' => home_url(),
        ),
    ));
}, 10, 2 );
```
