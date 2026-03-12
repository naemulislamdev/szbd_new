@extends('web.layouts.app')
<style>
    .application-form {
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
    }

    .bg-orange {
        background: #ff5d00 !important;
        color: #fff !important;
    }

    .btn.bg-orange:focus {
        outline: 0;
        box-shadow: 0 0 0 .2rem rgba(255, 93, 0, .25);
    }

    input.form-control {
        padding: 8px 14px;
    }
</style>

@section('title', 'career-details')

@section('content')
    <section class=" career">
        <div class="container mt-3 mb-4">
            {{-- Bredcrumb start  --}}
            <nav class="breadcrumb custom-breadcrumb mt-3">
                <a class="breadcrumb-item" href="{{ route('home') }}">Home</a>
                <a class="breadcrumb-item" href="{{ route('careers') }}">Career</a>

                <span class="breadcrumb-item active" aria-current="page">{{ $career->position }} Application Form</span>
            </nav>
            {{--  Bredcrumb End --}}
            <div class="row mt-3">
                <div class="col-lg-2"></div>
                <div class="col-lg-8">
                    <div class=" application-form rounded">
                        <div class="card shadow-lg border-0">
                            <div class="card-header py-3 bg-orange text-white">
                                <h4 class="mb-0">Apply for <strong>{{ $career->position }}</strong></h4>
                            </div>

                            <div class="card-body p-4">
                                <form action="{{ route('career.form.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('POST')
                                    <input type="hidden" name="career_id" value="{{ $career->id }}">
                                    <!-- Name -->
                                    <div class="form-group">
                                        <label for="name">Full Name <span class="text-danger">*</span></label>
                                        <input value="{{ old('name') }}" type="text" name="name" id="name"
                                            class="form-control @error('name')
                                                is-invalid
                                            @enderror"
                                            placeholder="Enter your full name">
                                        @error('name')
                                            <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                        @enderror
                                    </div>

                                    <!-- Email -->
                                    <div class="form-group">
                                        <label for="email">Email Address <span class="text-danger">*</span></label>
                                        <input value="{{ old('email') }}" type="email" name="email" id="email"
                                            class="form-control @error('email')
                                                is-invalid
                                            @enderror"
                                            placeholder="Enter your email">
                                        @error('email')
                                            <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                        @enderror
                                    </div>

                                    <!-- Phone -->
                                    <div class="form-group">
                                        <label for="phone">Phone Number <span class="text-danger">*</span></label>
                                        <input value="{{ old('phone') }}" type="text" name="phone" id="phone"
                                            class="form-control @error('phone')
                                                is-invalid
                                            @enderror"
                                            placeholder="e.g. 017XXXXXXXX">
                                        @error('phone')
                                            <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                        @enderror
                                    </div>
                                    <!-- Postion -->
                                    <div class="form-group">
                                        <label>Position <span class="text-danger">*</span></label>
                                        <input readonly value="{{ $career->position }}" type="text"
                                            class="form-control @error('phone')
                                                is-invalid
                                            @enderror">

                                    </div>
                                    <!-- Experience Level -->
                                    <div class="form-group">
                                        <label for="experience_level">Experience Level <span
                                                class="text-danger">*</span></label><br>
                                        <label class="cursor-pointer" for="exp1"><input
                                                {{ old('experience_level') == '1-2 years' ? 'checked' : '' }}
                                                type="radio" name="experience_level" id="exp1" value="1-2 years"> 1-2
                                            Years</label><br>
                                        <label class="cursor-pointer" for="exp2"> <input
                                                {{ old('experience_level') == '2-3 years' ? 'checked' : '' }}
                                                type="radio" name="experience_level" id="exp2" value="2-3 years"> 2-3
                                            Years</label><br>
                                        <label class="cursor-pointer" for="exp3"><input
                                                {{ old('experience_level') == '3-5 years' ? 'checked' : '' }}
                                                type="radio" name="experience_level" id="exp3" value="3-5 years"> 3-5
                                            Years</label><br>
                                        <label class="cursor-pointer" for="exp4"><input
                                                {{ old('experience_level') == '5+ years' ? 'checked' : '' }} type="radio"
                                                name="experience_level" id="exp4" value="5+ years"> 5+ Years</label>
                                        @error('experience_level')
                                            <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                        @enderror
                                    </div>

                                    <!-- Portfolio Link -->
                                    <div class="form-group">
                                        <label for="portfolio_link">Portfolio / LinkedIn <span
                                                class="text-muted">(optional)</span> </label>
                                        <input value="{{ old('portfolio_link') }}" type="text" name="portfolio_link"
                                            id="portfolio_link"
                                            class="form-control @error('portfolio_link')
                                                is-invalid
                                            @enderror"
                                            placeholder="Enter Your Portfolio or linkedin link">
                                        @error('portfolio_link')
                                            <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                        @enderror
                                    </div>

                                    <!-- Resume Upload -->
                                    <div class="form-group">
                                        <label for="resume">Upload Your Resume (PDF) <span
                                                class="text-danger">*</span></label>
                                        <input type="file" name="resume" id="resume"
                                            class="form-control-file @error('resume')
                                                is-invalid
                                            @enderror"
                                            accept=".pdf,.doc,.docx">
                                        @error('resume')
                                            <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                        @enderror
                                    </div>

                                    <!-- Cover Letter -->

                                    <div class="form-group pt-4">
                                        <label class="input-label" for="message">Cover Letter <span
                                                class="text-muted">(optional)</span></label>
                                        <textarea name="message" class="editor form-control" id="summernote" cols="6" rows="10">
                                        {{ old('message') }}
                                    </textarea>

                                        @error('message')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Submit Button -->

                                    <button type="submit" class="btn bg-orange px-4 mt-4">
                                        <i class="fa fa-paper-plane"></i> Submit Application
                                    </button>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2"></div>
            </div>
        </div>

    </section>
    @push('scripts')
        <!-- include summernote css/js -->
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
        <script>
            $(document).ready(function() {
                // Summernote initialize
                $('#summernote').summernote({
                    height: 150
                });

                // Form submit এর সময় Summernote এর content textarea তে সেট করা
                $('form').on('submit', function(e) {
                    var content = $('#summernote').summernote('code');
                    $('textarea[name="message"]').val(content);
                });
            });
        </script>
        <script>
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session('error') }}',
                });
            @endif
        </script>
    @endpush
@endsection
