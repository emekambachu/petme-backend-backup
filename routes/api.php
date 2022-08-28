<?php

use App\Http\Controllers\Admin\AdminPetController;
use App\Http\Controllers\Admin\AdminShopItemController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Auth\ApiAdminLoginController;
use App\Http\Controllers\Auth\ApiLoginController;
use App\Http\Controllers\Auth\ApiRegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Admin Auth
Route::post('/admin/login', [ApiAdminLoginController::class, 'login']);

// User Auth
Route::post('/user/register', [ApiRegisterController::class, 'register']);
Route::get('/user/verify/{token}', [ApiRegisterController::class, 'verifyAccount'])
    ->name('user.verify.token');
Route::post('/user/login', [ApiLoginController::class, 'login']);

// Custom sanctum admin guard authentication for admin
Route::middleware('auth:admin-api')->group(static function (){
    // Admin Auth API
    // Get users with specific guard
    Route::get('/admin/authenticate', static function (Request $request) {
        return $request->user('admin-api');
    });

    // Users
    Route::get('/admin/users', [AdminUserController::class, 'index']);
    Route::get('/admin/users/{id}', [AdminUserController::class, 'show']);
    Route::delete('/admin/users/{id}/delete', [AdminUserController::class, 'destroy']);

    // Pets
    Route::get('/admin/pets', [AdminPetController::class, 'index']);
    Route::get('/admin/pets/{id}', [AdminPetController::class, 'show']);

    // Users
    Route::get('/admin/shop/items', [AdminShopItemController::class, 'index']);
    Route::post('/admin/shop/items/create', [AdminShopItemController::class, 'store']);
    Route::get('/admin/shop/items/{id}', [AdminShopItemController::class, 'show']);
    Route::put('/admin/shop/items/{id}/update', [AdminShopItemController::class, 'update']);
    Route::delete('/admin/shop/items/{id}/delete', [AdminShopItemController::class, 'destroy']);

    // Admin Logout
    Route::post('/admin/logout', [ApiAdminLoginController::class, 'logout']);

    Route::get('/admin/test', static function () {
        return 'Admin tested';
    });
});

// Default sanctum api guard authentication for users
Route::middleware('auth:api')->group(static function (){
    // Admin Auth API
    // Get users with specific guard
    Route::get('/user/authenticate', static function (Request $request) {
        return $request->user('api');
    });

    // User Logout
    Route::post('/user/logout', [ApiAdminLoginController::class, 'logout']);

    Route::get('/user/test', static function () {
        return 'user tested';
    });
});



// Sentry test
Route::get('/debug-sentry', static function () {
    throw new \RuntimeException('My first Sentry error!');
});
