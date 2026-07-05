@extends('admin.layouts.app')
@section('title', 'Product Stock Report')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Products </h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->

                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Product Stock Report</li>
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
                                <h4 class="card-title">Product Stock Report <span class="badge bg-primary"
                                        id="totalProduct"></span></h4>
                            </div>
                            <div class="col-auto">
                                <label for="stock-filter">Filter by Product Stock</label>
                                <select id="stock-filter" class="form-select form-select-sm">
                                    <option value="">All</option>
                                    <option value="out_of_stock">Out of Stock (0)</option>
                                    <option value="low_stock">Low Stock <small>(Less than 10)</small></option>
                                    <option value="available">Available</option>
                                </select>
                            </div>
                            <div class="col-auto">
                                <label for="stock-filter">Filter by Product Status</label>
                                <select id="product-status-filter" class="form-select form-select-sm">
                                    <option value="">All Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive overflow-hidden">
                            <table class="table" id="szbd-datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>SL</th>
                                        <th>Image</th>
                                        <th>Product Name</th>
                                        <th>Code</th>
                                        <th>Price</th>
                                        <th>Product Status</th>
                                        <th>Stock Qty</th>
                                        <th>Stock Status</th>
                                        <th>Last Updated</th>
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
                ordering: false, // sort always qty asc from backend
                ajax: {
                    url: "{{ route('admin.product_stock.datatables') }}",
                    data: function(d) {
                        d.filter = $('#stock-filter').val();
                        d.status_filter = $('#product-status-filter').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'photo',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        orderable: false
                    },

                    {
                        data: 'code',
                        orderable: false
                    },
                    {
                        data: 'unit_price',
                        orderable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false
                    },
                    {
                        data: 'qty',
                        orderable: false
                    },
                    {
                        data: 'stock_status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'updated_at',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#stock-filter').on('change', function() {
                table.draw();
            });
            $('#product-status-filter').on('change', function() {
                table.draw();
            });
            table.on('draw', function() {
                let info = table.page.info();
                $('#totalProduct').text(info.recordsTotal);
            });

        })(jQuery);
    </script>
@endpush
