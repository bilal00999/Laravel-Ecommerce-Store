# E-Commerce Store - Authentication & Registration System

## Project Overview

Full-featured Laravel 13 e-commerce application with:

- ✅ **Session-based web authentication** (traditional login with cookies)
- ✅ **JWT API authentication** (token-based API with tymon/jwt-auth)
- ✅ **User registration** (web form + API endpoint)
- ✅ **Email verification** (signed URLs, 60-min expiration)
- ✅ **Admin system** (role-based access control)
- ✅ **Role-based middleware** (web + API guards)

---

## Quick Start

### Prerequisites

- PHP 8.3.30 (Laragon)
- SQLite database
- Composer dependencies installed
- Laravel 13 framework

### Installation

```bash
# 1. Navigate to project
cd c:\laragon\www\ecommerce_store

# 2. Install dependencies
composer install

# 3. Create environment file
cp .env.example .env

# 4. Generate APP_KEY
php artisan key:generate

# 5. Generate JWT_SECRET
php artisan jwt:secret

# 6. Run database migrations
php artisan migrate --force

# 7. (Optional) Create test data
php artisan tinker
# Then run:
User::factory()->create(['email' => 'test@example.com', 'password' => bcrypt('password123'), 'is_admin' => false]);
User::factory()->create(['email' => 'admin@example.com', 'password' => bcrypt('password123'), 'is_admin' => true]);
```

### Start Development Server

```bash
php artisan serve
```

Browse to: `http://localhost:8000`

---

## Core Features

### 1. Web Authentication (Session-Based)

**Login Route**: `GET /login` → Shows login form

```html
<!-- resources/views/auth/login.blade.php -->
<form method="POST" action="/login">
    @csrf
    <input name="email" type="email" required />
    <input name="password" type="password" required />
    <input name="remember" type="checkbox" /> Remember me
    <button>Sign In</button>
</form>
```

**Test Credentials:**

```
Email: test@example.com
Password: password123
```

**Features:**

- ✅ CSRF protection
- ✅ "Remember me" (30 days)
- ✅ Session persistence
- ✅ Logout with session invalidation

---

### 2. API Authentication (JWT)

**Login Endpoint**: `POST /api/login`

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

**Response:**

```json
{
    "success": true,
    "token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "user": {
        "id": 1,
        "name": "Test User",
        "email": "test@example.com",
        "is_admin": false
    }
}
```

**Using Token in API Calls:**

```bash
curl -X GET http://localhost:8000/api/me \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc..."
```

**Token Details:**

- Expires: 60 minutes (configurable in `.env`)
- Refresh token expiry: 14 days
- Can be refreshed before expiring: `POST /api/refresh`

---

### 3. User Registration

**Web Registration**: `GET /register` → Shows registration form

```html
<!-- resources/views/auth/register.blade.php -->
<form method="POST" action="/register">
    @csrf
    <input name="name" type="text" required />
    <input name="email" type="email" required unique />
    <input name="password" type="password" required pattern="..." />
    <input name="password_confirmation" type="password" required />
    <input name="agree" type="checkbox" required /> Accept Terms
    <button>Create Account</button>
</form>
```

**API Registration**: `POST /api/register`

```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "MyPassword@123",
    "password_confirmation": "MyPassword@123"
  }'
```

**Password Requirements:**

- ✅ Minimum 8 characters
- ✅ At least one uppercase letter
- ✅ At least one lowercase letter
- ✅ At least one number
- ✅ At least one special character

**Validation Rules:**

- Email: Required, unique, valid format
- Name: Required, string, max 255 chars
- Password: Required, confirmed, strong

---

### 4. Email Verification

**After Registration**: User redirected to `/email/verify`

```html
<!-- resources/views/auth/verify-email.blade.php -->
<p>We've sent a verification link to your email.</p>
<p>Click the link to verify your account.</p>

<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button>Resend Verification Email</button>
</form>
```

**Verification Process:**

1. User checks email
2. Clicks verification link
3. Link contains: `/email/verify/{id}/{hash}`
4. Signature verified (can't be forged)
5. Email marked as verified
6. User can access dashboard

**Verification Link Details:**

- Format: Signed URL with HMAC-SHA256 signature
- Expires: 60 minutes
- Cannot be tampered with (signature verification fails)

**Rate Limiting:**

- Resend: 6 attempts per minute
- Verification link click: 6 attempts per minute

---

### 5. Admin System

**Admin Dashboard**: `GET /api/admin/dashboard`

```bash
curl -X GET http://localhost:8000/api/admin/dashboard \
  -H "Authorization: Bearer token_here"
```

**Admin Routes** (requires `is_admin = true`):

- `GET /api/admin/users` - List all users
- `GET /api/admin/orders` - View all orders
- `POST /api/admin/products` - Manage products
- `GET /api/admin/dashboard` - Admin statistics

**Admin Middleware Check:**

```php
// Checks: auth()->user()->is_admin == true
// Returns: 403 Forbidden if not admin
```

---

## Authentication Architecture

### Web Guard (Session-Based)

```php
// config/auth.php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
]

// Login stored in session (cookie)
// Persists across requests
// Auto-logout after inactive period
```

**Routes:**

```
GET  /login              → Show form
POST /login              → Process login → Set session → Redirect to /dashboard
GET  /dashboard          → Access user dashboard (protected)
POST /logout             → Destroy session → Redirect to /
```

**Middleware:**

```php
Route::middleware(['auth'])->group(function () {
    // User authenticated via session
});
```

### API Guard (JWT)

```php
// config/auth.php
'guards' => [
    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
]

// Login returns JWT token
// Token sent in Authorization header
// Server validates signature on each request
```

**Routes:**

```
POST /api/login          → Validate credentials → Generate JWT → Return token
POST /api/refresh        → Validate old token → Generate new token
POST /api/logout         → Blacklist token
GET  /api/me             → Get current user (requires valid token)
POST /api/validate-token → Check token validity
```

**Middleware:**

```php
Route::middleware(['jwt.auth'])->group(function () {
    // User authenticated via JWT Bearer token
});
```

---

## Database Schema

### Users Table

```sql
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,  -- NULL if unverified
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100),
    api_token VARCHAR(255) UNIQUE,
    is_admin BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**Key Columns:**

- `email_verified_at`: Email verification timestamp (required for access to protected routes)
- `is_admin`: Admin privilege flag (0 = user, 1 = admin)
- `api_token`: Legacy API token (if needed)

---

## API Endpoints

### Authentication Endpoints

| Route                 | Method | Auth | Purpose                        |
| --------------------- | ------ | ---- | ------------------------------ |
| `/api/login`          | POST   | None | Get JWT token                  |
| `/api/register`       | POST   | None | Register new user              |
| `/api/refresh`        | POST   | JWT  | Get new token before expiry    |
| `/api/logout`         | POST   | JWT  | Blacklist token                |
| `/api/validate-token` | POST   | JWT  | Check token validity           |
| `/api/me`             | GET    | JWT  | Get current authenticated user |

### Admin Endpoints

| Route                  | Method   | Auth        | Purpose          |
| ---------------------- | -------- | ----------- | ---------------- |
| `/api/admin/dashboard` | GET      | JWT + Admin | Admin statistics |
| `/api/admin/users`     | GET      | JWT + Admin | List all users   |
| `/api/admin/orders`    | GET      | JWT + Admin | List all orders  |
| `/api/admin/products`  | GET/POST | JWT + Admin | Manage products  |

### Public Endpoints

| Route                | Method | Purpose             |
| -------------------- | ------ | ------------------- |
| `/api/products`      | GET    | List products       |
| `/api/products/{id}` | GET    | Get product details |

---

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php           # Web + API login/logout
│   │   └── RegisterController.php       # Web + API registration
│   ├── Middleware/
│   │   ├── VerifyJWTToken.php          # JWT validation middleware
│   │   └── IsAdmin.php                 # Admin authorization check
│   └── Notifications/
│       └── VerifyEmailNotification.php  # Email verification email
│
├── Models/
│   └── User.php                         # User model + MustVerifyEmail
│
config/
├── auth.php                             # Authentication config (guards)
├── jwt.php                              # JWT configuration
└── filesystems.php
│
database/
├── migrations/
│   ├── 0001_01_01_000000_create_users_table.php
│   └── 0001_01_01_000003_add_fields_to_users_table.php
│
resources/
└── views/
    └── auth/
        ├── login.blade.php              # Web login form
        ├── register.blade.php           # Web registration form
        └── verify-email.blade.php       # Email verification notice
│
routes/
├── web.php                              # Web routes (session auth)
└── api.php                              # API routes (JWT auth)

tests/
├── Feature/
│   ├── AuthenticationTest.php           # Login/logout tests
│   └── RegistrationTest.php             # Registration tests
```

---

## Configuration Files

### .env (Key Settings)

```bash
# App
APP_NAME="E-Commerce Store"
APP_KEY=base64:...  # Generate with: php artisan key:generate
APP_DEBUG=true      # Set to false in production

# Database
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# JWT Configuration
JWT_ALGORITHM=HS256
JWT_SECRET=base64:...  # Generate with: php artisan jwt:secret
JWT_TTL=60             # Token expires in 60 minutes
JWT_REFRESH_TTL=20160  # Refresh token expires in 14 days

# Mail (for verification emails)
MAIL_MAILER=log        # For local testing (logs to storage/logs)
# For production, use: smtp, sendgrid, ses, etc.
```

### config/auth.php (Key Settings)

```php
'defaults' => [
    'guard' => 'web',       // Default guard for auth()
    'passwords' => 'users',
],

'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
],
```

### config/jwt.php (Key Settings)

```php
'algo' => env('JWT_ALGORITHM', 'HS256'),
'secret' => env('JWT_SECRET'),
'ttl' => env('JWT_TTL', 60),
'refresh_ttl' => env('JWT_REFRESH_TTL', 20160),
'blacklist_enabled' => true,  // Blacklist tokens on logout
```

---

## Testing

### Run All Tests

```bash
php artisan test

# Or specific test file
php artisan test tests/Feature/RegistrationTest.php

# Or specific test
php artisan test --filter=test_user_can_register
```

### Test Categories

**Authentication Tests** (AuthenticationTest.php):

- Login with valid credentials
- Login with invalid credentials
- Logout functionality
- JWT token generation and validation

**Registration Tests** (RegistrationTest.php):

- User can register with valid data
- Registration fails with duplicate email
- Registration fails with weak password
- Password is hashed (not plaintext)
- Email verification required
- API registration endpoint

---

## Documentation Files

Complete documentation available in markdown files:

| File                                           | Purpose                           |
| ---------------------------------------------- | --------------------------------- |
| [AUTHENTICATION.md](AUTHENTICATION.md)         | Web + API authentication system   |
| [REGISTRATION.md](REGISTRATION.md)             | User registration flow            |
| [EMAIL_VERIFICATION.md](EMAIL_VERIFICATION.md) | Email verification implementation |
| [REGISTRATION_TESTS.md](REGISTRATION_TESTS.md) | How to run and interpret tests    |

---

## Common Tasks

### 1. Manual Login Test

```bash
# Navigate to
http://localhost:8000/login

# Fill form
Email: test@example.com
Password: password123

# Click "Sign In"
# Redirected to dashboard
```

### 2. API Login Test

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'

# Returns JWT token
```

### 3. Register New User

```bash
# Navigate to
http://localhost:8000/register

# Fill form with:
Name: John Doe
Email: john@example.com
Password: MyPassword@123
Confirmation: MyPassword@123

# Click "Create Account"
# Redirected to /email/verify
# Check logs for verification email
```

### 4. Create Test Users

```bash
php artisan tinker

# Create regular user
User::create([
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password123'),
    'is_admin' => false,
    'email_verified_at' => now(),
]);

# Create admin user
User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('password123'),
    'is_admin' => true,
    'email_verified_at' => now(),
]);

exit
```

### 5. Reset Database

```bash
# Drop all tables and re-run migrations
php artisan migrate:fresh

# Create fresh test data
php artisan tinker
# (run User::create commands above)
```

---

## Next Steps

### Phase 5: Orders & Shopping Cart

- [ ] Create Product model + migration
- [ ] Create Cart model + functions
- [ ] Implement add to cart endpoint
- [ ] Create checkout flow
- [ ] Order confirmation emails

### Phase 6: Additional Features

- [ ] Password reset flow
- [ ] User profile management
- [ ] Order history + status tracking
- [ ] Email notifications for orders
- [ ] Search and filtering for products

### Phase 7: Security & Performance

- [ ] Rate limiting on registration
- [ ] CAPTCHA on registration form
- [ ] 2FA (two-factor authentication)
- [ ] API throttling by user
- [ ] Database indexing for performance

### Phase 8: Deployment

- [ ] Deploy to Azure App Service
- [ ] Set up PostgreSQL database
- [ ] Configure email service (SendGrid)
- [ ] Set up monitoring and logging
- [ ] Enable HTTPS/SSL

---

## Troubleshooting

### Issue: "These credentials do not match our records"

**Cause**: Test user doesn't exist in database

**Solution**:

```bash
php artisan migrate --force
php artisan tinker
# Create test users (see Common Tasks section above)
```

### Issue: "The connection was refused"

**Cause**: Development server not running

**Solution**:

```bash
php artisan serve
# Browse to http://localhost:8000
```

### Issue: "SQLSTATE[HY000]: General error: 1 unable to open database file"

**Cause**: Database file doesn't exist or permissions issue

**Solution**:

```bash
touch database/database.sqlite
php artisan migrate --force
```

### Issue: Email verification link expired

**Cause**: Link older than 60 minutes

**Solution**:

- Resend verification email by clicking "Resend Verification Email"
- Get new link from email
- New link valid for 60 minutes

---

## Support & Resources

- **Laravel Docs**: https://laravel.com/docs
- **tymon/jwt-auth**: https://github.com/tymondesigns/jwt-auth
- **SQLite**: https://www.sqlite.org/cli.html
- **Laragon**: https://laragon.org/

---

## License

This project is built as an educational e-commerce platform for learning Laravel 13 and modern web development patterns.

---

**Version**: 1.0.0  
**Last Updated**: January 2025  
**Framework**: Laravel 13  
**PHP**: 8.3.30
