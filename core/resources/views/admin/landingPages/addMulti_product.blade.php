@extends('admin.layouts.app')
@section('title', 'Multiple Product Landing Page')

@push('styles')
    <style>
        .table-responsive {
            overflow-x: auto;
            overflow-y: hidden;
        }

        .card-body {
            overflow: visible;
        }

        .select2-container {
            z-index: 1056 !important;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center pb-2">
                    <h4 class="page-title">Add Products for {{ $deal->title }}</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item">Landing Pages
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active"><a
                                    href="{{ route('admin.landingpages.multiple.index') }}">Multiple Product Pages</a></li>
                            <li class="breadcrumb-item active">Add Multiple Product</li>
                        </ol>
                    </div>
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">Added Products</h4>
                            </div><!--end col-->
                            <div class="col-auto">
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#categoryModal"><i class="la la-plus-circle"></i> Add New
                                            Products</button>
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

                        <div class="table-responsive">
                            <table class="table" id="szbd-datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>SL</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Single Page Status</th>
                                        <th>Price</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div>
    </div><!-- container -->

    <!--Category Edit Modal -->
    {{-- <div class="modal fade" id="editModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="editForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="pageId">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Page</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Page Title</label>
                                <input required type="text" name="title" value="" id="title"
                                    class="form-control" placeholder="Enter Page title" required>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Add First Display Product</label>
                                <select class="form-select" style="overflow: hidden" name="product_id" id="productId">
                                    <option style="max-width: 100%; height: auto;" selected disabled>Select a product
                                    </option>
                                    @foreach (\App\Models\Product::active()->orderBy('id', 'DESC')->get() as $key => $product)
                                        <option style="max-width: 100%; height: auto;" value="{{ $product->id }}">
                                            {{ $product['name'] }} || {{ $product['code'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">
                                    Select Banner Slider Images
                                    <small class="text-danger">ratio 1:1</small>
                                </label>
                                <input id="customFileEg2" type="file" name="images[]" multiple class="form-control"
                                    accept=".jpg,.png,.jpeg,.gif,.bmp,.tif,.tiff|image/*">
                            </div>

                            <div class="col-12">
                                <hr>
                                <div id="imagePreviewContainer2" class="d-flex flex-wrap justify-content-center gap-1">
                                    <!-- Preview images will appear here -->
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
    </div> --}}

    <!-- Category Add Modal -->
    <div class="modal fade" id="categoryModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="categoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form id="categoryForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="categoryModalLabel">Add New Products</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="id" value="{{ $deal->id }}">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="name">Add new product</label>

                                <select required class="js-example-responsive form-control" name="product_id[]" multiple>
                                    @foreach (\App\Models\Product::whereNotIn('id', $flash_deal_products)->active()->orderBy('id', 'DESC')->get() as $key => $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product['name'] }} || {{ $product['code'] }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.js-example-responsive').select2({
                placeholder: "Select Products",
                allowClear: true,
                width: '100%'
            });
        });
    </script>

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
                    url: "{{ route('admin.landingpages.addedProductDatatable', $deal->id) }}",
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
                        data: 'code'
                    },
                    {
                        data: 'single_page_status'
                    },
                    {
                        data: 'unit_price'
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
                            url: "{{ route('admin.landingpages.multiple.delete-added-product') }}",
                            method: 'POST',
                            data: {
                                id: id
                            },
                            success: function() {
                                toastr.success(
                                    'Product Deleted Successfully.'
                                );
                                table.ajax.reload();

                            },
                            error: function() {
                                toastr.error("Something Went Wrong!");
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
                    url: "{{ route('admin.landingpages.multiple.products.store') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {

                        // modal close
                        $('#categoryModal').modal('hide');

                        // form reset
                        $('#categoryForm')[0].reset();
                        $('.js-example-responsive').val(null).trigger('change');



                        // datatable reload (🔥 main part)
                        table.ajax.reload(null, false);

                        toastr.success(res.message ?? 'Product added successfully');
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
                    url: "{{ route('admin.landingpages.multiple.landing_pages_update') }}",
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

                        toastr.success(res.message ?? 'Page Updated successfully');
                    },
                    error: function(err) {
                        toastr.error(err.responseJSON.message ?? 'Something went wrong!');
                    }
                });
            });

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
                    url: "{{ route('admin.landingpages.multiple.status-update') }}",
                    method: 'POST',
                    data: {
                        id: id,
                        status: status
                    },
                    success: function(data) {
                        console.log(data);

                        if (data.success == true) {
                            table.ajax.reload();
                            toastr.success('Status updated successfully');

                        }
                    },
                    error: function(err) {
                        toastr.error('Something went wrong!');
                    }
                });
            });

            // withSlide
            $(document).on('change', '.with_slide', function() {
                var id = $(this).attr("data-id");
                var with_slide = $(this).prop("checked") ? 1 : 0;

                $.ajax({
                    url: "{{ route('admin.landingpages.withSlideStatus') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id,
                        with_slide: with_slide
                    },
                    success: function() {
                        toastr.success('withSlide updated successfully');
                        // table.ajax.reload();

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
            $('#pageId').val(button.data('id'));
            $('#title').val(button.data('title'));
            // $('#images').attr('src', button.data('images'));
            let productId = $(this).data('product_id');
            $('#productId').val(productId).trigger('change');
            $('#editForm').attr('action', '/admin/category/' + button.data('id') + '/update');
        });
    </script>
    <script>
        function readMultipleFiles(input) {
            var container = $('#imagePreviewContainer');
            container.html(''); // clear previous previews

            if (input.files) {
                Array.from(input.files).forEach((file, index) => {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        // create preview div
                        var preview = $(`
                    <div class="position-relative" style="width:150px; height:150px; border:1px solid #ccc; border-radius:10px; overflow:hidden;">
                        <img src="${e.target.result}" style="width:100%; height:100%; object-fit:contain;">
                        <span class="btn btn-sm btn-danger position-absolute"
                              style="top:5px; right:5px; cursor:pointer;"
                              data-index="${index}">&times;</span>
                    </div>
                `);

                        container.append(preview);
                    }

                    reader.readAsDataURL(file);
                });
            }
        }

        // when files selected
        $("#customFileEg1").change(function() {
            readMultipleFiles(this);
        });

        // delete preview
        $(document).on('click', '#imagePreviewContainer span', function() {
            var index = $(this).data('index');
            var dt = new DataTransfer(); // new FileList

            var input = document.getElementById('customFileEg1');
            var {
                files
            } = input;

            for (let i = 0; i < files.length; i++) {
                if (i !== index) {
                    dt.items.add(files[i]); // keep other files
                }
            }

            input.files = dt.files; // update input

            // remove preview div
            $(this).parent().remove();

            // re-index remaining previews
            $('#imagePreviewContainer div').each(function(i, el) {
                $(el).find('span').attr('data-index', i);
            });
        });
    </script>
@endpush
