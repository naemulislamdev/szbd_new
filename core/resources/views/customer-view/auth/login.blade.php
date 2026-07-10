@extends('web.layouts.app')
@section('title', 'Customer Login')
@push('css_or_js')
    <style>
        .password-toggle-btn .custom-control-input:checked~.password-toggle-indicator {
            color: {{ $web_config['primary_color'] }};
        }

        .for-no-account {
            margin: auto;
            text-align: center;
        }

        .input-icons i {
            cursor: pointer;
        }

        .input-icons {
            width: 100%;
            margin-bottom: 10px;
        }

        .icon {
            padding: 9% 0 0 0;
            min-width: 40px;
        }

        .input-field {
            width: 94%;
            padding: 10px 0 10px 10px;
            text-align: center;
            border-right-style: none;
        }

        .card {
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
            padding-bottom: 10px;
        }

        .btn-primary {
            position: relative !important;
        }

        /* Loader spinner styles */
        .btn-loader {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.5);
            border-top-color: #fff;
            border-radius: 50%;
            animation: btn-spin 0.7s linear infinite;
            margin-left: 8px;
            vertical-align: middle;
        }

        @keyframes btn-spin {
            to {
                transform: rotate(360deg);
            }
        }

        #submit-btn[disabled] {
            opacity: 0.8;
            cursor: not-allowed;
        }
    </style>
@endpush
@section('content')
    <div class="container " style="text-align:left;">
        <nav class="breadcrumb custom-breadcrumb mt-3">
            <a class="breadcrumb-item" href="{{ route('home') }}">Home</a>
            <span class="breadcrumb-item active" aria-current="page">Customer Login</span>
        </nav>
        <div class="row justify-content-center mt-4">
            <div class="col-md-10">
                <div class="card border-0 box-shadow p-3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="login-image">
                                <img src="{{ asset('assets/frontend/img/login-img.jpg') }}" style="width: 100%;"
                                    alt="Shopping Zone BD Login image">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-none">
                                <div class="card-body">
                                    <h1 class="h4 mb-1">Login</h1>
                                    <p>Don't have an account yet? <a href="{{ route('customer.auth.sign-up') }}">Create
                                            account</a></p>

                                    {{-- Login error messages --}}
                                    @error('user_id')
                                        <div class="alert alert-danger py-2">{{ $message }}</div>
                                    @enderror

                                    @error('g-recaptcha-response')
                                        <div class="alert alert-danger py-2">{{ $message }}</div>
                                    @enderror

                                    <form class="needs-validation mt-2" autocomplete="off"
                                        action="{{ route('customer.auth.login') }}" method="post" id="form-id">
                                        @csrf
                                        <div class="form-group mb-2">
                                            <label for="si-email">Email address</label>
                                            <input class="form-control" type="text" name="user_id" id="si-email"
                                                style="text-align: left;" value="{{ old('user_id') }}"
                                                placeholder="Enter email address or phone number" required>
                                            <div class="invalid-feedback">
                                                please provide valid email or phone number
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <label for="si-password">Password</label>
                                            <div class="password-toggle">
                                                <input class="form-control" name="password" type="password" id="si-password"
                                                    style="text-align:left;" required>
                                                <label class="password-toggle-btn">
                                                    <input class="custom-control-input" type="checkbox"><i
                                                        class="czi-eye password-toggle-indicator"></i><span
                                                        class="sr-only">Show
                                                        password </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group d-flex flex-wrap justify-content-between mb-0">
                                            <div class="form-group">
                                                <input type="checkbox" name="remember" id="remember"
                                                    {{ old('remember') ? 'checked' : '' }}>

                                                <label class="" for="remember">Remember me</label>
                                            </div>
                                            <a class="font-size-sm" href="{{ route('customer.auth.recover-password') }}">
                                                forgot password?
                                            </a>
                                        </div>
                                        <div class="mt-2">
                                            <x-turnstile />

                                            @error('cf-turnstile-response')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <button class="btn btn-secondary btn-block btn-shadow mt-3" type="submit"
                                            id="submit-btn">
                                            <span id="submit-btn-text">Sign in</span>
                                            <span id="submit-btn-loader" class="btn-loader" style="display:none;"></span>
                                        </button>
                                    </form>
                                </div>
                                <div class="mb-2">
                                    <div class="row">
                                        @foreach (\App\CPU\Helpers::get_business_settings('social_login') as $socialLoginService)
                                            @if (isset($socialLoginService) && $socialLoginService['status'] == true)
                                                <div class="col-sm-6 text-center mb-1 mx-auto">
                                                    <a class=" border px-3 py-2 rounded d-block"
                                                        href="{{ route('customer.auth.service-login', $socialLoginService['login_medium']) }}"
                                                        style="width: 100%; color: #ff5d00">
                                                        <img style="max-width: 100%; width: 20px;"
                                                            src="{{ asset('assets/frontend/images/logo/google_logo.png') }}"
                                                            alt="google logo">
                                                        Sign in with {{ $socialLoginService['login_medium'] }}
                                                    </a>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('form-id').addEventListener('submit', function(e) {
            // HTML5 validation check — form invalid hole loader show korbe na
            if (!this.checkValidity()) {
                return;
            }

            const submitBtn = document.getElementById('submit-btn');
            const btnText = document.getElementById('submit-btn-text');
            const btnLoader = document.getElementById('submit-btn-loader');

            submitBtn.disabled = true;
            btnText.textContent = 'Signing in...';
            btnLoader.style.display = 'inline-block';
        });
    </script>
@endpush
