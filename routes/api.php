<?php

use App\Http\Controllers\Auth\ApiAdminLoginController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

// Sanctum middleware group
Route::middleware('auth:sanctum')->group(static function (){

    // Admin Auth API
    // Get users with specific guard
    Route::get('/admin/authenticate', static function (Request $request) {
        return $request->user('admin-api');
    });

    // Admin Logout
    Route::post('/admin/logout', [ApiAdminLoginController::class, 'logout']);
});

// Admin Login
Route::post('/admin/login', [ApiAdminLoginController::class, 'login']);



// Sentry test
Route::get('/debug-sentry', static function () {
    throw new \RuntimeException('My first Sentry error!');
});
