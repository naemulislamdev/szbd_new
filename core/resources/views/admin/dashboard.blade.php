@extends('admin.layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Dashboard</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Shopping Zone BD</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 bg-primary-subtle text-primary thumb-md rounded-circle">
                                <i class="iconoir-dollar-circle fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-2 text-truncate">
                                <p class="text-dark mb-0 fw-semibold fs-14">Total Revenue</p>
                                <p class="mb-0 text-muted">
                                    <span class="{{ $revenueGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format(abs($revenueGrowth), 1) }}%
                                    </span>
                                    {{ $revenueGrowth >= 0 ? 'Increase' : 'Decrease' }} from last month
                                </p>
                            </div><!--end media-body-->
                        </div><!--end media-->
                        <div class="row d-flex justify-content-center">
                            <div class="col">
                                <h3 class="mt-2 mb-0 fw-bold">৳{{ number_format($currentRevenue, 2) }}</h3>
                            </div>
                            <!--end col-->
                            <div class="col align-self-center">
                                <img src="assets/images/extra/line-chart.png" alt="" class="img-fluid">
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end card-body-->
                </div>
                <!--end card-->
            </div>
            <!--end col-->
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 bg-info-subtle text-info thumb-md rounded-circle">
                                <i class="iconoir-cart fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-2 text-truncate">
                                <p class="text-dark mb-0 fw-semibold fs-14">New Order</p>
                                <p class="mb-0 text-muted">
                                    <span class="{{ $orderGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format(abs($orderGrowth), 1) }}%
                                    </span>
                                    {{ $orderGrowth >= 0 ? 'Increase' : 'Decrease' }} from last month
                                </p>
                            </div><!--end media-body-->
                        </div><!--end media-->
                        <div class="row d-flex justify-content-center">
                            <div class="col">
                                <h3 class="mt-2 mb-0 fw-bold"> {{ $currentOrders }}</h3>
                            </div>
                            <!--end col-->
                            <div class="col align-self-center">
                                <img src="assets/images/extra/bar.png" alt="" class="img-fluid">
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end card-body-->
                </div>
                <!--end card-->
            </div>
            <!--end col-->
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 bg-warning-subtle text-warning thumb-md rounded-circle">
                                <i class="iconoir-percentage-circle fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-2 text-truncate">
                                <p class="text-dark mb-0 fw-semibold fs-14">Canceled</p>
                                <p class="mb-0 text-muted">
                                    <span class="{{ $cancelGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format(abs($cancelGrowth), 1) }}%
                                    </span>
                                    {{ $cancelGrowth >= 0 ? 'Increase' : 'Decrease' }} from last month
                                </p>
                            </div><!--end media-body-->
                        </div><!--end media-->
                        <div class="row d-flex justify-content-center">
                            <div class="col">
                                <h3 class="mt-2 mb-0 fw-bold">{{ $currentCanceled }}</h3>
                            </div>
                            <!--end col-->
                            <div class="col align-self-center">
                                <img src="assets/images/extra/donut.png" alt="" class="img-fluid">
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end card-body-->
                </div>
                <!--end card-->
            </div>
            <!--end col-->
            <div class="col-md-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 bg-danger-subtle text-danger thumb-md rounded-circle">
                                <i class="iconoir-hexagon-dice fs-4"></i>
                            </div>
                            <div class="flex-grow-1 ms-2 text-truncate">
                                <p class="text-dark mb-0 fw-semibold fs-14">Avg. Order value</p>
                                <p class="mb-0 text-muted">
                                    <span class="{{ $avgGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                                        {{ number_format(abs($avgGrowth), 1) }}%
                                    </span>
                                    {{ $avgGrowth >= 0 ? 'Increase' : 'Decrease' }} from last month
                                </p>
                            </div><!--end media-body-->
                        </div><!--end media-->
                        <div class="row d-flex justify-content-center">
                            <div class="col">
                                <h3 class="mt-2 mb-0 fw-bold">৳{{ number_format($currentAvg, 2) }}</h3>
                            </div>
                            <!--end col-->
                            <div class="col align-self-center">
                                <img src="assets/images/extra/tree.png" alt="" class="img-fluid">
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </div>
                    <!--end card-body-->
                </div>
                <!--end card-->
            </div>
            <!--end col-->
        </div>
        <!--end row-->
        <div class="row">
            {{--  Order Reports Overview start --}}
            <div class="col-md-12">
                <div class="card mb-3"
                    style="background-color: #ffffff; box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
                    <div class="card-body">
                        <div>

                            <h4 class="d-flex align-items-center" style="color:rgb(13, 13, 13)"><img style="height: 40px"
                                    src="{{ asset('assets/backend/images/chart-line-up.gif') }}" alt="order line up">Order
                                Reports
                            </h4>
                        </div>
                        <div class="row ">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Form Date</label>
                                    <input type="date" value="{{ date('Y-m-d') }}" class="form-control" id="from_date"
                                        name="from_date">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>To Date</label>
                                    <input type="date" value="{{ date('Y-m-d') }}" class="form-control"
                                        id="to_date" name="to_date">
                                </div>
                            </div>
                            <div class="col-md-4" style="margin-top: 20px">
                                <button class="btn btn-primary" onclick="dashboard_order_report_filter()">
                                    Filter
                                </button>
                            </div>

                        </div>
                        <div class="row gx-2 gx-lg-3 mt-4" id="orderStatus">
                            @include('admin.dashboard-order_report', [
                                'results' => $results,
                            ])
                        </div>
                    </div>
                </div>
            </div>
            {{--  Order Reports Overview end --}}
            {{-- Order Report Statics Start --}}
            <div class="col-md-12">
                <div class="card mb-3"
                    style="background-color: #ffffff; box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;">
                    <div class="card-body">
                        <div class="row flex-between gx-2 gx-lg-3 mb-2">
                            <div>
                                <h4><i style="font-size: 30px" class="tio-chart-bar-4"></i>Dashboard
                                    Order Statistics
                                </h4>
                            </div>
                            <div class="col-12 col-md-4" style="width: 20vw">
                                <select class="custom-select form-select" name="statistics_type"
                                    onchange="order_stats_update(this.value)">
                                    <option value="overall"
                                        {{ session()->has('statistics_type') && session('statistics_type') == 'overall' ? 'selected' : '' }}>
                                        Overall_statistics
                                    </option>
                                    <option value="today"
                                        {{ session()->has('statistics_type') && session('statistics_type') == 'today' ? 'selected' : '' }}>
                                        Todays Statistics
                                    </option>
                                    <option value="this_month"
                                        {{ session()->has('statistics_type') && session('statistics_type') == 'this_month' ? 'selected' : '' }}>
                                        This Months Statistics
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row gx-2 gx-lg-3" id="order_stats">
                            @include('admin.dashboard-order-stats', [
                                'data' => $data,
                            ])
                        </div>
                    </div>
                </div>

            </div>
            {{-- Order Report Statics End --}}

        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">Monthly Avg. Income</h4>
                            </div><!--end col-->
                            <div class="col-auto">
                                <div class="dropdown">
                                    <a href="#" class="btn bt btn-light dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="lar la-calendar fs-5 me-1"></i> This Month<i
                                            class="las la-angle-down ms-1"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item income-filter" data-filter="today">Today</a>
                                        <a class="dropdown-item income-filter" data-filter="last_week">Last Week</a>
                                        <a class="dropdown-item income-filter" data-filter="last_month">Last Month</a>
                                        <a class="dropdown-item income-filter" data-filter="this_year">This Year</a>
                                        <a class="dropdown-item income-filter" data-filter="this_month">This Month</a>
                                    </div>

                                </div>
                            </div><!--end col-->
                        </div> <!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body pt-0">
                        <div id="monthly_income" class="apex-charts"></div>
                    </div>
                    <!--end card-body-->
                </div>
                <!--end card-->
            </div>
            <!--end col-->
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">Customers</h4>
                            </div><!--end col-->
                            <div class="col-auto">
                                <div class="img-group d-flex">
                                    <a class="user-avatar position-relative d-inline-block" href="#">
                                        <img src="{{ asset('assets/backend/users/avatar-1.jpg') }}" alt="avatar"
                                            class="thumb-md shadow-sm rounded-circle">
                                    </a>
                                    <a class="user-avatar position-relative d-inline-block ms-n2" href="#">
                                        <img src="{{ asset('assets/backend/users/avatar-2.jpg') }}" alt="avatar"
                                            class="thumb-md shadow-sm rounded-circle">
                                    </a>
                                    <a class="user-avatar position-relative d-inline-block ms-n2" href="#">
                                        <img src="{{ asset('assets/backend/users/avatar-3.jpg') }}" alt="avatar"
                                            class="thumb-md shadow-sm rounded-circle">
                                    </a>
                                    <a class="user-avatar position-relative d-inline-block ms-n2" href="#">
                                        <img src="{{ asset('assets/backend/users/avatar-4.jpg') }}" alt="avatar"
                                            class="thumb-md shadow-sm rounded-circle">
                                    </a>
                                    <a href="" class="user-avatar position-relative d-inline-block ms-1">
                                        <span
                                            class="thumb-md shadow-sm justify-content-center d-flex align-items-center bg-info-subtle rounded-circle fw-semibold fs-6">+6</span>
                                    </a>
                                </div>
                            </div><!--end col-->
                        </div> <!--end row-->
                    </div>
                    <div class="card-body pt-0">
                        <div id="customers" class="apex-charts"></div>
                        <div class="bg-light py-3 px-2 mb-0 mt-3 text-center rounded">
                            <h6 class="mb-0"><i class="las la-calendar fs-5 me-1"></i> 01 January 2024 to
                                31
                                December 2024</h6>
                        </div>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!--end col-->
        </div>
        <!--end row-->

        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">Top Selling by Division</h4>
                            </div><!--end col-->
                            <div class="col-auto">
                                <div class="dropdown">
                                    <a href="#" class="btn bt btn-light dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="las la-calendar fs-5 me-1"></i> This Month<i
                                            class="las la-angle-down ms-1"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">Today</a>
                                        <a class="dropdown-item" href="#">Last Week</a>
                                        <a class="dropdown-item" href="#">Last Month</a>
                                        <a class="dropdown-item" href="#">This Year</a>
                                    </div>
                                </div>
                            </div><!--end col-->
                        </div> <!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <tbody>
                                    <tr class="">
                                        <td class="px-0">
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/flags/us_flag.jpg"
                                                    class="me-2 align-self-center thumb-md rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0 text-truncate">Dhaka</h6>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress bg-primary-subtle w-100" style="height:4px;"
                                                            role="progressbar" aria-label="Success example"
                                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar bg-primary" style="width: 85%"></div>
                                                        </div>
                                                        <small class="flex-shrink-1 ms-1">85%</small>
                                                    </div>
                                                </div><!--end media body-->
                                            </div><!--end media-->
                                        </td>
                                        <td class="px-0 text-end"><span
                                                class="text-body ps-2 align-self-center text-end fw-medium">$5860.00</span>
                                        </td>
                                    </tr><!--end tr-->
                                    <tr class="">
                                        <td class="px-0">
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/flags/spain_flag.jpg"
                                                    class="me-2 align-self-center thumb-md rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0 text-truncate">Barishal</h6>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress bg-primary-subtle w-100" style="height:4px;"
                                                            role="progressbar" aria-label="Success example"
                                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar bg-primary" style="width: 78%"></div>
                                                        </div>
                                                        <small class="flex-shrink-1 ms-1">78%</small>
                                                    </div>
                                                </div><!--end media body-->
                                            </div><!--end media-->
                                        </td>
                                        <td class="px-0 text-end"><span
                                                class="text-body ps-2 align-self-center text-end fw-medium">$5422.00</span>
                                        </td>
                                    </tr><!--end tr-->
                                    <tr class="">
                                        <td class="px-0">
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/flags/french_flag.jpg"
                                                    class="me-2 align-self-center thumb-md rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0 text-truncate">Chitagong</h6>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress bg-primary-subtle w-100" style="height:4px;"
                                                            role="progressbar" aria-label="Success example"
                                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar bg-primary" style="width: 71%"></div>
                                                        </div>
                                                        <small class="flex-shrink-1 ms-1">71%</small>
                                                    </div>
                                                </div><!--end media body-->
                                            </div><!--end media-->
                                        </td>
                                        <td class="px-0 text-end"><span
                                                class="text-body ps-2 align-self-center text-end fw-medium">$4587.00</span>
                                        </td>
                                    </tr><!--end tr-->
                                    <tr class="">
                                        <td class="px-0">
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/flags/germany_flag.jpg"
                                                    class="me-2 align-self-center thumb-md rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0 text-truncate">Rangpur</h6>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress bg-primary-subtle w-100" style="height:4px;"
                                                            role="progressbar" aria-label="Success example"
                                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar bg-primary" style="width: 65%"></div>
                                                        </div>
                                                        <small class="flex-shrink-1 ms-1">65%</small>
                                                    </div>
                                                </div><!--end media body-->
                                            </div><!--end media-->
                                        </td>
                                        <td class="px-0 text-end"><span
                                                class="text-body ps-2 align-self-center text-end fw-medium">$3655.00</span>
                                        </td>
                                    </tr><!--end tr-->
                                    <tr class="">
                                        <td class="px-0">
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/flags/baha_flag.jpg"
                                                    class="me-2 align-self-center thumb-md rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0 text-truncate">Sylhet</h6>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress bg-primary-subtle w-100" style="height:4px;"
                                                            role="progressbar" aria-label="Success example"
                                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar bg-primary" style="width: 48%"></div>
                                                        </div>
                                                        <small class="flex-shrink-1 ms-1">48%</small>
                                                    </div>
                                                </div><!--end media body-->
                                            </div><!--end media-->
                                        </td>
                                        <td class="px-0 text-end"><span
                                                class="text-body ps-2 align-self-center text-end fw-medium">$3325.00</span>
                                        </td>
                                    </tr><!--end tr-->
                                    <tr class="">
                                        <td class="px-0">
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/flags/russia_flag.jpg"
                                                    class="me-2 align-self-center thumb-md rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0 text-truncate">Rajshahi</h6>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress bg-primary-subtle w-100" style="height:4px;"
                                                            role="progressbar" aria-label="Success example"
                                                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                            <div class="progress-bar bg-primary" style="width: 35%"></div>
                                                        </div>
                                                        <small class="flex-shrink-1 ms-1">35%</small>
                                                    </div>
                                                </div><!--end media body-->
                                            </div><!--end media-->
                                        </td>
                                        <td class="px-0 text-end"><span
                                                class="text-body ps-2 align-self-center text-end fw-medium">$2275.00</span>
                                        </td>
                                    </tr><!--end tr-->
                                </tbody>
                            </table> <!--end table-->
                        </div><!--end /div-->
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!--end col-->
            <div class="col-md-6 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">Popular Products</h4>
                            </div><!--end col-->
                            <div class="col-auto">
                                <div class="dropdown">
                                    <a href="#" class="btn bt btn-light dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="las la-calendar fs-5 me-1"></i> This Year<i
                                            class="las la-angle-down ms-1"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">Today</a>
                                        <a class="dropdown-item" href="#">Last Week</a>
                                        <a class="dropdown-item" href="#">Last Month</a>
                                        <a class="dropdown-item" href="#">This Year</a>
                                    </div>
                                </div>
                            </div><!--end col-->
                        </div> <!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-top-0">Product</th>
                                        <th class="border-top-0">Price</th>
                                        <th class="border-top-0">Sell</th>
                                        <th class="border-top-0">Status</th>
                                        <th class="border-top-0">Action</th>
                                    </tr><!--end tr-->
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/products/01.png" height="40"
                                                    class="me-3 align-self-center rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0">History Book</h6>
                                                    <a href="#" class="fs-12 text-primary">ID:
                                                        A3652</a>
                                                </div><!--end media body-->
                                            </div>
                                        </td>
                                        <td>$50 <del class="text-muted fs-10">$70</del></td>
                                        <td>450 <small class="text-muted">(550)</small></td>
                                        <td><span class="badge bg-primary-subtle text-primary px-2">Stock</span>
                                        </td>
                                        <td>
                                            <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                            <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                        </td>
                                    </tr><!--end tr-->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/products/02.png" height="40"
                                                    class="me-3 align-self-center rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0">Colorful Pots</h6>
                                                    <a href="#" class="fs-12 text-primary">ID:
                                                        A5002</a>
                                                </div><!--end media body-->
                                            </div>
                                        </td>
                                        <td>$99 <del class="text-muted fs-10">$150</del></td>
                                        <td>750 <small class="text-muted">(00)</small></td>
                                        <td><span class="badge bg-danger-subtle text-danger px-2">Out of
                                                Stock</span></td>
                                        <td>
                                            <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                            <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                        </td>
                                    </tr><!--end tr-->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/products/04.png" height="40"
                                                    class="me-3 align-self-center rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0">Pearl Bracelet</h6>
                                                    <a href="#" class="fs-12 text-primary">ID:
                                                        A6598</a>
                                                </div><!--end media body-->
                                            </div>
                                        </td>
                                        <td>$199 <del class="text-muted fs-10">$250</del></td>
                                        <td>280 <small class="text-muted">(220)</small></td>
                                        <td><span class="badge bg-primary-subtle text-primary px-2">Stock</span>
                                        </td>
                                        <td>
                                            <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                            <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                        </td>
                                    </tr><!--end tr-->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/products/06.png" height="40"
                                                    class="me-3 align-self-center rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0">Dancing Man</h6>
                                                    <a href="#" class="fs-12 text-primary">ID:
                                                        A9547</a>
                                                </div><!--end media body-->
                                            </div>
                                        </td>
                                        <td>$40 <del class="text-muted fs-10">$49</del></td>
                                        <td>500 <small class="text-muted">(1000)</small></td>
                                        <td><span class="badge bg-danger-subtle text-danger px-2">Out of
                                                Stock</span></td>
                                        <td>
                                            <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                            <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                        </td>
                                    </tr><!--end tr-->
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="assets/images/products/05.png" height="40"
                                                    class="me-3 align-self-center rounded" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0">Fire Lamp</h6>
                                                    <a href="#" class="fs-12 text-primary">ID:
                                                        A2047</a>
                                                </div><!--end media body-->
                                            </div>
                                        </td>
                                        <td>$80 <del class="text-muted fs-10">$59</del></td>
                                        <td>800 <small class="text-muted">(2000)</small></td>
                                        <td><span class="badge bg-danger-subtle text-danger px-2">Out of
                                                Stock</span></td>
                                        <td>
                                            <a href="#"><i class="las la-pen text-secondary fs-18"></i></a>
                                            <a href="#"><i class="las la-trash-alt text-secondary fs-18"></i></a>
                                        </td>
                                    </tr><!--end tr-->
                                </tbody>
                            </table> <!--end table-->
                        </div><!--end /div-->
                    </div><!--end card-body-->
                </div><!--end card-->
            </div> <!--end col-->
        </div><!--end row-->
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            var options = {
                chart: {
                    type: 'area',
                    height: 350
                },
                series: [{
                    name: 'Income',
                    data: []
                }],
                xaxis: {
                    categories: []
                }
            };

            var chart = new ApexCharts(document.querySelector("#monthly_income"), options);
            chart.render();

            function loadChart(filter = 'this_month') {
                fetch("{{ route('admin.dashboard.monthly.income') }}?filter=" + filter)
                    .then(response => response.json())
                    .then(data => {
                        chart.updateOptions({
                            xaxis: {
                                categories: data.months
                            }
                        });

                        chart.updateSeries([{
                            name: 'Income',
                            data: data.totals
                        }]);
                    });
            }

            loadChart(); // default load

            document.querySelectorAll('.income-filter').forEach(item => {
                item.addEventListener('click', function() {
                    let filter = this.getAttribute('data-filter');
                    loadChart(filter);
                });
            });

        });
    </script>
    <script>
        function dashboard_order_report_filter() {
            let from_date = $('#from_date').val();
            let to_date = $('#to_date').val();

            $.ajax({
                url: "{{ route('admin.dashboard.order.report.filter') }}",
                type: "GET",
                data: {
                    from_date: from_date,
                    to_date: to_date
                },
                beforeSend: function() {
                    $('#orderStatus').html(`<div id="loader" style=" text-align:center; margin-bottom:10px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>`);
                },
                success: function(response) {
                    $('#orderStatus').html(response.view);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        }
    </script>
    <script>
        function order_stats_update(type) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.dashboard.order-stats') }}',
                data: {
                    statistics_type: type
                },
                beforeSend: function() {
                    $('#loading').show()
                },
                success: function(data) {
                    $('#order_stats').html(data.view)
                },
                complete: function() {
                    $('#loading').hide()
                }
            });
        }

        function business_overview_stats_update(type) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.dashboard.business-overview') }}',
                data: {
                    business_overview: type
                },
                beforeSend: function() {
                    $('#loading').show()
                },
                success: function(data) {
                    console.log(data.view)
                    $('#business-overview-board').html(data.view)
                },
                complete: function() {
                    $('#loading').hide()
                }
            });
        }
    </script>
@endpush
