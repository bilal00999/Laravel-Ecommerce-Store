<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

/**
 * MIDDLEWARE USAGE EXAMPLES
 * 
 * 1. 'auth' - Redirect unauthenticated users to login
 *    Usage: Route::middleware('auth')->group(...) or ->middleware('auth')
 *
 * 2. 'guest' - Redirect already-logged-in users away from login/register
 *    Usage: Route::middleware('guest')->group(...) or ->middleware('guest')
 *
 * 3. 'admin' - Allow access only to users with role = 'admin'
 *    Usage: Route::middleware('admin')->group(...) or ->middleware('admin')
 *
 * You can also combine multiple middleware:
 *    ->middleware(['auth', 'verified', 'admin'])
 */

// ============================================================
// 1. GUEST MIDDLEWARE - Routes for unauthenticated users only
// ============================================================
// Redirects logged-in users to dashboard
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'loginWeb'])->name('login.web');
    
    // Registration
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

// ============================================================
// 2. AUTH MIDDLEWARE - Routes for authenticated users
// ============================================================
// Redirects unauthenticated users to login
Route::middleware('auth')->group(function () {
    // Email Verification
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/dashboard')->with('status', 'Email verified successfully!');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware('throttle:6,1')->name('verification.send');

    // Authenticated dashboard (requires email verification)
    Route::middleware('verified')->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
        
        Route::post('/logout', [AuthController::class, 'logoutWeb'])->name('logout');
    });
});

// ============================================================
// 3. ADMIN MIDDLEWARE - Routes for admin users only
// ============================================================
// Checks if user is authenticated AND has role = 'admin'
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    // Admin dashboard
    Route::get('/admin', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Admin panel routes
    Route::get('/admin/users', function () {
        return view('admin.users.index');
    })->name('admin.users');

    Route::get('/admin/settings', function () {
        return view('admin.settings');
    })->name('admin.settings');
});

// ============================================================
// ALTERNATIVE: Apply middleware directly to routes
// ============================================================
/*
// Single middleware
Route::get('/profile', [ProfileController::class, 'show'])
    ->middleware('auth');

// Multiple middleware (applied in order)
Route::post('/admin/delete-user/{id}', [AdminController::class, 'deleteUser'])
    ->middleware(['auth', 'admin']);

// Conditional middleware with parameters
Route::get('/posts/{id}', [PostController::class, 'show'])
    ->middleware('throttle:60,1');
*/

