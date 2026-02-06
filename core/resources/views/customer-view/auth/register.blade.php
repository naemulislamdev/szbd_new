@extends('web.layouts.app')

@section('title', 'Register')

@push('css_or_js')
    <style>
        @media (max-width: 500px) {
            #sign_in {
                margin-top: -23% !important;
            }

        }

        .card {
            box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
        }

        .register-password-show {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .btn-primary {
            position: relative !important;
        }
    </style>
@endpush

@section('content')
    <div class="container py-4 py-lg-5 my-4" style="text-align: left;">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 box-shadow">
                    <div class="card-body">
                        <h2 class="h4 mb-1">Create Your New Account</h2>
                        <form class="needs-validation_" action="{{ route('customer.auth.sign-up') }}" method="post"
                            id="sign-up-form">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="reg-fn">Name <span class="text-danger">*</span></label>
                                        <input class="form-control" value="{{ old('name') }}" type="text"
                                            name="name" style="text-align: left;" required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="reg-email">Email address</label>
                                        <input class="form-control" type="email" value="{{ old('email') }}"
                                            name="email" style="text-align: left;" required>
                                        <div class="invalid-feedback">Please enter valid email address!</div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="reg-phone">Phone number</label>
                                        <input class="form-control" type="number" value="{{ old('phone') }}"
                                            id="phone" name="phone" style="text-align:left;" required>
                                        <span id="phoneFeedback" class="small text-danger"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="si-password">password'</label>
                                        <div class="register-password-show">
                                            <input class="form-control" name="password" type="password"
                                                style="text-align:left;" placeholder="minimum 8 characters long" required>
                                            <i class="fa fa-eye toggle-password" onclick="togglePassword('password')"></i>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="si-password">Confirm password</label>
                                        <div class="register-password-show">
                                            <input class="form-control" name="con_password" type="password"
                                                style="text-align: left;" placeholder="minimum 8 characters long" required>
                                            <i class="fa fa-eye toggle-password"
                                                onclick="togglePassword('con_password')"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group d-flex flex-wrap justify-content-between">

                                <div class="form-group mb-1">
                                    <strong>
                                        <input type="checkbox" class="mr-1" name="remember" id="inputCheckd">
                                    </strong>
                                    <label class="" for="remember">i agree to Your terms<a class="font-size-sm"
                                            target="_blank" href="{{ route('terms') }}">
                                            terms and condition
                                        </a></label>
                                </div>

                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="mx-1">
                                    <div class="text-right">
                                        <button class="btn btn-primary" id="sign-up" type="submit" disabled>
                                            <i class="fa fa-sign-in"></i>
                                            sing up
                                        </button>
                                    </div>
                                </div>
                                <div class="mx-1">
                                    <a class="btn btn-outline-primary" href="{{ route('customer.auth.login') }}">
                                        <i class="fa fa-sign-in"></i> sign_in
                                    </a>
                                </div>
                            </div>
                            <div class="row" style="direction: {{ Session::get('direction') }}">
                                <div class="col-12 mt-3">
                                    <div class="row">
                                        @foreach (\App\CPU\Helpers::get_business_settings('social_login') as $socialLoginService)
                                            @if (isset($socialLoginService) && $socialLoginService['status'] == true)
                                                <div class="col-sm-6 text-center mt-1 mx-auto">
                                                    <a class="btn btn-outline-primary"
                                                        href="{{ route('customer.auth.service-login', $socialLoginService['login_medium']) }}"
                                                        style="width: 100%">
                                                        <i class="czi-{{ $socialLoginService['login_medium'] }}"></i>
                                                        sing up with {{ $socialLoginService['login_medium'] }}
                                                    </a>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#inputCheckd').change(function() {
            // console.log('jell');
            if ($(this).is(':checked')) {
                $('#sign-up').removeAttr('disabled');
            } else {
                $('#sign-up').attr('disabled', 'disabled');
            }

        });
    </script>
    <script>
        function togglePassword(fieldId) {
            const field = document.querySelector(`input[name="${fieldId}"]`);
            const icon = field.nextElementSibling;
            if (field.type === "password") {
                field.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
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
