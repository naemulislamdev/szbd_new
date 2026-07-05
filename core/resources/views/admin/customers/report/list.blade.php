@extends('admin.layouts.app')
@section('title', 'Customer Loyalty Report Management')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <div>
                        <h4 class="page-title">Customer Loyalty Report</h4>
                        <small class="text-muted d-inline-block">Analyze Customer Loyalty, engagement and lifetime
                            value</small>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Customer Loyalty Report</li>
                        </ol>

                    </div>
                </div>
            </div>
        </div><!--end row-->

        {{-- ===== Summary Cards ===== --}}
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body position-relative">
                        <div class="position-absolute top-0 end-0 m-3 avatar-md bg-soft-primary rounded-circle d-flex align-items-center justify-content-center"
                            style="width:42px;height:42px;">
                            <i class="bi bi-person fs-4 text-primary"></i>
                        </div>
                        <p class="text-muted mb-1">Total Customers</p>
                        <h3 class="mb-1" id="stat-total-customers">0</h3>
                        <small class="text-muted" id="stat-total-customers-sub">100% of customers</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body position-relative">
                        <div class="position-absolute top-0 end-0 m-3 avatar-md bg-soft-success rounded-circle d-flex align-items-center justify-content-center"
                            style="width:42px;height:42px;">
                            <i class="bi bi-people fs-4 text-success"></i>
                        </div>
                        <p class="text-muted mb-1">Loyal Customers</p>
                        <h3 class="mb-1" id="stat-loyal-customers">0</h3>
                        <small class="text-muted" id="stat-loyal-customers-sub">0% of total</small>
                        <small class="text-muted">VIP, Gold, Silver</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body position-relative">
                        <div class="position-absolute top-0 end-0 m-3 avatar-md bg-soft-warning rounded-circle d-flex align-items-center justify-content-center"
                            style="width:42px;height:42px;">
                            <i class="bi bi-award fs-4 text-warning"></i>
                        </div>
                        <p class="text-muted mb-1">VIP Customers</p>
                        <h3 class="mb-1" id="stat-vip-customers">0</h3>
                        <small class="text-muted" id="stat-vip-customers-sub">0% of total</small>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body position-relative">
                        <div class="position-absolute top-0 end-0 m-3 avatar-md bg-soft-info rounded-circle d-flex align-items-center justify-content-center"
                            style="width:42px;height:42px;">
                            <i class="bi bi-arrow-repeat fs-4 text-info"></i>
                        </div>
                        <p class="text-muted mb-1">Repeat Rate</p>
                        <h3 class="mb-1" id="stat-repeat-rate">0%</h3>
                        <small class="text-muted">From total orders</small>
                    </div>
                </div>
            </div>
        </div><!--end row-->

        {{-- ===== Charts Row ===== --}}
        <div class="row">
            {{-- Loyalty Tier Distribution --}}
            <div class="col-xl-4 col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Loyalty Tier Distribution</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div style="width:150px; height:150px; position:relative; flex-shrink:0;">
                                <canvas id="loyaltyTierChart"></canvas>
                                <div class="position-absolute top-50 start-50 translate-middle text-center">
                                    <h4 class="mb-0" id="loyaltyTierTotal">0</h4>
                                    <small class="text-muted">Total</small>
                                </div>
                            </div>
                            <div class="flex-grow-1 ps-3" id="loyaltyTierLegend">
                                {{-- JS will inject: VIP, Gold, Silver, Bronze, New --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Loyalty Trend --}}
            <div class="col-xl-8 col-lg-7">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Loyalty Tier System Info</h4>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tier</th>
                                        <th>Criteria</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><span class="badge bg-purple text-white">VIP</span></td>
                                        <td>কমপক্ষে ১০টি completed order <strong>অথবা</strong> মোট ক্রয় ৳১০,০০০+</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-warning text-dark">Gold</span></td>
                                        <td>কমপক্ষে ৬টি completed order <strong>অথবা</strong> মোট ক্রয় ৳৫,০০০+</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-secondary text-white">Silver</span></td>
                                        <td>কমপক্ষে ৩টি completed order <strong>অথবা</strong> মোট ক্রয় ৳৩,০০০+</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-danger text-white">Bronze</span></td>
                                        <td>কমপক্ষে ১টি completed order <strong>অথবা</strong> মোট ক্রয় ৳১,০০০+</td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-info text-white">New</span></td>
                                        <td>মোট অর্ডার (যেকোনো status) ১টি বা তার কম</td>
                                    </tr>



                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted d-block mt-2">
                            * Completed order বলতে <strong>Delivered</strong> ও <strong>Confirmed</strong> status এর order
                            বোঝানো হয়েছে। Order count বা spend amount — যেটা আগে threshold এ পৌঁছায়, সেটার ভিত্তিতে tier
                            নির্ধারিত হয়।
                        </small>
                    </div>
                </div>
            </div>
        </div><!--end row-->
        {{-- ===== Existing Table (unchanged) ===== --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row align-items-center mb-2">
                            <div class="col">
                                <h4 class="card-title">Customer Loyalty Report Table <span
                                        class="badge bg-primary"id="currentTotalCustomer"></span></h4>
                            </div>
                            <div class="col-auto">
                                <label>Filter By Date</label>
                                <input type="text" id="table-date-range" class="form-control form-control-sm"
                                    style="width:220px;" placeholder="Filter by order date">
                            </div>
                            <div class="col-auto">
                                <label>Filter By Loyality Tier</label>
                                <select id="tier-filter" class="form-select form-select-sm" style="width:160px;">
                                    <option value="">All Tiers</option>
                                    <option value="VIP">VIP</option>
                                    <option value="Gold">Gold</option>
                                    <option value="Silver">Silver</option>
                                    <option value="Bronze">Bronze</option>
                                    <option value="New">New</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <button id="reset_btn" class="btn btn-primary btn-sm me-2" type="button">Reset</button>
                                <a href="#" id="export-btn" class="btn btn-success btn-sm ms-2">
                                    Export <i class="la la-file-download"></i>
                                </a>
                            </div>

                        </div>
                    </div>
                    <div class="card-body pt-0">
                        {{-- <div id="loader" style="display:none; text-align:center; margin-bottom:10px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div> --}}

                        <div class="table-responsive">
                            <table class="table" id="szbd-datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Total Order</th>
                                        <th>Confirmed</th>
                                        <th>Cancelled</th>
                                        <th>Loyalty Tier</th>
                                        <th>Block/Unblock</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- container -->

@endsection
@push('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css">
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.js"></script>
    {{-- new js start --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {

            var trendChart;
            var currentStartDate = moment().subtract(30, 'days').format('YYYY-MM-DD');
            var currentEndDate = moment().format('YYYY-MM-DD');

            // ===== Loyalty Tier Donut =====
            var tierCtx = document.getElementById('loyaltyTierChart').getContext('2d');
            tierChart = new Chart(tierCtx, {
                type: 'doughnut',
                data: {
                    labels: ['VIP', 'Gold', 'Silver', 'Bronze', 'New'],
                    datasets: [{
                        data: [0, 0, 0, 0, 0],
                        backgroundColor: ['#7b61ff', '#ffc107', '#adb5bd', '#fd7e14', '#4f8cff'],
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
            // ===== Load Data Function (AJAX) =====
            function loadLoyaltyReport(startDate, endDate, trend = 'daily') {
                currentStartDate = startDate; // ✅ যোগ করো
                currentEndDate = endDate;
                $('#loader').show();

                $.ajax({
                    url: "{{ route('admin.customer-report.report') }}",
                    method: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                        trend: trend
                    },
                    success: function(res) {
                        // ---- Summary cards ----
                        $('#stat-total-customers').text(res.total_customers);
                        $('#stat-loyal-customers').text(res.loyal_customers);
                        $('#stat-loyal-customers-sub').text(res.loyal_percentage + '% of total');
                        $('#stat-vip-customers').text(res.vip_customers);
                        $('#stat-vip-customers-sub').text(res.vip_percentage + '% of total');
                        $('#stat-repeat-rate').text(res.repeat_rate + '%');

                        // ---- Tier Donut ----
                        $('#loyaltyTierTotal').text(res.total_customers);
                        tierChart.data.datasets[0].data = [
                            res.tier.vip, res.tier.gold, res.tier.silver, res.tier.bronze, res.tier
                            .new
                        ];
                        tierChart.update();

                        var legendHtml = '';
                        var tiers = [{
                                name: 'VIP',
                                value: res.tier.vip,
                                percent: res.tier.vip_percentage,
                                color: '#7b61ff'
                            },
                            {
                                name: 'Gold',
                                value: res.tier.gold,
                                percent: res.tier.gold_percentage,
                                color: '#ffc107'
                            },
                            {
                                name: 'Silver',
                                value: res.tier.silver,
                                percent: res.tier.silver_percentage,
                                color: '#adb5bd'
                            },
                            {
                                name: 'Bronze',
                                value: res.tier.bronze,
                                percent: res.tier.bronze_percentage,
                                color: '#fd7e14'
                            },
                            {
                                name: 'New',
                                value: res.tier.new,
                                percent: res.tier.new_percentage,
                                color: '#4f8cff'
                            }
                        ];
                        tiers.forEach(function(t) {
                            legendHtml +=
                                '<div class="d-flex justify-content-between align-items-center mb-2">' +
                                '<span><i class="bi bi-circle-fill me-1" style="color:' + t
                                .color + '; font-size:10px;"></i>' + t.name + '</span>' +
                                '<span>' + t.value + ' (' + t.percent + '%)</span>' +
                                '</div>';
                        });
                        $('#loyaltyTierLegend').html(legendHtml);


                    },
                    error: function(err) {
                        console.error(err);
                    },
                    complete: function() {
                        $('#loader').hide();
                    }
                });
            }



            // ===== Trend filter change =====
            $('#loyaltyTrendFilter').on('change', function() {
                loadLoyaltyReport(currentStartDate, currentEndDate, $(this).val()); // ✅ এভাবে
            });

            // ===== Initial Load =====
            loadLoyaltyReport(
                moment().subtract(30, 'days').format('YYYY-MM-DD'),
                moment().format('YYYY-MM-DD'),
                'daily'
            );
        });
    </script>
    {{-- new js end --}}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    </script>

    <script>
        (function($) {
            "use strict";

            const table = $('#szbd-datatable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.customer-report.datatables') }}",
                    data: function(d) {
                        d.loyalty_tier = $('#tier-filter').val();
                        var dateRange = $('#table-date-range').val();
                        if (dateRange) {
                            var parts = dateRange.split(' - ');
                            d.order_start_date = parts[0];
                            d.order_end_date = parts[1];
                        }
                    }
                },

                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center p-1'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'phone'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'total_order',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'total_confirmed',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'total_cancelled',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'loyalty_tier', // ✅ এই entry যোগ করো
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'is_active',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],

                order: [
                    [1, 'desc']
                ]
            });

            // ✅ Loader handling (CORRECT)
            table.on('processing.dt', function(e, settings, processing) {
                if (processing) {
                    $('#loader').fadeIn(100);
                } else {
                    $('#loader').fadeOut(100);
                }
            });
            $('#tier-filter').on('change', function() {
                table.ajax.reload();
            });
            $('#table-date-range').daterangepicker({
                opens: 'left',
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('#table-date-range').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD'));
                table.ajax.reload();
            });
            table.on('draw', function() {
                let info = table.page.info();
                $('#currentTotalCustomer').text(info.recordsTotal);
            });
            $('#table-date-range').on('cancel.daterangepicker', function() {
                $(this).val('');
                table.ajax.reload();
            });
            // Reset button click
            $('#reset_btn').on('click', function() {
                $('#table-date-range').val('');
                $('#tier-filter').val('');
                table.ajax.reload();
            });
            $('#export-btn').on('click', function(e) {
                e.preventDefault();

                var params = new URLSearchParams();

                var tier = $('#tier-filter').val();
                if (tier) params.append('loyalty_tier', tier);

                var dateRange = $('#table-date-range').val();
                if (dateRange) {
                    var parts = dateRange.split(' - ');
                    params.append('order_start_date', parts[0]);
                    params.append('order_end_date', parts[1]);
                }

                window.location.href = "{{ route('admin.customer-report.export') }}?" + params.toString();
            });
            // category delete
            $(document).on('click', '.delete', function() {
                var id = $(this).attr("data-id");
                Swal.fire({
                    title: 'Are you sure',
                    text: "You will not be able to revert this!",
                    showCancelButton: true,
                    type: 'warning',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('admin.customer.delete') }}",
                            method: 'POST',
                            data: {
                                id: id
                            },
                            success: function() {
                                toastr.success(
                                    'Customer deleted successfully.'
                                );
                                table.ajax.reload();

                            },
                            error: function(err) {
                                toastr.error(err.responseJSON.message);
                            }
                        });
                    }
                })
            });



        })(jQuery);
    </script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content')
            }
        });
    </script>
    <script>
        $(document).on('change', '.status', function() {
            var id = $(this).attr("data-id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('admin.customer.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data.success == true) {
                        toastr.success('Status updated successfully');
                    }
                },
                error: function(err) {
                    toastr.error('Something Went Wrong!');
                }
            });
        });
    </script>
@endpush
