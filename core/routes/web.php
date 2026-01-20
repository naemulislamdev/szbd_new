<?php

use App\Http\Controllers\Front\FrontendController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->controller(FrontendController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/category', 'category')->name('category');
    Route::get('/product-details', 'productDeails')->name('product.details');
    Route::get('/shop', 'shop')->name('shop');
    Route::get('/outlets', 'outlets')->name('outlets');
    Route::get('/shop-cart', 'shop_cart')->name('shop-cart');
    Route::get('/special-offers', 'specialProducts')->name('offers.product');
    Route::post('/client-review', 'clientReview')->name('client_review');
    Route::get('/leads', 'leads')->name('leads');
    Route::post('/leads/store', 'leadsStore')->name('leads.store');
    Route::get('/color-variant', 'colorVariant')->name('color.variant');
    Route::post('/save-user-info', 'saveUserInfo')->name('save.user.info');
    Route::get('/careers', 'careers')->name('careers');
    Route::get('/offers/{slug}', 'discountOffers')->name('discount.offers');
    Route::get('/sitemap.xml', 'siteMaps')->name('sitemap');
});

require __DIR__.'/auth.php';
