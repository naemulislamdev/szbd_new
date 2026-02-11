@extends('admin.layouts.app')
@section('title', 'User Management')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">User Information</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->

                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Users</li>
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
                                <h4 class="card-title">Users List</h4>
                            </div><!--end col-->
                            <div class="col-6 text-end pb-2">
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#addUserModal">
                                    <i class="la la-plus-circle"></i> Add User
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
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Branch</th>
                                        <th>Role</th>
                                        <th>Block/Unblock</th>
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

    {{-- Add User Modal --}}
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <form id="addForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row">

                            <div class="col-md-6 mb-2">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter User Name"
                                    required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Phone</label>
                                <input required type="text" name="phone" class="form-control"
                                    placeholder="Enter User Phone">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="Enter User Email"
                                    required>
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="name">Role</label>
                                <select style="width: 100%" class=" form-select" name="role_id" required>
                                    <option disabled selected>----Select Role----</option>

                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach


                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="name">Branch</label>
                                <select style="width: 100%" class=" form-select" name="branch_id" required>
                                    <option disabled selected>----Select Branch----</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach


                                </select>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Password</label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Enter Password" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Employee image
                                    <small class="text-danger">
                                        ratio 1:1
                                    </small>
                                </label>
                                <input id="customFileEg" type="file" name="image" class="form-control"
                                    accept=".jpg,.png,.jpeg,.gif,.bmp,.tif,.tiff|image/*" required>
                            </div>

                            <div class="col-12">
                                <hr>
                                <div class="text-center">
                                    <img id="viewerAdd" class="imgViewer"
                                        src="{{ asset('assets/backend/images/placeholder.jpg') }}"
                                        style="width:200px;max-height:200px;border:1px solid;border-radius:10px; object-fit:contain;">
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
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="userId">
                        <div class="row">

                            <div class="col-md-6 mb-2">
                                <label class="form-label">Name</label>
                                <input id="name" required type="text" name="name" class="form-control"
                                    placeholder="Enter User Name" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Phone</label>
                                <input id="phone" required type="text" name="phone" class="form-control"
                                    placeholder="Enter User Phone" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Email</label>
                                <input id="email" required type="email" name="email" class="form-control"
                                    placeholder="Enter User Email" required>
                            </div>

                            <div class="col-md-6 form-group">
                                <label for="name">Role</label>
                                <select style="width: 100%" class=" form-select" id="role" name="role_id" required>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach


                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="name">Branch</label>
                                <select style="width: 100%" class=" form-select" id="branch" name="branch_id"
                                    required>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach

                                </select>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Password</label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Enter Password">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Employee image
                                    <small class="text-danger">
                                        ratio 1:1
                                    </small>
                                </label>
                                <input id="customFileEg1" type="file" name="image" class="form-control"
                                    accept=".jpg,.png,.jpeg,.gif,.bmp,.tif,.tiff|image/*">
                            </div>

                            <div class="col-12">
                                <hr>
                                <div class="text-center">
                                    <img id="viewer" class="imgViewer"
                                        src="{{ asset('assets/backend/images/placeholder.jpg') }}"
                                        style="width:200px;max-height:200px;border:1px solid;border-radius:10px; object-fit:contain;">
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>

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
                    url: "{{ route('admin.employee.datatables') }}",
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
                        data: 'email'
                    },
                    {
                        data: 'branch'
                    },
                    {
                        data: 'role'
                    },
                    {
                        data: 'status'
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
            // Update form
            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.employee.update') }}",
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

                        toastr.success(res.message ?? 'Employee updated successfully');
                    },
                    error: function(err) {
                        toastr.error(err.responseJSON.message ?? 'Something went wrong!');
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
                            url: "{{ route('admin.employee.delete') }}",
                            method: 'POST',
                            data: {
                                id: id
                            },
                            success: function() {
                                toastr.success(
                                    'User Deleted Successfully.'
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
                    url: "{{ route('admin.employee.store') }}",
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

                        toastr.success(res.message ?? 'User added successfully');
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
                url: "{{ route('admin.employee.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data.success == true) {
                        toastr.success('Status Changed successfully');
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
            $('#userId').val(button.data('id'));
            $('#name').val(button.data('name'));
            $('#phone').val(button.data('mobile'));
            $('#email').val(button.data('email'));

            $('#branch').val(button.data('branch')).trigger('change');
            $('#role').val(button.data('role')).trigger('change');

            $('#address').text(button.data('address'));
            $('#date').text(button.data('date'));
            $('#status').text(button.data('status'));
        });
    </script>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewerAdd').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg").change(function() {
            readURL(this);
        });
    </script>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function() {
            readURL(this);
        });
    </script>
@endpush
