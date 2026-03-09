# Newsletter Double Opt-In Implementation

## Overview

The newsletter subscription system now implements double opt-in functionality to ensure email addresses are valid and users genuinely want to subscribe.

## How It Works

### 1. User Subscribes

When a user submits the newsletter subscription form using the `[newsletter-subscribe]` shortcode:

- Email is validated
- A verification token is generated
- Subscriber is added to the database with `status = 'pending'`
- A verification email is sent with a confirmation link

### 2. Email Verification

The verification email contains a unique link that:

- Is valid for 48 hours
- Contains a secure SHA-256 token
- When clicked, activates the subscription

### 3. Subscription Activation

When the user clicks the verification link:

- The token is validated
- Subscription status changes from `pending` to `active`
- A welcome email is sent
- The user is shown a confirmation page

## Database Schema

The `wp_reforestamos_subscribers` table includes:

```sql
- id: Unique subscriber ID
- email: Email address (unique)
- name: Subscriber name (optional)
- status: 'pending', 'active', or 'unsubscribed'
- verification_token: SHA-256 token for email verification
- subscribed_at: Initial subscription timestamp
- verified_at: Verification completion timestamp
- unsubscribed_at: Unsubscription timestamp (if applicable)
```

## Shortcode Usage

```
[newsletter-subscribe]
```

### Shortcode Attributes

- `title`: Form title (default: "Suscríbete a nuestro boletín")
- `button_text`: Submit button text (default: "Suscribirse")
- `show_name`: Show name field (default: "yes")

### Examples

```
[newsletter-subscribe title="Join Our Newsletter" button_text="Subscribe" show_name="no"]
```

## Email Flow

### 1. Verification Email

**Subject:** Confirma tu suscripción - Reforestamos México

**Content:**
- Greeting with user's name
- Explanation of subscription
- Verification link (expires in 48 hours)
- Note that email can be ignored if not requested

### 2. Welcome Email

**Subject:** Confirmación de Suscripción - Reforestamos México

**Content:**
- Thank you message
- Information about what to expect
- Sent after successful verification

## Security Features

1. **Nonce Verification**: All AJAX requests are protected with WordPress nonces
2. **Email Validation**: Emails are validated using WordPress `is_email()` function
3. **Secure Tokens**: SHA-256 hashing with time-based salt
4. **Token Expiration**: Verification links expire after 48 hours
5. **Sanitization**: All user input is sanitized before database insertion

## Edge Cases Handled

1. **Already Subscribed (Active)**: Error message shown
2. **Already Subscribed (Pending)**: Verification email is resent
3. **Previously Unsubscribed**: New verification process initiated
4. **Expired Token**: Clear error message with instructions to resubscribe
5. **Already Verified**: Friendly message indicating subscription is active

## Frontend JavaScript

The subscription form uses AJAX for a seamless user experience:

```javascript
// Located in: assets/js/frontend.js
function initNewsletterSubscription() {
    $('.newsletter-subscribe-form').on('submit', function(e) {
        // Prevents page reload
        // Shows loading state
        // Displays success/error messages
    });
}
```

## Testing the Implementation

### Manual Testing Steps

1. **Subscribe with new email**
   - Fill out the form
   - Check for success message
   - Verify email is received
   - Click verification link
   - Confirm activation message

2. **Subscribe with existing active email**
   - Should show "already subscribed" error

3. **Subscribe with pending email**
   - Should resend verification email

4. **Test expired token**
   - Subscribe and wait 48+ hours
   - Click verification link
   - Should show expiration error

5. **Test invalid token**
   - Manually modify token in URL
   - Should show invalid token error

## Requirements Satisfied

- **Requirement 8.6**: ✅ Provides subscription form shortcode `[newsletter-subscribe]`
- **Requirement 8.7**: ✅ Validates email and stores in database with double opt-in

## Future Enhancements

- Add reCAPTCHA for spam protection
- Implement subscription preferences (frequency, topics)
- Add GDPR compliance checkbox
- Create admin dashboard for managing pending subscriptions
- Add email template customization in admin
