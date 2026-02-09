@extends('customer.layouts.master')

@section('title', auth('customer')->user()->f_name . 'Profile')

@push('css_or_js')
    <style>
        .headerTitle {
            font-size: 24px;
            font-weight: 600;
            margin-top: 1rem;
        }

        .border:hover {
            border: 3px solid{{ $web_config['primary_color'] }};
            margin-bottom: 5px;
            margin-top: -6px;
        }

        body {
            font-family: 'Titillium Web', sans-serif
        }


        .footer span {
            font-size: 12px
        }

        .product-qty span {
            font-size: 12px;
            color: #6A6A6A;
        }

        .spandHeadO {
            color: {{ $web_config['primary_color'] }};
            font-weight: 400;
            font-size: 13px;

        }

        .spandHeadO:hover {
            color: {{ $web_config['primary_color'] }};
            font-weight: 400;
            font-size: 13px;

        }

        .font-name {
            font-weight: 600;
            margin-top: 0px !important;
            margin-bottom: 0;
            font-size: 15px;
            color: #030303;
        }

        .font-nameA {
            font-weight: 600;
            margin-top: 0px;
            margin-bottom: 7px !important;
            font-size: 17px;
            color: #030303;
        }

        label {
            font-size: 16px;
        }

        .photoHeader {
            margin- {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}: 1rem;
            margin- {{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}: 2rem;
            padding: 13px;
        }

        .card-header {
            border-bottom: none;
        }

        .sidebarL h3:hover+.divider-role {
            border-bottom: 3px solid {{ $web_config['primary_color'] }} !important;
            transition: .2s ease-in-out;
        }

        @media (max-width: 350px) {

            .photoHeader {
                margin-left: 0.1px !important;
                margin-right: 0.1px !important;
                padding: 0.1px !important;

            }
        }

        @media (max-width: 600px) {
            .sidebar_heading {
                background: {{ $web_config['primary_color'] }};
            }

            .sidebar_heading h1 {
                text-align: center;
                color: aliceblue;
                padding-bottom: 17px;
                font-size: 19px;
            }

        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }

        .password-wrapper .toggle-password.active {
            color: #0d6efd;
        }
    </style>
@endpush

@section('customer_content')
    <!-- Page Title-->
    <div class="row mb-3">
        <div class="col-md-12 sidebar_heading">
            <h1 class="h3  mb-0 headerTitle">
                Profile information</h1>
        </div>
    </div>
    <!-- Page Content-->
    <div class="card box-shadow-sm">
        <div class="card-body">
            <form class="mt-3" action="{{ route('user-update') }}" method="post" enctype="multipart/form-data">
                <div class="row photoHeader">
                    @csrf
                    <img id="blah" style=" border-radius: 50px; width: 50px!important;height: 50px!important;"
                        class="rounded-circle border"
                        onerror="this.src='{{ asset('assets/frontend/img/image-place-holder.png') }}'"
                        src="{{ asset('assets/storage/profile') }}/{{ $customerDetail['image'] }}">

                    <div class="col-md-10">
                        <h5 class="font-name">{{ $customerDetail->name }}</h5>
                        <label for="files" style="cursor: pointer; color:{{ $web_config['primary_color'] }};"
                            class="spandHeadO">
                            Change your profile
                        </label>
                        <input id="files" name="image" style="visibility:hidden;" type="file">
                    </div>

                    <div class=" mt-md-3" style="padding: 0px;">
                        <h3 class="font-nameA">Account information </h3>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="firstName">Name </label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $customerDetail['name'] }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputEmail4">Email </label>
                                    <input type="email" class="form-control" name="email" id="account-email"
                                        value="{{ $customerDetail['email'] }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone number </label></label>
                                    <input type="number" class="form-control" type="text" id="phone" name="phone"
                                        value="{{ $customerDetail['phone'] }}" required disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>New password</label>
                                    <div class="password-wrapper">
                                        <input class="form-control" name="password" type="password" id="password">
                                        <i class="fa fa-eye toggle-password" data-target="password"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Confirm password</label>
                                    <div class="password-wrapper">
                                        <input class="form-control" name="con_password" type="password"
                                            id="confirm_password">
                                        <i class="fa fa-eye toggle-password" data-target="confirm_password"></i>
                                    </div>
                                    <div id="message" class="mt-1"></div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('c_script')
    <script>
        // Show / Hide password
        $(document).on('click', '.toggle-password', function() {
            let targetId = $(this).data('target');
            let input = $('#' + targetId);

            if (input.attr('type') === 'password') {
                input.attr('type', 'text');
                $(this).addClass('active').removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                input.attr('type', 'password');
                $(this).removeClass('active').removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        // Password match validation
        function checkPasswordMatch() {
            let password = $("#password").val();
            let confirmPassword = $("#confirm_password").val();
            let message = $("#message");

            message.removeAttr("style").html("");

            if (confirmPassword === "") {
                message.css("color", "black").html("Please retype password");
            } else if (password !== confirmPassword) {
                message.css("color", "red").html("Passwords do not match!");
            } else if (confirmPassword.length < 6) {
                message.css("color", "red").html("Password must be at least 6 characters");
            } else {
                message.css("color", "green").html("Passwords match ✔");
            }
        }

        $("#password, #confirm_password").on('keyup', checkPasswordMatch);
    </script>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blah').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]); // convert to base64 string
            }
        }

        $("#files").change(function() {
            readURL(this);
        });
    </script>
    <script>
        function form_alert(id, message) {
            Swal.fire({
                title: 'Are you sure?',
                text: message,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $('#' + id).submit()
                }
            })
        }
    </script>
@endpush
