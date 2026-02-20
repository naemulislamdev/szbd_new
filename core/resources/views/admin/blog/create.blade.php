@extends('admin.layouts.app')
@section('title', 'Blogs')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Blog Create</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item"><a href="{{ route('admin.blog.list') }}">Blogs</a>
                            </li><!--end nav-item-->

                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Blog Create</li>
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
                                <h4 class="card-title">Blog Create Form</h4>
                            </div><!--end col-->
                            <div class="col-auto">
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <a href="{{ route('admin.blog.list') }}" class="btn btn-primary"><i
                                                class="la la-chevron-left"></i> Back</a>
                                    </div>

                                </div>
                            </div><!--end col-->
                        </div><!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body pt-0">
                        <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="title">Blog Title <span class="text-danger">*</span></label>
                                    <input class="form-control @error('title') is-invalid @enderror"
                                        value="{{ old('title') }}" type="text" name="title" id="title">

                                    @error('title')
                                        <div class="invalid-feedback">
                                            {{ ucfirst($message) }}
                                        </div>
                                    @enderror

                                </div>
                                <div class="col-md-6">
                                    <label for="category">Select Category <span class="text-danger">*</span></label>
                                    <select class="form-select @error('category_id') is-invalid  @enderror"
                                        name="category_id">
                                        <option value="" disabled selected>----Select----</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <div class="invalid-feedback">
                                            {{ ucfirst($message) }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-12 my-3">
                                    <label for="description">Description <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" cols="30" rows="10">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ ucfirst($message) }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="meta_title">Meta Title</label>
                                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="meta_keywords">Meta Keywords</label>
                                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}"
                                        id="meta_keywords" class="form-control">
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label for="meta_description">Meta Description</label>
                                    <textarea name="meta_description" value="{{ old('meta_description') }}" id="meta_description" cols="30"
                                        rows="10"></textarea>
                                </div>
                                <div class="col-md-6 mt-3">
                                    <label>Blog Image <span class="text-danger">*</span></label>
                                    <input type="file" name="image" id="image"
                                        class="form-control @error('image') is-invalid @enderror">
                                    @error('image')
                                        <div class="invalid-feedback">
                                            {{ ucfirst($message) }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mt-3">
                                    <img style="width: 20%;border: 1px solid; border-radius: 10px;" id="viewer"
                                        src="" alt="" />
                                </div>
                                <div class="col-md-6 mx-auto mt-4">
                                    <button class="btn btn-success w-100">Save</button>
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
        $(document).ready(function() {
            $('#description').summernote();
            $('#meta_description').summernote();
            // $('#metaDesc').summernote();

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

        $("#image").change(function() {
            readURL(this);
        });
    </script>
@endpush
