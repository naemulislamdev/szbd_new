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
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">Daily Sales Report</h4>
                            </div><!--end col-->
                            <div class="col-auto">
                                <div class="row mb-3 align-items-center">
                                    <div class="col-md-3">
                                        <label for="from_date">From Date</label>
                                        <input type="date" id="from_date" value="{{ date('Y-m-d') }}"
                                            class="form-control" placeholder="From Date">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="to_date">To Date</label>
                                        <input type="date" id="to_date" value="{{ date('Y-m-d') }}"
                                            class="form-control" placeholder="To Date">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" id="filter_btn" class="btn btn-primary">Filter</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" id="reset_btn" class="btn btn-secondary">Reset</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-sm btn-primary" onclick="exportDailySales()"><i
                                                class="las la-file-excel"></i>
                                            Export</button>
                                    </div>
                                </div>

                            </div><!--end col-->
                        </div><!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body pt-0">
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
            </div> <!-- end col -->
        </div> <!-- end row -->
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

            var table = $('#szbd-datatable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: false,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.report.dailySalesData') }}",
                    data: function(d) {
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
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
                        name: 'products.thumbnail'
                    },
                    {
                        data: 'name',
                        name: 'products.name'
                    },
                    {
                        data: 'variation',
                        name: 'order_details.variation'
                    },
                    {
                        data: 'code',
                        name: 'products.code'
                    },
                    {
                        data: 'total_qty',
                        name: 'total_qty'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount'
                    },
                ]
            });
            // Filter button click
            $('#filter_btn').on('click', function() {
                table.ajax.reload();
            });

            // Reset button click
            $('#reset_btn').on('click', function() {
                $('#from_date').val('');
                $('#to_date').val('');
                table.ajax.reload();
            });


        })(jQuery);
    </script>
    <script>
        //Featured Status Change
        $(document).on('change', '.change-featured', function() {

            let featured = $(this).is(':checked') ? 1 : 0;
            let id = $(this).data('id');

            $.ajax({
                url: "{{ route('admin.product.featured.status') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    featured: featured
                },
                success: function(res) {
                    toastr.success(res.message);
                },
                error: function() {
                    toastr.error('Something went wrong!');
                }
            });

        });
        //Product Arrival Status Change
        $(document).on('change', '.change-arrival', function() {

            let arrival = $(this).is(':checked') ? 1 : 0;
            let id = $(this).data('id');

            $.ajax({
                url: "{{ route('admin.product.arrival.status') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    arrival: arrival
                },
                success: function(res) {
                    toastr.success(res.message);
                },
                error: function() {
                    toastr.error('Something went wrong!');
                }
            });

        });

        //Product Status Change
        $(document).on('change', '.change-status', function() {

            let status = $(this).is(':checked') ? 1 : 0;
            let id = $(this).data('id');

            $.ajax({
                url: "{{ route('admin.product.status') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    status: status
                },
                success: function(res) {
                    toastr.success(res.message);
                },
                error: function() {
                    toastr.error('Something went wrong!');
                }
            });

        });

        // Export Daily Sales
        function exportDailySales() {
            let from_date = $('#from_date').val();
            let to_date = $('#to_date').val();

            if (!from_date || !to_date) {
                toastr.error('Please select both From Date and To Date.');
                return;
            }

            let url = "{{ route('admin.report.dailySalesExport') }}?from_date=" + from_date + "&to_date=" + to_date +
                "&export=1";
            window.open(url, '_blank');
        }
    </script>
@endpush
