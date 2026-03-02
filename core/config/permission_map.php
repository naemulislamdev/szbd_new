<?php

// route_name => permission_name
return [
    // Products Route name
    'admin.product.index' => 'product_view',
    'admin.product.create' => 'product_create',
    'admin.product.edit' => 'product_edit',
    'admin.product.delete' => 'product_delete',

    /*
    |--------------------------------------------------------------------------
    | Order Module Routes
    |--------------------------------------------------------------------------
    */

    // Main list route (status based dynamic permission)
    'admin.order.list' => [
        'type' => 'order_status'
    ],

    // Other order routes (static permission)
    'admin.order.details'        => 'order_view',
    'admin.order.detailsProduct' => 'order_view',
    'admin.order.delete'         => 'order_delete',
    'admin.order.status'         => 'order_processing',
    'admin.order.payment-status' => 'order_processing',
    'admin.order.advance-payment' => 'order_processing',
    'admin.order.generate-invoice' => 'order_view',
    'admin.order.data_export'    => 'order_view',
    'admin.order.add_product'    => 'order_processing',
    'admin.order.remove_product' => 'order_processing',

    /*
    |--------------------------------------------------------------------------
    | User info Module Routes
    |--------------------------------------------------------------------------
    */
    'admin.userinfo.all' => 'userinfo_all',
    'admin.userinfo.delete' => 'userinfo_delete',
    'admin.userinfo.data_export' => 'userinfo_data_export',
    /*
    |--------------------------------------------------------------------------
    | Product Settings Module Routes
    |--------------------------------------------------------------------------
    */
    'admin.category.view' => 'category_view',
    'admin.sub-category.view' => 'sub_category_view',
    'admin.child-category.view' => 'child_category_view',
    'admin.brand.list' => 'brand_list',
    'admin.attribute.list' => 'attribute_list',

    /*
    |--------------------------------------------------------------------------
    | Landing Pages Module Routes
    |--------------------------------------------------------------------------
    */
    'admin.landingpages.single.index' => 'signle_page_view',
    'admin.landingpages.multiple.index' => 'multiple_page_view',

    //Others route
    'admin.banner.list' => 'banner_view',
    'admin.customer.list' => 'customer_view',
    'admin.investors.list' => 'investor_view',
    'admin.franchise.list' => 'franchise_view',
    'admin.wholesale.list' => 'wholesale_view',
    /*
    |--------------------------------------------------------------------------
    | Discount Management Module Routes
    |--------------------------------------------------------------------------
    */
    'admin.discount.flat' => 'discount_flat',
    'admin.discount.batch' => 'discount_batch',
    'admin.discount.offers' => 'discount_offers',
    'admin.discount.eid.offers' => 'discount_eid_offers',

    // Website Configuration
    'admin.web_config.view' => 'web_config',
    //Blogs Management
    'admin.blog.categoryList' => 'blog_category_view',
    'admin.blog.list' => 'blog_view',
    // Career Management
    'admin.career.department' => 'department_view',
    'admin.career.view' => 'career_view',
    'admin.application.view' => 'application_view',
    // Others
    'admin.coupon.view' => 'coupon_view',
    'admin.branch.list' => 'branch_view',
    // Reports
    'admin.report.dailySales' => 'daily_sales',
    'admin.report.productReport' => 'product_report',
    'admin.report.topSellingProducts' => 'top_sales',
    'admin.report.profitReport' => 'porfit_report',

    // Role & Permission
    'admin.role_permission.list' => 'role_view',
    'admin.role_permission.create' => 'role_create',
    'admin.role_permission.edit' => 'role_edit',
    'admin.role_permission.delete' => 'role_delete',
    'admin.role_permission.status' => 'role_status',
    // Employee
    'admin.employee.list' => 'employee_view',
    'admin.employee.store' => 'employee_create',
    'admin.employee.delete' => 'employee_delete',
    // Permission Modules
    'admin.permission_module.list' => 'module_view',
    'admin.permission_module.store' => 'module_create',
    'admin.permission_module.delete' => 'module_delete',

];
