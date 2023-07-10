<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\HomeApi;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Users\ChatApi;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Users\StoreController;
use App\Http\Controllers\Api\{CountryApi, CategoryApi};
use App\Http\Controllers\Api\Users\NotificationApiController;
use App\Http\Controllers\Api\Users\SubscriptionApiController;

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
Route::post('/save-token', 'FCMController@index');
Route::prefix('v1')->middleware(['CheckLang'])->group(function () {
    // Auth Routes
    Route::get('home', [HomeApi::class, 'index']);
    Route::get('settings', [HomeApi::class, 'settings']);
    Route::controller(AuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
    });

    // Country
    Route::controller(CountryApi::class)->group(function () {
        Route::get('countries', 'get_all_countries');
        Route::get('currencies', 'get_all_currencies');
        Route::get('country_by_name', 'get_country_by_name');
        Route::get('cities/{country_id}', 'get_all_cities');
        Route::get('states/{city_id}', 'get_all_states');
    });

    // Category
    Route::controller(CategoryApi::class)->group(function () {
        Route::get('categories', 'get_all_categories');
        Route::get('sub_categories/{category_id}', 'get_sub_categories');
    });

    Route::prefix('stores')->controller(StoreController::class)->group(function () {
        Route::get('', 'stores');
        Route::get('announcements', 'announcements');
    });
    Route::get('packages', [SubscriptionApiController::class, 'packages']);
    Route::get('users', [StoreController::class, 'users']);
    Route::middleware(['auth:sanctum'])->group(function () {
        // Chat

        Route::controller(ChatApi::class)->group(function () {
            Route::get('get_all_chats', 'get_all_chats');
            Route::post('send_msg', 'send_msg');
            Route::post('delete_msg', 'delete');
        });
        Route::prefix('stores')->controller(StoreController::class)->group(function () {
            Route::post('create_store', 'create_store');
            Route::post('update_store', 'update_store');
            Route::get('my_store', 'my_store');
            Route::get('my_announcements', 'my_announcements');
            Route::post('create_announcement', 'create_announcement');
        });
        Route::get('rate', [StoreController::class, 'rate']);
        Route::get('my_profile', [StoreController::class, 'get_my_profile']);
        Route::post('my_profile', [StoreController::class, 'my_profile']);

        Route::controller(SubscriptionApiController::class)->group(function () {
            Route::get('subscription_submit', 'subscription_submit');
            Route::get('my_subscriptions', 'my_subscriptions');
        });

        Route::get('my_notifications', [NotificationApiController::class, 'my_notifications']);
        Route::get('notification', [NotificationApiController::class, 'notification']);
        Route::get('notification_read_all', [NotificationApiController::class, 'all_notification_read']);

        Route::get('/logout', [AuthController::class, 'logout']);
    });
    Route::get('developer_codes', function () {
        return response()->json([
            'status' => false,
            'errNum' => 402,
            'codes' => [
                    "R50" => 'To Redirect Users To Packages Screen'
                ]
        ]);
    });
});


Route::get('v1/{page}', function () {
    return response()->json([
        'status' => false,
        'errNum' => 402,
        'msg' => "This Api Not Found"
    ]);
});
