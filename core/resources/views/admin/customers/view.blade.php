@extends('admin.layouts.app')
@section('title', 'Customers Details')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center container px-0">

                    <div>
                        <h3 class="card-title fw-bold">Customer ID: #{{ $customer->id }}</h3>
                        <span class="mt-3">
                            <i class="las la-calendar"></i>
                            </i> Joined At :
                            {{ date('d M Y h:i A', strtotime($customer['created_at'])) }}
                        </span>
                    </div>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item"><a href="{{ route('admin.customer.list') }}">Customers</a>
                            </li><!--end nav-item-->
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Customers Details</li>
                        </ol>
                    </div>
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="card-title">Order Info</h3>

                            </div><!--end col-->

                        </div><!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body pt-0">
                        <div id="loader" style="display:none; text-align:center; margin-bottom:10px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table" id="szbd-datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>SL#</th>
                                        <th>Date</th>
                                        <th>Order Id</th>
                                        <th>Total Amount</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header border-bottom py-2">
                        <h4 class="mb-0 fw-bold">Customer Info</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="las la-user-circle text-success" style="font-size: 30px"></i>
                            <h6 class="card-title text-success fw-bold ms-2  mb-0">
                                {{ $customer->f_name . ' ' . $customer->l_name }}
                            </h6>
                        </div>
                        <div class="d-flex align-items-center  mt-3">
                            <div class="border text-center bg-soft-success"
                                style="width: 40px; height: 40px; border-radius: 50%; line-height: 40px">
                                <i class="las la-shopping-basket" style="font-size: 20px"></i>
                            </div>
                            <h5 class="fw-bold ms-3 mb-0"> Total Order <span class="text-success">{{ $totalOrder }}</span>
                            </h5>
                        </div>
                        <div class="  mt-3">
                            <h5 class="fw-bold">Contact</h5>
                            <div class="d-flex align-items-center">
                                <div class="border text-center bg-soft-success"
                                    style="width: 40px; height: 40px; border-radius: 50%; line-height: 40px">
                                    <i class="las la-envelope" style="font-size: 20px"></i>
                                </div>
                                <p class="text-success mb-0 ms-2">{{ $customer->email }}</p>
                            </div>
                            <div class="d-flex align-items-center mt-3">
                                <div class="border text-center bg-soft-success"
                                    style="width: 40px; height: 40px; border-radius: 50%; line-height: 40px">
                                    <i class="las la-phone" style="font-size: 20px"></i>
                                </div>
                                <p class="text-success mb-0 ms-2">{{ $customer->phone }}</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
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

            const table = $('#szbd-datatable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.customer.customerDatatables', $customer->id) }}",
                },


                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center p-1'
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'id'
                    },
                    {
                        data: 'order_amount'
                    },
                    {
                        data: 'order_status'
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
