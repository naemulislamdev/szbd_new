@extends('admin.layouts.app')
@section('title', 'Edit Job Posts ')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Create Job Posts</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->

                            </li><!--end nav-item-->
                            <li class="breadcrumb-item "><a href="{{ route('admin.career.view') }}">Job Posts</a></li>
                            <li class="breadcrumb-item active">Edit Job Post</li>
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
                                <h4 class="card-title">Edit Job Posts</h4>
                            </div><!--end col-->
                            <div class="col-auto">
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <a href="{{ route('admin.career.view') }}" class="btn btn-primary"><i
                                                class="la la-chevron-circle-left"></i>
                                            Back</a>
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
                        <form action="{{ route('admin.career.update', $career->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                                {{-- Job Title --}}
                                <div class="col-md-6">
                                    <label for="title">Job Title <span class="text-danger">*</span></label>
                                    <input type="text" name="position" id="title"
                                        class="form-control @error('position') is-invalid @enderror"
                                        value="{{ $career->position }}">

                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Department --}}
                                <div class="col-md-6">
                                    <label for="dept">Department <span class="text-danger">*</span></label>
                                    <select class="form-select @error('department') is-invalid @enderror" id="dept"
                                        name="department">

                                        <option disabled>---Select Department---</option>

                                        @foreach ($departments as $dept)
                                            <option value="{{ $dept->name }}"
                                                {{ $career->department == $dept->name ? 'selected' : '' }}>
                                                {{ $dept->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Description --}}
                                <div class="col-md-12 my-3">
                                    <label for="summernote">Job Description</label>
                                    <textarea name="description" id="summernote" class="form-control @error('description') is-invalid @enderror"
                                        cols="25" rows="6">{{ $career->description }}</textarea>

                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Location --}}
                                <div class="col-md-6">
                                    <label for="job_location">Job Location</label>
                                    <input type="text" name="location" id="job_location"
                                        class="form-control @error('location') is-invalid @enderror"
                                        value="{{ $career->location }}">
                                </div>

                                {{-- Job Type --}}
                                <div class="col-md-6">
                                    <label for="type">Job Type</label>
                                    <select class="form-select @error('type') is-invalid @enderror" name="type"
                                        id="type">

                                        <option disabled>----Select----</option>

                                        <option value="Full Time" {{ $career->type == 'Full Time' ? 'selected' : '' }}>
                                            Full Time
                                        </option>

                                        <option value="Internship" {{ $career->type == 'Internship' ? 'selected' : '' }}>
                                            Internship
                                        </option>

                                        <option value="Part-time" {{ $career->type == 'Part-time' ? 'selected' : '' }}>
                                            Part-time
                                        </option>
                                    </select>
                                </div>

                                {{-- Opening Date --}}
                                <div class="col-md-6 mt-3">
                                    <label for="opening_date">Post Date</label>
                                    <input type="date" name="opening_date" id="opening_date"
                                        class="form-control @error('opening_date') is-invalid @enderror"
                                        value="{{ $career->opening_date }}">
                                </div>

                                {{-- Deadline --}}
                                <div class="col-md-6 mt-3">
                                    <label for="deadline">Post Deadline</label>
                                    <input type="date" name="deadline" id="deadline"
                                        class="form-control @error('deadline') is-invalid @enderror"
                                        value="{{ $career->deadline }}">
                                </div>

                                {{-- Image --}}
                                <div class="col-md-6 mt-3">
                                    <label for="image">Upload Job Post Image</label>
                                    <input class="form-control @error('image') is-invalid @enderror" type="file"
                                        name="image" id="image">
                                </div>

                                {{-- Existing Image Preview --}}
                                <div class="col-md-6 mt-3">
                                    <div>
                                        @if ($career->image)
                                            <img style="width: 50%;border: 1px solid; border-radius: 10px;" id="viewer"
                                                src="{{ asset('assets/storage/career/' . $career->image) }}"
                                                alt="Career Image">
                                        @endif
                                    </div>
                                </div>

                                {{-- Submit --}}
                                <div class="col-md-6 mx-auto mt-4">
                                    <button type="submit" class="btn btn-primary w-100">
                                        Update
                                    </button>
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
            $('#summernote').summernote({
                height: 150
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

        $("#image").change(function() {
            readURL(this);
        });
    </script>
@endpush
