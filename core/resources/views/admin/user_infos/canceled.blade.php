@extends('admin.layouts.app')
@section('title', 'Canceled User Infos')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Canceled User Infos <span class="badge bg-danger ms-2">Total: {{ $userInfoCounts->canceled ?? 0 }}</span></h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item"><a href="#">user info</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Canceled user info</li>
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
                                <h4 class="card-title">User Info</h4>
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
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                        <th>Product</th>
                                        <th>Type</th>
                                        <th>Order Process</th>
                                        <th>Order Status</th>
                                        <th>Note</th>
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

            const table = $('#szbd-datatable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.userinfo.datatables', 'canceled') }}",
                    data: function(d) {
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    }
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
                        data: 'name'
                    },
                    {
                        data: 'phone'
                    },
                    {
                        data: 'address'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'product_details'
                    },
                    {
                        data: 'type'
                    },
                    {
                        data: 'order_process',
                        className: 'text-center'
                    },
                    {
                        data: 'order_status',
                        width: '120px'
                    },
                    {
                        data: 'order_note'
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

            $('#filter_btn').on('click', () => table.ajax.reload());

            $('#reset_btn').on('click', function() {
                $('#from_date, #to_date').val('');
                table.ajax.reload();
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

        $(document).on('change', '.order-status-select', function() {

            let select = $(this);
            let orderId = select.data('id');
            let oldValue = select.data('current');
            let newValue = select.val();

            Swal.fire({
                title: 'Are you sure Change this?',
                input: 'text',
                inputPlaceholder: 'Write status note',
                showCancelButton: true,
                confirmButtonText: 'Yes, Change it!',
                cancelButtonText: 'Cancel',

                preConfirm: (note) => {
                    if (!note) {
                        Swal.showValidationMessage('Status note is required');
                        return false;
                    }
                    return note;
                }

            }).then((result) => {
                $.post("{{ route('admin.userinfo.status.update') }}", {
                        id: orderId,
                        status: newValue,
                        note: result.value
                    })
                    .done(function(res) {
                        Swal.fire('Updated!', res.message, 'success');

                        select.data('current', newValue);
                        $('#szbd-datatable').DataTable().ajax.reload(null, false);
                    })
                    .fail(function() {
                        Swal.fire('Error!', 'Something went wrong', 'error');
                        select.val(oldValue);
                    });
            });
        });
    </script>
@endpush
