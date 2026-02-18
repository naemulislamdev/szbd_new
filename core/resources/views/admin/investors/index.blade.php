@extends('admin.layouts.app')
@section('title', 'Investors Management')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Investors Information</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->

                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Investors</li>
                        </ol>
                    </div>
                </div><!--end page-title-box-->
            </div><!--end col-->

        </div><!--end row-->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row align-items-center">
                            <div class="col-6">
                                <h4 class="card-title">Investors List</h4>
                            </div><!--end col-->
                            <div class="col-sm-6 text-end mb-2">
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#dateExportModal"><i class="las la-file-excel"></i> Export</button>
                            </div>
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
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Invest Amount</th>
                                        <th>Home Status</th>
                                        <th>Comment</th>
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


    <div class="modal fade" id="remarkAddModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
        data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <form id="remarkForm">
                @csrf
                <input type="hidden" id="investId" name="id" value="">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Remark</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Remark <span class="text-danger">*</span></label>
                            <textarea name="remark" class="form-control" required></textarea>
                            <small class="text-danger error_remark"></small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="viewInvestorModal" tabindex="-1" aria-labelledby="viewInvestorModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <!-- Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="viewInvestorModalLabel">
                        Investor Info
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Body -->
                <div class="modal-body">
                    <div class="row">

                        <div class="col-12 mb-3">
                            <div class="row">
                                <div class="col-3">Date</div>
                                <div class="col-2">:</div>
                                <div class="col-7">
                                    <strong id="viewDate"></strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="row">
                                <div class="col-3">Investor Name</div>
                                <div class="col-2">:</div>
                                <div class="col-7">
                                    <strong id="viewName"></strong>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="row">
                                <div class="col-3">Phone</div>
                                <div class="col-2">:</div>
                                <div class="col-7">
                                    <strong id="viewPhone"></strong>
                                </div>
                            </div>
                        </div>


                        <div class="col-12 mb-3">
                            <div class="row">
                                <div class="col-3">Occupation</div>
                                <div class="col-2">:</div>
                                <div class="col-7">
                                    <strong id="viewOccupation"></strong>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="row">
                                <div class="col-3">Investment Amount</div>
                                <div class="col-2">:</div>
                                <div class="col-7">
                                    <strong id="viewInvestmentAmount"></strong>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="row">
                                <div class="col-3">Remark Note</div>
                                <div class="col-2">:</div>
                                <div class="col-7">
                                    <strong id="viewRemark"></strong>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mb-3">
                            <div class="row">
                                <div class="col-3">Status</div>
                                <div class="col-2">:</div>
                                <div class="col-7">
                                    <strong id="viewStatus"></strong>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>

            </div>
        </div>
    </div>
    {{-- Data Export Modal --}}
   <div class="modal fade" id="dateExportModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="GET" action="{{ route('admin.investors.data_export') }}">
                <div class="modal-header">
                    <h5 class="modal-title">Export Investors (Date Wise)</h5>
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

            const table = $('#szbd-datatable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: false,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.investors.datatables', 'all') }}",

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
                        data: 'mobile_number'
                    },
                    {
                        data: 'address'
                    },
                    {
                        data: 'investment_amount'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'remark'
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
                            url: "{{ route('admin.investors.delete') }}",
                            method: 'POST',
                            data: {
                                id: id
                            },
                            success: function() {
                                toastr.success(
                                    'Investor Deleted Successfully.'
                                );
                                table.ajax.reload();

                            }
                        });
                    }
                })
            });

            // Update form
            $('#remarkForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                console.log(formData);


                $.ajax({
                    url: "{{ route('admin.investors.remark.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {

                        // modal close
                        $('#remarkAddModal').modal('hide');

                        // form reset
                        $('#remarkForm')[0].reset();

                        // datatable reload (🔥 main part)
                        table.ajax.reload(null, false);

                        toastr.success(res.message ?? 'Remark added successfully');
                    },
                    error: function(err) {
                        toastr.error('Something went wrong!');
                    }
                });
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
                url: "{{ route('admin.category.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    home_status: status
                },
                success: function(data) {
                    if (data.success == true) {
                        toastr.success('Status updated successfully');
                    }
                },
                error: function(err) {
                    toastr.error(err.responseJSON.message ?? 'Something went wrong!');
                }
            });
        });
    </script>

    <script>
        $(document).on('click', '.addRemarkBtn', function() {
            let button = $(this);
            $('#investId').val(button.data('id'));
        });
        $(document).on('click', '.viewBtn', function() {
            let button = $(this);
            $('#viewName').text(button.data('name'));
            $('#viewPhone').text(button.data('mobile'));
            $('#viewAddress').text(button.data('address'));
            $('#viewOccupation').text(button.data('occupation'));
            $('#viewInvestmentAmount').text(button.data('investment-amount'));
            $('#viewRemark').text(button.data('remark'));
            $('#viewDate').text(button.data('date'));
            $('#viewStatus').text(button.data('status'));
        });
    </script>
@endpush
