@extends('layouts.front-end.app')
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
    <section class="py-3 career">
        <div class="container mt-3 mb-4">
            <div class="row">
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


                                    <!-- Expected Salary -->
                                    <div class="form-group">
                                        <label for="expected_salary">Expected Salary (optional)</label>
                                        <input value="{{ old('expected_salary') }}" type="text" name="expected_salary"
                                            id="expected_salary"
                                            class="form-control @error('expected_salary')
                                                is-invalid
                                            @enderror"
                                            placeholder="Negotiable / e.g. 10xxxxx BDT">
                                        @error('expected_salary')
                                            <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                        @enderror
                                    </div>

                                    <!-- Current Position -->
                                    <div class="form-group">
                                        <label for="current_position">Current Job Position (optional) </label>
                                        <input value="{{ old('current_position') }}" type="text" name="current_position"
                                            id="current_position"
                                            class="form-control @error('current_position')
                                                is-invalid
                                            @enderror"
                                            placeholder="e.g. Digital Marketer">
                                        @error('current_position')
                                            <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                        @enderror
                                    </div>

                                    <!-- Experience Level -->
                                    <div class="form-group">
                                        <label for="experience_level">Experience Level <span
                                                class="text-danger">*</span></label>
                                        <select name="experience_level" id="experience_level"
                                            class="form-control @error('experience_level')
                                                is-invalid
                                            @enderror">
                                            <option value="">Select Experience Level</option>
                                            <option value="Fresher">Fresher</option>
                                            <option value="1-2 years">1–2 Years</option>
                                            <option value="3-5 years">3–5 Years</option>
                                            <option value="5+ years">5+ Years</option>
                                        </select>
                                        @error('experience_level')
                                            <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                        @enderror
                                    </div>

                                    <!-- Portfolio Link -->
                                    <div class="form-group">
                                        <label for="portfolio_link">Portfolio / LinkedIn (optional) </label>
                                        <input value="{{ old('portfolio_link') }}" type="text" name="portfolio_link"
                                            id="portfolio_link"
                                            class="form-control @error('portfolio_link')
                                                is-invalid
                                            @enderror"
                                            placeholder="https://github.com/username">
                                        @error('portfolio_link')
                                            <div class="text-danger mt-2">{{ ucwords($message) }}</div>
                                        @enderror
                                    </div>

                                    <!-- Resume Upload -->
                                    <div class="form-group">
                                        <label for="resume">Upload Your Resume (PDF) <span
                                                class="text-danger">*</span></label>
                                        <input type="file" value="{{ old('resume') }}" name="resume" id="resume"
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
                                        <label class="input-label" for="message">Cover Letter</label>
                                        <textarea name="message" class="editor form-control" id="summernote" cols="6" rows="10">
                                        {{ old('message') }}
                                    </textarea>

                                        @error('message')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Submit Button -->

                                    <button type="submit" class="btn bg-orange px-4">
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
