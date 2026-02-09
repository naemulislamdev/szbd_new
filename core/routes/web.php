<?php

use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\ChattingController;
use App\Http\Controllers\Front\CheckoutControl;
use App\Http\Controllers\Front\ComplainController;
use App\Http\Controllers\Front\CouponController;
use App\Http\Controllers\Front\FeedController;
use App\Http\Controllers\Front\FrontendController;
use App\Http\Controllers\Front\InvestorController;
use App\Http\Controllers\Front\ReviewController;
use App\Http\Controllers\Front\UserLoyaltyController;
use App\Http\Controllers\Customer\UserProfileController;
use App\Http\Controllers\Front\UserWalletController;
use App\Http\Controllers\Front\WholesaleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::controller(FrontendController::class)->group(function () {
        Route::get('/', 'home')->name('home');
        Route::get('/category', 'category')->name('category');
        Route::get('/product-details', 'productDeails')->name('product.details');
        Route::get('/shop', 'shop')->name('shop');
        Route::get('/outlets', 'outlets')->name('outlets');
        Route::get('/checkout', 'checkout')->name('checkout');
        Route::get('/special-offers', 'specialProducts')->name('offers.product');
        Route::post('/client-review', 'clientReview')->name('client_review');
        Route::get('/leads', 'leads')->name('leads');
        Route::post('/leads/store', 'leadsStore')->name('leads.store');
        Route::get('/color-variant', 'colorVariant')->name('color.variant');
        Route::post('/save-user-info', 'saveUserInfo')->name('save.user.info');
        Route::get('/careers', 'careers')->name('careers');
        Route::get('/offers/{slug}', 'discountOffers')->name('discount.offers');
        Route::get('/sitemap.xml', 'siteMaps')->name('sitemap');
        Route::get('/collections/{slug}', 'multiCollection')->name('collections');
        Route::get('/blogs', 'blogs')->name('blogs');
        Route::get('/blogs/{slug}', 'blogDetails')->name('blog.details');

        Route::get('set-category-all-product', 'get_category_all_product'); // Not necessary
        Route::post('subscription', 'subscription')->name('subscription');
        Route::get('categories', 'all_categories')->name('categories');
        Route::get('brands', 'all_brands')->name('brands');
        Route::get('seller-profile/{id}', 'seller_profile')->name('seller-profile');

        Route::get('flash-deals/{id}', 'flash_deals')->name('flash-deals');
        Route::get('terms', 'termsandCondition')->name('terms');
        Route::get('privacy-policy', 'privacy_policy')->name('privacy-policy');

        Route::get('/video-shopping', 'videoShopping')->name('video_shopping');
        Route::get('/campaign', 'campaing_products')->name('campain');
        Route::get('/product/{slug}', 'product')->name('product');
        Route::get('category/{category?}/{subcategory?}/{childcategory?}', 'products')->name('category.products');
        Route::get('search', 'homeSearch')->name('home.search');
        Route::get('orderDetails', 'orderdetails')->name('orderdetails');
        Route::get('/product_search', 'searchProducts')->name('product_search');

        Route::post('review-list-product', 'review_list_product')->name('review-list-product');
        //Chat with seller from product details
        Route::get('chat-for-product', 'chat_for_product')->name('chat-for-product');

        Route::get('wishlists', 'viewWishlist')->name('wishlists')->middleware('customer');
        Route::post('store-wishlist', 'storeWishlist')->name('store-wishlist');
        Route::post('delete-wishlist', 'deleteWishlist')->name('delete-wishlist');
        Route::get('about-us', 'about_us')->name('about-us');
        //FAQ route
        Route::get('helpTopic', 'helpTopic')->name('helpTopic');
        //Contacts
        Route::get('contacts', 'contacts')->name('contacts');

        //sellerShop
        Route::get('shopView/{id}', 'seller_shop')->name('shopView');
        Route::post('shopView/{id}', 'seller_shop_product');

        //top Rated
        Route::get('top-rated', 'top_rated')->name('topRated');
        Route::get('best-sell', 'best_sell')->name('bestSell');
        Route::get('new-product', 'new_product')->name('newProduct');

        // modify DB
        Route::get('/currency-convert', 'currency_convert');
        //End
    });

    // Checkout routes
    Route::controller(CheckoutControl::class)->group(function () {
        Route::post('/send-otp', 'sendOtp')->name('send.otp');
        Route::post('/verify-otp', 'verifyOtp')->name('verify.otp');
        // checkout route
        Route::get('set-shipping-method','set_shipping_method')->name('set-shipping-method');
        Route::post('checkout-complete',  'productCheckout')->name('product.checkout');
        Route::post('checkout/complete', 'singlepCheckout')->name('sproduct.checkout');
        Route::get('checkout-complete/{id}', 'checkoutComplete')->name('checkout-complete');
        Route::post('customer-address-update', 'customerAddressUpdate')->name('address.update');
    });

    // Investor routes
    Route::controller(InvestorController::class)->group(function () {
        Route::get('/investor', 'create')->name('investor.crate');
        Route::post('/investor/store', 'store')->name('investor.store');
    });
    // Wholesale routes
    Route::controller(WholesaleController::class)->group(function () {
        Route::get('/wholesale', 'create')->name('wholesale.crate');
        Route::post('/wholesale/store', 'store')->name('wholesale.store');
    });


    //Support Ticket
    Route::controller(UserProfileController::class)->prefix('/support-ticket')->as('support-ticket.')->group(function () {
        Route::get('{id}', 'single_ticket')->name('index');
        Route::post('{id}', 'comment_submit')->name('comment');
        Route::get('delete/{id}', 'support_ticket_delete')->name('delete');
        Route::get('close/{id}', 'support_ticket_close')->name('close');
    });

    Route::get('account-transaction', [UserProfileController::class, 'account_transaction'])->name('account-transaction');
    Route::get('account-wallet-history', [UserProfileController::class, 'account_wallet_history'])->name('account-wallet-history');

    Route::post('review', [ReviewController::class, 'store'])->name('review.store');

    Route::get('wallet', [UserWalletController::class, 'index'])->name('wallet');
    Route::get('loyalty', [UserLoyaltyController::class, 'index'])->name('loyalty');
    Route::post('loyalty-exchange-currency', [UserLoyaltyController::class, 'loyalty_exchange_currency'])->name('loyalty-exchange-currency');

    Route::controller(UserProfileController::class)->prefix('/track-order')->as('track-order.')->group(function () {
        Route::get('', 'track_order')->name('index');
        Route::get('result-view', 'track_order_result')->name('result-view');
        Route::get('last', 'track_last_order')->name('last');
        Route::any('result', 'track_order_result')->name('result');
    });

    Route::group(['prefix' => 'contact', 'as' => 'contact.'], function () {
        Route::post('store', [FrontendController::class, 'contact_store'])->name('store');
    });

    // Chatting start
    Route::get('chat-with-seller', [ChattingController::class, 'chat_with_seller'])->name('chat-with-seller');
    Route::get('messages', [ChattingController::class, 'messages'])->name('messages');
    Route::post('messages-store', [ChattingController::class, 'messages_store'])->name('messages_store');
    // chatting end

});
Route::get('/complain', [ComplainController::class, 'customerComplain'])->name('customer.complain');

Route::post('/complain/store', [ComplainController::class, 'customerComplainStore'])->name('customer.complain.store');
// facebook feed route
Route::get('/feed/facebook', [FeedController::class, 'facebookFeed']);
//check done
Route::controller(CartController::class)->prefix('/cart')->as('cart.')->group(function () {
    Route::post('variant_price', 'variant_price')->name('variant_price');
    Route::post('/add-product', 'addToCartOnSession')->name('add');
    Route::post('/remove', 'removeFromCart')->name('remove');
    Route::post('/nav-cart-items', 'updateNavCart')->name('nav_cart');
    Route::post('total-cart-count', 'totalCartCount')->name('totalCart');
    Route::post('/updateQuantity', 'updateQuantity')->name('updateQuantity');
    Route::get('/subdomain-ordernow/{id}', 'subdomainOrdernow');
    // In web.php
    // Route::post('/add-to-cart', 'CartController@addToCart')->name('add.to.cart');

});
//Seller shop apply
Route::controller(CouponController::class)->prefix('/coupon')->as('coupon.')->group(function () {
    Route::post('apply', 'apply')->name('apply');
});
//check done

Route::get('/page/{slug}', [FrontendController::class, 'signleProductLandingPage'])->name('signle.landing_page');
// Route::get('/{slug}', [FrontendController::class, 'landingPage'])->name('landing_page');

require __DIR__ . '/auth.php';
