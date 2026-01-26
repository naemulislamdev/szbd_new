@extends('admin.layouts.app')
@section('title', 'Single Product Landing Page')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center pb-2">
                    <h4 class="page-title">Create Single Product Landing Page</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item">Landing Pages
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active"><a
                                    href="{{ route('admin.landingpages.single.index') }}">Single Product Pages</a></li>
                            <li class="breadcrumb-item active">Create Single Product Pages</li>
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
                        <div id="loader" style="display:none; text-align:center; margin-bottom:10px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <form action="{{ route('admin.landingpages.single.store') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12 mb-2">
                                    <div class="form-floating">
                                        <input type="text"
                                            class="form-control form-control-sm @error('title') is-invalid @enderror"
                                            id="floatingTitle" placeholder="Page title" name="title"
                                            value="{{ old('title') }}">
                                        <label for="floatingTitle">Enter Page Title</label>
                                    </div>
                                    @error('title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="form-group">
                                        <label>Upload product images</label><small style="color: red">* ( ratio) 1:1
                                        </small>
                                    </div>
                                    <div class="upload-container">
                                        <input type="file" id="image-upload" name="images[]" multiple accept="image/*"
                                            class="form-control">

                                        <div id="image-preview" class="image-preview-container d-flex gap-3"></div>
                                    </div>
                                </div>
                                <div class="col-lg-12 mb-3">
                                    <label class="input-label">Description<span class="text-danger">*</span></label>
                                    <textarea name="description" class="editor" id="description" name="description" cols="5" rows="10">{{ old('description') }}</textarea>
                                    @error('description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="name">Add new product<span class="text-danger">*</span></label>
                                    <select class="form-select form-select-lg" name="product_id">
                                        <option selected disabled>Select a product</option>
                                        @foreach (\App\Models\Product::active()->orderBy('id', 'DESC')->get() as $key => $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product['name'] }} || {{ $product['code'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                            <div class="row mt-3">
                                <h5 class="fw-bold">Feature of this product</h5>
                                <div class="col-md-6">
                                    <label>Feature title <span class="text-danger">*</span></label>
                                    <div id="input-container">
                                        <div class="input-group mb-3">
                                            <input type="text" value="{{ old('feature_title') }}"
                                                class="form-control @error('feature_title') is-invalid @enderror"
                                                name="feature_title[]" placeholder="Enter value">
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-success add-new">Add New</button>
                                    @error('feature_title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="feature_image">Feature image
                                        Banner</label><span class="badge bg-danger">* (ratio) 400x650
                                    </span>
                                    <div class="custom-file mb-3" style="text-align: left">
                                        <input class="form-control" type="file" name="feature_image"
                                            id="customFileUpload3" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">

                                    </div>
                                    @error('feature_image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                    <div style="text-align:center;">
                                        <img style="width:70%;border: 1px solid; border-radius: 10px; max-height:200px;"
                                            id="viewer3"
                                            src="{{ asset('public\assets\back-end\img\1920x400\img1.jpg') }}" />
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div>
                                        <label>Product Video <span class="text-danger">*</span></label>
                                        <input type="text" name="video_url" value="{{ old('video_url') }}"
                                            class="form-control">
                                        @error('video_url')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <h5 class="fw-bold">Add more product related content</h5>
                                <div class="col-md-12">
                                    <div class="more_sections">
                                    </div>
                                    <div class="col">
                                        <button type="button" class="btn btn-info" id="add_new_section">Add
                                            more</button>
                                    </div>
                                    <div class="mt-3 w-100 text-center">
                                        <button type="submit" class="btn btn-success float-right w-50">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container -->




@endsection
@push('scripts')
    <script>
        function readURL3(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewer3').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#customFileUpload3").change(function() {
            readURL3(this);
        });
    </script>
    <script>
        $(document).ready(function() {
            const previewContainer = $("#image-preview");
            $("#image-upload").on("change", function(event) {
                previewContainer.empty(); // Clear existing previews
                const files = event.target.files;

                if (files) {
                    $.each(files, function(index, file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const previewItem = $(`
                             <div class="preview-item ">
                                 <img style="max-width: 200px; height: auto;" src="${e.target.result}" class="preview-image">
                                 <button type="button" class="remove-icon btn btn-danger btn-sm" data-index="${index}">&#10005;</button>
                             </div>
                         `);
                            previewContainer.append(previewItem);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            });

            // Handle image removal
            previewContainer.on("click", ".remove-icon", function() {
                const indexToRemove = $(this).data("index");
                $(this).parent().remove();
                // Remove the corresponding file from the input (file list cannot be modified directly, so create a new list)
                const input = document.getElementById("image-upload");
                const dataTransfer = new DataTransfer();
                const files = input.files;

                // Add all files except the one to be removed
                for (let i = 0; i < files.length; i++) {
                    if (i !== indexToRemove) {
                        dataTransfer.items.add(files[i]);
                    }
                }

                // Update the input files
                input.files = dataTransfer.files;
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Function to handle adding new input fields
            $('.add-new').click(function() {
                let inputCount = $('#input-container .input-group').length;

                // Create new input field with delete button
                let newInput = `
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="feature_title[]" placeholder="Enter value">
                        <button type="button" class="btn btn-danger delete">Delete</button>
                    </div>`;

                // Append the new input field to the input container
                $('#input-container').append(newInput);
            });

            // Function to handle deleting input fields
            $(document).on('click', '.delete', function() {
                $(this).closest('.input-group').remove();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#description').summernote();


        });
    </script>
    <script>
        $(document).ready(function() {
            let sectionCounter = 0; // Initialize a counter

            // Add new section when clicking 'add_new_section'
            $('#add_new_section').click(function() {
                let newInput = `
            <div class="row mb-3">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>Section title ${sectionCounter}</label>
                        <input type="text" name="section_title[]" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                        <label class="form-check-label">Order button</label>
                    <div class="d-flex">
                        <div class="form-check me-2">
                            <input class="form-check-input" type="radio" name="order_button[${sectionCounter}]"
                                id="yes_${sectionCounter}" value="1" checked>
                            <label class="form-check-label" for="yes_${sectionCounter}">
                                Yes
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="order_button[${sectionCounter}]"
                                id="no_${sectionCounter}" value="0">
                            <label class="form-check-label" for="no_${sectionCounter}">
                                No
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group my-2">
                        <label>Section Description</label>
                        <textarea name="section_description[]" class="form-control" placeholder="Enter description"></textarea>
                    </div>
                    <div class="d-flex">
                        <div class="form-check me-2">
                            <input class="form-check-input" type="radio" name="section_direction[${sectionCounter}]"
                                id="descLeft_${sectionCounter}" value="left" checked>
                            <label class="form-check-label" for="descLeft_${sectionCounter}">
                                Left
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="section_direction[${sectionCounter}]"
                                id="descRight_${sectionCounter}" value="right">
                            <label class="form-check-label" for="descRight_${sectionCounter}">
                                Right
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Section Image</label>
                        <input type="file" name="section_img[]" class="form-control">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="delete-button text-end">
                        <button type="button" class="btn btn-danger section_delete">Remove</button>
                    </div>
                </div>
            </div>`;

                $('.more_sections').append(newInput);
                sectionCounter++; // Increment the counter for the next section
            });

            // Remove section when clicking 'section_delete'
            $(document).on('click', '.section_delete', function() {
                $(this).closest('.row').remove();
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
