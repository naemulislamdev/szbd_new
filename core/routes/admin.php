<?php

use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Backend\SubSubCategoryController;
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
