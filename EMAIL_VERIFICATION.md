# Email Verification Implementation Guide

## Overview

Complete email verification system using Laravel's **MustVerifyEmail** contract with:

- **Signed verification links** that expire after 60 minutes
- **Event-driven email delivery** (fired on user registration)
- **Queued notifications** for non-blocking email sending
- **Rate-limited resend** endpoint (6 per minute)
- **Middleware protection** to block unverified access

---

## How It Works: Step by Step

### 1️⃣ User Registers

User fills registration form and submits:

```
POST /register
{
  name: "John Doe",
  email: "john@example.com",
  password: "Password@123",
  password_confirmation: "Password@123"
}
```

### 2️⃣ User Created & Event Fired

RegisterController creates user:

```php
$user = User::create([...]);
event(new Registered($user));  // ← Fires verification email
```

### 3️⃣ Verification Email Sent

Event triggers `VerifyEmailNotification`:

- Email sent to user's address
- Contains **signed verification link**
- Link expires after **60 minutes**
- Sent via **queued job** (async)

**Email Content**:

```
From: noreply@ecommerce.com
To: john@example.com
Subject: Verify Email Address

Please verify your email address by clicking the button below:

[Verify Email Address Button]
↓
http://localhost:8000/email/verify/1/hash_signature_here?expires=...

This link expires in 1 hour.
```

### 4️⃣ User Receives Email

Email delivered to inbox (after mail service processes it)

### 5️⃣ User Clicks Verification Link

User clicks button or copy-pastes link:

```
GET http://localhost:8000/email/verify/1/hash_signature_here?expires=...
```

### 6️⃣ Signature Verified & Email Marked

Laravel verifies signature:

```php
// If signature valid:
$user->markEmailAsVerified();  // Sets email_verified_at = NOW()

// If signature invalid:
// → 403 Forbidden error
```

### 7️⃣ User Redirected to Dashboard

After verification:

```
REDIRECT to /dashboard
Message: "Email verified! Welcome!"
```

### 8️⃣ Protected Routes Accessible

With `verified` middleware, user can now access:

- Dashboard
- Profile
- Account settings
- All verified-only features

---

## Technical Implementation

### User Model Integration

[app/Models/User.php](app/Models/User.php)

```php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    // Laravel expects this method:
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification());
    }
}
```

**What MustVerifyEmail does:**

- Adds `markEmailAsVerified()` method
- Adds `hasVerifiedEmail()` method
- Requires implementation of `sendEmailVerificationNotification()`
- Enables `verified` middleware

### Verification Notification

[app/Notifications/VerifyEmailNotification.php](app/Notifications/VerifyEmailNotification.php)

```php
namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyEmailNotification extends VerifyEmail implements ShouldQueue
{
    // Queued: doesn't block response (async)

    // Inherited from VerifyEmail:
    // - Generates signed URL: $this->verificationUrl($notifiable)
    // - URL expires after toMailString()

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verify Email Address')
            ->line('Please verify your email address.')
            ->action('Verify Email Address', $this->verificationUrl($notifiable))
            ->line('This link expires in 1 hour.');
    }
}
```

**Key Features:**

- `ShouldQueue`: Notification sent asynchronously
- Inherits from `VerifyEmail`: Gets signed URL generation
- 60-minute expiration: Built into signed URL
- Professional template: MailMessage formatting

### Routes

[routes/web.php](routes/web.php)

```php
// Show verification notice (user sees this if email not verified)
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Handle verification link click
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();  // Verifies signature & marks email as verified
    return redirect('/dashboard')->with('status', 'Email verified!');
})
->middleware(['auth', 'signed', 'throttle:6,1'])  // Signed URL check + rate limit
->name('verification.verify');

// Resend verification email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'Verification link sent!');
})
->middleware(['auth', 'throttle:6,1'])  // Rate limited: 6 per minute
->name('verification.send');
```

**Route Parameters:**

- `{id}`: User ID
- `{hash}`: URL-safe hash of user's email
- `signed`: Middleware that verifies signature
- `throttle:6,1`: Rate limiting (6 requests per 1 minute)

### Email Verification Request

Laravel provides `EmailVerificationRequest` class:

```php
// Automatically:
// 1. Verifies signature (signed URL)
// 2. Checks expiration (60 min default)
// 3. Matches ID and hash to user

$request->fulfill();  // Sets email_verified_at = NOW()
```

### Middleware Protection

Use `verified` middleware on protected routes:

```php
// Requires email verification before access
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', ...);
    Route::get('/profile', ...);
    Route::post('/checkout', ...);
});

// If user not verified:
// → Redirects to /email/verify
```

---

## Views & Frontend

### Verification Notice Page

[resources/views/auth/verify-email.blade.php](resources/views/auth/verify-email.blade.php)

```html
<div class="bg-blue-50 border-l-4 border-blue-500 p-4">
    <h2 class="text-blue-800 font-bold">Verify Your Email Address</h2>
    <p class="text-blue-700">
        We've sent you a verification email. Click the link in the email to
        verify your account.
    </p>
</div>

<!-- Resend button -->
<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button type="submit">Resend Verification Email</button>
</form>

<!-- Success message -->
@if (session('status') == 'verification-link-sent')
<div class="bg-green-50 border-l-4 border-green-500 p-4">
    A new verification link has been sent to your email.
</div>
@endif
```

---

## Database Schema

### Users Table

```sql
CREATE TABLE users (
    id INTEGER PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    email_verified_at TIMESTAMP NULL,  ← Key column
    password VARCHAR(255),
    remember_token VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**email_verified_at:**

- `NULL` = Email not verified
- `TIMESTAMP` = Verified at this time (when user clicked link)

### Query Examples

```sql
-- Find unverified users (null email_verified_at)
SELECT * FROM users WHERE email_verified_at IS NULL;

-- Find verified users
SELECT * FROM users WHERE email_verified_at IS NOT NULL;

-- Manually verify a user (admin action)
UPDATE users SET email_verified_at = NOW() WHERE id = 1;

-- Find users verified in last 24 hours
SELECT * FROM users
WHERE email_verified_at >= DATE_SUB(NOW(), INTERVAL 1 DAY);
```

---

## How Signed URLs Work

### URL Generation

When notification created:

```php
$verificationUrl = URL::temporarySignedRoute(
    'verification.verify',
    now()->addHour(),  // Expires in 60 minutes
    ['id' => $user->id, 'hash' => sha1($user->email)]
);

// Generates:
// http://localhost:8000/email/verify/1/abc123hash?expires=1234567890&signature=sig_here
```

### URL Components

```
http://localhost:8000/email/verify/1/abc123hash?expires=1234567890&signature=xyz
                                    ↑ ↑          ↑              ↑
                                    | |          |              └─ Signature (HMAC-SHA256)
                                    | |          └─ Expiration timestamp
                                    | └─ Email hash
                                    └─ User ID
```

### Signature Verification

When link clicked, middleware validates:

```php
// 'signed' middleware verifies:
1. Signature matches URL + parameters + secret key
2. Current time < expiration time
3. If invalid → 403 Forbidden
```

**Security:**

- Cannot forge link (requires app secret key)
- Cannot modify ID/hash (signature would change)
- Cannot replay after expiration
- Server-side verification only

---

## Email Configuration

### Local Development (Log to File)

[.env](.env)

```bash
MAIL_MAILER=log
MAIL_LOG_CHANNEL=stack
```

**Result**: Verification emails logged to `storage/logs/laravel.log`

```
[2024-01-15 10:30:45] local.DEBUG: Message sent {"id":"abc123","from":...}
From: noreply@ecommerce.com
To: john@example.com
Subject: Verify Email Address
...
```

### Production (Real Email Service)

**Option 1: Mailtrap**

```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

**Option 2: SendGrid**

```bash
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=your_api_key
```

**Option 3: AWS SES**

```bash
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=us-east-1
```

---

## Testing Email Verification

### 1. Register User (Web)

```bash
Navigate to http://localhost:8000/register
Fill form with:
  Name: John Doe
  Email: john@example.com
  Password: MyPassword@123
  Confirmation: MyPassword@123
Click "Create Account"
```

**Expected**: Redirect to `/email/verify`

### 2. Check Email Sent

**With MAIL_MAILER=log:**

```bash
# Check logs for email content
tail -f storage/logs/laravel.log | grep -i "verify"
```

Look for:

```
From: noreply@ecommerce.com
To: john@example.com
Subject: Verify Email Address
```

Copy the verification link from the log

### 3. Visit Verification Link

```
Get the link from your email or logs
Example: http://localhost:8000/email/verify/1/abc123hash?expires=...&signature=...
```

**Expected**:

- Page redirects to `/dashboard`
- Shows "Email verified successfully!" message
- Can now access protected routes

### 4. Resend Email

```bash
# Navigate to http://localhost:8000/email/verify
# Click "Resend Verification Email"
# Check logs for new email with new signature
```

### 5. Test Rate Limiting

```bash
# Click "Resend" 7 times quickly
# After 6th click should still work
# 7th click should see: "Too Many Requests" error
# Throttle resets after 1 minute
```

---

## Troubleshooting

### Email Not Received

**Check:**

1. `.env` mail configuration correct?
2. Queue driver handling (`QUEUE_CONNECTION=sync` for testing)
3. `storage/logs/laravel.log` for errors
4. Firewall/ISP blocking outgoing email?
5. Mail service account active?

**Solution:**

```bash
# Use log driver for local testing
MAIL_MAILER=log

# Check logs
tail -f storage/logs/laravel.log

# If email in logs but not sending, configure real service
```

### Verification Link Expired

**Why:**

- Link expires after 60 minutes
- User clicked old link

**Solution:**

- Click "Resend Verification Email"
- Get new link from email
- New link valid for 60 minutes

**Change expiration:**

```php
// In VerifyEmailNotification.php
now()->addHour()  // Change to addMinutes(), addDays(), etc.
```

### Signature Invalid

**Why:**

- Link tampered with
- APP_KEY changed after link generation
- Wrong signature format (URL-encoded incorrectly)

**Solution:**

- Resend verification email
- Request new link (will have new signature)
- Ensure APP_KEY hasn't changed

### User Already Verified?

**Symptoms:**

- Link says "already verified"

**Check:**

```sql
SELECT email_verified_at FROM users WHERE email = 'john@example.com';
```

If `email_verified_at` is not NULL, user already verified

**Reset for testing:**

```sql
UPDATE users SET email_verified_at = NULL WHERE email = 'john@example.com';

# Then resend verification email
```

---

## Queue Configuration

Email notifications use Laravel's **queue system** for async delivery.

### Queue Driver

[.env](.env)

```bash
# Synchronous (for testing - sends immediately)
QUEUE_CONNECTION=sync

# Asynchronous (for production - queues jobs)
QUEUE_CONNECTION=database
# Requires: php artisan queue:table + migrate
```

### Running Queue Worker (Production)

```bash
# Process queued jobs
php artisan queue:work

# Run in daemon mode (watches for new jobs)
php artisan queue:work --daemon

# With specific queue
php artisan queue:work --queue=default
```

### Test Configuration

For local development:

```bash
# Use sync queue (emails send immediately, blocking request)
QUEUE_CONNECTION=sync

# This simplifies testing - no need to start queue worker
```

---

## Security Best Practices

✅ **Implemented:**

- Signed URLs (can't forge verification link)
- 60-minute link expiration (time-limited access)
- Rate limiting on resend (6 per minute - prevents spam)
- Email uniqueness at database level
- Password hashing (bcrypt)

⚠️ **For Production:**

1. **Email Verification Optional?**
    - Optional for some users (guests can browse)
    - Required for checkout/orders (verified)

2. **Add CAPTCHA**
    - Prevent automated registrations
    - Use Google reCAPTCHA v3

3. **Monitor Verification Success**
    - Log verification attempts
    - Alert if users not verifying after 24h
    - Send reminder emails

4. **Account Deactivation**
    - Auto-disable unverified accounts after 30 days
    - Queue background job to clean up

5. **Email Change**
    - Require verification when user changes email
    - Keep old email until new one verified

---

## API for Verification

### Register via API

```bash
POST /api/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "MyPassword@123",
    "password_confirmation": "MyPassword@123"
}
```

**Response:**

```json
{
    "success": true,
    "message": "Registration successful! Check your email to verify.",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "email_verified_at": null
    }
}
```

### Check Verification Status

```bash
GET /api/me
Authorization: Bearer your_jwt_token

# Response:
{
    "id": 1,
    "email": "john@example.com",
    "email_verified_at": "2024-01-15T10:35:00Z"
}
```

---

## Events & Listeners

### Event: Registered

Fired when user completes registration:

```php
// User Model dispatches:
event(new Registered($user));
```

**Listener**: Laravel's built-in `SendEmailVerificationNotification`

Automatically calls:

```php
$user->sendEmailVerificationNotification();
```

**Custom Implementation:**

```php
// app/Listeners/SendEmailVerificationNotification.php
public function handle(Registered $event)
{
    $event->user->sendEmailVerificationNotification();
}
```

### Event: Verified

Fired when user verifies email (optional tracking):

```php
// Could add:
event(new EmailVerified($user));

// Use case: Send welcome email, update newsletter signup, etc.
```

---

## Customization

### Change Email Template

[app/Notifications/VerifyEmailNotification.php](app/Notifications/VerifyEmailNotification.php)

```php
public function toMail($notifiable)
{
    return (new MailMessage)
        ->subject('Welcome! Verify Your Email')  // Custom subject
        ->greeting('Hello ' . $notifiable->name)  // Custom greeting
        ->line('Thank you for registering!')
        ->action('Confirm Your Email', $this->verificationUrl($notifiable))
        ->line('This link expires in 1 hour.')
        ->footer('© 2024 E-Commerce Store');
}
```

### Change Link Expiration

```php
// In User model or notification:
URL::temporarySignedRoute(
    'verification.verify',
    now()->addMinutes(30),  // 30 minutes instead of 1 hour
    ['id' => $user->id, 'hash' => sha1($user->email)]
);
```

### Add Custom Verification Logic

```php
// In EmailVerificationRequest or routes:
$request->fulfill();

// Add custom logic after verification
$user = $request->user();
$user->markEmailAsVerified();
$user->sendWelcomeEmail();  // Custom action
event(new UserVerified($user));  // Custom event
```

---

## Next Steps

1. ✅ Register user
2. ✅ Check email in logs
3. ✅ Click verification link
4. ✅ Verify user redirected to dashboard
5. **Configure real email service** (Mailtrap/SendGrid)
6. **Add reminder emails** for unverified users
7. **Monitor verification metrics**
8. **Add email change flow** (re-verify on email change)
