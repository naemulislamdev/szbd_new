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
        @canAny(['category_view', 'sub_category_view', 'child_category_view'])
            <li class="nav-item">
                <a class="nav-link {{ $isProductSettingActive ? 'active' : '' }}" href="#productsSettingDropdown" data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ $isProductSettingActive ? 'true' : 'false' }}" aria-controls="productsSettingDropdown">
                    <i class="las la-cog menu-icon"></i>
                    <span>Product Settings</span>
                </a>
                <div class="collapse {{ $isProductSettingActive ? 'show' : '' }}" id="productsSettingDropdown">
                    <ul class="nav flex-column">
                        @can('category_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.category.view') }}"
                                    class="nav-link {{ $isProductSettingActive ? 'active' : '' }}">Category</a>
                            </li>
                        @endcan
                        @can('sub_category_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.sub-category.view') }}"
                                    class="nav-link {{ $isProductSettingActive ? 'active' : '' }}">Sub
                                    Category</a>
                            </li>
                        @endcan
                        @can('child_category_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.child-category.view') }}"
                                    class="nav-link {{ $isProductSettingActive ? 'active' : '' }}">Child
                                    Category</a>
                            </li>
                        @endcan
                    </ul><!--end nav-->
                </div>
            </li><!--end nav-item-->
        @endcanAny
        @canAny(['signle_page_view', 'multiple_page_view'])
            <li class="nav-item">
                <a class="nav-link" href="#landingPageDropdown" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="landingPageDropdown">
                    <i class="la la-bolt menu-icon"></i>
                    <span>Landing Pages</span>
                </a>
                <div class="collapse " id="landingPageDropdown">
                    <ul class="nav flex-column">
                        @can('signle_page_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.landingpages.single.index') }}" class="nav-link ">Single Product</a>
                            </li>
                        @endcan
                        @can('multiple_page_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.landingpages.multiple.index') }}" class="nav-link ">Multiple
                                    Product</a>
                            </li>
                        @endcan
                    </ul>
                </div>
            </li>
        @endcanAny
        @canAny(['banner_view'])
            <li class="nav-item">
                <a class="nav-link" href="#bannerDropdown" data-bs-toggle="collapse" role="button" aria-expanded="false"
                    aria-controls="bannerDropdown">
                    <i class="la la-desktop menu-icon"></i>
                    <span>Banners</span>
                </a>
                <div class="collapse " id="bannerDropdown">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.banner.list') }}" class="nav-link ">Banner List</a>
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
                <div class="collapse " id="customersDropdown">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.customer.list') }}" class="nav-link ">Customers List</a>
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
                <div class="collapse " id="investorDropdown">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.investors.list') }}" class="nav-link ">Investors List</a>
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
                <div class="collapse " id="franchiseDropdown">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.franchise.list') }}" class="nav-link ">Franchise List</a>
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
                <div class="collapse " id="wholesaleDropdown">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="{{ route('admin.wholesale.list') }}" class="nav-link ">Wholesale List</a>
                        </li>
                    </ul><!--end nav-->
                </div>
            </li><!--end nav-item-->
        @endcanAny
        {{-- <li class="nav-item">
            <a class="nav-link" href="">
                <i class="las la-globe menu-icon"></i>
                <span>Website Configuration</span>
            </a>
        </li> --}}
        @canAny(['discount_flat', 'discount_batch', 'discount_offers', 'discount_eid_offers'])
            <li class="nav-item">
                <a class="nav-link" href="#discountDropdown" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="discountDropdown">
                    <i class="las la-tags menu-icon"></i>
                    <span>Discount Management</span>
                </a>
                <div class="collapse " id="discountDropdown">
                    <ul class="nav flex-column">
                        @can('discount_flat')
                            <li class="nav-item">
                                <a href="{{ route('admin.discount.flat') }}" class="nav-link ">Flat Discount</a>
                            </li>
                        @endcan
                        @can('discount_batch')
                            <li class="nav-item">
                                <a href="{{ route('admin.discount.batch') }}" class="nav-link ">Batch Discount</a>
                            </li>
                        @endcan
                        @can('discount_offers')
                            <li class="nav-item">
                                <a href="{{ route('admin.discount.offers') }}" class="nav-link ">Discount Offers</a>
                            </li>
                        @endcan
                        @can('discount_eid_offers')
                            <li class="nav-item">
                                <a href="{{ route('admin.discount.eid.offers') }}" class="nav-link ">Eid Offers ✨🌙</a>
                            </li>
                        @endcan
                    </ul><!--end nav-->
                </div>
            </li><!--end nav-item-->
        @endcanAny

        <li class="nav-item">
            <a class="nav-link" href="#settingsDropDown" data-bs-toggle="collapse" role="button"
                aria-expanded="false" aria-controls="settingsDropDown">
                <i class="la la-gear menu-icon"></i>
                <span>Settings</span>
            </a>
            <div class="collapse " id="settingsDropDown">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.web_config.view') }}" class="nav-link ">Website Configuration</a>
                    </li>
                </ul><!--end nav-->
            </div>
        </li><!--end nav-item-->
        <li class="nav-item">
            <a class="nav-link" href="#blogDropDown" data-bs-toggle="collapse" role="button" aria-expanded="false"
                aria-controls="blogDropDown">
                <i class="las la-blog menu-icon"></i>
                <span>Blogs Management</span>
            </a>
            <div class="collapse " id="blogDropDown">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.blog.categoryList') }}" class="nav-link ">Blog Category</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.blog.list') }}" class="nav-link ">Blog</a>
                    </li>
                </ul><!--end nav-->
            </div>
        </li><!--end nav-item-->
        <li class="nav-item">
            <a class="nav-link" href="#careerDropdown" data-bs-toggle="collapse" role="button"
                aria-expanded="false" aria-controls="blogDropDown">
                <i class="las la-briefcase menu-icon"></i>
                <span>Career Management</span>
            </a>
            <div class="collapse " id="careerDropdown">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.career.department') }}" class="nav-link ">Departments</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.career.view') }}" class="nav-link ">Job Posts</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link ">Applications</a>
                    </li>
                </ul><!--end nav-->
            </div>
        </li><!--end nav-item-->
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="las la-gift menu-icon"></i>
                <span>Coupon</span>
            </a>
        </li><!--end nav-item-->

        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="las la-phone menu-icon"></i>
                <span>Contact List</span>
            </a>
        </li><!--end nav-item-->

        <li class="nav-item">
            <a class="nav-link" href="#reportsDropdown" data-bs-toggle="collapse" role="button"
                aria-expanded="false" aria-controls="reportsDropdown">
                <i class="las la-file-alt menu-icon"></i>
                <span>Reports</span>
            </a>
            <div class="collapse " id="reportsDropdown">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="#" class="nav-link ">Order Reports</a>
                    </li>
                </ul><!--end nav-->
            </div>
        </li><!--end nav-item-->
        @canAny(['role_view', 'module_view', 'employee_view'])
            <li class="nav-item">
                <a class="nav-link" href="#rolePermissionDropdown" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="rolePermissionDropdown">
                    <i class="las la-user-tag menu-icon"></i>
                    <span>Role & Permission</span>
                </a>
                <div class="collapse" id="rolePermissionDropdown">
                    <ul class="nav flex-column">
                        @can('role_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.role_permission.list') }}" class="nav-link ">Roles List</a>
                            </li>
                        @endcan
                        @can('employee_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.employee.list') }}" class="nav-link ">Employees</a>
                            </li>
                        @endcan
                        @can('module_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.permission_module.list') }}" class="nav-link ">Modules</a>
                            </li>
                        @endcan
                    </ul><!--end nav-->
                </div>
            </li><!--end nav-item-->
        @endcanAny
    </ul><!--end navbar-nav--->
</div>
