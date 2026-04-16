<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group.
|
| Authentication: JWT Bearer Token in Authorization header
| Example: Authorization: Bearer <jwt_token>
|
*/

// Public API routes (No authentication required)
Route::post('/register', [RegisterController::class, 'storeApi']);
Route::post('/login', [AuthController::class, 'loginApi']);
Route::get('/products', function () {
    return response()->json(['message' => 'GET /api/products - List all products']);
});
Route::get('/products/{id}', function ($id) {
    return response()->json(['message' => 'GET /api/products/{id} - Get product details']);
});

// Protected API routes (JWT authentication required)
Route::middleware('jwt.auth')->group(function () {
    // User endpoints
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logoutApi']);
    Route::post('/refresh', [AuthController::class, 'refreshToken']);
    Route::post('/validate-token', [AuthController::class, 'validateToken']);

    // Customer endpoints
    Route::get('/profile', function () {
        return response()->json([
            'user' => auth('api')->user(),
        ]);
    });
    Route::patch('/profile', function (Request $request) {
        return response()->json(['message' => 'PATCH /api/profile - Update user profile']);
    });
    Route::get('/orders', function () {
        return response()->json(['message' => 'GET /api/orders - Get user\'s orders']);
    });
    Route::get('/orders/{id}', function ($id) {
        return response()->json(['message' => 'GET /api/orders/{id} - Get specific order']);
    });
    Route::post('/checkout', function (Request $request) {
        return response()->json(['message' => 'POST /api/checkout - Process checkout']);
    });

    // Admin routes
    Route::middleware('admin:api')->group(function () {
        Route::get('/admin/dashboard', function () {
            return response()->json(['message' => 'Admin dashboard stats']);
        });
        Route::get('/admin/products', function () {
            return response()->json(['message' => 'GET /api/admin/products - List all products']);
        });
        Route::post('/admin/products', function (Request $request) {
            return response()->json(['message' => 'POST /api/admin/products - Create product']);
        });
        Route::get('/admin/orders', function () {
            return response()->json(['message' => 'GET /api/admin/orders - List all orders']);
        });
        Route::get('/admin/users', function () {
            return response()->json(['message' => 'GET /api/admin/users - List all users']);
        });
    });
});
