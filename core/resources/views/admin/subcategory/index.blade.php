@extends('admin.layouts.app')
@section('title', 'Sub-category Management')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">All Sub-Categories</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item"><a href="#">Sub-category</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">All Sub-category</li>
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
                                <h4 class="card-title">All subcategories</h4>
                            </div><!--end col-->
                            <div class="col-auto">
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#categoryModal"><i class="la la-plus-circle"></i> Add New
                                            Sub-category</button>
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
                                        <th>Name</th>
                                        <th>Slug</th>
                                        <th>Category</th>
                                        <th>Order Number</th>
                                        <th>Status</th>
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

    <!--Category Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Sub-category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id" id="subCatId">
                            <div class="col-md-12 mb-2">
                                <label class="form-label">Category Name</label>
                                <select required class="form-select" name="category_id" id="categoryId">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label class="form-label">Sub-category Name</label>
                                <input required type="text" name="name" id="subName" class="form-control"
                                    placeholder="Enter New Sub-category" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Order Number</label>
                                <input type="number" name="order_number" id="categoryOrder" class="form-control"
                                    placeholder="Ex: 1,2,3..." required>
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

    <!-- Category Add Modal -->
    <div class="modal fade" id="categoryModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="categoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="categoryForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="categoryModalLabel">Add New Sub-Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label class="form-label">Category Name</label>
                                <select required class="form-select" name="category_id" id="">
                                    <option disabled selected>-----Select Category-----</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 mb-2">
                                <label class="form-label">Sub-category Name</label>
                                <input required type="text" name="name" class="form-control"
                                    placeholder="Enter New Sub-category" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Order Number</label>
                                <input type="number" name="order_number" class="form-control"
                                    placeholder="Ex: 1,2,3..." required>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Save changes
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
                scrollX: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.sub-category.datatables', 'all') }}",

                },

                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center p-1'
                    },

                    {
                        data: 'name'
                    },
                    {
                        data: 'slug'
                    },
                    {
                        data: 'category'
                    },
                    {
                        data: 'order_number'
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
                            url: "{{ route('admin.sub-category.delete') }}",
                            method: 'POST',
                            data: {
                                id: id
                            },
                            success: function() {
                                toastr.success(
                                    'Sub-category deleted successfully.'
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
            // store category
            $('#categoryForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.sub-category.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {

                        // modal close
                        $('#categoryModal').modal('hide');

                        // form reset
                        $('#categoryForm')[0].reset();

                        // datatable reload (🔥 main part)
                        table.ajax.reload(null, false);

                        toastr.success(res.message ?? 'Sub-category added successfully');
                    },
                    error: function(err) {
                        toastr.error(err.responseJSON.message);
                    }
                });
            });
            // Update form
            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.sub-category.update') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {

                        // modal close
                        $('#editModal').modal('hide');

                        // form reset
                        $('#editForm')[0].reset();

                        // datatable reload (🔥 main part)
                        table.ajax.reload(null, false);

                        toastr.success(res.message ?? 'Sub-category added successfully');
                    },
                    error: function(err) {
                        toastr.error(err.responseJSON.message);
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
                url: "{{ route('admin.sub-category.status') }}",
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
                    toastr.error(err.responseJSON.message);
                }
            });
        });
    </script>

    <script>
        $(document).on('click', '.edit', function() {
            let button = $(this);
            $('#subCatId').val(button.data('id'));
            // $('#categoryId').val(button.data('category_id'));
            $('#subName').val(button.data('name'));
            $('#categoryOrder').val(button.data('order'));
            $('#previewIcon').attr('src', button.data('icon'));
            // Form action dynamically set if needed
            $('#editForm').attr('action', '/admin/category/' + button.data('id') + '/update');
            let categoryId = $(this).data('category_id');

            $('#categoryId').val(categoryId).trigger('change');
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
