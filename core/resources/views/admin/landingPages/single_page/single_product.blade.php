@extends('admin.layouts.app')
@section('title', 'Single Product Landing Page')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center pb-2">
                    <h4 class="page-title">Single Product Landing Pages</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item">Landing Pages
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Single Product Pages</li>
                        </ol>
                    </div>
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <div class="row" style="max-width: 100%;">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">All Pages</h4>
                            </div><!--end col-->
                            <div class="col-auto">
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <a href="{{ route('admin.landingpages.single.create') }}" class="btn btn-primary"><i
                                                class="la la-plus-circle"></i> Add New
                                            Page</a>
                                    </div>

                                </div>
                            </div><!--end col-->
                        </div><!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body pt-0">
                        {{-- <div id="loader" style="display:none; text-align:center; margin-bottom:10px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div> --}}

                        <div class="table-responsive overflow-hidden">
                            <table class="table" id="szbd-datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>SL</th>
                                        <th>Title</th>
                                        <th>Slug</th>
                                        <th>Product Name</th>
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
                    url: "{{ route('admin.landingpages.single.datatables') }}",
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
                        data: 'slug'
                    },
                    {
                        data: 'product_id'
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
                            url: "{{ route('admin.landingpages.remove_single_page') }}",
                            method: 'POST',
                            data: {
                                id: id
                            },
                            success: function() {
                                toastr.success(
                                    'Page Deleted Successfully.'
                                );
                                table.ajax.reload();

                            },
                            error: function(err) {
                                toastr.error("Something Went wrong !")
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
                    url: "{{ route('admin.landingpages.multiple.store') }}",
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

                        toastr.success(res.message ?? 'Page added successfully');
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
                    url: "{{ route('admin.landingpages.single.status') }}",
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
