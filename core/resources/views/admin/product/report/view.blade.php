@extends('admin.layouts.app')
@section('title', 'Product Sales Report')

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Product Sales Report From <span id="startDate"></span> to <span
                            id="endDate"></span></h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}">Products</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Sales Report</li>
                        </ol>
                    </div>
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <div class="row">
            <div class="col-md-12">
                <div class="row mb-3 align-items-center">
                    <div class="col-md-3 mb-2">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select id="statusFilter" name="status" class="form-control" required>
                            <option value="all">all</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="processing">Processing</option>
                            <option value="out_for_delivery">Out for delivery</option>
                            <option value="delivered">Delivered</option>
                            <option value="returned">Returned</option>
                            <option value="failed">Failed</option>
                            <option value="canceled">Canceled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="from_date">From Date</label>
                        <input type="date" id="from_date" class="form-control" placeholder="From Date">
                    </div>
                    <div class="col-md-3">
                        <label for="to_date">To Date</label>
                        <input type="date" id="to_date" class="form-control" placeholder="To Date">
                    </div>
                    <div class="col-md-1">
                        <button type="button" id="filter_btn" class="btn btn-primary mt-3">Filter</button>
                    </div>
                    <div class="col-md-1">
                        <button type="button" id="reset_btn" class="btn btn-secondary mt-3">Reset</button>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-sm btn-primary mt-3" data-bs-toggle="modal"
                            data-bs-target="#dateExportModal"><i class="las la-file-excel"></i>
                            Export</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="main" id="mainContent">
            <div class="row">
                <div class="col-md-3">
                    <div class="card text-left">
                        <div class="card-body">
                            <h5 class="card-title">Total Sales Amount</h5>
                            <h3 id="total_price" class="card-text">578412 BDT</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-left">
                        <div class="card-body">
                            <h5 class="card-title">Total Discount</h5>
                            <h3 id="total_discount" class="card-text">507 BDT</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-left">
                        <div class="card-body">
                            <h5 class="card-title">Total Paid Amount</h5>
                            <h3 class="card-text">0 BDT</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-left">
                        <div class="card-body">
                            <h5 class="card-title">Total Expense</h5>
                            <h3 class="card-text">0 BDT</h3>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                <h4 class="card-title">Product Sales List</h4>
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
                                        <th>SL#</th>
                                        <th>Image</th>
                                        <th>Code</th>
                                        <th>Attributes</th>
                                        <th>Qty</th>
                                        <th>Purchase Price</th>
                                        <th>Sold Price</th>
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
                <form method="GET" action="{{ route('admin.order.data_export') }}">
                    <div class="modal-header">
                        <h5 class="modal-title">Export Orders (Date Wise)</h5>
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
                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-control" required>
                                <option value="all">all</option>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="processing">Processing</option>
                                <option value="out_for_delivery">Out for delivery</option>
                                <option value="delivered">Delivered</option>
                                <option value="returned">Returned</option>
                                <option value="failed">Failed</option>
                                <option value="canceled">Canceled</option>
                            </select>
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
                    url: "{{ route('admin.product.reportDatatables') }}", // remove hardcoded 'all'
                    data: function(d) {
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                        d.status = $('#statusFilter').val() || 'all'; // fallback to 'all'
                    },
                    dataSrc: function(json) {
                        // Totals update
                        // $('#total_qty').text(json.totals.total_qty);
                        $('#total_price').text(json.totals.total_price.toFixed(2));
                        $('#total_discount').text(json.totals.total_discount.toFixed(2));
                        return json.data;
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
                        orderable: false,
                        searchable: false,
                        className: 'text-center p-1'
                    },
                    {
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'attribute',
                        name: 'attribute'
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'purchase_price',
                        name: 'purchase_price'
                    },
                    {
                        data: 'sold_price',
                        name: 'sold_price'
                    }
                ]
            });

            // 🔹 Filter on status change
            $('#statusFilter').change(function() {
                table.ajax.reload();
            });

            // 🔹 Filter button click (date range)
            $('#filter_btn').on('click', function() {
                table.ajax.reload();
            });

            // 🔹 Reset button click (reset all filters)
            $('#reset_btn').on('click', function() {
                $('#from_date').val('');
                $('#to_date').val('');
                $('#statusFilter').val('all'); // reset status
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
    </script>
@endpush
