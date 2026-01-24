<div class="d-flex align-items-start flex-column w-100">
    <!-- Navigation -->
    <ul class="navbar-nav mb-auto w-100">
        <li class="menu-label mt-2">
            <span>Navigation</span>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('admin.dashboard.index') }}">
                <i class="iconoir-report-columns menu-icon"></i>
                <span>Dashboard</span>
                <span class="badge text-bg-warning ms-auto">08</span>
            </a>
        </li><!--end nav-item-->
        <li class="nav-item">
            <a class="nav-link" href="#sidebarEcommerce" data-bs-toggle="collapse" role="button" aria-expanded="false"
                aria-controls="sidebarEcommerce">
                <i class="iconoir-cart-alt menu-icon"></i>
                <span>Orders</span>
            </a>
            <div class="collapse " id="sidebarEcommerce">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.order.list') }}">All Orders
                            <span class="badge bg-dark ms-2">{{ $orderCounts->total ?? 0 }}</span>
                        </a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.order.list') }}?status=pending">Pending
                            <span class="badge bg-warning ms-2">{{ $orderCounts->pending ?? 0 }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.order.list') }}?status=confirmed">Confirmed
                            <span class="badge bg-primary ms-2">{{ $orderCounts->confirmed ?? 0 }}</span>
                        </a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.order.list') }}?status=processing">Processing
                            <span class="badge bg-info ms-2">{{ $orderCounts->processing ?? 0 }}</span>
                        </a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.order.list') }}?status=out_for_delivery">Out for delivery
                            <span class="badge bg-primary ms-2">{{ $orderCounts->out_for_delivery ?? 0 }}</span>
                        </a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.order.list') }}?status=delivered">Delivery
                            <span class="badge bg-success ms-2">{{ $orderCounts->delivered ?? 0 }}</span>
                        </a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.order.list') }}?status=returned">Returned
                            <span class="badge bg-secondary ms-2">{{ $orderCounts->returned ?? 0 }}</span>
                        </a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.order.list') }}?status=failed">Failed
                            <span class="badge bg-dark ms-2">{{ $orderCounts->failed ?? 0 }}</span>
                        </a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.order.list') }}?status=canceled">Canceled
                            <span class="badge bg-danger ms-2">{{ $orderCounts->canceled ?? 0 }}</span>
                        </a>
                    </li><!--end nav-item-->
                </ul><!--end nav-->
            </div>
        </li><!--end nav-item-->

        <li class="nav-item">
            <a class="nav-link" href="#sidebarAnalytics" data-bs-toggle="collapse" role="button" aria-expanded="false"
                aria-controls="sidebarAnalytics">
                <i class="iconoir-reports menu-icon"></i>
                <span>Products</span>
            </a>
            <div class="collapse " id="sidebarAnalytics">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.product.create') }}" class="nav-link ">Add New Product</a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a href="{{ route('admin.product.index') }}" class="nav-link ">All Products</a>
                    </li><!--end nav-item-->
                </ul><!--end nav-->
            </div>
        </li><!--end nav-item-->
        <li class="nav-item">
            <a class="nav-link" href="#sidebarAnalytics" data-bs-toggle="collapse" role="button" aria-expanded="false"
                aria-controls="sidebarAnalytics">
                <i class="iconoir-reports menu-icon"></i>
                <span>User Info</span>
            </a>
            <div class="collapse " id="sidebarAnalytics">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.userinfo.all')}}" class="nav-link ">All User Info
                            <span class="badge bg-dark ms-2">{{ $userInfoCounts->total ?? 0 }}</span>
                        </a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a href="{{ route('admin.userinfo.all')}}?status=pending" class="nav-link ">Pending
                            <span class="badge bg-warning ms-2">{{ $userInfoCounts->pending ?? 0 }}</span>
                        </a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a href="{{ route('admin.userinfo.all')}}?status=confirmed" class="nav-link ">Confirmed
                            <span class="badge bg-success ms-2">{{ $userInfoCounts->confirmed ?? 0 }}</span>
                        </a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a href="{{ route('admin.userinfo.all')}}?status=canceled" class="nav-link ">Canceled
                            <span class="badge bg-danger ms-2">{{ $userInfoCounts->canceled ?? 0 }}</span>
                        </a>
                    </li><!--end nav-item-->
                </ul><!--end nav-->
            </div>
        </li><!--end nav-item-->

        <li class="nav-item">
            <a class="nav-link" href="apps-chat.html">
                <i class="iconoir-chat-bubble menu-icon"></i>
                <span>Chat</span>
            </a>
        </li><!--end nav-item-->
        <li class="nav-item">
            <a class="nav-link" href="apps-contact-list.html">
                <i class="iconoir-community menu-icon"></i>
                <span>Contact List</span>
            </a>
        </li><!--end nav-item-->
        <li class="nav-item">
            <a class="nav-link" href="apps-calendar.html">
                <i class="iconoir-calendar menu-icon"></i>
                <span>Calendar</span>
            </a>
        </li><!--end nav-item-->
        <li class="nav-item">
            <a class="nav-link" href="apps-invoice.html">
                <i class="iconoir-paste-clipboard menu-icon"></i>
                <span>Invoice</span>
            </a>
        </li><!--end nav-item-->
    </ul><!--end navbar-nav--->
</div>
