# Troubleshooting

Common issues and solutions for BuddyPress Member Reviews.

## Reviews Tab Not Appearing

### Symptom

The Reviews tab does not show on member profiles.

### Possible Causes

**1. BuddyPress Not Active**

Ensure BuddyPress is installed and activated.

**Solution:**

```bash
Go to Plugins → Installed Plugins
Verify BuddyPress is active
```

**2. Profiles Component Disabled**

The Extended Profiles component must be enabled.

**Solution:**

```bash
Go to Settings → BuddyPress → Components
Enable "Extended Profiles"
Click "Save Settings"
```

**3. User Role Not Allowed**

Check if the displayed member's role is allowed to receive reviews.

**Solution:**

```bash
Go to Settings → BuddyPress Member Reviews → General Settings
Check "User roles allowed to get reviewed"
Ensure the member's role is selected
```

**4. Cache Not Cleared**

Page caching may show outdated content.

**Solution:**

```bash
Clear your caching plugin (WP Super Cache, W3 Total Cache, etc.)
Clear browser cache (Ctrl+Shift+Delete)
Hard refresh page (Ctrl+F5 or Cmd+Shift+R)
```

**5. Theme Compatibility**

Some themes override BuddyPress templates incorrectly.

**Solution:**

```bash
Switch to a default WordPress theme (Twenty Twenty-Four)
Check if Reviews tab appears
If yes, contact your theme developer
```

---

## Email Notifications Not Sending

### Symptom

Email notifications for new reviews are not delivered.

### Possible Causes

**1. WordPress Email Not Configured**

WordPress uses PHP mail() which may not work on all servers.

**Solution:**

Install an SMTP plugin:

```bash
Install WP Mail SMTP or Easy WP SMTP
Configure with your email provider credentials
Send test email to verify
```

**2. Setting Disabled**

Email notifications may be turned off.

**Solution:**

```bash
Go to Settings → BuddyPress Member Reviews → Notifications
Enable "Send email notifications"
Click "Save Changes"
```

**3. Server Blocking Emails**

Hosting providers may block outgoing mail.

**Solution:**

```bash
Check server logs at wp-content/debug.log
Contact your hosting provider
Ask if they block PHP mail() function
Request SMTP credentials
```

**4. Emails Going to Spam**

Emails may be flagged as spam by email providers.

**Solution:**

```bash
Check spam/junk folder
Add site email to contacts
Configure SPF/DKIM records (ask hosting provider)
Use transactional email service (SendGrid, Mailgun)
```

---

## Ratings Not Calculating

### Symptom

Aggregate rating shows 0 or incorrect value.

### Possible Causes

**1. Only Pending Reviews**

Only published reviews count toward aggregate rating.

**Solution:**

```bash
Go to Reviews → All Reviews (WordPress admin)
Check post_status of reviews
Publish any pending reviews
```

**2. Cache Issue**

User meta may be cached.

**Solution:**

```php
// Clear user meta cache
delete_user_meta( $user_id, 'bupr_aggregate_rating' );
delete_user_meta( $user_id, 'bupr_review_count' );

// Trigger recalculation by editing any review for that member
```

**3. Criteria Mismatch**

Reviews submitted with old criteria may have incomplete data.

**Solution:**

The plugin uses fallback for missing criteria. If ratings still incorrect:

```bash
Go to one of the member's reviews
Click Edit
Re-save without changes (triggers recalculation)
Check if aggregate rating updates
```

---

## Activity Not Posting

### Symptom

Reviews do not appear in BuddyPress Activity stream.

### Possible Causes

**1. Setting Disabled**

Activity posting may be turned off.

**Solution:**

```bash
Go to Settings → BuddyPress Member Reviews → General Settings
Enable "Post to Activity Stream"
Save settings
```

**2. Activity Component Disabled**

BuddyPress Activity component must be active.

**Solution:**

```bash
Go to Settings → BuddyPress → Components
Enable "Activity Streams"
Click "Save Settings"
```

**3. JavaScript Errors**

JS errors may prevent AJAX requests.

**Solution:**

```bash
Open browser Developer Tools (F12)
Go to Console tab
Look for JavaScript errors
Disable other plugins to find conflict
```

**4. Filter Blocking**

A plugin or theme may filter out review activities.

**Solution:**

Check for this filter in your theme/plugins:

```php
add_filter( 'bupr_allow_activity_posting', '__return_false' );
```

Remove it or modify the condition.

---

## Plugin Conflicts

### Symptom

Features break when certain plugins are active.

### Common Conflicts

**1. Caching Plugins**

Plugins like WP Super Cache, W3 Total Cache may cache AJAX responses.

**Solution:**

```bash
Exclude BuddyPress pages from caching
Add this to cache plugin settings:
- /members/*
- /activity/*
- wp-admin/admin-ajax.php
```

**2. Security Plugins**

Plugins like Wordfence, Sucuri may block AJAX requests.

**Solution:**

```bash
Check firewall logs in security plugin
Whitelist admin-ajax.php
Add your IP to whitelist
Disable "Block fake Googlebots" feature
```

**3. Optimization Plugins**

Plugins that minify/combine JS may break functionality.

**Solution:**

```bash
Exclude BuddyPress Member Reviews JS files:
- buddypress-member-review-public.js
- buddypress-member-review-admin.js
```

**4. Other BuddyPress Plugins**

Multiple plugins modifying profiles can conflict.

**Solution:**

```bash
Disable BuddyPress plugins one by one
Test after each deactivation
Identify conflicting plugin
Contact that plugin's support
```

---

## Mode Switching Cache Issue

### Symptom (Pro Only)

After switching from Free Mode to Review Mode or vice versa, old content still shows.

### Cause

Object cache not cleared after mode switch.

### Solution

**Clear Object Cache:**

If using Redis, Memcached, or similar:

```bash
wp cache flush
```

Or via plugin:

```bash
Go to your caching plugin settings
Click "Flush Cache" or "Purge All Cache"
```

**Clear Transients:**

```php
// Add to functions.php temporarily
delete_transient( 'bupr_global_settings' );
```

---

## Shortcode Showing No Members

### Symptom

`[bupr_display_top_members]` shortcode displays empty or no results.

### Possible Causes

**1. No Published Reviews**

Members must have published reviews to appear.

**Solution:**

```bash
Ensure some members have at least one published review
Check Reviews → All Reviews in WordPress admin
```

**2. Wrong Role Filter**

Shortcode may be filtering by role that has no reviewed members.

**Solution:**

```php
// Try without role filter first
[bupr_display_top_members number="10"]

// Or specify role
[bupr_display_top_members number="10" role="subscriber"]
```

**3. Minimum Rating Too High**

If you filter by minimum rating, it may exclude all members.

**Solution:**

```php
// Lower minimum rating
[bupr_display_top_members number="10" min_rating="3.0"]
```

---

## Widget Empty (Displayed Member)

### Symptom

"Displayed Member Reviews" widget shows nothing.

### Cause

This widget only displays on member profile pages, not sitewide.

### Solution

**Use the correct context:**

```bash
Widget works on: example.com/members/john/
Widget DOES NOT work on: homepage, posts, pages
```

**For sitewide display, use:**

- "Top Rated Members" widget (shows sitewide)
- `[bupr_display_top_members]` shortcode
- Custom query in your template

---

## Permission Errors

### Symptom

Users get "You don't have permission" errors when submitting reviews.

### Possible Causes

**1. Role Not Allowed to Review**

Check reviewer role permissions.

**Solution:**

```bash
Go to Settings → BuddyPress Member Reviews → General Settings
Check "User roles allowed to review"
Ensure reviewer's role is selected
```

**2. Self-Review Attempted**

Users cannot review themselves.

**Solution:**

This is expected behavior. Direct users to review other members.

**3. Already Reviewed**

If "one review per member" is enabled, users can't submit multiple reviews.

**Solution:**

```bash
Go to Settings → BuddyPress Member Reviews → General Settings
Check "Allow multiple reviews"
Or direct user to edit existing review
```

---

## Debug Mode

Enable WordPress debug mode to get detailed error information:

**Add to wp-config.php:**

```php
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', false );
```

**Check logs:**

```bash
wp-content/debug.log
```

Look for errors containing "bupr" or "review".

---

## Still Need Help?

If you've tried these solutions and still have issues:

**1. Gather Information:**

- WordPress version
- PHP version
- Plugin version
- Active plugins list
- Theme name and version
- Error messages from debug.log
- Steps to reproduce the issue

**2. Contact Support:**

- Free version: WordPress.org support forum
- Pro version: Submit ticket at wbcomdesigns.com/support

**3. Provide System Info:**

```bash
Go to Tools → Site Health → Info
Click "Copy site info to clipboard"
Include in your support request
```

---

## Related Documentation

- [FAQs](faqs.md)
- [Installation Guide](../getting-started/installation.md)
- [Settings Overview](../settings/general-settings.md)
