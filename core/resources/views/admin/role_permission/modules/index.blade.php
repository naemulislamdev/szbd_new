@extends('admin.layouts.app')
@section('title', 'User Management')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Roles Modules</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->

                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Modules</li>
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
                                <h4 class="card-title">Module List</h4>
                            </div><!--end col-->
                            <div class="col-6 text-end pb-2">
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#addUserModal">
                                    <i class="la la-plus-circle"></i> Add New Module
                                </button>
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
                                        <th>Title</th>
                                        <th>Module Items</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container -->

    {{-- Add User Modal --}}
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="addForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Module</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" placeholder="Enter Module Title"
                                    required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <h5>Module Items</h6>
                                    <div class="d-flex align-items-center">
                                        <input type="checkbox" name="actions[]" id="action1" value="view">
                                        <label class="ms-1" for="action1">View</label>
                                        <input class="ms-1" type="checkbox" name="actions[]" id="action2"
                                            value="create">
                                        <label class="ms-1" for="action2">Create</label>
                                        <input class="ms-1" type="checkbox" name="actions[]" id="action3"
                                            value="edit">
                                        <label class="ms-1" for="action3">Edit</label>
                                        <input class="ms-1" type="checkbox" name="actions[]" id="action4"
                                            value="delete">
                                        <label class="ms-1" for="action4">Delete</label>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Edit User Modal --}}
    <div class="modal fade" id="viewInvestorModal" tabindex="-1" aria-labelledby="viewInvestorModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <form id="editForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="moduleId">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Module</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" id="title" class="form-control"
                                    placeholder="Enter Module Title" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <h5>Module Items</h6>
                                    <div class="d-flex align-items-center">
                                        <input type="checkbox" name="actions[]" id="action1.1" value="view">
                                        <label class="ms-1" for="action1.1">View</label>
                                        <input class="ms-1" type="checkbox" name="actions[]" id="action1.2"
                                            value="create">
                                        <label class="ms-1" for="action1.2">Create</label>
                                        <input class="ms-1" type="checkbox" name="actions[]" id="action1.3"
                                            value="edit">
                                        <label class="ms-1" for="action1.3">Edit</label>
                                        <input class="ms-1" type="checkbox" name="actions[]" id="action1.4"
                                            value="delete">
                                        <label class="ms-1" for="action1.4">Delete</label>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
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
                    url: "{{ route('admin.permission_module.datatables') }}",
                },

                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center p-1'
                    },
                    {
                        data: 'title'
                    },

                    {
                        data: 'actions',

                    },
                    {
                        data: 'action',
                        searchable: false,
                        orderable: false,
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
            // Update form
            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.permission_module.update') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {

                        // modal close
                        $('#viewInvestorModal').modal('hide');

                        // form reset
                        $('#editForm')[0].reset();

                        // datatable reload (🔥 main part)
                        table.ajax.reload(null, false);

                        toastr.success(res.message ?? 'Module updated successfully');
                    },
                    error: function(err) {
                        toastr.error('Something went wrong!');
                    }
                });
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
                            url: "{{ route('admin.permission_module.delete') }}",
                            method: 'POST',
                            data: {
                                id: id
                            },
                            success: function() {
                                toastr.success(
                                    'Module Deleted Successfully.'
                                );
                                table.ajax.reload();

                            }
                        });
                    }
                })
            });

            // add form
            $('#addForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                console.log(formData);


                $.ajax({
                    url: "{{ route('admin.permission_module.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {

                        // modal close
                        $('#addUserModal').modal('hide');

                        // form reset
                        $('#addForm')[0].reset();

                        // datatable reload (🔥 main part)
                        table.ajax.reload(null, false);

                        toastr.success(res.message ?? 'Module added successfully');
                    },
                    error: function(err) {
                        toastr.error('Something went wrong!');
                    }
                });
            });


        })(jQuery);
    </script>
@endpush
