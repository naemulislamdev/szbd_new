@extends('admin.layouts.app')
@section('title', 'Delivered Orders')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Delivered Orders</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item"><a href="#">Orders</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Delivered Order</li>
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
                                <h4 class="card-title">Order</h4>
                            </div><!--end col-->
                            <div class="col-auto">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <input type="date" id="from_date" class="form-control" placeholder="From Date">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" id="to_date" class="form-control" placeholder="To Date">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" id="filter_btn" class="btn btn-primary">Filter</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" id="reset_btn" class="btn btn-secondary">Reset</button>
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
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Customer Name</th>
                                        <th>Phone</th>
                                        <th>Amount</th>
                                        <th>Delivery Charge</th>
                                        <th>Total</th>
                                        <th>Order Status</th>
                                        <th>Order Type</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container -->
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
                    url: "{{ route('admin.order.datatables', 'delivered') }}",
                    data: function(d) {
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    },
                    beforeSend: function() {
                        // Show loader before request
                        $('#loader').show();
                    },
                    complete: function() {
                        // Hide loader after response
                        $('#loader').hide();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'order_number',
                        name: 'order_number'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'time',
                        name: 'time'
                    },
                    {
                        data: 'customer_name',
                        name: 'shipping_addresses.contact_person_name'
                    },
                    {
                        data: 'phone',
                        name: 'shipping_addresses.phone'
                    },
                    {
                        data: 'amount',
                        name: 'order_amount'
                    },
                    {
                        data: 'delivery_charge',
                        name: 'shipping_cost'
                    },
                    {
                        data: 'total',
                        name: 'total'
                    },
                    {
                        data: 'order_status',
                        name: 'order_status'
                    },
                    {
                        data: 'order_type',
                        name: 'order_type'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
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
@endpush
