<?php

use App\Http\Controllers\Api\ApiGeneralController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostImageController;
use App\Http\Controllers\StarCampController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LoginController::class, 'showLoginForm'])->middleware('guest')->name('userLogin');
Route::get('/post/{id}', function ($id) {
    return view('welcome', compact('id'));
})->name('post');
// Route::get('/profile/{id}', function ($id) {
//     return view('profile', compact('id'));
// })->name('profile');
Route::get('/otp', function () {
    return view('otp');
})->name('otp');

Route::get('/email', function () {
    return view('email');
})->name('email');

Route::get('profile/{id}', [App\Http\Controllers\Api\PostImageController::class, 'allTestPostImage']);


Route::post('/send-email', [HomeController::class, 'sendEmail'])->name('sendEmail');
Route::post('/contact-us-mail', [HomeController::class, 'contactUsMail'])->name('contactUsMail');
Route::get('/gift-email', [HomeController::class, 'giftEmail'])->name('giftEmail');

Route::get('/privacy-and-policy', [HomeController::class, 'viewPrivacy'])->name('privacy-and-policy');
Route::get('/terms-and-conditions', [HomeController::class, 'viewTerms'])->name('terms-and-conditions');
Route::get('/subscription', [PaymentController::class, 'storeSubscription']);
Route::get('/subscribed/{id}/{plan_id}', [PaymentController::class, 'Subscribed']);
Route::get('/subscription-cancel/{id}', [PaymentController::class, 'subscriptionCancel']);
Route::get('/paypal-subscription', [PaymentController::class, 'create']);
Route::get('/paypal-subscribed/{id}/{plan_id}', [PaymentController::class, 'paypalSubscribed']);
Route::get('/paypal-subscription-cancel/{id}', [PaymentController::class, 'paypalsubscriptionCancel']);
Route::get('/create-web-hook', [PaymentController::class, 'createWebHook']);
Route::get('/create-plan', [PaymentController::class, 'createPlan']);
Route::get('/create-product/{id}', [PaymentController::class, 'createProduct']);
Route::post('stripe/webhook', '\Laravel\Cashier\Http\Controllers\WebhookController@handleWebhook');
Route::post('/paypal/webhook', [PaymentController::class, 'paypalWebhook'])->name('paypal.subscription.webhook');
Route::post('login', [LoginController::class, 'login'])->name('login');
Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacyPolicy');
    Route::get('/terms-conditions', [HomeController::class, 'termsConditions'])->name('termsConditions');
    Route::get('/security', [HomeController::class, 'security'])->name('security');
    Route::get('/help', [HomeController::class, 'help'])->name('help');
    Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('about-us');
    Route::post('/store-content', [HomeController::class, 'StoreContent'])->name('StoreContent');
    Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
    Route::delete('/faq-destroy/{id}', [HomeController::class, 'faqDelete'])->name('faq.destroy');
    Route::post('/faq-edit', [HomeController::class, 'faqUpdate'])->name('faq.edit');
    Route::post('/store-faq-content', [HomeController::class, 'StoreFaqContent'])->name('StoreFaqContent');
    Route::post('/allow-registration', [HomeController::class, 'allowRegistration'])->name('allowRegistration');
    Route::get('/app-version', [HomeController::class, 'appVersion'])->name('app-version');
    Route::get('/payment-status', [HomeController::class, 'paymentStatus'])->name('payment-status');
    Route::post('/store-app-version', [HomeController::class, 'storeAppVersion'])->name('storeAppVersion');
    Route::post('/update-payment-status', [HomeController::class, 'updatePaymentStatus'])->name('updatePaymentStatus');
    Route::get('/subscriptions-data', [HomeController::class, 'getSubscriptionData'])->name('SubscriptionData');
    Route::get('/contact-us', [HomeController::class, 'contactUs'])->name('contactUs');
    Route::get('/export-user/{subscription}', [HomeController::class, 'exportUser'])->name('exportUser');


    Route::get('/get-all-user', [UserController::class, 'getAllUser'])->name('getAllUser');
    Route::get('/get-user-starcamp', [UserController::class, 'getUserStarCamp'])->name('getUserStarCamp');
    Route::get('/block-user/{id}', [UserController::class, 'blockUser'])->name('blockUser');
    Route::get('/unblock-user/{id}', [UserController::class, 'unblockUser'])->name('unblockUser');
    Route::get('/block-to-user/{id}', [UserController::class, 'blockToUser'])->name('blockToUser');
    Route::get('/paypalSubscription', [UserController::class, 'paypalSubscription'])->name('paypalSubscription');
    Route::get('/stripeSubscription', [UserController::class, 'stripeSubscription'])->name('stripeSubscription');
    Route::resource('users', UserController::class);

    Route::get('/get-all-starcamp', [StarCampController::class, 'getAllstarcamp'])->name('getAllstarcamp');
    Route::resource('starcamps', StarCampController::class);

    Route::resource('posts', PostImageController::class);

    Route::get('get-bulk-notification', [ApiGeneralController::class, 'getBulkNotification'])->name('getBulkNotification');
    Route::get('create-bulk-notification', [ApiGeneralController::class, 'createBulkNotification'])->name('createBulkNotification');
    Route::post('send-bulk-notification', [ApiGeneralController::class, 'sendBulkNotification'])->name('sendBulkNotification');
    Route::get('trial-period', [ApiGeneralController::class, 'trialPeriod'])->name('trial-period');
    Route::post('store-trial-period', [ApiGeneralController::class, 'storeTrialPeriod'])->name('storeTrialPeriod');
    Route::get('general-setting', [ApiGeneralController::class, 'generalSetting'])->name('generalSetting');
    Route::post('maintenance', [ApiGeneralController::class, 'maintenance'])->name('maintenance');

    Route::get('rating-popup', [ApiGeneralController::class, 'trialPeriod'])->name('trial-period-popup');

    Route::resource('coupon', CouponController::class);
    Route::get('get-coupon/{id}', [CouponController::class, 'getCoupon']);


    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});
