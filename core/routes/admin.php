<?php

use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\LandingPagesController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\EmplyeeController;
use App\Http\Controllers\Backend\FranchiseController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Backend\SubSubCategoryController;
use App\Http\Controllers\Backend\UserInfoController;
use App\Http\Controllers\Backend\InvestorController;
use App\Http\Controllers\Backend\PermissionModuleController;
use App\Http\Controllers\Backend\RolePermissionController;
use App\Http\Controllers\Backend\WholesaleController;
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
            Route::post('update/{id}', 'update')->name('update');
            Route::get('delete/{product}', 'delete')->name('delete');
            Route::post('product/status-update',  'statusUpdate')->name('status');
            Route::post('product/featured-update',  'updateFeatured')->name('featured.status');
            Route::post('product/arrival-update',  'updateArrival')->name('arrival.status');
            Route::post('sku-combination', 'sku_combination')->name('sku-combination');
            Route::post('color-combination', 'color_combination')->name('color-combination');
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
            Route::get('delete/{id}', 'delete')->name('delete');

            Route::get('product-search', 'productSearch')->name('product_search');
            Route::post('add-product', 'addProduct')->name('add_product');
            Route::post('remove-product', 'removeProduct')->name('remove_product');
            Route::get('recalculate/{order}', 'recalculate')->name('recalculate');
            Route::get('product-variation/{id}', 'productVariation')->name('product_variation');



            // Route::get('Individual/{status}', 'Individual')->name('Individual');
            // Route::get('products/search/', 'productSearch')->name('products.search');
            Route::post('status', 'status')->name('status');
            Route::post('payment-status', 'payment_status')->name('payment-status');
            Route::post('advance-payment/{id}', 'advance_payment')->name('advance-payment');
            // Route::post('productStatus', 'productStatus')->name('productStatus');
            Route::get('generate-invoice/{id}', 'generate_invoice')->name('generate-invoice');
            // Route::get('exchange-generate-invoice/{id}', 'exchange_generate_invoice')->name('exchange-generate-invoice');
            // Route::get('inhouse-order-filter', 'inhouse_order_filter')->name('inhouse-order-filter');

            // Route::post('update-deliver-info', 'update_deliver_info')->name('update-deliver-info');
            // Route::get('add-delivery-man/{order_id}/{d_man_id}', 'add_delivery_man')->name('add-delivery-man');

            Route::get('export-order-data/{status}', 'bulk_export_data')->name('order-bulk-export');
        });

        Route::controller(UserInfoController::class)->prefix('/userinfo')->as('userinfo.')->group(function () {
            Route::get('all', 'all')->name('all');
            Route::get('datatables/{slug}', 'datatables')->name('datatables');
            Route::post('show', 'show')->name('show');
            Route::get('delete/{userinfo}', 'delete')->name('delete');
            Route::post('/status-update', 'updateStatus')->name('status.update');
        });
        // Route::controller(EmployeeController::class)->prefix('/employee')->as('employee.')->middleware('module:employee_section')->group(function () {
        //     Route::get('add-new', 'add_new')->name('add-new');
        //     Route::post('add-new', 'store');
        //     Route::get('list', 'list')->name('list');
        //     Route::get('update/{id}', 'edit')->name('update');
        //     Route::post('update/{id}', 'update');
        //     Route::get('status/{id}/{status}', 'status')->name('status');
        // });

        Route::controller(CategoryController::class)->as('category.')->group(function () {
            Route::get('/category/view', 'index')->name('view');
            Route::get('/category/fetch', 'fetch')->name('fetch');
            Route::post('/category/store', 'store')->name('store');
            Route::post('/category/update', 'update')->name('update');
            Route::post('/category/delete', 'delete')->name('delete');
            Route::post('/category/status', 'status')->name('status');
            Route::get('/category/datatables', 'datatables')->name('datatables');
        });

        Route::controller(SubCategoryController::class)->as('sub-category.')->group(function () {
            Route::get('/sub-category/view', 'index')->name('view');
            Route::post('/sub-category/store', 'store')->name('store');
            Route::post('/sub-category/update', 'update')->name('update');
            Route::post('/sub-category/delete', 'delete')->name('delete');
            Route::get('/sub-category/datatables', 'datatables')->name('datatables');
            Route::post('/sub-category/status', 'status')->name('status');
        });

        Route::controller(SubSubCategoryController::class)->prefix('/child-category')->as('child-category.')->group(function () {
            Route::get('view', 'index')->name('view');
            Route::post('store', 'store')->name('store');
            Route::post('update', 'update')->name('update');
            Route::post('delete', 'delete')->name('delete');
            Route::post('get-sub-category', 'getSubCategory')->name('getSubCategory');
            Route::post('get-category-id', 'getCategoryId')->name('getCategoryId');
            Route::post('/child-category/status', 'status')->name('status');
            Route::get('/child-category/datatables', 'datatables')->name('datatables');
        });
        Route::controller(LandingPagesController::class)->prefix('/landingpages')->as('landingpages.')->group(function () {
            Route::get('multiple-product/landing', 'multiIndex')->name('multiple.index');
            Route::post('multiple-product/store', 'multipleStore')->name('multiple.store');
            Route::post('multiple-product/status-update', 'status_update')->name('multiple.status-update');
            Route::post('multiple-product/landing_pages_update', 'update')->name('multiple.landing_pages_update');
            Route::get('multiple-product/add-product/{landing_id}', 'add_product')->name('multiple.add-product');
            Route::post('multiple-product/store-product/{landing_id}', 'add_product_submit');
            Route::post('multiple-product/delete-product', 'delete_product')->name('multiple.delete-product');
            Route::get('/multiple-product/datatables', 'multipleProductdatatables')->name('multiple.datatables');
            Route::post('withSlideStatus', 'LandingPageWithSlide')->name('withSlideStatus');
            Route::post('multiple-product/remove', 'removeMultiplePage')->name('remove_multiple_page');

            // multiple product added routes
            Route::get("multiple-prouct-create/{id}", 'multipleProductCreate')->name("createMultipleProduct");
            Route::get("multiple-prouct-added-datatable/{id}", 'addedProductsDatatable')->name("addedProductDatatable");
            Route::post('multiple-product/delete-added-product', 'delete_added_product')->name('multiple.delete-added-product');
            Route::post('multiple-product/store-multiple-product', 'multipleProductsAddedStore')->name('multiple.products.store');


            // Single Product Landing page routes
            Route::get('/single-product/view', 'singleIndex')->name('single.index');
            Route::get('/single-product/datatables', 'singleProductdatatables')->name('single.datatables');
            Route::post('/single-product/status', 'LandingPageStatus')->name('single.status');
            Route::post('/single-product/remove', 'removeSinglePage')->name('remove_single_page');
            Route::get('/single-product/create', 'create')->name('single.create');
            Route::post('/single-product/store', 'storeSingleProduct')->name('single.store');
        });
        Route::controller(BannerController::class)->prefix('/banner')->as('banner.')->group(function () {
            Route::get('list', 'list')->name('list');
            Route::get('datatables', 'datatables')->name('datatables');
            Route::post('store', 'store')->name('store');
            Route::post('status', 'status')->name('status');
            Route::post('delete', 'delete')->name('delete');
            Route::post('update', 'update')->name('update');
        });

        // Customers Routes
        Route::controller(CustomerController::class)->prefix('/customer')->as('customer.')->group(function () {
            Route::get('list', 'customer_list')->name('list');
            Route::get('datatables', 'datatables')->name('datatables');
            Route::post('delete', 'delete')->name('delete');
            Route::post('status', 'status')->name('status');

            // Customer Details route
            Route::get('view/{id}', 'view')->name('view');
            Route::get('customer-datatables/{customer_id}', 'customerDatatables')->name('customerDatatables');
        });
        // Investors Routes
        Route::controller(InvestorController::class)->prefix('/investors')->as('investors.')->group(function () {
            Route::get('list', 'investorsList')->name('list');
            Route::get('datatables', 'datatables')->name('datatables');
            Route::post('delete', 'delete')->name('delete');
            Route::post('status', 'status')->name('status');
            Route::post('remark', 'remarkStore')->name('remark.store');
            // Route::get('bulk-export', 'bulk_export_investors')->name('bulk-export');
        });
        // Franchise Routes
        Route::controller(FranchiseController::class)->prefix('/franchise')->as('franchise.')->group(function () {
            Route::get('list', 'list')->name('list');
            Route::get('datatables', 'datatables')->name('datatables');
            Route::post('delete', 'destroy')->name('delete');
            Route::post('status', 'status')->name('status');
            Route::post('remark', 'updateLeadRemark')->name('remark.store');
            // Route::get('bulk-export', 'bulk_export_investors')->name('bulk-export');
        });
        // Wholesale Routes
        Route::controller(WholesaleController::class)->prefix('/wholesale')->as('wholesale.')->group(function () {
            Route::get('list', 'list')->name('list');
            Route::get('datatables', 'datatables')->name('datatables');
            Route::post('delete', 'destroy')->name('delete');
            Route::post('status', 'status')->name('status');
            // Route::post('remark', 'updateLeadRemark')->name('remark.store');
            // Route::get('bulk-export', 'bulk_export_investors')->name('bulk-export');
        });
        // Employee Routes
        Route::controller(EmplyeeController::class)->prefix('/employee')->as('employee.')->group(function () {
            Route::get('list', 'list')->name('list');
            Route::get('datatables', 'datatables')->name('datatables');
            Route::post('delete', 'destroy')->name('delete');
            Route::post('status', 'status')->name('status');
            Route::post('store', 'store')->name('store');
            Route::post('update', 'update')->name('update');
            // Route::get('bulk-export', 'bulk_export_investors')->name('bulk-export');
        });
        // Admin Roles Routes
        Route::controller(RolePermissionController::class)->prefix('/role-permission')->as('role_permission.')->group(function () {
            Route::get('list', 'list')->name('list');
            Route::get('datatables', 'datatables')->name('datatables');
            Route::post('delete', 'destroy')->name('delete');
            Route::post('status', 'status')->name('status');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::post('update', 'update')->name('update');
            // Route::get('bulk-export', 'bulk_export_investors')->name('bulk-export');
        });
        // Admin Roles Modules Routes
        Route::controller(PermissionModuleController::class)->prefix('/permission-module')->as('permission_module.')->group(function () {
            Route::get('list', 'list')->name('list');
            Route::get('datatables', 'datatables')->name('datatables');
            Route::post('delete', 'destroy')->name('delete');
            Route::post('store', 'store')->name('store');
            Route::post('update', 'update')->name('update');
            // Route::post('status', 'status')->name('status');
            // Route::get('bulk-export', 'bulk_export_investors')->name('bulk-export');
        });
    });
});
