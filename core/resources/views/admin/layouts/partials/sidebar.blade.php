@php
    $routeName = request()->route()->getName();
@endphp
<div class="d-flex align-items-start flex-column w-100">
    <!-- Navigation -->
    <ul class="navbar-nav mb-auto w-100">
        <li class="menu-label mt-2">
            <span>Navigation</span>
        </li>
        {{-- @dd(auth('admin')->user()->hasRole('super-admin')) --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard.index') }}">
                <i class="iconoir-report-columns menu-icon"></i>
                <span>Dashboard</span>
                <span class="badge text-bg-warning ms-auto"></span>
            </a>
        </li><!--end nav-item-->
        @php
            $statuses = [
                'all' => [
                    'permission' => 'order_all',
                    'label' => 'All Orders',
                    'badge' => 'dark',
                    'count' => $orderCounts->total ?? 0,
                ],
                'pending' => [
                    'permission' => 'order_pending',
                    'label' => 'Pending',
                    'badge' => 'warning',
                    'count' => $orderCounts->pending ?? 0,
                ],
                'confirmed' => [
                    'permission' => 'order_confirmed',
                    'label' => 'Confirmed',
                    'badge' => 'primary',
                    'count' => $orderCounts->confirmed ?? 0,
                ],
                'processing' => [
                    'permission' => 'order_processing',
                    'label' => 'Processing',
                    'badge' => 'info',
                    'count' => $orderCounts->processing ?? 0,
                ],
                'out_for_delivery' => [
                    'permission' => 'order_out_for_delivery',
                    'label' => 'Out for delivery',
                    'badge' => 'primary',
                    'count' => $orderCounts->out_for_delivery ?? 0,
                ],
                'delivered' => [
                    'permission' => 'order_delivered',
                    'label' => 'Delivery',
                    'badge' => 'success',
                    'count' => $orderCounts->delivered ?? 0,
                ],
                'returned' => [
                    'permission' => 'order_returned',
                    'label' => 'Returned',
                    'badge' => 'secondary',
                    'count' => $orderCounts->returned ?? 0,
                ],
                'failed' => [
                    'permission' => 'order_failed',
                    'label' => 'Failed',
                    'badge' => 'dark',
                    'count' => $orderCounts->failed ?? 0,
                ],
                'canceled' => [
                    'permission' => 'order_canceled',
                    'label' => 'Canceled',
                    'badge' => 'danger',
                    'count' => $orderCounts->canceled ?? 0,
                ],
            ];
        @endphp
        @canAny(['order_all', 'order_pending', 'order_confirmed', 'order_processing', 'order_out_for_delivery',
            'order_delivered', 'order_returned', 'order_failed', 'order_canceled'])
            @php
                $isOrderRoute = $routeName == 'admin.order.list';
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $isOrderRoute ? 'active' : '' }}" href="#sidebarEcommerce" data-bs-toggle="collapse"
                    role="button" aria-expanded="{{ $isOrderRoute ? 'true' : 'false' }}" aria-controls="sidebarEcommerce">
                    <i class="iconoir-cart-alt menu-icon"></i>
                    <span>Orders</span>
                </a>

                <div class="collapse {{ $isOrderRoute ? 'show' : '' }}" id="sidebarEcommerce">
                    <ul class="nav flex-column">
                        @foreach ($statuses as $status => $data)
                            @can($data['permission'])
                                @php
                                    $isActive =
                                        $isOrderRoute &&
                                        (($status == 'all' && !request('status')) || request('status') == $status);
                                @endphp

                                <li class="nav-item">
                                    <a class="nav-link {{ $isActive ? 'active' : '' }}"
                                        href="{{ $status == 'all' ? route('admin.order.list') : route('admin.order.list', ['status' => $status]) }}">
                                        {{ $data['label'] }}
                                        <span class="badge bg-{{ $data['badge'] }} ms-2">
                                            {{ $data['count'] }}
                                        </span>
                                    </a>
                                </li>
                            @endcan
                        @endforeach
                    </ul>
                </div>
            </li><!--end nav-item-->
        @endcanAny

        @canAny(['product_create', 'product_view', 'product_edit', 'product_delete'])

            <li class="nav-item">
                <a class="nav-link" href="#productsDropDown" data-bs-toggle="collapse" role="button" aria-expanded="false"
                    aria-controls="productsDropDown">
                    <i class="iconoir-reports menu-icon"></i>
                    <span>Products</span>
                </a>
                <div class="collapse " id="productsDropDown">
                    <ul class="nav flex-column">
                        @can('product_create')
                            <li class="nav-item">
                                <a href="{{ route('admin.product.create') }}" class="nav-link ">Add New Product</a>
                            </li><!--end nav-item-->
                        @endcan
                        @can('product_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.product.index') }}" class="nav-link ">All Products</a>
                            </li><!--end nav-item-->
                        @endcan
                    </ul><!--end nav-->
                </div>
            </li><!--end nav-item-->
        @endcanAny
        @php
            $userInfoStatuses = [
                'all' => [
                    'permission' => 'userinfo_all',
                    'label' => 'All User Info',
                    'badge' => 'dark',
                    'count' => $userInfoCounts->total ?? 0,
                ],
                'pending' => [
                    'permission' => 'userinfo_pending',
                    'label' => 'Pending',
                    'badge' => 'warning',
                    'count' => $userInfoCounts->pending ?? 0,
                ],
                'confirmed' => [
                    'permission' => 'userinfo_confirmed',
                    'label' => 'Confirmed',
                    'badge' => 'success',
                    'count' => $userInfoCounts->confirmed ?? 0,
                ],
                'canceled' => [
                    'permission' => 'userinfo_canceled',
                    'label' => 'Canceled',
                    'badge' => 'danger',
                    'count' => $userInfoCounts->canceled ?? 0,
                ],
            ];
        @endphp
        @canAny(['userinfo_all', 'userinfo_pending', 'userinfo_confirmed', 'userinfo_canceled'])
            @php
                $isUserInfoRoute = $routeName == 'admin.userinfo.all';
            @endphp
            <li class="nav-item">
                <a class="nav-link {{ $isUserInfoRoute ? 'active' : '' }}" href="#userInfoDropDown"
                    data-bs-toggle="collapse" role="button" aria-expanded="{{ $isUserInfoRoute ? 'true' : 'false' }}"
                    aria-controls="userInfoDropDown">
                    <i class="iconoir-community menu-icon"></i>
                    <span>User Info</span>
                </a>

                <div class="collapse {{ $isUserInfoRoute ? 'show' : '' }}" id="userInfoDropDown">
                    <ul class="nav flex-column">
                        @foreach ($userInfoStatuses as $status => $data)
                            @can($data['permission'])
                                @php
                                    $isActive =
                                        $routeName == 'admin.userinfo.all' &&
                                        (($status == 'all' && !request('status')) || request('status') == $status);
                                @endphp

                                <li class="nav-item">
                                    <a href="{{ $status == 'all' ? route('admin.userinfo.all') : route('admin.userinfo.all', ['status' => $status]) }}"
                                        class="nav-link {{ $isActive ? 'active' : '' }}">
                                        {{ $data['label'] }}
                                        <span class="badge bg-{{ $data['badge'] }} ms-2">
                                            {{ $data['count'] }}
                                        </span>
                                    </a>
                                </li>
                            @endcan
                        @endforeach
                    </ul>
                </div>
            </li>
        @endcanAny
        @php
            $productRoutes = ['admin.category.view', 'admin.sub-category.view', 'admin.child-category.view'];

            $isProductSettingActive = request()->routeIs($productRoutes);
        @endphp

        @canAny(['category_view', 'sub_category_view', 'child_category_view', 'brand_list', 'attribute_list'])
            <li class="nav-item">
                <a class="nav-link {{ $isProductSettingActive ? 'active' : '' }}" href="#productsSettingDropdown"
                    data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ $isProductSettingActive ? 'true' : 'false' }}"
                    aria-controls="productsSettingDropdown">
                    <i class="las la-cog menu-icon"></i>
                    <span>Product Settings</span>
                </a>

                <div class="collapse {{ $isProductSettingActive ? 'show' : '' }}" id="productsSettingDropdown">
                    <ul class="nav flex-column">

                        @can('category_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.category.view') }}"
                                    class="nav-link {{ request()->routeIs('admin.category.view') ? 'active' : '' }}">
                                    Category
                                </a>
                            </li>
                        @endcan

                        @can('sub_category_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.sub-category.view') }}"
                                    class="nav-link {{ request()->routeIs('admin.sub-category.view') ? 'active' : '' }}">
                                    Sub Category
                                </a>
                            </li>
                        @endcan

                        @can('child_category_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.child-category.view') }}"
                                    class="nav-link {{ request()->routeIs('admin.child-category.view') ? 'active' : '' }}">
                                    Child Category
                                </a>
                            </li>
                        @endcan
                        @can('brand_list')
                            <li class="nav-item">
                                <a href="{{ route('admin.brand.list') }}"
                                    class="nav-link {{ request()->routeIs('admin.brand.*') ? 'active' : '' }}">
                                    Brand List
                                </a>
                            </li>
                        @endcan
                        @can('attribute_list')
                            <li class="nav-item">
                                <a href="{{ route('admin.attribute.list') }}"
                                    class="nav-link {{ request()->routeIs('admin.attribute.*') ? 'active' : '' }}">
                                    Attribute
                                </a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcanAny

        @php
            $landingPageRoutes = ['admin.landingpages.single.index', 'admin.landingpages.multiple.index'];
            $isLandingPageActive = request()->routeIs($landingPageRoutes);
        @endphp

        @canAny(['signle_page_view', 'multiple_page_view'])
            <li class="nav-item">
                <a class="nav-link {{ $isLandingPageActive ? 'active' : '' }}" href="#landingPageDropdown"
                    data-bs-toggle="collapse" role="button" aria-expanded="{{ $isLandingPageActive ? 'true' : 'false' }}"
                    aria-controls="landingPageDropdown">
                    <i class="la la-bolt menu-icon"></i>
                    <span>Landing Pages</span>
                </a>
                <div class="collapse {{ $isLandingPageActive ? 'show' : '' }}" id="landingPageDropdown">
                    <ul class="nav flex-column">
                        @can('signle_page_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.landingpages.single.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.landingpages.single.index') ? 'active' : '' }}">Single
                                    Product</a>
                            </li>
                        @endcan
                        @can('multiple_page_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.landingpages.multiple.index') }}"
                                    class="nav-link {{ request()->routeIs('admin.landingpages.multiple.index') ? 'active' : '' }}">Multiple
                                    Product</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcanAny
        @canAny(['banner_view'])
            <li class="nav-item">
                <a class="nav-link" href="#bannerDropdown" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="bannerDropdown">
                    <i class="la la-desktop menu-icon"></i>
                    <span>Banners</span>
                </a>
                <div class="collapse {{ request()->routeIs('admin.banner.list') ? 'show' : '' }}" id="bannerDropdown">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.banner.list') }}"
                                class="nav-link {{ request()->routeIs('admin.banner.list') ? 'active' : '' }}">Banner
                                List</a>
                        </li>
                    </ul><!--end nav-->
                </div>
            </li><!--end nav-item-->
        @endcanAny
        @canAny(['customer_view'])
            <li class="nav-item">
                <a class="nav-link" href="#customersDropdown" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="customersDropdown">
                    <i class="iconoir-community  menu-icon"></i>
                    <span>Customers</span>
                </a>
                <div class="collapse {{ request()->routeIs('admin.customer.list') ? 'show' : '' }}"
                    id="customersDropdown">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.customer.list') }}"
                                class="nav-link {{ request()->routeIs('admin.customer.list') ? 'active' : '' }}">Customers
                                List</a>
                        </li>
                    </ul><!--end nav-->
                </div>
            </li><!--end nav-item-->
        @endcanAny
        @canAny(['investor_view'])
            <li class="nav-item">
                <a class="nav-link" href="#investorDropdown" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="investorDropdown">
                    <i class="las la-hand-holding-usd menu-icon"></i>
                    <span>Investors</span>
                </a>
                <div class="collapse {{ request()->routeIs('admin.investors.list') ? 'show' : '' }}"
                    id="investorDropdown">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.investors.list') }}"
                                class="nav-link {{ request()->routeIs('admin.investors.list') ? 'active' : '' }}">Investors
                                List</a>
                        </li>
                    </ul><!--end nav-->
                </div>
            </li><!--end nav-item-->
        @endcanAny
        @canAny(['franchise_view'])
            <li class="nav-item">
                <a class="nav-link" href="#franchiseDropdown" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="franchiseDropdown">
                    <i class="las la-handshake menu-icon"></i>
                    <span>Franchise</span>
                </a>
                <div class="collapse {{ request()->routeIs('admin.franchise.list') ? 'show' : '' }}"
                    id="franchiseDropdown">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.franchise.list') }}"
                                class="nav-link {{ request()->routeIs('admin.franchise.list') ? 'active' : '' }}">Franchise
                                List</a>
                        </li>
                    </ul><!--end nav-->
                </div>
            </li><!--end nav-item-->
        @endcanAny
        @canAny(['wholesale_view'])
            <li class="nav-item">
                <a class="nav-link" href="#wholesaleDropdown" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="wholesaleDropdown">
                    <i class="las la-truck-moving menu-icon"></i>
                    <span>Wholesale</span>
                </a>
                <div class="collapse {{ request()->routeIs('admin.wholesale.list') ? 'show' : '' }}"
                    id="wholesaleDropdown">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.wholesale.list') }}"
                                class="nav-link {{ request()->routeIs('admin.wholesale.list') ? 'active' : '' }}">Wholesale
                                List</a>
                        </li>
                    </ul><!--end nav-->
                </div>
            </li><!--end nav-item-->
        @endcanAny

        @php
            $isDiscountRoute = request()->routeIs([
                'admin.discount.flat',
                'admin.discount.batch',
                'admin.discount.offers',
                'admin.discount.eid.offers',
            ]);
        @endphp

        @canAny(['discount_flat', 'discount_batch', 'discount_offers', 'discount_eid_offers'])
            <li class="nav-item">
                <a class="nav-link {{ $isDiscountRoute ? 'active' : '' }}" href="#discountDropdown"
                    data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="discountDropdown">
                    <i class="las la-tags menu-icon"></i>
                    <span>Discount Management</span>
                </a>
                <div class="collapse {{ $isDiscountRoute ? 'show' : '' }}" id="discountDropdown">
                    <ul class="nav flex-column">
                        @can('discount_flat')
                            <li class="nav-item">
                                <a href="{{ route('admin.discount.flat') }}"
                                    class="nav-link {{ request()->routeIs('admin.discount.flat') ? 'active' : '' }}">Flat
                                    Discount</a>
                            </li>
                        @endcan
                        @can('discount_batch')
                            <li class="nav-item">
                                <a href="{{ route('admin.discount.batch') }}"
                                    class="nav-link {{ request()->routeIs('admin.discount.batch') ? 'active' : '' }}">Batch
                                    Discount</a>
                            </li>
                        @endcan
                        @can('discount_offers')
                            <li class="nav-item">
                                <a href="{{ route('admin.discount.offers') }}"
                                    class="nav-link {{ request()->routeIs('admin.discount.offers') ? 'active' : '' }}">Discount
                                    Offers</a>
                            </li>
                        @endcan
                        @can('discount_eid_offers')
                            <li class="nav-item">
                                <a href="{{ route('admin.discount.eid.offers') }}"
                                    class="nav-link {{ request()->routeIs('admin.discount.eid.offers') ? 'active' : '' }}">Eid
                                    Offers ✨🌙</a>
                            </li>
                        @endcan
                    </ul><!--end nav-->
                </div>
            </li><!--end nav-item-->
        @endcanAny

        @php
            $blogRoutes = request()->routeIs(['admin.blog.categoryList', 'admin.blog.list']);
        @endphp
        @canAny(['blog_view', 'blog_category_view'])
            <li class="nav-item">
                <a class="nav-link {{ $blogRoutes ? 'active' : '' }}" href="#blogDropDown" data-bs-toggle="collapse"
                    role="button" aria-expanded="{{ $blogRoutes ? 'true' : 'false' }}" aria-controls="blogDropDown">
                    <i class="las la-blog menu-icon"></i>
                    <span>Blogs Management</span>
                </a>
                <div class="collapse {{ $blogRoutes ? 'show' : '' }}" id="blogDropDown">
                    <ul class="nav flex-column">
                        @can('blog_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.blog.categoryList') }}"
                                    class="nav-link {{ request()->routeIs('admin.blog.categoryList') ? 'active' : '' }}">Blog
                                    Category</a>
                            </li>
                        @endcan
                        @can('blog_category_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.blog.list') }}"
                                    class="nav-link {{ request()->routeIs('admin.blog.list') ? 'active' : '' }}">Blog</a>
                            </li>
                        @endcan
                    </ul><!--end nav-->
                </div>
            </li><!--end nav-item-->
        @endcanAny
        @php
            $careerRoutes = request()->routeIs([
                'admin.career.department',
                'admin.career.view',
                'admin.application.view',
            ]);
        @endphp
        @canany(['department_view', 'career_view', 'application_view'])
            <li class="nav-item">
                <a class="nav-link {{ $careerRoutes ? 'active' : '' }}" href="#careerDropdown" data-bs-toggle="collapse"
                    role="button" aria-expanded=" {{ $careerRoutes ? 'true' : 'false' }}" aria-controls="blogDropDown">
                    <i class="las la-briefcase menu-icon"></i>
                    <span>Career Management</span>
                </a>
                <div class="collapse  {{ $careerRoutes ? 'active' : '' }}" id="careerDropdown">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.career.department') }}"
                                class="nav-link {{ request()->routeIs('admin.career.department') ? 'active' : '' }}">Departments</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.career.view') }}"
                                class="nav-link {{ request()->routeIs('admin.career.view') ? 'active' : '' }}">Job
                                Posts</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.application.view') }}"
                                class="nav-link {{ request()->routeIs('admin.application.view') ? 'active' : '' }}">Applications</a>
                        </li>
                    </ul><!--end nav-->
                </div>
            </li><!--end nav-item-->
        @endcanany
        @can(['coupon_view'])
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.coupon.view') ? 'active' : '' }}"
                    href="{{ route('admin.coupon.view') }}">
                    <i class="las la-gift menu-icon"></i>
                    <span>Coupon</span>
                </a>
            </li><!--end nav-item-->
        @endcan
        @can(['contact_view'])
            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="las la-phone menu-icon"></i>
                    <span>Contact List</span>
                </a>
            </li><!--end nav-item-->
        @endcan
        @can(['branch_view'])
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.branch.list') ? 'active' : '' }}"
                    href="{{ route('admin.branch.list') }}">
                    <i class="las la-store menu-icon"></i>
                    <span>Branch List</span>
                </a>
            </li><!--end nav-item-->
        @endcan
        @canAny(['daily_sales', 'product_report', 'top_sales', 'porfit_report'])
            <li class="nav-item">
                <a class="nav-link" href="#reportsDropdown" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="reportsDropdown">
                    <i class="las la-file-alt menu-icon"></i>
                    <span>Reports</span>
                </a>
                <div class="collapse " id="reportsDropdown">
                    <ul class="nav flex-column">
                        @can('daily_sales')
                            <li class="nav-item">
                                <a href="{{ route('admin.report.dailySales') }}"
                                    class="nav-link {{ request()->routeIs('admin.report.dailySales') ? 'active' : '' }}">Daily
                                    Sales Report</a>
                            </li>
                        @endcan
                        @can('product_report')
                            <li class="nav-item">
                                <a href="{{ route('admin.report.productReport') }}"
                                    class="nav-link {{ request()->routeIs('admin.report.productReport') ? 'active' : '' }}">Product
                                    Report</a>
                            </li>
                        @endcan
                        @can('top_sales')
                            <li class="nav-item">
                                <a href="{{ route('admin.report.topSellingProducts') }}"
                                    class="nav-link {{ request()->routeIs('admin.report.topSellingProducts') ? 'active' : '' }}">Top
                                    Selling Product</a>
                            </li>
                        @endcan
                        @can('porfit_report')
                            <li class="nav-item">
                                <a href="{{ route('admin.report.profitReport') }}"
                                    class="nav-link {{ request()->routeIs('admin.report.profitReport') ? 'active' : '' }}">Profit
                                    Report</a>
                            </li>
                        @endcan
                        @can('track_visitor_report')
                            <li class="nav-item">
                                <a href="{{ route('admin.report.trackVisitorReport') }}"
                                    class="nav-link {{ request()->routeIs('admin.report.trackVisitorReport') ? 'active' : '' }}">Track
                                    Visitor Report</a>
                            </li>
                        @endcan
                    </ul><!--end nav-->
                </div>
            </li><!--end nav-item-->
        @endcanAny
        @php
            $rolePermissionRoutes = [
                'admin.role_permission.list',
                'admin.employee.list',
                'admin.permission_module.list',
            ];
            $isRolePermissionActive = request()->routeIs($rolePermissionRoutes);
        @endphp
        @canAny(['role_view', 'module_view', 'employee_view'])
            <li class="nav-item">
                <a class="nav-link {{ $isRolePermissionActive ? 'active' : '' }}" href="#rolePermissionDropdown"
                    data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ $isRolePermissionActive ? 'true' : 'false' }}"
                    aria-controls="rolePermissionDropdown">
                    <i class="las la-user-tag menu-icon"></i>
                    <span>Role & Permission</span>
                </a>
                <div class="collapse {{ $isRolePermissionActive ? 'show' : '' }}" id="rolePermissionDropdown">
                    <ul class="nav flex-column">
                        @can('role_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.role_permission.list') }}"
                                    class="nav-link {{ request()->routeIs('admin.role_permission.list') ? 'active' : '' }}">Roles
                                    List</a>
                            </li>
                        @endcan
                        @can('employee_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.employee.list') }}"
                                    class="nav-link {{ request()->routeIs('admin.employee.list') ? 'active' : '' }}">Employees</a>
                            </li>
                        @endcan
                        @can('module_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.permission_module.list') }}"
                                    class="nav-link {{ request()->routeIs('admin.permission_module.list') ? 'active' : '' }}">Modules</a>
                            </li>
                        @endcan
                        <li class="nav-item">
                            <a href="{{ route('admin.role_department.list') }}"
                                class="nav-link {{ request()->routeIs('admin.role_department.list') ? 'active' : '' }}">Role
                                Departments</a>
                        </li>
                    </ul><!--end nav-->
                </div>
            </li><!--end nav-item-->
        @endcanAny
        @canAny(['web_config', "sitemap"])
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.web_config.view') ? 'active' : '' }}"
                    href="#settingsDropDown" data-bs-toggle="collapse" role="button" aria-expanded="false"
                    aria-controls="settingsDropDown">
                    <i class="la la-gear menu-icon"></i>
                    <span>Settings</span>
                </a>
                <div class="collapse {{ request()->routeIs('admin.web_config.view') ? 'show' : '' }}"
                    id="settingsDropDown">
                    <ul class="nav flex-column">
                        @can('web_config')
                            <li class="nav-item">
                                <a href="{{ route('admin.web_config.view') }}"
                                    class="nav-link {{ request()->routeIs('admin.web_config.view') ? 'active' : '' }}">Website
                                    Configuration</a>
                            </li>
                        @endcan
                        @can('sitemap')
                            <li class="nav-item">
                                <a href="{{ route('admin.web_config.view') }}"
                                    class="nav-link {{ request()->routeIs('admin.web_config.view') ? 'active' : '' }}">Sitemap</a>
                            </li>
                        @endcan
                    </ul><!--end nav-->
                </div>
            </li><!--end nav-item-->
        @endcanAny
    </ul><!--end navbar-nav--->
</div>
