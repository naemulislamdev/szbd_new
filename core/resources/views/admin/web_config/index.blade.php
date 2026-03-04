@extends('admin.layouts.app')
@section('title', 'Web Configuration')

@push('styles')
    <style>
        label {
            font-weight: 500;
            cursor: pointer;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Web Configuration</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->

                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Web Configuration</li>
                        </ol>
                    </div>
                </div><!--end page-title-box-->
            </div><!--end col-->

        </div><!--end row-->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row ">
                            <div class="col-md-12 bg-primary rounded p-2 d-flex align-items-center mb-2">
                                <i style="font-size: 25px" class="la la-info-circle text-white"></i>
                                <p class="mb-0 text-white ms-2">Changing some settings will take time to show effect please
                                    clear
                                    session
                                    or wait for 60
                                    minutes else browse from incognito mode</p>
                            </div>
                            <div class="col-md-12 card rounded p-2 ">
                                @php
                                    $config = \App\CPU\Helpers::get_business_settings('maintenance_mode');
                                @endphp
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <i style="font-size: 25px" class="la la-gear"></i>
                                        <h6 class="mb-0 ms-2 fw-bold">
                                            Site Maintenance mode
                                        </h6>
                                    </div>
                                    <div>
                                        <div class="form-check form-switch form-switch form-check">
                                            <input onclick="maintenance_mode()" class="form-check-input status"
                                                type="checkbox" {{ isset($config) && $config ? 'checked' : '' }}
                                                name="maintenance_mode" value="1" id="flexSwitch">
                                            <label class="form-check-label" for="flexSwitch"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 ps-0 border-primary">
                                @php
                                    $config = \App\CPU\Helpers::get_business_settings('download_app_apple_stroe');

                                @endphp
                                <form action="{{ route('admin.web_config.app-store-update', 'download_app_apple_stroe') }}"
                                    method="POST">
                                    @csrf
                                    <div class="card">
                                        <div class="card-header border-bottom py-2">
                                            <h5 class="fw-bold mb-0">Apple Store Status</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <label for="active_status">
                                                    <input type="radio" name="status" value="1"
                                                        class="form-check-input " id="active_status"
                                                        {{ $config['status'] == 1 ? 'checked' : '' }}>
                                                    Enable
                                                </label>
                                            </div>
                                            <div class="mb-2">
                                                <label for="inactive_status">
                                                    <input type="radio" name="status" value="0"
                                                        class="form-check-input " id="inactive_status"
                                                        {{ $config['status'] == 0 ? 'checked' : '' }}>
                                                    Disabled
                                                </label>
                                            </div>
                                            <div class="mb-2">
                                                <label for="googleLink d-inline-block mb-2">Link</label>
                                                <input type="text" name="link" id="googleLink" class="form-control"
                                                    value="{{ $config['link'] }}">
                                            </div>
                                            <div>
                                                <button type="submit" class="btn btn-primary">
                                                    Save Changes
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 ps-0 border-primary">
                                @php
                                    $config = \App\CPU\Helpers::get_business_settings('download_app_google_stroe');

                                @endphp
                                <form action="{{ route('admin.web_config.app-store-update', 'download_app_google_stroe') }}"
                                    method="POST">
                                    @csrf
                                    <div class="card">
                                        <div class="card-header border-bottom py-2">
                                            <h5 class="fw-bold mb-0">Google Play Store Status</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <label for="googleactive_status">
                                                    <input type="radio" name="status" value="1"
                                                        class="form-check-input " id="googleactive_status"
                                                        {{ $config['status'] == 1 ? 'checked' : '' }}>
                                                    Enable
                                                </label>
                                            </div>
                                            <div class="mb-2">
                                                <label for="googleinactive_status">
                                                    <input type="radio" name="status" value="0"
                                                        class="form-check-input " id="googleinactive_status"
                                                        {{ $config['status'] == 0 ? 'checked' : '' }}>
                                                    Disabled
                                                </label>
                                            </div>
                                            <div class="mb-2">
                                                <label for="googleLink d-inline-block mb-2">Link</label>
                                                <input type="text" name="link" id="googleLink" class="form-control"
                                                    value="{{ $config['link'] }}">
                                            </div>
                                            <div>
                                                <button type="submit" class="btn btn-primary">
                                                    Save Changes
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div><!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body pt-0 px-2">
                        <div id="loader" style="display:none; text-align:center; margin-bottom:10px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        {{-- Main Setting Form --}}
                        <div class="col-md-12 ps-0">
                            <form action="{{ route('admin.web_config.updateInfo') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card">
                                    <div class="card-header border-bottom py-2">
                                        <h5 class="fw-bold mb-0"><i class="la la-home"></i> Admin Shop Banner</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="text-center" style="height: 200px; width: 100%">
                                            <img style="max-height: 100%; width: auto;"
                                                src="{{ asset('assets/storage/shop/') }}/{{ \App\CPU\Helpers::get_business_settings('shop_banner') }}"
                                                id="viewer1" alt="Admin Shop Banner">
                                        </div>
                                        <div class="mt-3">
                                            <label for="adminShopBanner" class="d-block mb-2">Upload New
                                                Banner</label>
                                            <input class="form-control" type="file" name="shop_banner"
                                                id="customFileUpload1">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-4">
                                        @php
                                            $companyName = \App\Models\BusinessSetting::where(
                                                'type',
                                                'company_name',
                                            )->first();
                                        @endphp
                                        <label for="companyName">Company Name</label>
                                        <input type="text" name="company_name" id="companyName" class="form-control"
                                            value="{{ $companyName->value ? $companyName->value : '' }}">
                                    </div>
                                    <div class="col-md-4">
                                        @php
                                            $companyEmail = \App\Models\BusinessSetting::where(
                                                'type',
                                                'company_email',
                                            )->first();
                                        @endphp
                                        <label for="companyEmail">Company Email</label>
                                        <input type="email" name="company_email" id="companyEmail"
                                            class="form-control"
                                            value="{{ $companyEmail->value ? $companyEmail->value : '' }}">
                                    </div>
                                    <div class="col-md-4">
                                        @php
                                            $companyPhone = \App\Models\BusinessSetting::where(
                                                'type',
                                                'company_phone',
                                            )->first();
                                        @endphp
                                        <label for="companyPhone">Company Phone</label>
                                        <input type="text" name="company_phone" id="companyPhone"
                                            class="form-control"
                                            value="{{ $companyPhone->value ? $companyPhone->value : '' }}">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    @php
                                        $default_location = \App\CPU\Helpers::get_business_settings('default_location');
                                    @endphp
                                    <div class="col-md-4">

                                        <label for="latitude">Latitude</label>
                                        <input id="latitude" class="form-control" type="text" name="latitude"
                                            value="{{ isset($default_location) ? $default_location['lat'] : '' }}"
                                            placeholder="Latitude">
                                    </div>
                                    <div class="col-md-4">

                                        <label for="longitude">Longitude</label>
                                        <input class="form-control" type="text" name="longitude"
                                            value="{{ isset($default_location) ? $default_location['lng'] : '' }}"
                                            placeholder="longitude">
                                    </div>
                                    <div class="col-md-4">
                                        @php
                                            $stock_limit = \App\Models\BusinessSetting::where(
                                                'type',
                                                'stock_limit',
                                            )->first();
                                        @endphp
                                        <label for="companyPhone">Minimum stock limit for warning</label>
                                        <input class="form-control" type="number" name="stock_limit"
                                            value="{{ $stock_limit->value ? $stock_limit->value : '' }}"
                                            placeholder="EX: 123">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        @php
                                            $pagination_limit = \App\CPU\Helpers::get_business_settings(
                                                'pagination_limit',
                                            );
                                        @endphp
                                        <label for="pgn_limit">Pagination Limit</label>
                                        <input type="number" value="{{ $pagination_limit }}" name="pagination_limit"
                                            class="form-control" id="pgn_limit" placeholder="25">
                                    </div>
                                    <div class="col-md-4">
                                        @php
                                            $decimal_point = \App\Models\BusinessSetting::where(
                                                'type',
                                                'decimal_point_settings',
                                            )->first();
                                        @endphp
                                        <label for="decimal_point_settings">Digit after decimal point</label>
                                        <input type="number" value="{{ $decimal_point->value }}"
                                            name="decimal_point_settings" class="form-control" min="0"
                                            placeholder="EX: 2" id="decimal_point_settings">
                                    </div>
                                    <div class="col-md-4">
                                        @php
                                            $company_copyright_text = \App\Models\BusinessSetting::where(
                                                'type',
                                                'company_copyright_text',
                                            )->first();
                                        @endphp
                                        <label for="company_copyright_text">Company Copyright Text</label>
                                        <input class="form-control" type="text" name="company_copyright_text"
                                            value="{{ $company_copyright_text->value ? $company_copyright_text->value : ' ' }}"
                                            placeholder="Company_copyright_text">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-8">
                                        @php
                                            $shop_address = \App\CPU\Helpers::get_business_settings('shop_address');
                                        @endphp
                                        <label for="shop_address">Company Shop Address</label>
                                        <input id="shop_address" type="text"
                                            value="{{ isset($shop_address) != null ? $shop_address : '' }}"
                                            name="shop_address" class="form-control" placeholder="Your_shop_address"
                                            required>
                                    </div>
                                    <div class="col-md-4">
                                        @php
                                            $company_hotline = \App\Models\BusinessSetting::where(
                                                'type',
                                                'company_hotline',
                                            )->first();
                                        @endphp
                                        <label for="company_hotline">Company Hotline</label>
                                        <input id="company_hotline" type="text"
                                            value="{{ isset($company_hotline) != null ? $company_hotline->value : '' }}"
                                            name="company_hotline" class="form-control"
                                            placeholder="Your company hotline" required>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        @php
                                            $tz = \App\Models\BusinessSetting::where('type', 'timezone')->first();
                                            $tz = $tz ? $tz->value : 0;
                                        @endphp
                                        <label for="timezone">Timezone</label>
                                        <select name="timezone" class="form-select ">

                                            <option value="Asia/Dhaka"
                                                {{ $tz ? ($tz == 'Asia/Dhaka' ? 'selected' : '') : '' }}>
                                                (GMT+06:00)
                                                Asia,
                                                Dhaka
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        @php
                                            $cc = \App\Models\BusinessSetting::where('type', 'country_code')->first();
                                            $cc = $cc ? $cc->value : 0;
                                        @endphp
                                        <label for="country">Country</label>
                                        <select id="country" name="country" class="form-select  js-select2-custom">
                                            <option value="BD" {{ $cc ? ($cc == 'BD' ? 'selected' : '') : '' }}>
                                                Bangladesh
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        @php
                                            $fpv = \App\CPU\Helpers::get_business_settings(
                                                'forgot_password_verification',
                                            );
                                        @endphp
                                        <label for="forgot_password_verification">Forgot Password Verification Via</label>
                                        <select name="forgot_password_verification" class="form-select">
                                            <option value="email"
                                                {{ isset($fpv) ? ($fpv == 'email' ? 'selected' : '') : '' }}>
                                                Email</option>
                                            <option value="phone"
                                                {{ isset($fpv) ? ($fpv == 'phone' ? 'selected' : '') : '' }}>
                                                Phone</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">

                                    <div class="col-md-4">
                                        @php
                                            $pv = \App\CPU\Helpers::get_business_settings('phone_verification');
                                        @endphp
                                        <label>Phone Verification (OTP)</label>
                                        <div class="input-group input-group-md-down-break">
                                            <!-- Custom Radio -->
                                            <div class="form-control p-0">
                                                <div class=" custom-radio d-flex">
                                                    <label class="custom-control px-3 py-1 w-100 d-inline-block"
                                                        for="phone_verification_on"><input type="radio"
                                                            class="custom-control-input" value="1"
                                                            name="phone_verification" id="phone_verification_on"
                                                            {{ isset($pv) && $pv == 1 ? 'checked' : '' }}> On</label>
                                                </div>
                                            </div>
                                            <!-- End Custom Radio -->
                                            <!-- Custom Radio -->
                                            <div class="form-control p-0">
                                                <div class="custom-radio d-flex">
                                                    <label class="custom-control px-3 py-1 w-100 d-inline-block"
                                                        for="phone_verification_off"><input type="radio"
                                                            class="custom-control-input" value="0"
                                                            name="phone_verification" id="phone_verification_off"
                                                            {{ isset($pv) && $pv == 0 ? 'checked' : '' }}> Off</label>
                                                </div>
                                            </div>
                                            <!-- End Custom Radio -->
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        @php
                                            $ev = \App\CPU\Helpers::get_business_settings('email_verification');
                                        @endphp
                                        <label>Email Verification</label>
                                        <div class="input-group input-group-md-down-break">
                                            <!-- Custom Radio -->
                                            <div class="form-control p-0">
                                                <div class=" custom-radio d-flex">
                                                    <label class="custom-control px-3 py-1 w-100 d-inline-block"
                                                        for="email_verification_on"><input type="radio"
                                                            class="custom-control-input" value="1"
                                                            name="email_verification" id="email_verification_on"
                                                            {{ isset($ev) && $ev == 1 ? 'checked' : '' }}> On</label>
                                                </div>
                                            </div>
                                            <!-- End Custom Radio -->
                                            <!-- Custom Radio -->
                                            <div class="form-control p-0">
                                                <div class="custom-radio d-flex">
                                                    <label class="custom-control px-3 py-1 w-100 d-inline-block"
                                                        for="email_verification_off"><input type="radio"
                                                            class="custom-control-input" value="0"
                                                            name="email_verification" id="email_verification_off"
                                                            {{ isset($ev) && $ev == 0 ? 'checked' : '' }}> Off</label>
                                                </div>
                                            </div>
                                            <!-- End Custom Radio -->
                                        </div>

                                    </div>
                                    <div class="col-md-4">
                                        @php
                                            $order_verification = \App\CPU\Helpers::get_business_settings(
                                                'order_verification',
                                            );
                                        @endphp
                                        <label>Order Verification</label>
                                        <div class="input-group input-group-md-down-break">
                                            <!-- Custom Radio -->
                                            <div class="form-control p-0">
                                                <div class=" custom-radio d-flex">
                                                    <label class="custom-control px-3 py-1 w-100 d-inline-block"
                                                        for="order_verification_on"><input type="radio"
                                                            class="custom-control-input" value="1"
                                                            name="order_verification" id="order_verification_on"
                                                            {{ isset($order_verification) && $order_verification == 1 ? 'checked' : '' }}>
                                                        On</label>
                                                </div>
                                            </div>
                                            <!-- End Custom Radio -->
                                            <!-- Custom Radio -->
                                            <div class="form-control p-0">
                                                <div class="custom-radio d-flex">
                                                    <label class="custom-control px-3 py-1 w-100 d-inline-block"
                                                        for="order_verification_off"><input type="radio"
                                                            class="custom-control-input" value="0"
                                                            name="order_verification" id="order_verification_off"
                                                            {{ isset($order_verification) && $order_verification == 0 ? 'checked' : '' }}>
                                                        Off</label>
                                                </div>
                                            </div>
                                            <!-- End Custom Radio -->
                                        </div>

                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div
                                                class="card-header border-bottom d-flex justify-content-between align-items-center">
                                                <h5 class="card-title mb-0">Web Logo</h5>
                                                <span class="text-danger">( 250x60 px )</span>
                                            </div>

                                            <div class="card-body">
                                                <div class="d-flex flex-column align-items-center">
                                                    <img id="preview"
                                                        src="{{ \App\CPU\Helpers::get_business_settings('company_web_logo')
                                                            ? asset('assets/storage/company/' . \App\CPU\Helpers::get_business_settings('company_web_logo'))
                                                            : '' }}"
                                                        alt="Web Logo" class="img-fluid mb-3" style="max-height: 200px;">
                                                    <label for="webLogoUpload" class="btn btn-primary">Choose
                                                        File</label>
                                                    <input type="file" id="webLogoUpload" name="company_web_logo"
                                                        class="d-none"
                                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        <div class="card">
                                            <div
                                                class="card-header border-bottom d-flex justify-content-between align-items-center">
                                                <h5 class="card-title mb-0">Mobile Logo</h5>
                                                <span class="text-danger">( 100x60 px )</span>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex flex-column align-items-center">
                                                    <img id="viewer2"
                                                        src="{{ \App\CPU\Helpers::get_business_settings('company_mobile_logo')
                                                            ? asset('assets/storage/company/' . \App\CPU\Helpers::get_business_settings('company_mobile_logo'))
                                                            : '' }}"
                                                        alt="Mobile Logo" class="img-fluid mb-3"
                                                        style="max-height: 200px;">
                                                    <label for="customFileUpload2" class="btn btn-primary">Choose
                                                        File</label>
                                                    <input type="file" id="customFileUpload2"
                                                        name="company_mobile_logo" class="d-none"
                                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div
                                                class="card-header border-bottom d-flex justify-content-between align-items-center">
                                                <h5 class="card-title mb-0">Web Footer Logo</h5>
                                                <span class="text-danger">( 250x60 px )</span>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex flex-column align-items-center">
                                                    <img id="viewer3"
                                                        src="{{ \App\CPU\Helpers::get_business_settings('company_footer_logo')
                                                            ? asset('assets/storage/company/' . \App\CPU\Helpers::get_business_settings('company_footer_logo'))
                                                            : '' }}"
                                                        alt="Web Footer Logo" class="img-fluid mb-3"
                                                        style="max-height: 200px;">
                                                    <label for="customFileUpload3" class="btn btn-primary">Choose
                                                        File</label>
                                                    <input type="file" id="customFileUpload3"
                                                        name="company_footer_logo" class="d-none"
                                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div
                                                class="card-header border-bottom d-flex justify-content-between align-items-center">
                                                <h5 class="card-title mb-0">Web Favicon</h5>
                                                <span class="text-danger">(Ratio 1:1)</span>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex flex-column align-items-center">
                                                    <img id="viewer4"
                                                        src="{{ \App\CPU\Helpers::get_business_settings('company_fav_icon')
                                                            ? asset('assets/storage/company/' . \App\CPU\Helpers::get_business_settings('company_fav_icon'))
                                                            : '' }}"
                                                        alt="Web Favicon" class="img-fluid mb-3"
                                                        style="max-height: 200px;">
                                                    <label for="customFileUpload4" class="btn btn-primary">Choose
                                                        File</label>
                                                    <input type="file" id="customFileUpload4" name="company_fav_icon"
                                                        class="d-none"
                                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card">
                                            <div
                                                class="card-header border-bottom d-flex justify-content-between align-items-center">
                                                <h5 class="card-title mb-0">Loader Gif</h5>
                                                <span class="text-danger">(Ratio 1:1)</span>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex flex-column align-items-center">
                                                    <img id="viewer5"
                                                        src="{{ \App\CPU\Helpers::get_business_settings('loader_gif')
                                                            ? asset('assets/storage/company/' . \App\CPU\Helpers::get_business_settings('loader_gif'))
                                                            : '' }}"
                                                        alt="Loader Gif" class="img-fluid mb-3"
                                                        style="max-height: 200px;">
                                                    <label for="customFileUpload5" class="btn btn-primary">Choose
                                                        File</label>
                                                    <input type="file" id="customFileUpload5" name="loader_gif"
                                                        class="d-none">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary w-100 d-block">Save
                                            Changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container -->

@endsection
@push('scripts')
    <script>
        function maintenance_mode() {
            @if (env('APP_MODE') == 'demo')
                call_demo();
            @else
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Be careful before you turn on/off maintenance mode',
                    type: 'warning',
                    showCancelButton: true,
                    cancelButtonColor: 'default',
                    confirmButtonColor: '#377dff',
                    cancelButtonText: 'No',
                    confirmButtonText: 'Yes',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        $.get({
                            url: '{{ route('admin.maintenance-mode') }}',
                            contentType: false,
                            processData: false,
                            beforeSend: function() {
                                $('#loading').show();
                            },
                            success: function(data) {
                                toastr.success(data.message);
                            },
                            complete: function() {
                                $('#loading').hide();
                            },
                        });
                    } else {
                        location.reload();
                    }
                })
            @endif
        };

        function currency_symbol_position(route) {
            $.get({
                url: route,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    toastr.success(data.message);
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        }
    </script>
    <script>
        $(document).ready(function() {

            $('.all_access').on('change', function() {

                let card = $(this).closest('.permission-item');

                card.find('.permission-checkbox').prop('checked', $(this).is(':checked'));

            });

        });
    </script>
    <script>
        function readURL1(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#preview').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewer2').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL3(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewer3').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL4(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewer4').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL5(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewer5').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL6(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewer1').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }


        $("#webLogoUpload").change(function() {
            readURL1(this);
        });
        $("#customFileUpload2").change(function() {
            readURL2(this);
        });
        $("#customFileUpload3").change(function() {
            readURL3(this);
        });
        $("#customFileUpload4").change(function() {
            readURL4(this);
        });

        $("#customFileUpload5").change(function() {
            readURL5(this);
        });

        $("#customFileUpload1").change(function() {
            readURL6(this);
        });
    </script>
@endpush
