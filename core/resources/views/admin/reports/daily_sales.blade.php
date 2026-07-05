@extends('admin.layouts.app')
@section('title', 'Daily Sales Report')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Daily Sales Report</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item"><a href="#">Reports</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Daily Sales Report</li>
                        </ol>
                    </div>
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-end g-2">
                            <div class="col-md-3">
                                <label for="report_type">Report Type</label>
                                <select id="report_type" class="form-control">
                                    <option value="today" selected>Today sales</option>
                                    <option value="yesterday">Yesterday sales</option>
                                    <option value="last_7_days">Last 7 days sales</option>
                                    <option value="monthly">Monthly sales</option>
                                    <option value="custom">Custom range</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="order_status">Order Status</label>
                                <select id="order_status" class="form-control" multiple>
                                    <option value="pending">Pending</option>
                                    <option value="confirmed" selected>Confirmed</option>
                                    <option value="processing">Processing</option>
                                    <option value="out_for_delivery">Out for delivery</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="canceled">Canceled</option>
                                    <option value="returned">Returned</option>
                                    <option value="failed">Failed</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="">From Date</label>
                                <input type="date" id="from_date" value="{{ date('Y-m-d') }}" class="form-control">
                            </div>

                            <div class="col-md-2">
                                <label for="">To Date</label>
                                <input type="date" id="to_date" value="{{ date('Y-m-d') }}" class="form-control">
                            </div>

                            <div class="col-md-1">
                                <button type="button" id="filter_btn" class="btn btn-primary w-100">Filter</button>
                            </div>

                            <div class="col-md-1">
                                <button type="button" id="reset_btn" class="btn btn-secondary w-100">Reset</button>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12 text-end">
                                <button class="btn btn-sm btn-primary" onclick="exportDailySales()">
                                    <i class="las la-file-excel"></i> Export
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-0">

                        <!-- Summary cards -->
                        <div class="row mb-4 mt-3" id="sales_summary_cards">
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6>Today Sales</h6>
                                        <h4 id="today_sales">0.00</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6>Yesterday Sales</h6>
                                        <h4 id="yesterday_sales">0.00</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6>Last 7 Days Sales</h6>
                                        <h4 id="last_7_days_sales">0.00</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6>Monthly Sales</h6>
                                        <h4 id="monthly_sales">0.00</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="loader" style="display:none; text-align:center; margin-bottom:10px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        <div class="table-responsive overflow-hidden">
                            <table class="table" id="szbd-datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>SL</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Attributes</th>
                                        <th>Code</th>
                                        <th>Qty</th>
                                        <th>Total Selling Amount</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- container -->
    {{-- Data Export Modal --}}
    <div class="modal fade" id="dateExportModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="GET" action="#">
                    <div class="modal-header">
                        <h5 class="modal-title">Export Daily (Date Wise)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Form Date <span class="text-danger">*</span></label>
                            <input type="date" name="from_date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">To Date <span class="text-danger">*</span></label>
                            <input type="date" name="to_date" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="btn btn-success">
                            Export Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
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

            $('#order_status').select2({
                placeholder: 'Select order status',
                allowClear: true,
                width: '100%'
            });

            function toggleCustomDateFields() {
                let reportType = $('#report_type').val();

                if (reportType === 'custom') {
                    $('#from_date').prop('disabled', false);
                    $('#to_date').prop('disabled', false);
                } else {
                    $('#from_date').prop('disabled', true);
                    $('#to_date').prop('disabled', true);
                }
            }

            function loadSummaryData() {
                $.ajax({
                    url: "{{ route('admin.report.dailySalesSummary') }}",
                    type: "GET",
                    data: {
                        report_type: $('#report_type').val(),
                        from_date: $('#from_date').val(),
                        to_date: $('#to_date').val(),
                        order_status: $('#order_status').val()
                    },
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    success: function(res) {
                        $('#today_sales').text(res.today_sales);
                        $('#yesterday_sales').text(res.yesterday_sales);
                        $('#last_7_days_sales').text(res.last_7_days_sales);
                        $('#monthly_sales').text(res.monthly_sales);
                    },
                    complete: function() {
                        $('#loader').hide();
                    }
                });
            }

            var table = $('#szbd-datatable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: false,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.report.dailySalesData') }}",
                    data: function(d) {
                        d.report_type = $('#report_type').val();
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                        d.order_status = $('#order_status').val();
                    },
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    complete: function() {
                        $('#loader').hide();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'thumbnail',
                        name: 'products.thumbnail',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'products.name'
                    },
                    {
                        data: 'variation',
                        name: 'variation',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'code',
                        name: 'products.code'
                    },
                    {
                        data: 'total_qty',
                        name: 'total_qty',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#report_type').on('change', function() {
                toggleCustomDateFields();
            });

            $('#filter_btn').on('click', function() {
                table.ajax.reload();
                loadSummaryData();
            });

            $('#reset_btn').on('click', function() {
                $('#report_type').val('today').trigger('change');
                $('#from_date').val("{{ date('Y-m-d') }}");
                $('to_date').val("{{ date('Y-m-d') }}");
                $('#order_status').val(['confirmed']).trigger('change');

                table.ajax.reload();
                loadSummaryData();
            });

            toggleCustomDateFields();
            loadSummaryData();

        })(jQuery);
    </script>
    <script>
        // Export Daily Sales
        function exportDailySales() {
            let report_type = $('#report_type').val();
            let from_date = $('#from_date').val();
            let to_date = $('#to_date').val();
            let order_status = $('#order_status').val();

            let url =
                `{{ route('admin.report.dailySalesExport') }}?report_type=${report_type}&from_date=${from_date}&to_date=${to_date}`;

            if (order_status && order_status.length > 0) {
                order_status.forEach(function(status) {
                    url += `&order_status[]=${status}`;
                });
            }

            window.location.href = url;
        }
    </script>
@endpush
