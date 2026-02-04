@extends('web.layouts.app')
@section('title', 'Login')
@push('css_or_js')
    <style>
        .password-toggle-btn .custom-control-input:checked~.password-toggle-indicator {
            color: {{ $web_config['primary_color'] }};
        }

        .for-no-account {
            margin: auto;
            text-align: center;
        }
    </style>

    <style>
        .input-icons i {
            /* position: absolute; */
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
        .btn-primary{
            position: relative !important;
        }
    </style>
@endpush
@section('content')
    <div class="container py-4 py-lg-5 my-4"
        style="text-align:left;">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card border-0 box-shadow">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="login-image">
                                <img src="{{ asset('assets/frontend/img/login-img.jpg')}}" style="width: 100%;" alt="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="">
                                <div class="card-body">
                                    <h2 class="h4 mb-1">Sign in</h2>
                                    <hr class="mt-2">
                                    <form class="needs-validation mt-2" autocomplete="off" action="{{ route('customer.auth.login') }}"
                                        method="post" id="form-id">
                                        @csrf
                                        <div class="form-group">
                                            <label for="si-email">Email address</label>
                                            <input class="form-control" type="text" name="user_id" id="si-email"
                                                style="text-align: left;"
                                                value="{{ old('user_id') }}"
                                                placeholder="Enter email address or phone number" required>
                                            <div class="invalid-feedback">
                                                please provide valid email or phone number
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="si-password">password</label>
                                            <div class="password-toggle">
                                                <input class="form-control" name="password" type="password" id="si-password"
                                                    style="text-align:left;"
                                                    required>
                                                <label class="password-toggle-btn">
                                                    <input class="custom-control-input" type="checkbox"><i
                                                        class="czi-eye password-toggle-indicator"></i><span
                                                        class="sr-only">Show
                                                        password </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group d-flex flex-wrap justify-content-between">
                                            <div class="form-group">
                                                <input type="checkbox" name="remember"
                                                    id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                <label class="" for="remember">remember_me</label>
                                            </div>
                                            <a class="font-size-sm" href="{{ route('customer.auth.recover-password') }}">
                                                forgot password?
                                            </a>
                                        </div>
                                        <button class="btn btn-primary btn-block btn-shadow"
                                            type="submit">sign in</button>
                                    </form>
                                </div>
                                <div class="">
                                    <div class="p-3 d-flex justify-content-between">
                                        <div class="mb-3">
                                            <h6>No account Sign up now</h6>
                                        </div>
                                        <div class="mb-3">
                                            <a class="btn btn-outline-primary" href="{{ route('customer.auth.sign-up') }}">
                                                <i class="fa fa-user-circle"></i>sign up
                                            </a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        @foreach (\App\CPU\Helpers::get_business_settings('social_login') as $socialLoginService)
                                            @if (isset($socialLoginService) && $socialLoginService['status'] == true)
                                                <div class="col-sm-6 text-center mb-1 mx-auto">
                                                    <a class="btn btn-outline-primary"
                                                        href="{{ route('customer.auth.service-login', $socialLoginService['login_medium']) }}"
                                                        style="width: 100%">
                                                        <i
                                                            class="czi-{{ $socialLoginService['login_medium'] }} mr-2 ml-n1"></i> Sign in with {{$socialLoginService['login_medium'] }}
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
        document.getElementById('phone').addEventListener('input', function() {
            const phoneInput = this.value;
            const phoneFeedback = document.getElementById('phoneFeedback');
            const regex = /^(01[3-9]\d{8})$/;

            if (phoneInput === '') {
                phoneFeedback.textContent = '';
            } else if (!regex.test(phoneInput)) {
                phoneFeedback.classList.add('text-danger');
                phoneFeedback.textContent = 'Please enter a valid Bangladeshi phone number (e.g. 0171XXXXXXX)';
            } else {
                phoneFeedback.textContent = 'Valid phone number!';
                phoneFeedback.classList.remove('text-danger');
                phoneFeedback.classList.add('text-success');
            }
        });

        // Also validate when the field loses focus
        document.getElementById('phone').addEventListener('blur', function() {
            const phoneInput = this.value;
            const phoneFeedback = document.getElementById('phoneFeedback');
            const regex = /^(01[3-9]\d{8})$/;

            if (phoneInput === '') {
                phoneFeedback.textContent = 'Phone number is required';
            } else if (!regex.test(phoneInput)) {
                phoneFeedback.textContent = 'Please enter a valid Bangladeshi phone number (e.g. 0171XXXXXXX)';
            }
        });
    </script>
@endpush
