<?php

use App\Http\Controllers\Admin\Blog\AdminBlogCategoryController;
use App\Http\Controllers\Admin\Blog\AdminBlogPostController;
use App\Http\Controllers\Admin\Pet\AdminPetController;
use App\Http\Controllers\Admin\Pet\AdminPetTypeController;
use App\Http\Controllers\Admin\ServiceProvider\AdminServiceProviderCategoryController;
use App\Http\Controllers\Admin\ServiceProvider\AdminServiceProviderController;
use App\Http\Controllers\Admin\Shop\AdminShopCategoryController;
use App\Http\Controllers\Admin\Shop\AdminShopDiscountController;
use App\Http\Controllers\Admin\Shop\AdminShopItemController;
use App\Http\Controllers\Admin\Shop\AdminShopItemDiscountController;
use App\Http\Controllers\Admin\Shop\AdminShopMetricController;
use App\Http\Controllers\Admin\User\AdminUserController;
use App\Http\Controllers\Auth\Admin\ApiAdminLoginController;
use App\Http\Controllers\Auth\ServiceProvider\ApiLoginServiceProviderController;
use App\Http\Controllers\Auth\ServiceProvider\ApiRegisterServiceProviderController;
use App\Http\Controllers\Auth\User\ApiLoginController;
use App\Http\Controllers\Auth\User\ApiRegisterController;
use App\Http\Controllers\Home\Blog\HomeBlogController;
use App\Http\Controllers\Home\Pet\HomePetController;
use App\Http\Controllers\Home\ServiceProvider\HomeServiceProviderController;
use App\Http\Controllers\Home\Shop\HomeShopController;
use App\Http\Controllers\ServiceProvider\Appointment\ServiceProviderAppointmentController;
use App\Http\Controllers\ServiceProvider\Services\ServiceProviderServiceController;
use App\Http\Controllers\User\Appointment\UserAppointmentController;
use App\Http\Controllers\User\Location\UserLocationController;
use App\Http\Controllers\User\Pet\UserPetController;
use App\Http\Controllers\User\Pet\UserPetDewormController;
use App\Http\Controllers\User\Pet\UserPetDietController;
use App\Http\Controllers\User\Pet\UserPetVaccinationController;
use App\Http\Controllers\User\ServiceProvider\UserServiceProviderController;
use App\Http\Controllers\User\Wallet\UserWalletController;
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

Route::get("test-mail",function (){

    //dd(config("mail.mailers.smtp.username"));
    \Illuminate\Support\Facades\Mail::to("oyebamijitobi@gmail.com")
        ->send(new \App\Mail\TestMail());
    return true;
});

// Home Shop
Route::get('/shop', [HomeShopController::class, 'index']);
Route::post('/shop/search', [HomeShopController::class, 'search']);
Route::get('/shop/{id}/show', [HomeShopController::class, 'show']);

// Home Blog
Route::get('/blog', [HomeBlogController::class, 'index']);
Route::post('/blog/search', [HomeBlogController::class, 'search']);
Route::get('/blog/{id}/show', [HomeBlogController::class, 'show']);
Route::post('/blog/{postId}/add-comment', [HomeBlogController::class, 'addComment']);
Route::get('/blog/{postId}/comments', [HomeBlogController::class, 'getPostComments']);

// Home Service Provider
Route::get('/home/service-providers/categories', [HomeServiceProviderController::class, 'getCategories']);

// Home Pets
Route::get('/home/pet/types', [HomePetController::class, 'getPetTypes']);

// Admin Auth
Route::post('/admin/login', [ApiAdminLoginController::class, 'login']);

// User Auth
Route::post('/user/register', [ApiRegisterController::class, 'register']);
Route::post('/user/otp/email/send', [ApiRegisterController::class, 'sendOtpToUserEmail']);
Route::post('/user/otp/submit', [ApiRegisterController::class, 'submitOtp']);
Route::post('/user/login', [ApiLoginController::class, 'login']);

// Service Provider Auth
Route::post('/service-provider/register', [ApiRegisterServiceProviderController::class, 'register']);
Route::post('/service-provider/otp/submit', [ApiRegisterServiceProviderController::class, 'submitOtp']);
Route::post('/service-provider/login', [ApiLoginServiceProviderController::class, 'login']);

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

    // Admin Pet Types
    Route::get('/admin/pet/types', [AdminPetTypeController::class, 'index']);
    Route::post('/admin/pet/types/add', [AdminPetTypeController::class, 'store']);
    Route::post('/admin/pet/types/{id}/update', [AdminPetTypeController::class, 'update']);
    Route::delete('/admin/pet/types/{id}/delete', [AdminPetTypeController::class, 'delete']);

    // Admin Shop
    Route::get('/admin/shop/items', [AdminShopItemController::class, 'index']);
    Route::post('/admin/shop/items/create', [AdminShopItemController::class, 'store']);
    Route::post('/admin/shop/items/{id}/publish', [AdminShopItemController::class, 'publish']);
    Route::post('/admin/shop/items/search', [AdminShopItemController::class, 'search']);
    Route::get('/admin/shop/items/{id}', [AdminShopItemController::class, 'show']);
    Route::post('/admin/shop/items/{id}/update', [AdminShopItemController::class, 'update']);
    Route::delete('/admin/shop/items/{id}/delete', [AdminShopItemController::class, 'destroy']);
    Route::delete('/admin/shop/image/{id}/delete', [AdminShopItemController::class, 'deleteShopItemImage']);

    // Admin Shop Item Discount
    Route::get('/admin/shop/item/{id}/discount', [AdminShopItemDiscountController::class, 'show']);
    Route::post('/admin/shop/item/{id}/discount/add', [AdminShopItemDiscountController::class, 'store']);
    Route::delete('/admin/shop/item/{itemId}/discount/{discountId}/delete',
        [AdminShopItemDiscountController::class, 'delete']);

    // Admin Shop Discount
    Route::get('/admin/shop/discounts', [AdminShopDiscountController::class, 'index']);
    Route::post('/admin/shop/discounts/create', [AdminShopDiscountController::class, 'store']);
    Route::get('/admin/shop/discounts/{id}', [AdminShopDiscountController::class, 'show']);
    Route::post('/admin/shop/discounts/{id}/update', [AdminShopDiscountController::class, 'update']);
    Route::delete('/admin/shop/discounts/{id}/delete', [AdminShopDiscountController::class, 'destroy']);

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
    Route::post('/admin/blog/posts/{id}/update', [AdminBlogPostController::class, 'update']);
    Route::delete('/admin/blog/posts/{id}/delete', [AdminBlogPostController::class, 'destroy']);

    // Admin Blog category
    Route::get('/admin/blog/categories', [AdminBlogCategoryController::class, 'index']);
    Route::post('/admin/blog/categories/create', [AdminBlogCategoryController::class, 'store']);
    Route::post('/admin/blog/categories/{id}/update', [AdminBlogCategoryController::class, 'update']);
    Route::delete('/admin/blog/categories/{id}/delete', [AdminBlogCategoryController::class, 'destroy']);

    // Admin Service provider
    Route::get('/admin/service-providers', [AdminServiceProviderController::class, 'index']);
    Route::post('/admin/service-providers/create', [AdminServiceProviderController::class, 'store']);
    Route::post('/admin/service-providers/{id}/publish', [AdminServiceProviderController::class, 'publish']);
    Route::post('/admin/service-providers/search', [AdminServiceProviderController::class, 'search']);
    Route::get('/admin/service-providers/{id}/show', [AdminServiceProviderController::class, 'show']);
    Route::post('/admin/service-providers/{id}/update', [AdminServiceProviderController::class, 'update']);
    Route::delete('/admin/service-providers/{id}/delete', [AdminServiceProviderController::class, 'destroy']);

    // Admin Service Provider Category
    Route::get('/admin/service-providers/categories',
        [AdminServiceProviderCategoryController::class, 'index']);
    Route::post('/admin/service-providers/categories/store',
        [AdminServiceProviderCategoryController::class, 'store']);
    Route::post('/admin/service-providers/categories/{id}/update',
        [AdminServiceProviderCategoryController::class, 'update']);
    Route::delete('/admin/service-providers/categories/{id}/delete',
        [AdminServiceProviderCategoryController::class, 'delete']);

    // Admin Service provider Documents
    Route::get('/admin/service-providers/{id}/documents', [AdminServiceProviderController::class, 'documents']);
    Route::post('/admin/service-providers/{id}/document/upload', [AdminServiceProviderController::class, 'storeDocument']);
    Route::post('/admin/service-providers/{id}/document/delete', [AdminServiceProviderController::class, 'deleteDocument']);

    // Admin Logout
    Route::post('/admin/logout', [ApiAdminLoginController::class, 'logout']);

    Route::get('/admin/test', static function () {
        return 'Admin tested';
    });
});

// Default sanctum api guard authentication for service providers
Route::middleware('auth:service-provider-api')->group(static function (){
    // Admin Auth API
    // Get users with specific guard
    Route::get('/service-provider/authenticate', static function (Request $request) {
        return $request->user('service-provider-api');
    });

    // Service Provider Services
    Route::get('/service-provider/services', [ServiceProviderServiceController::class, 'index']);
    Route::post('/service-provider/services/create', [ServiceProviderServiceController::class, 'store']);
    Route::post('/service-provider/services/{id}/update', [ServiceProviderServiceController::class, 'update']);
    Route::delete('/service-provider/services/{id}/delete', [ServiceProviderServiceController::class, 'destroy']);

    // Service Provider Appointment
    Route::get('/service-provider/appointments', [ServiceProviderAppointmentController::class, 'index']);
    Route::post('/service-provider/appointments/{id}/accept', [ServiceProviderAppointmentController::class, 'acceptAppointment']);
    Route::post('/service-provider/appointments/{id}/reject', [ServiceProviderAppointmentController::class, 'rejectAppointment']);

    // Service provider Logout
    Route::post('/service-provider/logout', [ApiLoginServiceProviderController::class, 'logout']);

});

// Default sanctum api guard authentication for users
Route::middleware('auth:api')->group(static function (){
    // Admin Auth API
    // Get users with specific guard
    Route::get('/user/authenticate', static function (Request $request) {
        return $request->user('api');
    });

    // User Location
    Route::get('/user/location', [UserLocationController::class, 'currentLocation']);
    Route::get('/user/location/update', [UserLocationController::class, 'updateLocation']);

    // User Pets
    Route::get('/user/pets', [UserPetController::class, 'index']);
    Route::post('/user/pets/create', [UserPetController::class, 'store']);
    Route::post('/user/{userId}/pets/{petId}/publish', [UserPetController::class, 'publish']);
    Route::post('/user/{userId}/pets/{petId}/update', [UserPetController::class, 'update']);
    Route::delete('/user/{userId}/pets/{petId}/delete', [UserPetController::class, 'delete']);

    // User Pets Deworm
    Route::get('/user/pets/deworm', [UserPetDewormController::class, 'index']);
    Route::post('/user/pets/{petId}/deworm/create', [UserPetDewormController::class, 'store']);
    Route::post('/user/pets/{petId}/deworm/{id}/update', [UserPetDewormController::class, 'update']);
    Route::delete('/user/pets/{petId}/deworm/{id}/delete', [UserPetDewormController::class, 'delete']);

    // User Pets Diet
    Route::get('/user/pets/diet', [UserPetDietController::class, 'index']);
    Route::post('/user/pets/{petId}/diet/create', [UserPetDietController::class, 'store']);
    Route::post('/user/pets/{petId}/diet/{id}/update', [UserPetDietController::class, 'update']);
    Route::delete('/user/pets/{petId}/diet/{id}/delete', [UserPetDietController::class, 'delete']);

    // User Pets Vaccination
    Route::get('/user/pets/vaccination', [UserPetVaccinationController::class, 'index']);
    Route::post('/user/pets/{petId}/vaccination/create', [UserPetVaccinationController::class, 'store']);
    Route::post('/user/pets/{petId}/vaccination/{id}/update', [UserPetVaccinationController::class, 'update']);
    Route::delete('/user/pets/{petId}/vaccination/{id}/delete', [UserPetVaccinationController::class, 'delete']);

    // User Appointments
    Route::get('/user/appointments', [UserAppointmentController::class, 'index']);
    Route::post('/user/appointments/create', [UserAppointmentController::class, 'store']);
    Route::post('/user/appointments/{id}/reschedule', [UserAppointmentController::class, 'update']);
    Route::delete('/user/appointments/{id}/cancel', [UserAppointmentController::class, 'destroy']);
    Route::post('/user/appointments/{appointmentId}/service/{serviceId}/add', [UserAppointmentController::class, 'addService']);
    Route::post('/user/appointments/{appointmentId}/service/{serviceId}/remove', [UserAppointmentController::class, 'removeService']);

    // User Service Providers
    Route::get('/user/service-providers', [UserServiceProviderController::class, 'index']);
    Route::get('/user/service-providers/categories', [UserServiceProviderController::class, 'categories']);
    Route::get('/user/service-providers/{id}/category', [UserServiceProviderController::class, 'category']);

    // User Wallet
    Route::get('/user/wallet', [UserWalletController::class, 'index']);

    // User Logout
    Route::post('/user/logout', [ApiAdminLoginController::class, 'logout']);

});


// Sentry test
Route::get('/debug-sentry', static function () {
    throw new \RuntimeException('My first Sentry error!');
});
