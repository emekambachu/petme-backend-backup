<?php

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
        return $request->user('admin');
    });

});

// Sentry test
Route::get('/debug-sentry', static function () {
    throw new \RuntimeException('My first Sentry error!');
});
