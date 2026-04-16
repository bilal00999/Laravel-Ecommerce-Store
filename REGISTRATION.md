# User Registration & Email Verification Guide

## Overview

Complete user registration flow with:

- **Registration form** with validation
- **Password hashing** using bcrypt
- **Email verification** using Laravel's MustVerifyEmail
- **Unique email constraint** at database level
- **Strong password requirements** (uppercase, lowercase, number, special char)
- **Both web & API endpoints**

---

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── RegisterController.php      # Registration handler (web & API)
│   │   └── AuthController.php          # Authentication handler
│   └── Notifications/
│       └── VerifyEmailNotification.php # Email verification email
│
├── Models/
│   └── User.php                        # Implements MustVerifyEmail
│
resources/
└── views/
    └── auth/
        ├── register.blade.php          # Registration form
        └── verify-email.blade.php      # Email verification notice

routes/
├── web.php                             # Web registration & verification routes
└── api.php                             # API registration endpoint

tests/
└── Feature/
    └── RegistrationTest.php            # 13 registration test cases
```

---

## Web Registration Flow

### Routes

```
GET  /register                          → Show registration form
POST /register                          → Process registration
GET  /email/verify                      → Show email verification notice
GET  /email/verify/{id}/{hash}          → Verify email (from email link)
POST /email/verification-notification   → Resend verification email
```

### How It Works

1. **User visits** `http://localhost:8000/register`
2. **Fills form** with: name, email, password, password confirmation
3. **Submits form** → `POST /register`
4. **Laravel validates** input and creates user
5. **Password hashed** with bcrypt
6. **Verification email sent** to user's email address
7. **User redirected** to `/email/verify` (verification notice)
8. **User checks email** and clicks verification link
9. **Link verified** → User redirected to dashboard
10. **User can now access** protected routes

### Validation Rules

```php
'name' => ['required', 'string', 'max:255']
'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users']
'password' => ['required', 'confirmed', Rules\Password::defaults()]
            // Min 8 chars, uppercase, lowercase, number, special char
'password_confirmation' => required (implicit with 'confirmed' rule)
```

### Testing in Browser

1. **Navigate to registration**: `http://localhost:8000/register`
2. **Fill in the form**:
    ```
    Full Name: John Doe
    Email: john@example.com
    Password: Password@123
    Confirm Password: Password@123
    ```
3. **Check the checkbox**: "I agree to Terms & Conditions"
4. **Click "Create Account"**
5. **Check email** for verification link
6. **Click link** to verify email
7. **Redirected to dashboard** (if verified), or see unverified message

---

## API Registration Flow

### Route

```
POST /api/register
```

### Request

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "Password@123",
    "password_confirmation": "Password@123"
}
```

### Response (201 Created)

```json
{
    "success": true,
    "message": "Registration successful! Please check your email to verify your account.",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "email_verified_at": null
    }
}
```

### Testing with cURL

```bash
# Register new user
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "Password@123",
    "password_confirmation": "Password@123"
  }'

# Response:
{
  "success": true,
  "message": "Registration successful! Please check your email to verify your account.",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "email_verified_at": null
  }
}
```

### Testing with Postman

1. **Create new request**: `POST http://localhost:8000/api/register`
2. **Headers tab**: Set `Content-Type: application/json`
3. **Body tab**: Select "raw" JSON and paste:
    ```json
    {
        "name": "John Doe",
        "email": "john@example.com",
        "password": "Password@123",
        "password_confirmation": "Password@123"
    }
    ```
4. **Click "Send"**

---

## Password Requirements

Passwords must have ALL of:

- ✅ Minimum 8 characters
- ✅ At least one uppercase letter (A-Z)
- ✅ At least one lowercase letter (a-z)
- ✅ At least one number (0-9)
- ✅ At least one special character (!@#$%^&\*...)

### Valid Passwords

- `Password@123`
- `SecurePass#2024`
- `MyP@ss123`

### Invalid Passwords

- `password123` (no uppercase, no special char)
- `PASSWORD@123` (no lowercase)
- `Pass@123` (only 8 chars - acceptable, but needs variety)
- `12345678` (no letters, no special char)

---

## Email Verification

### How It Works

1. **User registers** → `Registered` event fired
2. **Event listener** sends verification email
3. **Email contains** signed verification link with 60-minute expiration
4. **Link format**: `/email/verify/{id}/{hash}`
5. **User clicks link** → signature validated
6. **Email marked** as verified in database
7. **`email_verified_at`** set to current timestamp

### Verification Email Template

The email includes:

- Welcome message
- "Verify Email Address" button (clickable link)
- Expiration notice (60 minutes)
- Fallback message if using email client that doesn't render HTML

### Resend Verification Email

If user doesn't receive email:

1. **Visit verification notice**: `http://localhost:8000/email/verify`
2. **Click "Resend Verification Email"**
3. **New email sent** (throttled to 6 per minute)
4. **User checks email** for new link

---

## Key Features

### 1. Password Hashing

```php
// Passwords are automatically hashed with bcrypt
$user = User::create([
    'password' => Hash::make($validated['password']),
]);

// The User model casts password to 'hashed', so it's done automatically:
// In User.php: 'password' => 'hashed'
```

### 2. Email Uniqueness

```php
// Database level constraint
// Validation rule: 'unique:users'
// Prevents duplicate emails in database
```

### 3. MustVerifyEmail Contract

```php
// User model implements MustVerifyEmail interface
class User extends Authenticatable implements MustVerifyEmail
{
    // Must verify email before accessing certain routes
    // Dashboard requires: middleware('verified')
}
```

### 4. Email Verification Middleware

Protected routes use `verified` middleware:

```php
// routes/web.php
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', ...);
});

// If email_verified_at is null, redirect to /email/verify
```

---

## User Creation Flow

### Web Registration Controller

```php
// 1. Validate input
$validated = $request->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'email', 'unique:users'],
    'password' => ['required', 'confirmed', ...],
]);

// 2. Create user (password auto-hashed via model cast)
$user = User::create([
    'name' => $validated['name'],
    'email' => $validated['email'],
    'password' => Hash::make($validated['password']),
    'is_admin' => false,
]);

// 3. Fire Registered event (triggers verification email)
event(new Registered($user));

// 4. Login user
auth()->login($user);

// 5. Redirect to verification notice
return redirect()->route('verification.notice');
```

---

## Database Changes

### Users Table

```sql
-- Added by migration (already exists)
ALTER TABLE users ADD email_verified_at TIMESTAMP NULL;
```

When user verifies email:

```sql
UPDATE users SET email_verified_at = NOW() WHERE id = 1;
```

---

## Testing

### Run Registration Tests

```bash
# Run all registration tests
php artisan test tests/Feature/RegistrationTest.php

# Run specific test
php artisan test tests/Feature/RegistrationTest.php::test_user_can_register

# Run with verbose output
php artisan test tests/Feature/RegistrationTest.php --verbose
```

### Test Coverage

✅ Registration form accessibility  
✅ User can register with valid data  
✅ Duplicate email rejection  
✅ Mismatched password rejection  
✅ Weak password rejection  
✅ Invalid email rejection  
✅ Missing field rejection  
✅ Password hashing verification  
✅ API registration endpoint  
✅ API duplicate email rejection  
✅ New users not admin by default  
✅ Email verification notice display  
✅ Unverified users blocked from dashboard

---

## Error Handling

### Common Validation Errors

| Error                                    | Cause                      | Solution                          |
| ---------------------------------------- | -------------------------- | --------------------------------- |
| "Email has already been taken"           | Email exists               | Use different email               |
| "The passwords do not match"             | Confirmation doesn't match | Ensure passwords match            |
| "Password must contain uppercase letter" | No A-Z in password         | Add uppercase letter              |
| "The name field is required"             | Name is empty              | Enter full name                   |
| "The email field must be a valid email"  | Invalid email format       | Use valid email (name@domain.com) |

---

## Configuration

### Mail Configuration (for verification emails)

Update `.env`:

```bash
MAIL_MAILER=log                    # For local testing (logs to storage/logs)
# Or:
MAIL_MAILER=smtp                   # For production
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@ecommerce.com
MAIL_FROM_NAME="E-Commerce Store"
```

### Email Verification Notification

Custom notification in `app/Notifications/VerifyEmailNotification.php`:

- Customizable email subject
- Customizable email content
- 60-minute link expiration
- Signed link for security

---

## Security Best Practices

✅ **Implemented:**

- Password hashing with bcrypt (configurable rounds: 12)
- Unique email constraint at DB and validation level
- Email verification before accessing protected routes
- Signed verification links (cannot be forged)
- Rate limiting on verification link resend (6 per minute)
- Password confirmation requirement
- Strong password requirements (uppercase, lowercase, number, special char)

⚠️ **For Production:**

1. Set up real email service (SendGrid, Mailtrap, AWS SES)
2. Customize verification email template
3. Add CAPTCHA to registration form to prevent bots
4. Implement rate limiting on registration endpoint
5. Add phone number verification for 2FA
6. Log registration events for analytics
7. Send welcome email after verification
8. Add password reset flow
9. Keep verification link expiration short (60 minutes)

---

## Troubleshooting

**"Verification email not received"**

- Check `.env` MAIL settings
- In local development, check `storage/logs/laravel.log`
- Click "Resend Verification Email" button
- Check spam/junk folder

**"Verification link expired"**

- Link expires after 60 minutes
- Click "Resend Verification Email" to get new link

**"Cannot verify email - signature invalid"**

- Link was tampered with
- Resend verification email

**"Unverified user blocked from dashboard"**

- Expected behavior (security feature)
- Visit `/email/verify` to see verification notice
- Resend or click verification email link

---

## Next Steps

1. **Customize email template**: Edit `app/Notifications/VerifyEmailNotification.php`
2. **Add CAPTCHA**: Prevent automated registrations
3. **Send welcome email**: After email verification
4. **Add profile completion**: Redirect verified users to complete profile
5. **Implement password reset**: `/forgot-password` flow
6. **Add social login**: OAuth providers (Google, GitHub, Facebook)
7. **Monitor registrations**: Log suspicious activity
