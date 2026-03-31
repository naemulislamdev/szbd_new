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
        @can('dashboard_view', 'order_report_view')
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
        @endcan
        <!--end row-->
        <div class="row justify-content-center">
            @can('monthly_income_view')
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
            @endcan
            <!--end col-->
            @can('customer_view')
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
            @endcan
        </div>
        <!--end row-->
        @can('top_selling_view', 'popular_products_view')
            <div class="row justify-content-center">
                @can('top_selling_view')
                    <div class="col-md-6 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <h4 class="card-title">Top Selling Products</h4>
                                    </div>
                                    <div class="col">
                                        <div id="customRangeBox" class="p-0 border-top d-none">
                                            <div class="row g-2">
                                                <div class="col-md-5">
                                                    <input type="date" id="startDate" class="form-control">
                                                </div>
                                                <div class="col-md-5">
                                                    <input type="date" id="endDate" class="form-control">
                                                </div>
                                                <div class="col-md-2">
                                                    <button class="btn btn-primary w-100" id="applyCustomRange">
                                                        Apply
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">

                                        <div class="dropdown">
                                            <a href="#" class="btn bt btn-light dropdown-toggle" data-bs-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false" id="topSellingFilterBtn">
                                                <i class="las la-calendar fs-5 me-1"></i>
                                                <span id="filterLabel">This Month</span>
                                                <i class="las la-angle-down ms-1"></i>
                                            </a>

                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item top-selling-filter" href="#" data-filter="today"
                                                    data-label="Today">Today</a>
                                                <a class="dropdown-item top-selling-filter" href="#"
                                                    data-filter="yesterday" data-label="Yesterday">Yesterday</a>
                                                <a class="dropdown-item top-selling-filter" href="#"
                                                    data-filter="last_week" data-label="Last Week">Last Week</a>
                                                <a class="dropdown-item top-selling-filter" href="#"
                                                    data-filter="last_month" data-label="Last Month">Last Month</a>
                                                <a class="dropdown-item top-selling-filter" href="#"
                                                    data-filter="this_year" data-label="This Year">This Year</a>
                                                <a class="dropdown-item top-selling-filter" href="#"
                                                    data-filter="this_month" data-label="This Month">This Month</a>
                                                <a class="dropdown-item top-selling-filter" href="#"
                                                    data-filter="custom_range" data-label="Custom Range">Custom Range</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <tbody id="top-selling-products-body">
                                            @include('admin.partials.top_selling_products_list', [
                                                'topSellingProducts' => $topSellingProducts,
                                            ])
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div> <!--end col-->
                @endcan
            </div><!--end row-->
        @endcan
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
        $(document).ready(function() {

            // Dropdown click
            $('.top-selling-filter').on('click', function(e) {
                e.preventDefault();

                let filter = $(this).data('filter');
                let label = $(this).data('label');

                $('#filterLabel').text(label);

                // Custom range হলে input show
                if (filter === 'custom_range') {
                    $('#customRangeBox').removeClass('d-none');
                    return;
                } else {
                    $('#customRangeBox').addClass('d-none');
                }

                loadProducts(filter);
            });

            // Apply custom range
            $('#applyCustomRange').on('click', function() {
                let startDate = $('#startDate').val();
                let endDate = $('#endDate').val();

                if (!startDate || !endDate) {
                    alert('Please select both dates');
                    return;
                }

                loadProducts('custom_range', startDate, endDate);
            });

            // Common AJAX function
            function loadProducts(filter, startDate = null, endDate = null) {

                $('#top-selling-products-body').html(`
            <div id="loader" style=" text-align:center; margin-bottom:10px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
        `);

                $.ajax({
                    url: "{{ route('admin.dashboard.top_selling_products') }}",
                    type: "GET",
                    data: {
                        filter: filter,
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(res) {
                        $('#top-selling-products-body').html(res.html);
                    },
                    error: function() {
                        $('#top-selling-products-body').html(`
                    <tr>
                        <td colspan="2" class="text-center text-danger">Error</td>
                    </tr>
                `);
                    }
                });
            }

        });
    </script>
@endpush
