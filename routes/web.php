<?php

use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostImageController;
use App\Http\Controllers\StarCampController;
use App\Http\Controllers\UserController;
use App\Models\PostImage;
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
    Route::get('/post/{id}', function(){
    return json_encode([
        'message' => 'Download Picastro app to see this post'
    ]);
})->name('post');
Route::get('/otp', function () {
    return view('otp');
})->name('otp');
Route::get('/privacy-and-policy', [HomeController::class, 'viewPrivacy'])->name('privacy-and-policy');
Route::get('/terms-and-conditions', [HomeController::class, 'viewTerms'])->name('terms-and-conditions');
Route::get('/subscription', [PaymentController::class, 'storeSubscription']);
Route::get('/subscribed/{id}', [PaymentController::class, 'Subscribed']);
Route::get('/subscription-cancel/{id}', [PaymentController::class, 'subscriptionCancel']);
Route::get('/paypal-subscription', [PaymentController::class, 'create']);
Route::get('/paypal-subscribed/{id}', [PaymentController::class, 'paypalSubscribed']);
Route::get('/paypal-subscription-cancel/{id}', [PaymentController::class, 'paypalsubscriptionCancel']);
Route::get('/create-web-hook', [PaymentController::class, 'createWebHook']);
Route::get('/create-plan', [PaymentController::class, 'createPlan']);
Route::get('/create-product', [PaymentController::class, 'createProduct']);
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
    
    
    Route::get('/get-all-user', [UserController::class, 'getAllUser'])->name('getAllUser');
    Route::get('/get-user-starcamp', [UserController::class, 'getUserStarCamp'])->name('getUserStarCamp');
    Route::get('/block-user/{id}', [UserController::class, 'blockUser'])->name('blockUser');
    Route::get('/unblock-user/{id}', [UserController::class, 'unblockUser'])->name('unblockUser');
    Route::get('/block-to-user/{id}', [UserController::class, 'blockToUser'])->name('blockToUser');
    Route::resource('users', UserController::class);
    Route::get('/get-all-starcamp', [StarCampController::class, 'getAllstarcamp'])->name('getAllstarcamp');
    Route::resource('starcamps', StarCampController::class);
    Route::resource('posts', PostImageController::class);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});