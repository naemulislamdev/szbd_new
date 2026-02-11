@extends('admin.layouts.app')
@section('title', 'Flat Discount')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">All Flat Discounts</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->

                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Flat Discounts</li>
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
                            <div class="col">
                                <h4 class="card-title">All Flat Discounts</h4>
                            </div><!--end col-->
                            <div class="col-auto">
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#categoryModal"><i class="la la-plus-circle"></i> Add New
                                            Flat Discount</button>
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
                                        <th>Category</th>
                                        <th>Discount Type</th>
                                        <th>Discount Amount</th>
                                        <th> Status</th>
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
    <div class="modal fade" id="editModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Flat Discount</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="rowId">
                        <div class="row">

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select id="category" required name="category" class="form-select">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Discount Amount <span class="text-danger">*</span></label>
                                <input required type="number" id="dAmount" name="discount_amount" class="form-control"
                                    placeholder="Enter discount amount">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    Discount Type <span class="text-danger">*</span>
                                </label>
                                <select required id="dType" name="discount_type" class="form-select">
                                    <option value="">Select Discount Type</option>
                                    <option value="flat">Flat</option>
                                    <option value="percent">Percentage</option>
                                </select>
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
                <form id="addForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="categoryModalLabel">Add New Flat Discount</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select required name="category" class="form-select">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Discount Amount <span class="text-danger">*</span></label>
                                <input required type="number" name="discount_amount" class="form-control"
                                    placeholder="Enter discount amount">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    Discount Type <span class="text-danger">*</span>
                                </label>
                                <select required name="discount_type" class="form-select">
                                    <option>Select Discount Type</option>
                                    <option value="flat">Flat</option>
                                    <option value="percent">Percentage</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Save
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
                    url: "{{ route('admin.discount.flat.datatables') }}",

                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center p-1'
                    },

                    {
                        data: 'created_at',
                    },
                    {
                        data: 'category_id'
                    },
                    {
                        data: 'discount_type'
                    },
                    {
                        data: 'discount_amount'
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
                            url: "{{ route('admin.discount.flat.delete') }}",
                            method: 'POST',
                            data: {
                                id: id
                            },
                            success: function() {
                                toastr.success(
                                    'Flat discount deleted successfully.'
                                );
                                table.ajax.reload();

                            }
                        });
                    }
                })
            });
            // store category
            $('#addForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.discount.flat.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        // modal close
                        $('#categoryModal').modal('hide');
                        // form reset
                        $('#addForm')[0].reset();

                        // datatable reload (🔥 main part)
                        table.ajax.reload(null, false);

                        toastr.success('Flat discount added successfully');
                    },
                    error: function(err) {
                        toastr.error('Something went wrong!');
                    }
                });
            });
            // Update form
            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.discount.flat.update') }}",
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

                        toastr.success('Flat discount updated successfully');
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
                url: "{{ route('admin.discount.flat.status') }}",
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
                    toastr.error('Something went wrong!');
                }
            });
        });
    </script>

    <script>
        $(document).on('click', '.edit', function() {
            let button = $(this);
            $('#rowId').val(button.data('id'));
            $('#category').val(button.data('category'));
            $('#dAmount').val(button.data('amount'));
            $('#dType').val(button.data('type'));
            // Form action dynamically set if needed
            $('#editForm').attr('action', '/admin/discount/flat/' + button.data('id') + '/update');
        });
    </script>
@endpush
