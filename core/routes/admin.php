<?php

use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Backend\SubSubCategoryController;
use App\Http\Controllers\Backend\UserInfoController;
use Illuminate\Support\Facades\Route;

Route::prefix('/admin')->as('admin.')->group(function () {

    Route::controller(LoginController::class)->prefix('/auth')->as('auth.')->group(function () {
        Route::get('login', 'login')->name('login');
        Route::post('login/store', 'submit')->name('login.store');
        Route::get('logout', 'logout')->name('logout');
    });
    Route::middleware('admin')->group(function () {
        Route::controller(DashboardController::class)->prefix('/dashboard')->as('dashboard.')->group(function () {
            Route::get('/', 'dashboard')->name('index');
            Route::post('order-stats', 'order_stats')->name('order-stats');
            Route::post('business-overview', 'business_overview')->name('business-overview');
            Route::get('/admin/report/order/filter', 'OrderReportFilter')->name('order.report.filter');
        });

        Route::controller(ProductController::class)->prefix('/product')->as('product.')->group(function () {
            Route::get('Create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('datatables', 'datatables')->name('datatables');
            Route::get('list', 'index')->name('index');
            Route::get('show/{product}', 'show')->name('show');
            Route::get('edit/{product}', 'edit')->name('edit');
            Route::post('update/{product}', 'update')->name('update');
            Route::get('delete/{product}', 'delete')->name('delete');
            Route::post('product/status-update',  'statusUpdate')->name('status');
            Route::post('product/featured-update',  'updateFeatured')->name('featured.status');
            Route::post('product/arrival-update',  'updateArrival')->name('arrival.status');
            Route::post('sku-combination', 'sku_combination')->name('sku-combination');
            Route::get('remove-image', 'remove_image')->name('remove-image');
            Route::post('status-update', 'status_update')->name('status-update');
            Route::get('stock-limit-list/{type}', 'stock_limit_list')->name('stock-limit-list');
            Route::get('get-variations', 'get_variations')->name('get-variations');
            Route::post('update-quantity', 'update_quantity')->name('update-quantity');

            Route::get('barcode/generate', 'barcode_generate')->name('barcode.generate');
            Route::get('productsearch', 'productsearch')->name('productsearch');
            Route::get('updateProductFlatDiscount', 'updateProductFlatDiscount')->name('updateProductFlatDiscount');

            Route::get('/get-subcategories/{category_id}', 'getSubCategories')->name('get-subcategories');
            Route::get('/get-child-categories/{subcategory_id}', 'getChildCategories')->name('get-child-categories');
        });

        //order management
        Route::controller(OrderController::class)->prefix('/order')->as('order.')->group(function () {
            Route::get('list/', 'list')->name('list');
            Route::get('datatables/{slug}', 'datatables')->name('datatables');
            Route::get('detailsProduct/{product_id}', 'detailsProduct')->name('detailsProduct');
            Route::post("multiple-note", 'multipleNote')->name("multiple_note");
            Route::get('details/{id}', 'details')->name('details');
            Route::get('edit/{id}', 'edit')->name('edit');
            Route::post('update/{id}', 'orderUpdate')->name('update');
            Route::get('show/{id}', 'show')->name('show');
            Route::get('delete/{id}', 'delete')->name('delete');
            Route::post('ordersPrice/{id}', 'ordersPrice')->name('ordersPrice');

            Route::get('Individual/{status}', 'Individual')->name('Individual');
            Route::get('products/search/', 'productSearch')->name('products.search');
            Route::post('status', 'status')->name('status');
            Route::post('payment-status', 'payment_status')->name('payment-status');
            Route::post('advance-payment/{id}', 'advance_payment')->name('advance-payment');
            Route::post('productStatus', 'productStatus')->name('productStatus');
            Route::get('generate-invoice/{id}', 'generate_invoice')->name('generate-invoice')->withoutMiddleware(['module:order_management']);
            Route::get('exchange-generate-invoice/{id}', 'exchange_generate_invoice')->name('exchange-generate-invoice')->withoutMiddleware(['module:order_management']);
            Route::get('inhouse-order-filter', 'inhouse_order_filter')->name('inhouse-order-filter');

            Route::post('update-deliver-info', 'update_deliver_info')->name('update-deliver-info');
            Route::get('add-delivery-man/{order_id}/{d_man_id}', 'add_delivery_man')->name('add-delivery-man');

            Route::get('export-order-data/{status}', 'bulk_export_data')->name('order-bulk-export');
        });

        Route::controller(UserInfoController::class)->prefix('/userinfo')->as('userinfo.')->group(function () {
            Route::get('all', 'all')->name('all');
            Route::get('datatables/{slug}', 'datatables')->name('datatables');
            Route::post('show', 'show')->name('show');
            Route::get('delete/{userinfo}', 'delete')->name('delete');
            Route::post('/status-update', 'updateStatus')->name('status.update');
        });
        Route::controller(EmployeeController::class)->prefix('/employee')->as('employee.')->middleware('module:employee_section')->group(function () {
            Route::get('add-new', 'add_new')->name('add-new');
            Route::post('add-new', 'store');
            Route::get('list', 'list')->name('list');
            Route::get('update/{id}', 'edit')->name('update');
            Route::post('update/{id}', 'update');
            Route::get('status/{id}/{status}', 'status')->name('status');
        });

        Route::controller(CategoryController::class)->as('category.')->middleware('module:product_management')->group(function () {
            Route::get('/category/view', 'index')->name('view');
            Route::get('/category/fetch', 'fetch')->name('fetch');
            Route::post('/category/store', 'store')->name('store');
            Route::get('/category/edit/{id}', 'edit')->name('edit');
            Route::post('/category/update/{id}', 'update')->name('update');
            Route::post('/category/delete', 'delete')->name('delete');
            Route::post('/category/status', 'status')->name('status');
        });

        Route::controller(SubCategoryController::class)->as('sub-category.')->middleware('module:product_management')->group(function () {
            Route::get('/sub-category/view', 'index')->name('view');
            Route::get('/sub-category/fetch', 'fetch')->name('fetch');
            Route::post('/sub-category/store', 'store')->name('store');
            Route::post('/sub-category/edit', 'edit')->name('edit');
            Route::post('/sub-category/update/{id}', 'update')->name('update');
            Route::post('/sub-category/delete', 'delete')->name('delete');
        });

        Route::controller(SubSubCategoryController::class)->prefix('/child-category')->as('child-category.')->middleware('module:product_management')->group(function () {
            Route::get('view', 'index')->name('view');
            Route::get('fetch', 'fetch')->name('fetch');
            Route::post('store', 'store')->name('store');
            Route::post('edit', 'edit')->name('edit');
            Route::post('update/{id}', 'update')->name('update');
            Route::post('delete', 'delete')->name('delete');
            Route::post('get-sub-category', 'getSubCategory')->name('getSubCategory');
            Route::post('get-category-id', 'getCategoryId')->name('getCategoryId');
        });
    });
});
