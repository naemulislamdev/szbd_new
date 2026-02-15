@extends('admin.layouts.app')
@section('title', 'Banners Management')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">All Banners</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->

                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">All Banners</li>
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
                                <h4 class="card-title">All category</h4>
                            </div><!--end col-->
                            <div class="col-auto">
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#bannerModal"><i class="la la-plus-circle"></i> Add New
                                            Banner</button>
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
                                        <th>Banner Image</th>
                                        <th>Banner Type</th>
                                        <th>Order Number</th>
                                        <th>Published Status</th>
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

    <!--banner Edit Modal -->
    <div class="modal fade" id="editModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Banner</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="bannerId">
                        <div class="row">

                            <div class="col-md-12 mb-2">
                                <label class="form-label">Banner URL</label>
                                <input id="url" required type="text" name="url" class="form-control"
                                    placeholder="Add new Banner" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="name">Banner Type</label>
                                <select style="width: 100%" class=" form-control" id="bannerType" name="banner_type"
                                    required>
                                    <option value="Main Banner">Main Banner
                                    </option>
                                    <option value="Mobile Banner">Mobile Banner
                                    </option>
                                    <option value="Footer Banner">Footer Banner
                                    </option>
                                    <option value="Popup Banner">Popup Banner
                                    </option>
                                    <option value="Main Section Banner">
                                        Main Section Banner</option>
                                    <option value="promo_offer">Promo Offer
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Order Number</label>
                                <input required type="number" name="order_number" id="order" class="form-control"
                                    placeholder="Ex: 1,2,3..." required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Banner image
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
            </div>
        </div>
    </div>

    <!-- Banner Add Modal -->
    <div class="modal fade" id="bannerModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="bannerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="bannerForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="categoryModalLabel">Add New Banner</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12 mb-2">
                                <label class="form-label">Banner URL</label>
                                <input required type="text" name="url" class="form-control"
                                    placeholder="Add new Banner" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="name">Banner Type</label>
                                <select style="width: 100%" class=" form-control" name="banner_type" required>
                                    <option value="Main Banner">Main Banner
                                    </option>
                                    <option value="Mobile Banner">Mobile Banner
                                    </option>
                                    <option value="Footer Banner">Footer Banner
                                    </option>
                                    <option value="Popup Banner">Popup Banner
                                    </option>
                                    <option value="Main Section Banner">
                                        Main Section Banner</option>
                                    <option value="promo_offer">Promo Offer
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-2">
                                <label>Order Number</label>
                                <input required type="number" name="order_number" class="form-control"
                                    placeholder="Ex: 1,2,3..." required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Banner image
                                    <small class="text-danger">
                                        ratio 1:1
                                    </small>
                                </label>
                                <input id="customFileEg1" type="file" name="image" class="form-control"
                                    accept=".jpg,.png,.jpeg,.gif,.bmp,.tif,.tiff|image/*" required>
                            </div>

                            <div class="col-12">
                                <hr>
                                <div class="text-center">
                                    <img id="viewer" src="{{ asset('assets/backend/images/placeholder.jpg') }}"
                                        style="width:200px;max-height:200px;border:1px solid;border-radius:10px; object-fit:contain;">
                                </div>
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
                scrollX: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.banner.datatables') }}",

                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center p-1'
                    },
                    {
                        data: 'photo'
                    },
                    {
                        data: 'banner_type'
                    },
                    {
                        data: 'order_number',

                    },
                    {
                        data: 'published'
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
                            url: "{{ route('admin.banner.delete') }}",
                            method: 'POST',
                            data: {
                                id: id
                            },
                            success: function() {
                                toastr.success(
                                    'Banner Deleted Successfully !.'
                                );
                                table.ajax.reload();

                            },
                            error: function() {
                                toastr.error(
                                    'Something Went Wrong!.'
                                );
                            }
                        });
                    }
                })
            });
            // store category
            $('#bannerForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.banner.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {

                        // modal close
                        $('#bannerModal').modal('hide');

                        // form reset
                        $('#bannerForm')[0].reset();

                        // datatable reload (🔥 main part)
                        table.ajax.reload(null, false);

                        toastr.success(res.message ?? 'Banner added successfully');
                    },
                    error: function(err) {
                        toastr.error(err.responseJSON.message ?? 'Something went wrong!');
                    }
                });
            });
            // Update form
            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.banner.update') }}",
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

                        toastr.success(res.message ?? 'Banner added successfully');
                    },
                    error: function(err) {
                        toastr.error(err.responseJSON.message ?? 'Something went wrong!');
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
                url: "{{ route('admin.banner.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    published: status
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
    <script>
        $(document).on('click', '.edit', function() {
            let button = $(this);
            console.log(button.data())
            $('#bannerId').val(button.data('id'));
            $('#url').val(button.data('url'));
            $('#bannerType').val(button.data('type'));
            $('#categoryName').val(button.data('banner_type'));
            $('#order').val(button.data('ordernumber'));
            $('.imgViewer').attr('src', button.data('image'));
            // Form action dynamically set if needed
            $('#editForm').attr('action', '/admin/banner/' + button.data('id') + '/update');
        });
    </script>
@endpush
