<?php

use App\Http\Controllers\Customer\Auth\ForgotPasswordController;
use App\Http\Controllers\Customer\Auth\LoginController;
use App\Http\Controllers\Customer\Auth\RegisterController;
use App\Http\Controllers\Customer\Auth\SocialAuthController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\UserProfileController;
use App\Http\Controllers\Front\ComplainController;
use Illuminate\Support\Facades\Route;

Route::prefix('/customer')->as('customer.')->group(function () {

    Route::prefix('/auth')->as('auth.')->group(function () {
        Route::get('login', [LoginController::class, 'login'])->name('login');
        Route::post('login', [LoginController::class, 'submit']);
        Route::get('logout', [LoginController::class, 'logout'])->name('logout');

        Route::get('sign-up', [RegisterController::class, 'register'])->name('sign-up');
        Route::post('sign-up', [RegisterController::class, 'submit']);

        Route::get('check/{id}', [RegisterController::class, 'check'])->name('check');

        Route::post('verify', [RegisterController::class, 'verify'])->name('verify');

        Route::get('update-phone/{id}', [SocialAuthController::class, 'editPhone'])->name('update-phone');
        Route::post('update-phone/{id}', [SocialAuthController::class, 'updatePhone']);

        Route::get('login/{service}', [SocialAuthController::class, 'redirectToProvider'])->name('service-login');
        Route::get('login/{service}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('service-callback');

        Route::get('recover-password', [ForgotPasswordController::class, 'reset_password'])->name('recover-password');
        Route::post('forgot-password', [ForgotPasswordController::class, 'reset_password_request'])->name('forgot-password');
        Route::get('otp-verification', [ForgotPasswordController::class, 'otp_verification'])->name('otp-verification');
        Route::post('otp-verification', [ForgotPasswordController::class, 'otp_verification_submit']);
        Route::get('reset-password', [ForgotPasswordController::class, 'reset_password_index'])->name('reset-password');
        Route::post('reset-password', [ForgotPasswordController::class, 'reset_password_submit']);
    });
    Route::get('/complain', [ComplainController::class, 'customerComplain'])->name('complain');

    Route::post('/complain/store', [ComplainController::class, 'customerComplainStore'])->name('complain.store');

    Route::prefix('/payment-mobile')->group(function () {
        Route::get('/', [PaymentController::class, 'payment'])->name('payment-mobile');
    });
});
//profile Route
Route::controller(UserProfileController::class)->middleware('customer')->group(function () {
    Route::get('user-account', 'user_account')->name('user-account'); // user profile
    Route::post('user-account-update', 'user_update')->name('user-update'); // user profile update
    Route::get('account-address', 'account_address')->name('account-address'); // user address
    Route::post('account-address-store', 'address_store')->name('address-store'); // user address store
    ROute::get('account-address-edit/{id}', 'address_edit')->name('address-edit');
    Route::post('account-address-update', 'address_update')->name('address-update');
    Route::post('address-delete', 'address_delete')->name('address-delete');
    Route::get('account-oder', 'account_oder')->name('account-oder'); // user orders
    Route::get('account-order-details', 'account_order_details')->name('account-order-details');
    Route::get('generate-invoice/{id}', 'generate_invoice')->name('generate-invoice');
    Route::get('account-wishlist', 'account_wishlist')->name('account-wishlist'); // user wishlist
    Route::get('refund-request/{id}', 'refund_request')->name('refund-request');
    Route::get('refund-details/{id}', 'refund_details')->name('refund-details');
    Route::get('submit-review/{id}', 'submit_review')->name('submit-review');
    Route::post('refund-store', 'store_refund')->name('refund-store');
    Route::get('account-tickets', 'account_tickets')->name('account-tickets'); // user support tickets
    Route::post('ticket-submit', 'ticket_submit')->name('ticket-submit'); // user support tickets submit
    Route::get('order-cancel/{id}', 'order_cancel')->name('order-cancel');
    Route::get('account-logout', 'accountLogout')->name('account-logout'); // user logout
});
