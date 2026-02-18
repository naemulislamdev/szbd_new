<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="light" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <title>Login Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <!-- App css -->
    <link href="{{ asset('assets/backend/css/line-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/default/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/backend/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/backend/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/backend/css/app.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        #toast-container>.toast-success {
            background-color: #28a745;
        }

        #toast-container>.toast-error {
            background-color: #dc3545;
        }

        #toast-container>.toast-warning {
            background-color: #ffc107;
            color: #000 !important;
        }

        #toast-container>.toast-info {
            background-color: #0dcaf0;
        }

        #toast-container .toast-message {
            color: #fff !important;
            font-weight: 500;
        }
    </style>
</head>
<!-- Top Bar Start -->

<body>
    <div class="container-xxl">
        <div class="row vh-100 d-flex justify-content-center">
            <div class="col-12 align-self-center">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 mx-auto">
                            <div class="card">
                                <div class="card-body p-0 bg-black auth-header-box rounded-top">
                                    @php($e_commerce_logo = \App\Models\BusinessSetting::where(['type' => 'company_web_logo'])->first()->value)
                                    <div class="text-center p-3">
                                        <a href="{{ route('home') }}" class="logo logo-admin">
                                            <img src="{{ asset('assets/storage/logo/' . $e_commerce_logo) }}"
                                                height="50" alt="logo" class="auth-logo">
                                        </a>
                                        <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Let's Get Started Shopping
                                            Zone BD</h4>
                                        <p class="text-muted fw-medium mb-0">Sign in to continue to Shopping Zone BD.
                                        </p>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    @if (Session::has('error'))
                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                            <strong>{{ Session::get('error') }}</strong>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif
                                    <form class="my-4" action="{{ route('admin.auth.login.store') }}" method="POST">
                                        @csrf
                                        <div class="form-group mb-2">
                                            <label class="form-label" for="username">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="username" name="email"
                                                placeholder="Enter email">
                                            @error('email')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="userpassword">Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="password"
                                                id="userpassword" placeholder="Enter password">
                                            @error('password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div><!--end form-group-->

                                        <div class="form-group row mt-3">
                                            <div class="col-sm-6">
                                                <div class="form-check form-switch form-switch-success">
                                                    <input class="form-check-input" type="checkbox"
                                                        id="customSwitchSuccess">
                                                    <label class="form-check-label" for="customSwitchSuccess">Remember
                                                        me</label>
                                                </div>
                                            </div><!--end col-->
                                            <div class="col-sm-6 text-end">
                                                <a href="auth-recover-pw.html" class="text-muted font-13"><i
                                                        class="dripicons-lock"></i> Forgot password?</a>
                                            </div><!--end col-->
                                        </div><!--end form-group-->

                                        <div class="form-group mb-0 row">
                                            <div class="col-12">
                                                <div class="d-grid mt-3">
                                                    <button
                                                        class="btn btn-primary d-flex justify-content-center align-items-center"
                                                        type="submit">Log In <i class="las la-sign-in-alt ms-1"
                                                            style="font-size: 17px"></i></button>
                                                </div>
                                            </div><!--end col-->
                                        </div> <!--end form-group-->
                                    </form><!--end form-->
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end card-body-->
            </div><!--end col-->
        </div><!--end row-->
    </div><!-- container -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('assets/default/toastr/toastr.min.js') }}"></script>

    @if (Session::has('success'))
        <script>
            toastr.success("{{ Session::get('success') }}");
        </script>
    @endif

    @if (Session::has('error'))
        <script>
            toastr.error("{{ Session::get('error') }}");
        </script>
    @endif

    @if (Session::has('warning'))
        <script>
            toastr.warning("{{ Session::get('warning') }}");
        </script>
    @endif

    @if (Session::has('info'))
        <script>
            toastr.info("{{ Session::get('info') }}");
        </script>
    @endif
    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}', Error, {
                    CloseButton: true,
                    ProgressBar: true
                });
            @endforeach
        </script>
    @endif

</body>
<!--end body-->

</html>
