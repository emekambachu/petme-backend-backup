<?php

use App\Http\Controllers\Admin\Blog\AdminBlogCategoryController;
use App\Http\Controllers\Admin\Blog\AdminBlogPostController;
use App\Http\Controllers\Admin\Pet\AdminPetController;
use App\Http\Controllers\Admin\Shop\AdminShopCategoryController;
use App\Http\Controllers\Admin\Shop\AdminShopItemController;
use App\Http\Controllers\Admin\Shop\AdminShopMetricController;
use App\Http\Controllers\Admin\User\AdminUserController;
use App\Http\Controllers\Auth\ApiAdminLoginController;
use App\Http\Controllers\Auth\ApiLoginController;
use App\Http\Controllers\Auth\ApiRegisterController;
use App\Http\Controllers\Home\Blog\HomeBlogController;
use App\Http\Controllers\Home\Shop\HomeShopController;
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

// Home Shop
Route::get('/shop', [HomeShopController::class, 'index']);
Route::get('/shop/{id}/show', [HomeShopController::class, 'show']);

// Home Blog
Route::get('/blog', [HomeBlogController::class, 'index']);
Route::get('/blog/{id}/show', [HomeBlogController::class, 'show']);
Route::post('/blog/{id}/add-comment', [HomeBlogController::class, 'addComment']);
Route::get('/blog/{id}/comments', [HomeBlogController::class, 'comments']);

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

    // Admin Users
    Route::get('/admin/users', [AdminUserController::class, 'index']);
    Route::get('/admin/users/{id}', [AdminUserController::class, 'show']);
    Route::post('/admin/users/{id}/verify', [AdminUserController::class, 'verify']);
    Route::delete('/admin/users/{id}/delete', [AdminUserController::class, 'destroy']);

    // Admin Pets
    Route::get('/admin/pets', [AdminPetController::class, 'index']);
    Route::get('/admin/pets/{id}', [AdminPetController::class, 'show']);
    Route::get('/admin/pets/{id}/deworming', [AdminPetController::class, 'showDeworming']);
    Route::get('/admin/pets/{id}/vaccination', [AdminPetController::class, 'showVaccination']);
    Route::get('/admin/pets/{id}/diet', [AdminPetController::class, 'showDiet']);

    // Admin Shop
    Route::get('/admin/shop/items', [AdminShopItemController::class, 'index']);
    Route::post('/admin/shop/items/create', [AdminShopItemController::class, 'store']);
    Route::post('/admin/shop/items/{id}/publish', [AdminShopItemController::class, 'publish']);
    Route::post('/admin/shop/items/search', [AdminShopItemController::class, 'search']);
    Route::get('/admin/shop/items/{id}', [AdminShopItemController::class, 'show']);
    Route::post('/admin/shop/items/{id}/update', [AdminShopItemController::class, 'update']);
    Route::delete('/admin/shop/items/{id}/delete', [AdminShopItemController::class, 'destroy']);
    Route::delete('/admin/shop/image/{id}/delete', [AdminShopItemController::class, 'deleteShopItemImage']);

    // Admin Shop Category
    Route::get('/admin/shop/categories', [AdminShopCategoryController::class, 'index']);
    Route::post('/admin/shop/categories/create', [AdminShopCategoryController::class, 'store']);
    Route::put('/admin/shop/categories/{id}/update', [AdminShopCategoryController::class, 'update']);
    Route::delete('/admin/shop/categories/{id}/delete', [AdminShopCategoryController::class, 'destroy']);

    // Admin Shop Metrics
    Route::get('/admin/shop/metrics', [AdminShopMetricController::class, 'index']);
    Route::post('/admin/shop/metrics/create', [AdminShopMetricController::class, 'store']);
    Route::put('/admin/shop/metrics/{id}/update', [AdminShopMetricController::class, 'update']);
    Route::delete('/admin/shop/metrics/{id}/delete', [AdminShopMetricController::class, 'destroy']);

    // Admin Blog Post
    Route::get('/admin/blog/posts', [AdminBlogPostController::class, 'index']);
    Route::post('/admin/blog/posts/create', [AdminBlogPostController::class, 'store']);
    Route::post('/admin/blog/posts/{id}/publish', [AdminBlogPostController::class, 'publish']);
    Route::post('/admin/blog/posts/search', [AdminBlogPostController::class, 'search']);
    Route::get('/admin/blog/posts/{id}', [AdminBlogPostController::class, 'show']);
    Route::put('/admin/blog/posts/{id}/update', [AdminBlogPostController::class, 'update']);
    Route::delete('/admin/blog/posts/{id}/delete', [AdminBlogPostController::class, 'destroy']);

    // Admin Blog category
    Route::get('/admin/blog/categories', [AdminBlogCategoryController::class, 'index']);
    Route::post('/admin/blog/categories/create', [AdminBlogCategoryController::class, 'store']);
    Route::put('/admin/blog/categories/{id}/update', [AdminBlogCategoryController::class, 'update']);
    Route::delete('/admin/blog/categories/{id}/delete', [AdminBlogCategoryController::class, 'destroy']);

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
