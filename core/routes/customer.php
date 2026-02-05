<?php

use App\Http\Controllers\Customer\Auth\ForgotPasswordController;
use App\Http\Controllers\Customer\Auth\LoginController;
use App\Http\Controllers\Customer\Auth\RegisterController;
use App\Http\Controllers\Customer\Auth\SocialAuthController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\RewardPointController;
use App\Http\Controllers\Customer\SystemController;
use App\Http\Controllers\Front\ComplainController;
use Illuminate\Support\Facades\Route;

Route::prefix('/customer')->as('customer.')->group(function () {

    Route::prefix('/auth')->as('auth.')->group(function () {
        Route::get('/code/captcha/{tmp}', [LoginController::class, 'captcha'])->name('default-captcha');
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

    Route::group([], function () {
        Route::get('set-payment-method/{name}', [SystemController::class, 'set_payment_method'])->name('set-payment-method');
        Route::get('set-shipping-method', [SystemController::class, 'set_shipping_method'])->name('set-shipping-method');
        Route::get('set-pos-shipping-method', [SystemController::class, 'set_pos_shipping_method'])->name('set-pos-shipping-method');
        Route::post('checkout-complete', [SystemController::class, 'productCheckoutOrder'])->name('product.checkout.order');
        Route::post('checkout/complete', [SystemController::class, 'singlepCheckout'])->name('sproduct.checkout');
        Route::get('checkout-complete/{id}', [SystemController::class, 'checkoutComplete'])->name('checkout-complete');
        Route::post('choose-billing-address', [SystemController::class, 'choose_billing_address'])->name('choose-billing-address');

        Route::prefix('/reward-points')->as('reward-points.')->middleware('auth:customer')->group(function () {
            Route::get('convert', [RewardPointController::class, 'convert'])->name('convert');
        });
    });
});
