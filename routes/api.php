<?php

use App\Http\Controllers\Api\ApiGeneralController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostCommentController;
use App\Http\Controllers\Api\PostImageController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\SetupController;
use App\Http\Controllers\Api\StarCardController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\StarCampController;
use App\Http\Controllers\Api\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('signup', [RegisterController::class, 'signup']);
Route::post('signup-test', [RegisterController::class, 'signupTest']);

Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('verify-otp', [AuthController::class, 'VerifyOTP']);
Route::post('reset-password', [AuthController::class, 'ResetPassword']);

Route::get('get-object-info',[PostImageController::class, 'GetObjectInfo']);

Route::get('/get-content', [ApiGeneralController::class, 'getContent']);
Route::get('/get-payment-method-status', [ApiGeneralController::class, 'getPaymentMethodStatus']);

Route::get('/subscription', [PaymentController::class, 'storeSubscription']);
Route::get('/subscribed/{id}', [PaymentController::class, 'Subscribed'])->name('subscribed');
Route::get('/subscription-cancel/{id}', [PaymentController::class, 'subscriptionCancel'])->name('subscriptionCancel');
Route::get('/paypal-subscription', [PaymentController::class, 'create']);
Route::get('/paypal-subscribed/{id}', [PaymentController::class, 'paypalSubscribed']);
Route::get('/paypal-subscription-cancel/{id}', [PaymentController::class, 'paypalsubscriptionCancel']);
Route::get('/create-plan', [PaymentController::class, 'createPlan']);
Route::get('get-all-test-post-image', [PostImageController::class, 'allTestPostImage']);

Route::group(['middleware' => 'auth:sanctum'], function () {    
    Route::post('profile-setup', [UserProfileController::class, 'profileSetup']);
    Route::post('update-profile', [UserProfileController::class, 'updateProfile']);
    Route::post('update_fcm_token', [UserProfileController::class, 'updateFcmToken']);
    Route::get('get-user-profile', [UserProfileController::class, 'getUserProfile']);

    Route::get('get-post-image', [PostImageController::class, 'index']);
    Route::get('get-all-post-image', [PostImageController::class, 'allPostImage']);
    Route::get('get-user-post-image', [PostImageController::class, 'userPostImage']);
    Route::post('store-post-image', [PostImageController::class, 'store']);
    Route::get('edit-post-image/{id}', [PostImageController::class, 'edit']);
    Route::post('update-post-image', [PostImageController::class, 'update']);
    Route::get('delete-post-image', [PostImageController::class, 'destroy']);
    Route::get('filter-post-image', [PostImageController::class, 'filterPostImage']);

    Route::resource('starcard',StarCardController::class);

    Route::get('get-setup', [SetupController::class, 'index']);
    Route::post('store-setup', [SetupController::class, 'store']);
    Route::get('edit-setup/{id}', [SetupController::class, 'edit']);
    Route::post('update-setup', [SetupController::class, 'update']);
    Route::get('delete-setup', [SetupController::class, 'destroy']);
    Route::get('setup-detail', [SetupController::class, 'setupDetail']);

    Route::get('get-member-list', [StarCampController::class, 'getMemberList']);
    Route::post('store-starcamp', [StarCampController::class, 'storeStarcamp']);
    Route::get('get-starcamp', [StarCampController::class, 'getStarcamp']);
    Route::get('add-member-starcamp', [StarCampController::class, 'addStarcampMember']);
    Route::get('remove-starcamp-member', [StarCampController::class, 'removeStarcampMember']);
    Route::get('delete-starcamp', [StarCampController::class, 'deleteStarcamp']);
    Route::get('get-starcamp-detail', [StarCampController::class, 'starcampDetail']);

    Route::get('follow', [ApiGeneralController::class, 'follow'])->name('follow');
    Route::get('get-save-object', [ApiGeneralController::class, 'getSaveObject']);
    Route::get('save-object', [ApiGeneralController::class, 'saveObject']);
    Route::get('remove-save-object', [ApiGeneralController::class, 'deleteSaveObject']);
    Route::get('give-star', [ApiGeneralController::class, 'givStar']);
    Route::get('image-of-month', [ApiGeneralController::class, 'imageOfMonth']);
    Route::get('vote-image', [ApiGeneralController::class, 'voteImage']);
    Route::post('report', [ApiGeneralController::class, 'report']);
    Route::get('block-to-user', [ApiGeneralController::class, 'blockToUser']);
    Route::post('contact-us', [ApiGeneralController::class, 'contactUs']);
    Route::get('test-notification', [ApiGeneralController::class, 'testNotification']);
    Route::get('warning-comment', [ApiGeneralController::class, 'warningComment']);
    Route::get('get-notification', [ApiGeneralController::class, 'getNotification']);
    Route::get('read-notification', [ApiGeneralController::class, 'readNotification']);
    Route::post('invite-user', [ApiGeneralController::class, 'inviteUser']);
    Route::get('get-user-by-id', [ApiGeneralController::class, 'getUserById']);
    Route::get('generate-post-link', [ApiGeneralController::class, 'generateSharePostLink']);
    Route::get('get-shared-post', [ApiGeneralController::class, 'getSharedPost']);
    Route::post('send-chat-notifications', [ApiGeneralController::class, 'sendChatNotifications']);
    Route::get('/start-trial-period', [ApiGeneralController::class, 'startTrialPeriod']);
    

    Route::post('add-post-comment', [PostCommentController::class, 'postComment']);
    Route::get('delete-comment', [PostCommentController::class, 'deleteComment']);
    Route::get('like-comment', [PostCommentController::class, 'likeComment']);
    Route::get('get-post-comment', [PostCommentController::class, 'getPostComment']);
    Route::get('get-comment-by-id', [PostCommentController::class, 'getCommentById']);

    
    Route::post('logout', [AuthController::class, 'logout']);
});
