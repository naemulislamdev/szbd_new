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
                        <a class="nav-link" href="ecommerce-products.html">Products</a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="ecommerce-customers.html">Customers</a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="ecommerce-customer-details.html">Customer
                            Details</a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="ecommerce-orders.html">Orders</a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="ecommerce-order-details.html">Order Details</a>
                    </li><!--end nav-item-->
                    <li class="nav-item">
                        <a class="nav-link" href="ecommerce-refunds.html">Refunds</a>
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
