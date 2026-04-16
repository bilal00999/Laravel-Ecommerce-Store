# Registration Testing Guide

## Quick Start

### Prerequisites

- PHP 8.3+ installed and in PATH
- Composer dependencies installed
- SQLite database configured
- Laravel environment set up

### Run Registration Tests

```bash
# Navigate to project
cd c:\laragon\www\ecommerce_store

# Run all registration tests
php artisan test tests/Feature/RegistrationTest.php

# Run with verbose output
php artisan test tests/Feature/RegistrationTest.php --verbose

# Run specific test
php artisan test tests/Feature/RegistrationTest.php --filter=test_user_can_register

# Run with real-time output
php artisan test tests/Feature/RegistrationTest.php --parallel
```

---

## Test Cases (13 Total)

### ✅ Form & Validation Tests

#### 1. **test_registration_page_is_accessible**

- **What**: Verify `/register` page loads
- **Status**: GET /register → 200 OK
- **Expected View**: `auth.register`
- **Run**: `php artisan test --filter=test_registration_page_is_accessible`

#### 2. **test_user_can_register**

- **What**: Complete successful registration
- **Steps**:
    1. POST valid data to `/register`
    2. User created in database
    3. User authenticated
    4. Redirected to `/email/verify`
    5. Registered event fired
- **Data**:
    ```
    name: John Doe
    email: john@example.com
    password: Password@123
    password_confirmation: Password@123
    agree: true
    ```
- **Assertions**:
    - ✅ Database has user with email
    - ✅ User is authenticated
    - ✅ Redirected to /email/verify
    - ✅ Registered event dispatched
- **Expected**: PASS (201 Created behavior)

#### 3. **test_registration_fails_with_duplicate_email**

- **What**: Reject duplicate email
- **Setup**: Create user with email john@example.com
- **Attempt**: Register another user with same email
- **Expected**: ❌ 422 Unprocessable Entity
- **Error**: `email` has validation error
- **Database**: No second user created

#### 4. **test_registration_fails_with_mismatched_passwords**

- **What**: Reject mismatched password confirmation
- **Data**:
    ```
    password: Password@123
    password_confirmation: DifferentPassword@123
    ```
- **Expected**: ❌ 422 Validation Error
- **Error**: `password` field failed 'confirmed' rule
- **Database**: No user created

#### 5. **test_registration_fails_with_weak_password**

- **What**: Enforce password strength rules
- **Weak Password**: `weakpass`
- **Missing**: Uppercase, number, special character
- **Expected**: ❌ 422 Validation Error
- **Error**: Password doesn't meet `Rules\Password::defaults()` requirements
- **Future**: Show specific missing requirements to user
- **Database**: No user created

#### 6. **test_registration_fails_with_invalid_email**

- **What**: Reject malformed email
- **Invalid Email**: `not-an-email`
- **Expected**: ❌ 422 Validation Error
- **Error**: `email` fails email format validation
- **Database**: No user created

#### 7. **test_registration_fails_with_missing_name**

- **What**: Require name field
- **Data**: Omit `name` field
- **Expected**: ❌ 422 Validation Error
- **Error**: `name` field is required
- **Database**: No user created

### 🔐 Security Tests

#### 8. **test_password_is_hashed**

- **What**: Verify password stored as bcrypt hash, not plaintext
- **User Created**:
    ```
    email: john@example.com
    password (plaintext): Password@123
    ```
- **Database Check**: `users.password` field
- **Assertions**:
    - ✅ Password != "Password@123" (not plaintext)
    - ✅ Hash::check() returns true (valid bcrypt)
    - ✅ Starts with "$2" (bcrypt signature)
- **Expected**: PASS - Password securely hashed

#### 9. **test_is_admin_is_false_for_new_users**

- **What**: New registrations default to non-admin
- **User Created**: Standard registration
- **Database Check**: `users.is_admin`
- **Assertions**:
    - ✅ is_admin = false (0)
- **Security**: Prevents privilege escalation
- **Expected**: PASS - New users are not admins

### 📧 Email Verification Tests

#### 10. **test_email_verification_notice_shows**

- **What**: Verify `/email/verify` page displays when unverified
- **Setup**: Create unverified user
- **Action**: GET `/email/verify` while authenticated
- **Expected**:
    - ✅ Status 200 OK
    - ✅ View `auth.verify-email` rendered
- **Content**: Shows "Verify your email address" message

#### 11. **test_unverified_user_redirected_to_verification**

- **What**: Block unverified users from protected routes
- **Setup**: Create unverified user
- **Action**: GET `/dashboard` while authenticated but unverified
- **Expected**:
    - ✅ Redirect to `/email/verify`
    - ❌ NOT allowed to access dashboard
- **Middleware**: `verified` middleware enforces this
- **Security**: Email verification required for access

### 🔌 API Tests

#### 12. **test_api_registration_with_valid_data**

- **What**: API endpoint registration works
- **Endpoint**: `POST /api/register`
- **Data**:
    ```json
    {
        "name": "API User",
        "email": "api@example.com",
        "password": "Password@123",
        "password_confirmation": "Password@123"
    }
    ```
- **Expected Response**: 201 Created
    ```json
    {
        "success": true,
        "message": "Registration successful! Please check your email to verify your account.",
        "user": {
            "id": 1,
            "name": "API User",
            "email": "api@example.com",
            "email_verified_at": null
        }
    }
    ```
- **Assertions**:
    - ✅ Status 201
    - ✅ JSON structure correct
    - ✅ User created in database
    - ✅ Registered event fired

#### 13. **test_api_registration_fails_with_duplicate_email**

- **What**: API rejects duplicate emails
- **Setup**: User exists with api@example.com
- **Attempt**: POST /api/register with same email
- **Expected Response**: 422 Unprocessable Entity
    ```json
    {
        "message": "The email has already been taken.",
        "errors": {
            "email": ["The email has already been taken."]
        }
    }
    ```
- **Assertions**:
    - ✅ Status 422
    - ✅ Validation error returned
    - ✅ No duplicate user created

---

## Manual Testing Checklist

### Web Registration Manual Tests

- [ ] Navigate to `http://localhost:8000/register`
- [ ] Form displays with all fields
- [ ] Submit with valid data
    ```
    Name: John Doe
    Email: john@test.com
    Password: Password@123
    Confirm: Password@123
    Agree: ✓
    ```
- [ ] Account created
- [ ] Redirected to /email/verify
- [ ] See verification notice
- [ ] See "Resend" button
- [ ] Try register with existing email
    - [ ] See validation error
    - [ ] No duplicate created
- [ ] Try mismatched passwords
    - [ ] See "passwords do not match" error
- [ ] Try weak password (e.g., "weak")
    - [ ] See password strength error
- [ ] Try invalid email (e.g., "notanemail")
    - [ ] See email format error

### API Registration Manual Tests

**Using cURL:**

```bash
# Valid registration
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "API Test",
    "email": "apitest@example.com",
    "password": "Password@123",
    "password_confirmation": "Password@123"
  }'

# Expected: 201 with user data

# Duplicate email
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Duplicate",
    "email": "apitest@example.com",
    "password": "Password@123",
    "password_confirmation": "Password@123"
  }'

# Expected: 422 validation error
```

**Using Postman:**

1. New request: POST http://localhost:8000/api/register
2. Headers: Content-Type: application/json
3. Body (raw JSON): Valid data
4. Send → Verify 201 response
5. Send again → Verify 422 duplicate error

---

## Database State After Tests

### Users Table After Registration

```sql
SELECT id, name, email, is_admin, email_verified_at FROM users;
```

**Expected**:

```
id | name           | email                  | is_admin | email_verified_at
1  | John Doe       | john@example.com       | 0        | NULL
2  | API User       | api@example.com        | 0        | NULL
```

All passwords should be bcrypt hashed (starts with `$2`)

---

## Debugging Failed Tests

### Test Fails: "User not created"

**Troubleshooting**:

- Check validation rules in RegisterController
- Verify User model can be instantiated
- Check database migration ran correctly
- Ensure table has all required columns

### Test Fails: "Redirect wrong location"

**Troubleshooting**:

- Check routes in routes/web.php
- Verify middleware configured correctly
- Check RegisterController store() method

### Test Fails: "Event not fired"

**Troubleshooting**:

- Ensure event(new Registered($user)) is called
- Check User model fires events on create
- Verify Registered event listener registered

### Test Fails: "Validation not working"

**Troubleshooting**:

- Check validation rules array in controller
- Verify Rules\Password::defaults() imported
- Check unique:users rule configured

---

## CI/CD Integration

### GitHub Actions Example

```yaml
name: Tests

on: [push]

jobs:
    test:
        runs-on: ubuntu-latest

        steps:
            - uses: actions/checkout@v3
            - uses: php-actions/composer@v6

            - name: Run Tests
              run: php artisan test tests/Feature/RegistrationTest.php
```

### Continuous Testing

- Run on every push to `main`
- Run on pull requests
- Generate coverage reports
- Block merge if tests fail

---

## Performance Notes

- Each test runs in ~50-200ms (depending on system)
- Tests use RefreshDatabase (clean DB per test)
- Total test suite time: ~5-10 seconds
- Parallel testing available with `--parallel`

---

## Next: Email Verification Testing

Tests verify email verification page displays, but actual email sending requires:

1. **Mail Configuration** in `.env`:

    ```bash
    MAIL_MAILER=log  # For testing (logs to storage/logs)
    ```

2. **Email Verification Test** (manual):
    - Register new user
    - Check `storage/logs/laravel.log` for email content
    - Verify signed URL format: `/email/verify/{id}/{hash}`
    - Click URL or copy-paste into browser
    - Verify email_verified_at timestamp set

3. **Resend Email Test**:
    - Visit `/email/verify`
    - Click "Resend Verification Email"
    - Check logs for new email (with new hash)
    - Verify old link still works (or shows "already verified")
