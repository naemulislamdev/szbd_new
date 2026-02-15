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
        @canAny(['order_all', 'order_pending', 'order_confirmed', 'order_processing', 'order_out_for_delivery',
            'order_delivered', 'order_returned', 'order_failed', 'order_canceled'])
            <li class="nav-item">
                <a class="nav-link" href="#sidebarEcommerce" data-bs-toggle="collapse" role="button" aria-expanded="false"
                    aria-controls="sidebarEcommerce">
                    <i class="iconoir-cart-alt menu-icon"></i>
                    <span>Orders</span>
                </a>
                <div class="collapse " id="sidebarEcommerce">
                    <ul class="nav flex-column">
                        @can('order_all')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.order.list') }}">All Orders
                                    <span class="badge bg-dark ms-2">{{ $orderCounts->total ?? 0 }}</span>
                                </a>
                            </li><!--end nav-item-->
                        @endcan
                        @can('order_pending')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.order.list') }}?status=pending">Pending
                                    <span class="badge bg-warning ms-2">{{ $orderCounts->pending ?? 0 }}</span>
                                </a>
                            </li>
                        @endcan
                        @can('order_confirmed')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.order.list') }}?status=confirmed">Confirmed
                                    <span class="badge bg-primary ms-2">{{ $orderCounts->confirmed ?? 0 }}</span>
                                </a>
                            </li><!--end nav-item-->
                        @endcan
                        @can('order_processing')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.order.list') }}?status=processing">Processing
                                    <span class="badge bg-info ms-2">{{ $orderCounts->processing ?? 0 }}</span>
                                </a>
                            </li><!--end nav-item-->
                        @endcan
                        @can('order_out_for_delivery')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.order.list') }}?status=out_for_delivery">Out for
                                    delivery
                                    <span class="badge bg-primary ms-2">{{ $orderCounts->out_for_delivery ?? 0 }}</span>
                                </a>
                            </li><!--end nav-item-->
                        @endcan
                        @can('order_delivered')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.order.list') }}?status=delivered">Delivery
                                    <span class="badge bg-success ms-2">{{ $orderCounts->delivered ?? 0 }}</span>
                                </a>
                            </li><!--end nav-item-->
                        @endcan
                        @can('order_returned')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.order.list') }}?status=returned">Returned
                                    <span class="badge bg-secondary ms-2">{{ $orderCounts->returned ?? 0 }}</span>
                                </a>
                            </li><!--end nav-item-->
                        @endcan
                        @can('order_failed')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.order.list') }}?status=failed">Failed
                                    <span class="badge bg-dark ms-2">{{ $orderCounts->failed ?? 0 }}</span>
                                </a>
                            </li><!--end nav-item-->
                        @endcan
                        @can('order_canceled')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.order.list') }}?status=canceled">Canceled
                                    <span class="badge bg-danger ms-2">{{ $orderCounts->canceled ?? 0 }}</span>
                                </a>
                            </li><!--end nav-item-->
                        @endcan
                    </ul><!--end nav-->
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
        @canAny(['userinfo_all', 'userinfo_pending', 'userinfo_confirmed', 'userinfo_canceled'])
            <li class="nav-item">
                <a class="nav-link" href="#userInfoDropDown" data-bs-toggle="collapse" role="button" aria-expanded="false"
                    aria-controls="userInfoDropDown">
                    <i class="iconoir-community menu-icon"></i>
                    <span>User Info</span>
                </a>
                <div class="collapse " id="userInfoDropDown">
                    <ul class="nav flex-column">
                        @can('userinfo_all')
                            <li class="nav-item">
                                <a href="{{ route('admin.userinfo.all') }}" class="nav-link ">All User Info
                                    <span class="badge bg-dark ms-2">{{ $userInfoCounts->total ?? 0 }}</span>
                                </a>
                            </li><!--end nav-item-->
                        @endcan
                        @can('userinfo_pending')
                            <li class="nav-item">
                                <a href="{{ route('admin.userinfo.all') }}?status=pending" class="nav-link ">Pending
                                    <span class="badge bg-warning ms-2">{{ $userInfoCounts->pending ?? 0 }}</span>
                                </a>
                            </li><!--end nav-item-->
                        @endcan
                        @can('userinfo_confirmed')
                            <li class="nav-item">
                                <a href="{{ route('admin.userinfo.all') }}?status=confirmed" class="nav-link ">Confirmed
                                    <span class="badge bg-success ms-2">{{ $userInfoCounts->confirmed ?? 0 }}</span>
                                </a>
                            </li><!--end nav-item-->
                        @endcan
                        @can('userinfo_canceled')
                            <li class="nav-item">
                                <a href="{{ route('admin.userinfo.all') }}?status=canceled" class="nav-link ">Canceled
                                    <span class="badge bg-danger ms-2">{{ $userInfoCounts->canceled ?? 0 }}</span>
                                </a>
                            </li><!--end nav-item-->
                        @endcan
                    </ul><!--end nav-->
                </div>
            </li><!--end nav-item-->
        @endcanAny
        @canAny(['category_view', 'sub_category_view', 'child_category_view'])
            <li class="nav-item">
                <a class="nav-link" href="#productsSettingDropdown" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="productsSettingDropdown">
                    <i class="las la-cog menu-icon"></i>
                    <span>Product Settings</span>
                </a>
                <div class="collapse " id="productsSettingDropdown">
                    <ul class="nav flex-column">
                        @can('category_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.category.view') }}" class="nav-link ">Category</a>
                            </li>
                        @endcan
                        @can('sub_category_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.sub-category.view') }}" class="nav-link ">Sub Category</a>
                            </li>
                        @endcan
                        @can('child_category_view')
                            <li class="nav-item">
                                <a href="{{ route('admin.child-category.view') }}" class="nav-link ">Child Category</a>
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
                <a class="nav-link" href="#bannerDropdown" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="bannerDropdown">
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
            <a class="nav-link" href="#rolePermissionDropdown" data-bs-toggle="collapse" role="button"
                aria-expanded="false" aria-controls="rolePermissionDropdown">
                <i class="la la-gear menu-icon"></i>
                <span>Settings</span>
            </a>
            <div class="collapse " id="rolePermissionDropdown">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.web_config.view') }}" class="nav-link ">Website Configuration</a>
                    </li>
                </ul><!--end nav-->
            </div>
        </li><!--end nav-item-->
        <li class="nav-item">
            <a class="nav-link" href="apps-chat.html">0
                <i class="iconoir-chat-bubble menu-icon"></i>
                <span>Chat</span>
            </a>
        </li><!--end nav-item-->
        <li class="nav-item">
            <a class="nav-link" href="apps-contact-list.html">
                <i class="las la-phone menu-icon"></i>
                <span>Contact List</span>
            </a>
        </li><!--end nav-item-->

        <li class="nav-item">
            <a class="nav-link" href="#reportsDropdown" data-bs-toggle="collapse" role="button"
                aria-expanded="false" aria-controls="reportsDropdown">
                <i class="las la-user-tag menu-icon"></i>
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
