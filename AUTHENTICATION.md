# Authentication Implementation Guide

## Overview

This Laravel e-commerce app has a complete authentication system supporting:

- **Session-based authentication** (Web - Blade forms)
- **JWT authentication** (API - JSON responses with tymon/jwt-auth)

### What's New (tymon/jwt-auth)

✅ **Production-Ready JWT Package**

- Secure token generation and validation
- Token refresh mechanism (renew tokens without re-login)
- Token blacklisting for logout
- Configurable token expiration
- Full exception handling

---

## File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── AuthController.php          # Login/logout handlers (JWT + Session)
│   ├── Middleware/
│   │   ├── VerifyJWTToken.php         # JWT token verification
│   │   ├── IsAdmin.php                # Admin authorization
│   │   └── Kernel.php                 # Middleware registration
│
config/
├── auth.php                           # Auth guards & providers
└── jwt.php                            # JWT configuration

database/
└── migrations/
    └── 0001_01_01_000003_*.php        # is_admin, api_token fields

resources/
└── views/
    └── auth/
        └── login.blade.php            # Web login form

routes/
├── web.php                            # Web authentication routes
└── api.php                            # API authentication routes
```

---

## Web Authentication (Session-based)

### Routes

```
GET  /login                  → Show login form
POST /login                  → Handle login (redirects to dashboard)
POST /logout                 → Handle logout (redirects to /)
GET  /dashboard              → Protected (requires authentication)
```

### How It Works

1. **User enters credentials** in the login form (`/login`)
2. **Form submits** to `POST /login`
3. **Laravel creates a session** and stores user ID in the session
4. **Browser receives session cookie** (httpOnly for security)
5. **Subsequent requests** include session cookie automatically
6. **Middleware verifies** user is authenticated on protected routes

### Testing with cURL

```bash
# 1. Get login form
curl -i http://localhost:8000/login

# 2. Login (this won't work easily due to CSRF token requirement)
# Use Postman or the browser instead
```

### Testing in Browser

1. Navigate to `http://localhost:8000/login`
2. Enter test user credentials:
    ```
    Email: test@example.com
    Password: password123
    ```
3. Click "Sign In"
4. Redirected to dashboard at `/dashboard`
5. Click "Logout" to end session

---

## API Authentication (JWT-based with tymon/jwt-auth)

### Routes

```
POST /api/login              → Login and get JWT token
GET  /api/me                 → Get current user (requires JWT)
POST /api/refresh            → Refresh token (get new token)
POST /api/logout             → Logout and invalidate token
POST /api/validate-token     → Check if token is valid
GET  /api/profile            → Get user profile (requires JWT)
GET  /api/orders             → Get user orders (requires JWT)
```

### JWT Token Flow

1. **Client sends credentials** to `POST /api/login`
2. **tymon/jwt-auth verifies credentials** and creates signed JWT token
3. **Token contains**: user ID, email, is_admin, issued time, expiration time
4. **Default expiration**: 60 minutes (configurable in `.env` via `JWT_TTL`)
5. **Client stores token** (localStorage, sessionStorage, or secure cookie)
6. **Client sends token** in Authorization header: `Authorization: Bearer <token>`
7. **Middleware verifies** token signature using `JWT_SECRET` from `.env`
8. **If expired**, client can use `POST /api/refresh` to get a new token without re-login
9. **On logout**, token is blacklisted (can't be reused)

### Configuration

All JWT settings are in `.env`:

```bash
JWT_ALGO=HS256                    # Algorithm (HS256, RS256, etc.)
JWT_SECRET=base64:NxgMsYJ9...    # Secret key for signing
JWT_TTL=60                        # Token expiration (minutes)
JWT_REFRESH_TTL=20160            # Refresh token expiration (14 days)
```

Configuration is also in `config/jwt.php` for advanced options.

### Testing with cURL

```bash
# 1. Login and get JWT token
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'

# Response:
{
  "success": true,
  "message": "Login successful",
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "user": {
    "id": 1,
    "name": "Test User",
    "email": "test@example.com",
    "is_admin": false
  }
}

# 2. Use token to access protected routes
curl http://localhost:8000/api/profile \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."

# Response:
{
  "success": true,
  "user": {
    "id": 1,
    "name": "Test User",
    "email": "test@example.com",
    "is_admin": false,
    "created_at": "2026-04-16T...",
    "updated_at": "2026-04-16T..."
  }
}

# 3. Refresh token (get new token before current expires)
curl -X POST http://localhost:8000/api/refresh \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."

# Response:
{
  "success": true,
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..." (new token)
}

# 4. Validate current token
curl -X POST http://localhost:8000/api/validate-token \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."

# Response:
{
  "success": true,
  "message": "Token is valid"
}

# 5. Logout (blacklist token)
curl -X POST http://localhost:8000/api/logout \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."

# Response:
{
  "success": true,
  "message": "Logout successful"
}

# After logout, token can't be used anymore (even if not expired)
curl http://localhost:8000/api/profile \
  -H "Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."

# Response:
{
  "error": "Token error: Token has been blacklisted"
}
```

### Testing with Postman

1. **POST /api/login**
    - Set Body as JSON:
        ```json
        {
            "email": "test@example.com",
            "password": "password123"
        }
        ```
    - Click Send
    - Copy the `token` value from response

2. **GET /api/profile** (or any protected route)
    - Click on "Headers" tab
    - Add new header:
        - Key: `Authorization`
        - Value: `Bearer <paste_token_here>`
    - Click Send

3. **Token Refresh**
    - **POST /api/refresh**
    - Same Authorization header as above
    - Get new token from response
    - Use new token for subsequent requests

---

## Admin Routes

### Web Admin Routes

```
GET  /admin/dashboard        → Admin dashboard (admin only)
GET  /admin/products         → Product management (admin only)
GET  /admin/orders           → Order management (admin only)
GET  /admin/users            → User management (admin only)
```

### API Admin Routes

```
GET  /api/admin/dashboard    → Admin stats (admin + JWT required)
GET  /api/admin/products     → List products (admin + JWT required)
POST /api/admin/products     → Create product (admin + JWT required)
GET  /api/admin/orders       → List orders (admin + JWT required)
GET  /api/admin/users        → List users (admin + JWT required)
```

### How Admin Access Works

1. **User must be authenticated** (session or JWT)
2. **User must have `is_admin = true`** in database
3. **IsAdmin middleware** checks both conditions
4. **If not admin** → Error 403 (web) or 403 JSON (API)

---

## Setting Up Test Data

### Create a test user in Tinker

```bash
php artisan tinker

# Create regular user
$user = User::factory()->create([
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'password' => bcrypt('password123'),
    'is_admin' => false,
]);

# Create admin user
$admin = User::factory()->create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('password123'),
    'is_admin' => true,
]);

exit()
```

### Quick Database Setup

```bash
# Run migrations
php artisan migrate

# Seed test data (after creating seeder)
php artisan db:seed
```

---

## Running Tests

```bash
# Run all authentication tests
php artisan test tests/Feature/AuthenticationTest.php

# Run specific test
php artisan test tests/Feature/AuthenticationTest.php::test_user_can_login_web

# Run with verbose output
php artisan test --verbose
```

**JWT signature verification** (using JWT_SECRET from .env)

- **Token expiration** (default 60 minutes, configurable)
- **Token blacklisting** on logout (can't reuse invalidated tokens)
- **Token refresh mechanism** (get new token without re-login)
- Bearer token in Authorization header (not in URL or cookies)

⚠️ **For production:**

1. ✅ tymon/jwt-auth is already installed and configured
2. Use HTTPS only (enforce in middleware or load balancer)
3. Set secure JWT_SECRET in .env (use `php artisan jwt:secret` if available)
4. Implement rate limiting on login route
5. Add email verification flow
6. Store tokens secu (JWT)

```
Request → jwt.auth Middleware (tymon/JWTAuth) → Verify signature & expiration
→ Router → Auth Middleware (api guard) → IsAdmin Middleware (if admin)
→ Controller → JSON Response
```

### Token Verification in jwt.auth Middleware

```php
// 1. Extract token from Authorization header
$token = $request->bearerToken();  // from "Authorization: Bearer <token>"

// 2. Decode and verify signature
JWTAuth::authenticate($token);

// 3. Check if token is blacklisted (after logout)
// 4. Verify expiration time
// 5. Load user from database
// 6. Make user available as auth('api')->user()
```

---

## Key Configuration Files

### `config/auth.php`

```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'api' => [
        'driver' => 'jwt',          // Now using JWT instead of token
        'provider' => 'users',
    ],
]
```

### `config/jwt.php`

Contains JWT-specific configuration:

- `secret`: Signing key from JWT_SECRET
- `algo`: Algorithm (HS256, RS256, etc.)
- `ttl`: Token expiration in minutes
- `refresh_ttl`: Refresh token expiration
- `blacklist_enabled`: Token blacklisting for logout
- `claims`: Additional JWT claims

### `.env` (JWT Settings)

```
JWT_ALGO=HS256
JWT_SECRET=base64:NxgMsYJ9...
JWT_TTL=60
JWT_REFRESH_TTL=20160
```

### `app/Http/Middleware/IsAdmin.php`

- Checks `is_admin` column on User model
- Blocks access for non-admin users
- Returns appropriate response for web/API

### `app/Http/Middleware/VerifyJWTToken.php`

- Now uses tymon/JWTAuth package
- Handles TokenExpiredException, TokenInvalidException, JWTException
- Returns JSON errors for API requests
  ],
  'api' => [
  'driver' => 'token',
  'provider' => 'users',
  'hash' => false,
  ],
  ]

```

### `app/Http/Middleware/IsAdmin.php`

- Checks `is_admin` column on User model
- Blocks access for non-admin users
- Returns appropriate response for web/API

### `app/Http/Middleware/VerifyJWTToken.php`

- Extracts bearer token from Authorization header
- Verifies JWT signature using `config('app.key')`
- Decodes payload and checks expiration
- Sets authenticated user for that request

---

## Security Notes

✅ **What's implemented:**

- CSRF protection on web login form
- Password hashing (bcrypt) via Eloquent model
- Session fixation prevention
- JWT signature verification
- Token expiration (24 hours)

⚠️ **For production:**

1. Use `tymon/jwt-auth` package for advanced JWT features
2. Enable HTTPS only (enforce in middleware)
3. Set secure cookies (httpOnly, sameSite)
4. Implement rate limiting on login route
5. Add email verification
6. Add remember-me token rotation
7. Use refresh tokens for API

---

## Common Issues

**"Unauthorized" on API request**
- Check JWT token is in Authorization header format: `Authorization: Bearer <token>`
- Verify token hasn't expired (default 60 minutes, set by JWT_TTL)
- Check if token was blacklisted (use `/api/refresh` to get new token)
- Verify JWT_SECRET in .env matches what was used to sign the token

**"Token has been blacklisted"**
- Token was used after logout
- Generate new token via `/api/login`

**Token expiration issues**
- Use `POST /api/refresh` endpoint to get a new token before current expires
- Refresh endpoint returns new token without requiring login again
- Configure `JWT_TTL` in .env to adjust expiration time

**"Cannot verify token signature"**
- JWT_SECRET in .env doesn't match the secret used to sign tokens
- This happens after changing JWT_SECRET - all old tokens become invalid
- Regenerate token via `/api/login`

**CSRF token mismatch on web login**
- Ensure `@csrf` is in the login form
- Browser must accept cookies

**401 on protected routes**
- User must be logged in first (have valid JWT token)
- Token must be in Authorization header
- Session/token must not be expired or blacklisted

**"Token not provided"**
- Authorization header is missing
- Header format must be: `Authorization: Bearer <token>`
- Not just `Authorization: <token>` (Bearer prefix required)
```
