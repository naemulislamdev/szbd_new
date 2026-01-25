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
                        <div class="row">
                            <div class="col-lg-12 mb-2">
                                <div class="form-floating">
                                    <input type="text" class="form-control form-control-sm" id="floatingTitle"
                                        placeholder="Password">
                                    <label for="floatingTitle">Enter Page Title</label>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <label class="input-label">Description<span class="text-danger">*</span></label>
                                <textarea name="description" class="editor" id="description" cols="5" rows="10">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="name">Add new product<span class="text-danger">*</span></label>

                                <select id="example-getting-started" class=" js-example-responsive form-control"
                                    name="product_id">
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


                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container -->




@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#description').summernote();


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
