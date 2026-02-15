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
                                                name="colors_active" data-id="" value="1" id="flexSwitch">
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
                                                src="{{ asset('assets/storage/shop') }}/{{ \App\CPU\Helpers::get_business_settings('shop_banner') }}"
                                                id="viewer1" alt="Admin Shop Banner">
                                        </div>
                                        <div class="mt-3">
                                            <label for="adminShopBanner" class="d-block mb-2">Upload New
                                                Banner</label>
                                            <input class="form-control" type="file" name="admin_shop_banner"
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

                                        <label for="companyName">Latitude</label>
                                        <input class="form-control" type="text" name="latitude"
                                            value="{{ isset($default_location) ? $default_location['lat'] : '' }}"
                                            placeholder="Latitude">
                                    </div>
                                    <div class="col-md-4">

                                        <label for="companyEmail">Longitude</label>
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
                                            <option value="UTC" {{ $tz ? ($tz == '' ? 'selected' : '') : '' }}>UTC
                                            </option>
                                            <option value="Etc/GMT+12"
                                                {{ $tz ? ($tz == 'Etc/GMT+12' ? 'selected' : '') : '' }}>
                                                (GMT-12:00)
                                                International Date Line West
                                            </option>
                                            <option value="Pacific/Midway"
                                                {{ $tz ? ($tz == 'Pacific/Midway' ? 'selected' : '') : '' }}>
                                                (GMT-11:00)
                                                Midway Island, Samoa
                                            </option>
                                            <option value="Pacific/Honolulu"
                                                {{ $tz ? ($tz == 'Pacific/Honolulu' ? 'selected' : '') : '' }}>
                                                (GMT-10:00)
                                                Hawaii
                                            </option>
                                            <option value="US/Alaska"
                                                {{ $tz ? ($tz == 'US/Alaska' ? 'selected' : '') : '' }}>
                                                (GMT-09:00) Alaska
                                            </option>
                                            <option value="America/Los_Angeles"
                                                {{ $tz ? ($tz == 'America/Los_Angeles' ? 'selected' : '') : '' }}>
                                                (GMT-08:00) Pacific Time (US & Canada)
                                            </option>
                                            <option value="America/Tijuana"
                                                {{ $tz ? ($tz == 'America/Tijuana' ? 'selected' : '') : '' }}>
                                                (GMT-08:00)
                                                Tijuana, Baja California
                                            </option>
                                            <option value="US/Arizona"
                                                {{ $tz ? ($tz == 'US/Arizona' ? 'selected' : '') : '' }}>
                                                (GMT-07:00)
                                                Arizona
                                            </option>
                                            <option value="America/Chihuahua"
                                                {{ $tz ? ($tz == 'America/Chihuahua' ? 'selected' : '') : '' }}>
                                                (GMT-07:00) Chihuahua, La Paz, Mazatlan
                                            </option>
                                            <option value="US/Mountain"
                                                {{ $tz ? ($tz == 'US/Mountain' ? 'selected' : '') : '' }}>
                                                (GMT-07:00)
                                                Mountain
                                                Time (US & Canada)
                                            </option>
                                            <option value="America/Managua"
                                                {{ $tz ? ($tz == 'America/Managua' ? 'selected' : '') : '' }}>
                                                (GMT-06:00)
                                                Central America
                                            </option>
                                            <option value="US/Central"
                                                {{ $tz ? ($tz == 'US/Central' ? 'selected' : '') : '' }}>
                                                (GMT-06:00)
                                                Central Time
                                                (US & Canada)
                                            </option>
                                            <option value="America/Mexico_City"
                                                {{ $tz ? ($tz == 'America/Mexico_City' ? 'selected' : '') : '' }}>
                                                (GMT-06:00) Guadalajara, Mexico City, Monterrey
                                            </option>
                                            <option value="Canada/Saskatchewan"
                                                {{ $tz ? ($tz == 'Canada/Saskatchewan' ? 'selected' : '') : '' }}>
                                                (GMT-06:00) Saskatchewan
                                            </option>
                                            <option value="America/Bogota"
                                                {{ $tz ? ($tz == 'America/Bogota' ? 'selected' : '') : '' }}>
                                                (GMT-05:00)
                                                Bogota, Lima, Quito, Rio Branco
                                            </option>
                                            <option value="US/Eastern"
                                                {{ $tz ? ($tz == 'US/Eastern' ? 'selected' : '') : '' }}>
                                                (GMT-05:00)
                                                Eastern Time
                                                (US & Canada)
                                            </option>
                                            <option value="US/East-Indiana"
                                                {{ $tz ? ($tz == 'US/East-Indiana' ? 'selected' : '') : '' }}>
                                                (GMT-05:00)
                                                Indiana (East)
                                            </option>
                                            <option value="Canada/Atlantic"
                                                {{ $tz ? ($tz == 'Canada/Atlantic' ? 'selected' : '') : '' }}>
                                                (GMT-04:00)
                                                Atlantic Time (Canada)
                                            </option>
                                            <option value="America/Caracas"
                                                {{ $tz ? ($tz == 'America/Caracas' ? 'selected' : '') : '' }}>
                                                (GMT-04:00)
                                                Caracas, La Paz
                                            </option>
                                            <option value="America/Manaus"
                                                {{ $tz ? ($tz == 'America/Manaus' ? 'selected' : '') : '' }}>
                                                (GMT-04:00)
                                                Manaus
                                            </option>
                                            <option value="America/Santiago"
                                                {{ $tz ? ($tz == 'America/Santiago' ? 'selected' : '') : '' }}>
                                                (GMT-04:00)
                                                Santiago
                                            </option>
                                            <option value="Canada/Newfoundland"
                                                {{ $tz ? ($tz == 'Canada/Newfoundland' ? 'selected' : '') : '' }}>
                                                (GMT-03:30) Newfoundland
                                            </option>
                                            <option value="America/Sao_Paulo"
                                                {{ $tz ? ($tz == 'America/Sao_Paulo' ? 'selected' : '') : '' }}>
                                                (GMT-03:00) Brasilia
                                            </option>
                                            <option value="America/Argentina/Buenos_Aires"
                                                {{ $tz ? ($tz == 'America/Argentina/Buenos_Aires' ? 'selected' : '') : '' }}>
                                                (GMT-03:00) Buenos Aires, Georgetown
                                            </option>
                                            <option value="America/Godthab"
                                                {{ $tz ? ($tz == 'America/Godthab' ? 'selected' : '') : '' }}>
                                                (GMT-03:00)
                                                Greenland
                                            </option>
                                            <option value="America/Montevideo"
                                                {{ $tz ? ($tz == 'America/Montevideo' ? 'selected' : '') : '' }}>
                                                (GMT-03:00) Montevideo
                                            </option>
                                            <option value="America/Noronha"
                                                {{ $tz ? ($tz == 'America/Noronha' ? 'selected' : '') : '' }}>
                                                (GMT-02:00)
                                                Mid-Atlantic
                                            </option>
                                            <option value="Atlantic/Cape_Verde"
                                                {{ $tz ? ($tz == 'Atlantic/Cape_Verde' ? 'selected' : '') : '' }}>
                                                (GMT-01:00) Cape Verde Is.
                                            </option>
                                            <option value="Atlantic/Azores"
                                                {{ $tz ? ($tz == 'Atlantic/Azores' ? 'selected' : '') : '' }}>
                                                (GMT-01:00)
                                                Azores
                                            </option>
                                            <option value="Africa/Casablanca"
                                                {{ $tz ? ($tz == 'Africa/Casablanca' ? 'selected' : '') : '' }}>
                                                (GMT+00:00) Casablanca, Monrovia, Reykjavik
                                            </option>
                                            <option value="Etc/Greenwich"
                                                {{ $tz ? ($tz == 'Etc/Greenwich' ? 'selected' : '') : '' }}>
                                                (GMT+00:00)
                                                Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London
                                            </option>
                                            <option value="Europe/Amsterdam"
                                                {{ $tz ? ($tz == 'Europe/Amsterdam' ? 'selected' : '') : '' }}>
                                                (GMT+01:00)
                                                Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna
                                            </option>
                                            <option value="Europe/Belgrade"
                                                {{ $tz ? ($tz == 'Europe/Belgrade' ? 'selected' : '') : '' }}>
                                                (GMT+01:00)
                                                Belgrade, Bratislava, Budapest, Ljubljana, Prague
                                            </option>
                                            <option value="Europe/Brussels"
                                                {{ $tz ? ($tz == 'Europe/Brussels' ? 'selected' : '') : '' }}>
                                                (GMT+01:00)
                                                Brussels, Copenhagen, Madrid, Paris
                                            </option>
                                            <option value="Europe/Sarajevo"
                                                {{ $tz ? ($tz == 'Europe/Sarajevo' ? 'selected' : '') : '' }}>
                                                (GMT+01:00)
                                                Sarajevo, Skopje, Warsaw, Zagreb
                                            </option>
                                            <option value="Africa/Lagos"
                                                {{ $tz ? ($tz == 'Africa/Lagos' ? 'selected' : '') : '' }}>
                                                (GMT+01:00)
                                                West
                                                Central Africa
                                            </option>
                                            <option value="Asia/Amman"
                                                {{ $tz ? ($tz == 'Asia/Amman' ? 'selected' : '') : '' }}>
                                                (GMT+02:00)
                                                Amman
                                            </option>
                                            <option value="Europe/Athens"
                                                {{ $tz ? ($tz == 'Europe/Athens' ? 'selected' : '') : '' }}>
                                                (GMT+02:00)
                                                Athens, Bucharest, Istanbul
                                            </option>
                                            <option value="Asia/Beirut"
                                                {{ $tz ? ($tz == 'Asia/Beirut' ? 'selected' : '') : '' }}>
                                                (GMT+02:00)
                                                Beirut
                                            </option>
                                            <option value="Africa/Cairo"
                                                {{ $tz ? ($tz == 'Africa/Cairo' ? 'selected' : '') : '' }}>
                                                (GMT+02:00)
                                                Cairo
                                            </option>
                                            <option value="Africa/Harare"
                                                {{ $tz ? ($tz == 'Africa/Harare' ? 'selected' : '') : '' }}>
                                                (GMT+02:00)
                                                Harare, Pretoria
                                            </option>
                                            <option value="Europe/Helsinki"
                                                {{ $tz ? ($tz == 'Europe/Helsinki' ? 'selected' : '') : '' }}>
                                                (GMT+02:00)
                                                Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius
                                            </option>
                                            <option value="Asia/Jerusalem"
                                                {{ $tz ? ($tz == 'Asia/Jerusalem' ? 'selected' : '') : '' }}>
                                                (GMT+02:00)
                                                Jerusalem
                                            </option>
                                            <option value="Europe/Minsk"
                                                {{ $tz ? ($tz == 'Europe/Minsk' ? 'selected' : '') : '' }}>
                                                (GMT+02:00)
                                                Minsk
                                            </option>
                                            <option value="Africa/Windhoek"
                                                {{ $tz ? ($tz == 'Africa/Windhoek' ? 'selected' : '') : '' }}>
                                                (GMT+02:00)
                                                Windhoek
                                            </option>
                                            <option value="Asia/Kuwait"
                                                {{ $tz ? ($tz == 'Asia/Kuwait' ? 'selected' : '') : '' }}>
                                                (GMT+03:00)
                                                Kuwait,
                                                Riyadh, Baghdad
                                            </option>
                                            <option value="Europe/Moscow"
                                                {{ $tz ? ($tz == 'Europe/Moscow' ? 'selected' : '') : '' }}>
                                                (GMT+03:00)
                                                Moscow, St. Petersburg, Volgograd
                                            </option>
                                            <option value="Africa/Nairobi"
                                                {{ $tz ? ($tz == 'Africa/Nairobi' ? 'selected' : '') : '' }}>
                                                (GMT+03:00)
                                                Nairobi
                                            </option>
                                            <option value="Asia/Tbilisi"
                                                {{ $tz ? ($tz == 'Asia/Tbilisi' ? 'selected' : '') : '' }}>
                                                (GMT+03:00)
                                                Tbilisi
                                            </option>
                                            <option value="Asia/Tehran"
                                                {{ $tz ? ($tz == 'Asia/Tehran' ? 'selected' : '') : '' }}>
                                                (GMT+03:30)
                                                Tehran
                                            </option>
                                            <option value="Asia/Muscat"
                                                {{ $tz ? ($tz == 'Asia/Muscat' ? 'selected' : '') : '' }}>
                                                (GMT+04:00)
                                                Abu Dhabi,
                                                Muscat
                                            </option>
                                            <option value="Asia/Baku"
                                                {{ $tz ? ($tz == 'Asia/Baku' ? 'selected' : '') : '' }}>
                                                (GMT+04:00) Baku
                                            </option>
                                            <option value="Asia/Yerevan"
                                                {{ $tz ? ($tz == 'Asia/Yerevan' ? 'selected' : '') : '' }}>
                                                (GMT+04:00)
                                                Yerevan
                                            </option>
                                            <option value="Asia/Kabul"
                                                {{ $tz ? ($tz == 'Asia/Kabul' ? 'selected' : '') : '' }}>
                                                (GMT+04:30)
                                                Kabul
                                            </option>
                                            <option value="Asia/Yekaterinburg"
                                                {{ $tz ? ($tz == 'Asia/Yekaterinburg' ? 'selected' : '') : '' }}>
                                                (GMT+05:00) Yekaterinburg
                                            </option>
                                            <option value="Asia/Karachi"
                                                {{ $tz ? ($tz == 'Asia/Karachi' ? 'selected' : '') : '' }}>
                                                (GMT+05:00)
                                                Islamabad, Karachi, Tashkent
                                            </option>
                                            <option value="Asia/Calcutta"
                                                {{ $tz ? ($tz == 'Asia/Calcutta' ? 'selected' : '') : '' }}>
                                                (GMT+05:30)
                                                Chennai, Kolkata, Mumbai, New Delhi
                                            </option>
                                            <!-- <option value="Asia/Calcutta"  {{ $tz ? ($tz == 'Asia/Calcutta' ? 'selected' : '') : '' }}>(GMT+05:30) Sri Jayawardenapura</option> -->
                                            <option value="Asia/Katmandu"
                                                {{ $tz ? ($tz == 'Asia/Katmandu' ? 'selected' : '') : '' }}>
                                                (GMT+05:45)
                                                Kathmandu
                                            </option>
                                            <option value="Asia/Almaty"
                                                {{ $tz ? ($tz == 'Asia/Almaty' ? 'selected' : '') : '' }}>
                                                (GMT+06:00)
                                                Almaty,
                                                Novosibirsk
                                            </option>
                                            <option value="Asia/Dhaka"
                                                {{ $tz ? ($tz == 'Asia/Dhaka' ? 'selected' : '') : '' }}>
                                                (GMT+06:00)
                                                Astana,
                                                Dhaka
                                            </option>
                                            <option value="Asia/Rangoon"
                                                {{ $tz ? ($tz == 'Asia/Rangoon' ? 'selected' : '') : '' }}>
                                                (GMT+06:30)
                                                Yangon
                                                (Rangoon)
                                            </option>
                                            <option value="Asia/Bangkok"
                                                {{ $tz ? ($tz == '"Asia/Bangkok' ? 'selected' : '') : '' }}>(GMT+07:00)
                                                Bangkok, Hanoi, Jakarta
                                            </option>
                                            <option value="Asia/Krasnoyarsk"
                                                {{ $tz ? ($tz == 'Asia/Krasnoyarsk' ? 'selected' : '') : '' }}>
                                                (GMT+07:00)
                                                Krasnoyarsk
                                            </option>
                                            <option value="Asia/Hong_Kong"
                                                {{ $tz ? ($tz == 'Asia/Hong_Kong' ? 'selected' : '') : '' }}>
                                                (GMT+08:00)
                                                Beijing, Chongqing, Hong Kong, Urumqi
                                            </option>
                                            <option value="Asia/Kuala_Lumpur"
                                                {{ $tz ? ($tz == 'Asia/Kuala_Lumpur' ? 'selected' : '') : '' }}>
                                                (GMT+08:00) Kuala Lumpur, Singapore
                                            </option>
                                            <option value="Asia/Irkutsk"
                                                {{ $tz ? ($tz == 'Asia/Irkutsk' ? 'selected' : '') : '' }}>
                                                (GMT+08:00)
                                                Irkutsk,
                                                Ulaan Bataar
                                            </option>
                                            <option value="Australia/Perth"
                                                {{ $tz ? ($tz == 'Australia/Perth' ? 'selected' : '') : '' }}>
                                                (GMT+08:00)
                                                Perth
                                            </option>
                                            <option value="Asia/Taipei"
                                                {{ $tz ? ($tz == 'Asia/Taipei' ? 'selected' : '') : '' }}>
                                                (GMT+08:00)
                                                Taipei
                                            </option>
                                            <option value="Asia/Tokyo"
                                                {{ $tz ? ($tz == 'Asia/Tokyo' ? 'selected' : '') : '' }}>
                                                (GMT+09:00)
                                                Osaka,
                                                Sapporo, Tokyo
                                            </option>
                                            <option value="Asia/Seoul"
                                                {{ $tz ? ($tz == 'Asia/Seoul' ? 'selected' : '') : '' }}>
                                                (GMT+09:00)
                                                Seoul
                                            </option>
                                            <option value="Asia/Yakutsk"
                                                {{ $tz ? ($tz == 'Asia/Yakutsk' ? 'selected' : '') : '' }}>
                                                (GMT+09:00)
                                                Yakutsk
                                            </option>
                                            <option value="Australia/Adelaide"
                                                {{ $tz ? ($tz == 'Australia/Adelaide' ? 'selected' : '') : '' }}>
                                                (GMT+09:30) Adelaide
                                            </option>
                                            <option value="Australia/Darwin"
                                                {{ $tz ? ($tz == 'Australia/Darwin' ? 'selected' : '') : '' }}>
                                                (GMT+09:30)
                                                Darwin
                                            </option>
                                            <option value="Australia/Brisbane"
                                                {{ $tz ? ($tz == 'Australia/Brisbane' ? 'selected' : '') : '' }}>
                                                (GMT+10:00) Brisbane
                                            </option>
                                            <option value="Australia/Canberra"
                                                {{ $tz ? ($tz == 'Australia/Canberra' ? 'selected' : '') : '' }}>
                                                (GMT+10:00) Canberra, Melbourne, Sydney
                                            </option>
                                            <option value="Australia/Hobart"
                                                {{ $tz ? ($tz == 'Australia/Hobart' ? 'selected' : '') : '' }}>
                                                (GMT+10:00)
                                                Hobart
                                            </option>
                                            <option value="Pacific/Guam"
                                                {{ $tz ? ($tz == 'Pacific/Guam' ? 'selected' : '') : '' }}>
                                                (GMT+10:00)
                                                Guam,
                                                Port Moresby
                                            </option>
                                            <option value="Asia/Vladivostok"
                                                {{ $tz ? ($tz == 'Asia/Vladivostok' ? 'selected' : '') : '' }}>
                                                (GMT+10:00)
                                                Vladivostok
                                            </option>
                                            <option value="Asia/Magadan"
                                                {{ $tz ? ($tz == 'Asia/Magadan' ? 'selected' : '') : '' }}>
                                                (GMT+11:00)
                                                Magadan,
                                                Solomon Is., New Caledonia
                                            </option>
                                            <option value="Pacific/Auckland"
                                                {{ $tz ? ($tz == 'Pacific/Auckland' ? 'selected' : '') : '' }}>
                                                (GMT+12:00)
                                                Auckland, Wellington
                                            </option>
                                            <option value="Pacific/Fiji"
                                                {{ $tz ? ($tz == 'Pacific/Fiji' ? 'selected' : '') : '' }}>(GMT+12:00)
                                                Fiji,
                                                Kamchatka, Marshall Is.
                                            </option>
                                            <option value="Pacific/Tongatapu"
                                                {{ $tz ? ($tz == 'Pacific/Tongatapu' ? 'selected' : '') : '' }}>
                                                (GMT+13:00) Nuku'alofa
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
                                            <option value="AF" {{ $cc ? ($cc == 'AF' ? 'selected' : '') : '' }}>
                                                Afghanistan
                                            </option>
                                            <option value="AX" {{ $cc ? ($cc == 'AX' ? 'selected' : '') : '' }}>Åland
                                                Islands
                                            </option>
                                            <option value="AL" {{ $cc ? ($cc == 'AL' ? 'selected' : '') : '' }}>
                                                Albania
                                            </option>
                                            <option value="DZ" {{ $cc ? ($cc == 'DZ' ? 'selected' : '') : '' }}>
                                                Algeria
                                            </option>
                                            <option value="AS" {{ $cc ? ($cc == 'AS' ? 'selected' : '') : '' }}>
                                                American Samoa
                                            </option>
                                            <option value="AD" {{ $cc ? ($cc == 'AD' ? 'selected' : '') : '' }}>
                                                Andorra
                                            </option>
                                            <option value="AO" {{ $cc ? ($cc == 'AO' ? 'selected' : '') : '' }}>
                                                Angola</option>
                                            <option value="AI" {{ $cc ? ($cc == 'AI' ? 'selected' : '') : '' }}>
                                                Anguilla
                                            </option>
                                            <option value="AQ" {{ $cc ? ($cc == 'AQ' ? 'selected' : '') : '' }}>
                                                Antarctica
                                            </option>
                                            <option value="AG" {{ $cc ? ($cc == 'AG' ? 'selected' : '') : '' }}>
                                                Antigua and
                                                Barbuda</option>
                                            <option value="AR" {{ $cc ? ($cc == 'AR' ? 'selected' : '') : '' }}>
                                                Argentina
                                            </option>
                                            <option value="AM" {{ $cc ? ($cc == 'AM' ? 'selected' : '') : '' }}>
                                                Armenia
                                            </option>
                                            <option value="AW" {{ $cc ? ($cc == 'AW' ? 'selected' : '') : '' }}>Aruba
                                            </option>
                                            <option value="AU" {{ $cc ? ($cc == 'AU' ? 'selected' : '') : '' }}>
                                                Australia
                                            </option>
                                            <option value="AT" {{ $cc ? ($cc == 'AT' ? 'selected' : '') : '' }}>
                                                Austria
                                            </option>
                                            <option value="AZ" {{ $cc ? ($cc == 'AZ' ? 'selected' : '') : '' }}>
                                                Azerbaijan
                                            </option>
                                            <option value="BS" {{ $cc ? ($cc == 'BS' ? 'selected' : '') : '' }}>
                                                Bahamas
                                            </option>
                                            <option value="BH" {{ $cc ? ($cc == 'BH' ? 'selected' : '') : '' }}>
                                                Bahrain
                                            </option>
                                            <option value="BD" {{ $cc ? ($cc == 'BD' ? 'selected' : '') : '' }}>
                                                Bangladesh
                                            </option>
                                            <option value="BB" {{ $cc ? ($cc == 'BB' ? 'selected' : '') : '' }}>
                                                Barbados
                                            </option>
                                            <option value="BY" {{ $cc ? ($cc == 'BY' ? 'selected' : '') : '' }}>
                                                Belarus
                                            </option>
                                            <option value="BE" {{ $cc ? ($cc == 'BE' ? 'selected' : '') : '' }}>
                                                Belgium
                                            </option>
                                            <option value="BZ" {{ $cc ? ($cc == 'BZ' ? 'selected' : '') : '' }}>
                                                Belize</option>
                                            <option value="BJ" {{ $cc ? ($cc == 'BJ' ? 'selected' : '') : '' }}>Benin
                                            </option>
                                            <option value="BM" {{ $cc ? ($cc == 'BM' ? 'selected' : '') : '' }}>
                                                Bermuda
                                            </option>
                                            <option value="BT" {{ $cc ? ($cc == 'BT' ? 'selected' : '') : '' }}>
                                                Bhutan</option>
                                            <option value="BO" {{ $cc ? ($cc == 'BO' ? 'selected' : '') : '' }}>
                                                Bolivia,
                                                Plurinational State
                                                of
                                            </option>
                                            <option value="BQ" {{ $cc ? ($cc == 'BQ' ? 'selected' : '') : '' }}>
                                                Bonaire, Sint
                                                Eustatius and
                                                Saba
                                            </option>
                                            <option value="BA" {{ $cc ? ($cc == 'BA' ? 'selected' : '') : '' }}>
                                                Bosnia and
                                                Herzegovina
                                            </option>
                                            <option value="BW" {{ $cc ? ($cc == 'BW' ? 'selected' : '') : '' }}>
                                                Botswana
                                            </option>
                                            <option value="BV" {{ $cc ? ($cc == 'BV' ? 'selected' : '') : '' }}>
                                                Bouvet Island
                                            </option>
                                            <option value="BR" {{ $cc ? ($cc == 'BR' ? 'selected' : '') : '' }}>
                                                Brazil</option>
                                            <option value="IO" {{ $cc ? ($cc == 'IO' ? 'selected' : '') : '' }}>
                                                British Indian
                                                Ocean
                                                Territory
                                            </option>
                                            <option value="BN" {{ $cc ? ($cc == 'BN' ? 'selected' : '') : '' }}>
                                                Brunei
                                                Darussalam</option>
                                            <option value="BG" {{ $cc ? ($cc == 'BG' ? 'selected' : '') : '' }}>
                                                Bulgaria
                                            </option>
                                            <option value="BF" {{ $cc ? ($cc == 'BF' ? 'selected' : '') : '' }}>
                                                Burkina Faso
                                            </option>
                                            <option value="BI" {{ $cc ? ($cc == 'BI' ? 'selected' : '') : '' }}>
                                                Burundi
                                            </option>
                                            <option value="KH" {{ $cc ? ($cc == 'KH' ? 'selected' : '') : '' }}>
                                                Cambodia
                                            </option>
                                            <option value="CM" {{ $cc ? ($cc == 'CM' ? 'selected' : '') : '' }}>
                                                Cameroon
                                            </option>
                                            <option value="CA" {{ $cc ? ($cc == 'CA' ? 'selected' : '') : '' }}>
                                                Canada</option>
                                            <option value="CV" {{ $cc ? ($cc == 'CV' ? 'selected' : '') : '' }}>Cape
                                                Verde
                                            </option>
                                            <option value="KY" {{ $cc ? ($cc == 'KY' ? 'selected' : '') : '' }}>
                                                Cayman Islands
                                            </option>
                                            <option value="CF" {{ $cc ? ($cc == 'CF' ? 'selected' : '') : '' }}>
                                                Central African
                                                Republic
                                            </option>
                                            <option value="TD" {{ $cc ? ($cc == 'TD' ? 'selected' : '') : '' }}>Chad
                                            </option>
                                            <option value="CL" {{ $cc ? ($cc == 'CL' ? 'selected' : '') : '' }}>Chile
                                            </option>
                                            <option value="CN" {{ $cc ? ($cc == 'CN' ? 'selected' : '') : '' }}>China
                                            </option>
                                            <option value="CX" {{ $cc ? ($cc == 'CX' ? 'selected' : '') : '' }}>
                                                Christmas
                                                Island</option>
                                            <option value="CC" {{ $cc ? ($cc == 'CC' ? 'selected' : '') : '' }}>Cocos
                                                (Keeling)
                                                Islands
                                            </option>
                                            <option value="CO" {{ $cc ? ($cc == 'CO' ? 'selected' : '') : '' }}>
                                                Colombia
                                            </option>
                                            <option value="KM" {{ $cc ? ($cc == 'KM' ? 'selected' : '') : '' }}>
                                                Comoros
                                            </option>
                                            <option value="CG" {{ $cc ? ($cc == 'CG' ? 'selected' : '') : '' }}>Congo
                                            </option>
                                            <option value="CD" {{ $cc ? ($cc == 'CD' ? 'selected' : '') : '' }}>
                                                Congo, the
                                                Democratic Republic
                                                of the
                                            </option>
                                            <option value="CK" {{ $cc ? ($cc == 'CK' ? 'selected' : '') : '' }}>Cook
                                                Islands
                                            </option>
                                            <option value="CR" {{ $cc ? ($cc == 'CR' ? 'selected' : '') : '' }}>Costa
                                                Rica
                                            </option>
                                            <option value="CI" {{ $cc ? ($cc == 'CI' ? 'selected' : '') : '' }}>Côte
                                                d'Ivoire
                                            </option>
                                            <option value="HR" {{ $cc ? ($cc == 'HR' ? 'selected' : '') : '' }}>
                                                Croatia
                                            </option>
                                            <option value="CU" {{ $cc ? ($cc == 'CU' ? 'selected' : '') : '' }}>Cuba
                                            </option>
                                            <option value="CW" {{ $cc ? ($cc == 'CW' ? 'selected' : '') : '' }}>
                                                Curaçao
                                            </option>
                                            <option value="CY" {{ $cc ? ($cc == 'CY' ? 'selected' : '') : '' }}>
                                                Cyprus</option>
                                            <option value="CZ" {{ $cc ? ($cc == 'CZ' ? 'selected' : '') : '' }}>Czech
                                                Republic
                                            </option>
                                            <option value="DK" {{ $cc ? ($cc == 'DK' ? 'selected' : '') : '' }}>
                                                Denmark
                                            </option>
                                            <option value="DJ" {{ $cc ? ($cc == 'DJ' ? 'selected' : '') : '' }}>
                                                Djibouti
                                            </option>
                                            <option value="DM" {{ $cc ? ($cc == 'DM' ? 'selected' : '') : '' }}>
                                                Dominica
                                            </option>
                                            <option value="DO" {{ $cc ? ($cc == 'DO' ? 'selected' : '') : '' }}>
                                                Dominican
                                                Republic</option>
                                            <option value="EC" {{ $cc ? ($cc == 'EC' ? 'selected' : '') : '' }}>
                                                Ecuador
                                            </option>
                                            <option value="EG" {{ $cc ? ($cc == 'EG' ? 'selected' : '') : '' }}>Egypt
                                            </option>
                                            <option value="SV" {{ $cc ? ($cc == 'SV' ? 'selected' : '') : '' }}>El
                                                Salvador
                                            </option>
                                            <option value="GQ" {{ $cc ? ($cc == 'GQ' ? 'selected' : '') : '' }}>
                                                Equatorial
                                                Guinea</option>
                                            <option value="ER" {{ $cc ? ($cc == 'ER' ? 'selected' : '') : '' }}>
                                                Eritrea
                                            </option>
                                            <option value="EE" {{ $cc ? ($cc == 'EE' ? 'selected' : '') : '' }}>
                                                Estonia
                                            </option>
                                            <option value="ET" {{ $cc ? ($cc == 'ET' ? 'selected' : '') : '' }}>
                                                Ethiopia
                                            </option>
                                            <option value="FK" {{ $cc ? ($cc == 'FK' ? 'selected' : '') : '' }}>
                                                Falkland
                                                Islands (Malvinas)
                                            </option>
                                            <option value="FO" {{ $cc ? ($cc == 'FO' ? 'selected' : '') : '' }}>Faroe
                                                Islands
                                            </option>
                                            <option value="FJ" {{ $cc ? ($cc == 'FJ' ? 'selected' : '') : '' }}>Fiji
                                            </option>
                                            <option value="FI" {{ $cc ? ($cc == 'FI' ? 'selected' : '') : '' }}>
                                                Finland
                                            </option>
                                            <option value="FR" {{ $cc ? ($cc == 'FR' ? 'selected' : '') : '' }}>
                                                France</option>
                                            <option value="GF" {{ $cc ? ($cc == 'GF' ? 'selected' : '') : '' }}>
                                                French Guiana
                                            </option>
                                            <option value="PF" {{ $cc ? ($cc == 'PF' ? 'selected' : '') : '' }}>
                                                French
                                                Polynesia</option>
                                            <option value="TF" {{ $cc ? ($cc == 'TF' ? 'selected' : '') : '' }}>
                                                French Southern
                                                Territories
                                            </option>
                                            <option value="GA" {{ $cc ? ($cc == 'GA' ? 'selected' : '') : '' }}>Gabon
                                            </option>
                                            <option value="GM" {{ $cc ? ($cc == 'GM' ? 'selected' : '') : '' }}>
                                                Gambia</option>
                                            <option value="GE" {{ $cc ? ($cc == 'GE' ? 'selected' : '') : '' }}>
                                                Georgia
                                            </option>
                                            <option value="DE" {{ $cc ? ($cc == 'DE' ? 'selected' : '') : '' }}>
                                                Germany
                                            </option>
                                            <option value="GH" {{ $cc ? ($cc == 'GH' ? 'selected' : '') : '' }}>Ghana
                                            </option>
                                            <option value="GI" {{ $cc ? ($cc == 'GI' ? 'selected' : '') : '' }}>
                                                Gibraltar
                                            </option>
                                            <option value="GR" {{ $cc ? ($cc == 'GR' ? 'selected' : '') : '' }}>
                                                Greece</option>
                                            <option value="GL" {{ $cc ? ($cc == 'GL' ? 'selected' : '') : '' }}>
                                                Greenland
                                            </option>
                                            <option value="GD" {{ $cc ? ($cc == 'GD' ? 'selected' : '') : '' }}>
                                                Grenada
                                            </option>
                                            <option value="GP" {{ $cc ? ($cc == 'GP' ? 'selected' : '') : '' }}>
                                                Guadeloupe
                                            </option>
                                            <option value="GU" {{ $cc ? ($cc == 'GU' ? 'selected' : '') : '' }}>Guam
                                            </option>
                                            <option value="GT" {{ $cc ? ($cc == 'GT' ? 'selected' : '') : '' }}>
                                                Guatemala
                                            </option>
                                            <option value="GG" {{ $cc ? ($cc == 'GG' ? 'selected' : '') : '' }}>
                                                Guernsey
                                            </option>
                                            <option value="GN" {{ $cc ? ($cc == 'GN' ? 'selected' : '') : '' }}>
                                                Guinea</option>
                                            <option value="GW" {{ $cc ? ($cc == 'GW' ? 'selected' : '') : '' }}>
                                                Guinea-Bissau
                                            </option>
                                            <option value="GY" {{ $cc ? ($cc == 'GY' ? 'selected' : '') : '' }}>
                                                Guyana</option>
                                            <option value="HT" {{ $cc ? ($cc == 'HT' ? 'selected' : '') : '' }}>Haiti
                                            </option>
                                            <option value="HM" {{ $cc ? ($cc == 'HM' ? 'selected' : '') : '' }}>Heard
                                                Island
                                                and McDonald
                                                Islands
                                            </option>
                                            <option value="VA" {{ $cc ? ($cc == 'VA' ? 'selected' : '') : '' }}>Holy
                                                See
                                                (Vatican City
                                                State)
                                            </option>
                                            <option value="HN" {{ $cc ? ($cc == 'HN' ? 'selected' : '') : '' }}>
                                                Honduras
                                            </option>
                                            <option value="HK" {{ $cc ? ($cc == 'HK' ? 'selected' : '') : '' }}>Hong
                                                Kong
                                            </option>
                                            <option value="HU" {{ $cc ? ($cc == 'HU' ? 'selected' : '') : '' }}>
                                                Hungary
                                            </option>
                                            <option value="IS" {{ $cc ? ($cc == 'IS' ? 'selected' : '') : '' }}>
                                                Iceland
                                            </option>
                                            <option value="IN" {{ $cc ? ($cc == 'IN' ? 'selected' : '') : '' }}>India
                                            </option>
                                            <option value="ID" {{ $cc ? ($cc == 'ID' ? 'selected' : '') : '' }}>
                                                Indonesia
                                            </option>
                                            <option value="IR" {{ $cc ? ($cc == 'IR' ? 'selected' : '') : '' }}>Iran,
                                                Islamic
                                                Republic of
                                            </option>
                                            <option value="IQ" {{ $cc ? ($cc == 'IQ' ? 'selected' : '') : '' }}>Iraq
                                            </option>
                                            <option value="IE" {{ $cc ? ($cc == 'IE' ? 'selected' : '') : '' }}>
                                                Ireland
                                            </option>
                                            <option value="IM" {{ $cc ? ($cc == 'IM' ? 'selected' : '') : '' }}>Isle
                                                of Man
                                            </option>
                                            <option value="IL" {{ $cc ? ($cc == 'IL' ? 'selected' : '') : '' }}>
                                                Israel</option>
                                            <option value="IT" {{ $cc ? ($cc == 'IT' ? 'selected' : '') : '' }}>Italy
                                            </option>
                                            <option value="JM" {{ $cc ? ($cc == 'JM' ? 'selected' : '') : '' }}>
                                                Jamaica
                                            </option>
                                            <option value="JP" {{ $cc ? ($cc == 'JP' ? 'selected' : '') : '' }}>Japan
                                            </option>
                                            <option value="JE" {{ $cc ? ($cc == 'JE' ? 'selected' : '') : '' }}>
                                                Jersey</option>
                                            <option value="JO" {{ $cc ? ($cc == 'JO' ? 'selected' : '') : '' }}>
                                                Jordan</option>
                                            <option value="KZ" {{ $cc ? ($cc == 'KZ' ? 'selected' : '') : '' }}>
                                                Kazakhstan
                                            </option>
                                            <option value="KE" {{ $cc ? ($cc == 'KE' ? 'selected' : '') : '' }}>Kenya
                                            </option>
                                            <option value="KI" {{ $cc ? ($cc == 'KI' ? 'selected' : '') : '' }}>
                                                Kiribati
                                            </option>
                                            <option value="KP" {{ $cc ? ($cc == 'KP' ? 'selected' : '') : '' }}>
                                                Korea,
                                                Democratic People's
                                                Republic of
                                            </option>
                                            <option value="KR" {{ $cc ? ($cc == 'KR' ? 'selected' : '') : '' }}>
                                                Korea, Republic
                                                of</option>
                                            <option value="KW" {{ $cc ? ($cc == 'KW' ? 'selected' : '') : '' }}>
                                                Kuwait</option>
                                            <option value="KG" {{ $cc ? ($cc == 'KG' ? 'selected' : '') : '' }}>
                                                Kyrgyzstan
                                            </option>
                                            <option value="LA" {{ $cc ? ($cc == 'LA' ? 'selected' : '') : '' }}>Lao
                                                People's
                                                Democratic
                                                Republic
                                            </option>
                                            <option value="LV" {{ $cc ? ($cc == 'LV' ? 'selected' : '') : '' }}>
                                                Latvia</option>
                                            <option value="LB" {{ $cc ? ($cc == 'LB' ? 'selected' : '') : '' }}>
                                                Lebanon
                                            </option>
                                            <option value="LS" {{ $cc ? ($cc == 'LS' ? 'selected' : '') : '' }}>
                                                Lesotho
                                            </option>
                                            <option value="LR" {{ $cc ? ($cc == 'LR' ? 'selected' : '') : '' }}>
                                                Liberia
                                            </option>
                                            <option value="LY" {{ $cc ? ($cc == 'LY' ? 'selected' : '') : '' }}>Libya
                                            </option>
                                            <option value="LI" {{ $cc ? ($cc == 'LI' ? 'selected' : '') : '' }}>
                                                Liechtenstein
                                            </option>
                                            <option value="LT" {{ $cc ? ($cc == 'LT' ? 'selected' : '') : '' }}>
                                                Lithuania
                                            </option>
                                            <option value="LU" {{ $cc ? ($cc == 'LU' ? 'selected' : '') : '' }}>
                                                Luxembourg
                                            </option>
                                            <option value="MO" {{ $cc ? ($cc == 'MO' ? 'selected' : '') : '' }}>Macao
                                            </option>
                                            <option value="MK" {{ $cc ? ($cc == 'MK' ? 'selected' : '') : '' }}>
                                                Macedonia, the
                                                former Yugoslav
                                                Republic of
                                            </option>
                                            <option value="MG" {{ $cc ? ($cc == 'MG' ? 'selected' : '') : '' }}>
                                                Madagascar
                                            </option>
                                            <option value="MW" {{ $cc ? ($cc == 'MW' ? 'selected' : '') : '' }}>
                                                Malawi</option>
                                            <option value="MY" {{ $cc ? ($cc == 'MY' ? 'selected' : '') : '' }}>
                                                Malaysia
                                            </option>
                                            <option value="MV" {{ $cc ? ($cc == 'MV' ? 'selected' : '') : '' }}>
                                                Maldives
                                            </option>
                                            <option value="ML" {{ $cc ? ($cc == 'ML' ? 'selected' : '') : '' }}>Mali
                                            </option>
                                            <option value="MT" {{ $cc ? ($cc == 'MT' ? 'selected' : '') : '' }}>Malta
                                            </option>
                                            <option value="MH" {{ $cc ? ($cc == 'MH' ? 'selected' : '') : '' }}>
                                                Marshall
                                                Islands</option>
                                            <option value="MQ" {{ $cc ? ($cc == 'MQ' ? 'selected' : '') : '' }}>
                                                Martinique
                                            </option>
                                            <option value="MR" {{ $cc ? ($cc == 'MR' ? 'selected' : '') : '' }}>
                                                Mauritania
                                            </option>
                                            <option value="MU" {{ $cc ? ($cc == 'MU' ? 'selected' : '') : '' }}>
                                                Mauritius
                                            </option>
                                            <option value="YT" {{ $cc ? ($cc == 'YT' ? 'selected' : '') : '' }}>
                                                Mayotte
                                            </option>
                                            <option value="MX" {{ $cc ? ($cc == 'MX' ? 'selected' : '') : '' }}>
                                                Mexico</option>
                                            <option value="FM" {{ $cc ? ($cc == 'FM' ? 'selected' : '') : '' }}>
                                                Micronesia,
                                                Federated States
                                                of
                                            </option>
                                            <option value="MD" {{ $cc ? ($cc == 'MD' ? 'selected' : '') : '' }}>
                                                Moldova,
                                                Republic of</option>
                                            <option value="MC" {{ $cc ? ($cc == 'MC' ? 'selected' : '') : '' }}>
                                                Monaco</option>
                                            <option value="MN" {{ $cc ? ($cc == 'MN' ? 'selected' : '') : '' }}>
                                                Mongolia
                                            </option>
                                            <option value="ME" {{ $cc ? ($cc == 'ME' ? 'selected' : '') : '' }}>
                                                Montenegro
                                            </option>
                                            <option value="MS" {{ $cc ? ($cc == 'MS' ? 'selected' : '') : '' }}>
                                                Montserrat
                                            </option>
                                            <option value="MA" {{ $cc ? ($cc == 'MA' ? 'selected' : '') : '' }}>
                                                Morocco
                                            </option>
                                            <option value="MZ" {{ $cc ? ($cc == 'MZ' ? 'selected' : '') : '' }}>
                                                Mozambique
                                            </option>
                                            <option value="MM" {{ $cc ? ($cc == 'MM' ? 'selected' : '') : '' }}>
                                                Myanmar
                                            </option>
                                            <option value="NA" {{ $cc ? ($cc == 'NA' ? 'selected' : '') : '' }}>
                                                Namibia
                                            </option>
                                            <option value="NR" {{ $cc ? ($cc == 'NR' ? 'selected' : '') : '' }}>Nauru
                                            </option>
                                            <option value="NP" {{ $cc ? ($cc == 'NP' ? 'selected' : '') : '' }}>Nepal
                                            </option>
                                            <option value="NL" {{ $cc ? ($cc == 'NL' ? 'selected' : '') : '' }}>
                                                Netherlands
                                            </option>
                                            <option value="NC" {{ $cc ? ($cc == 'NC' ? 'selected' : '') : '' }}>New
                                                Caledonia
                                            </option>
                                            <option value="NZ" {{ $cc ? ($cc == 'NZ' ? 'selected' : '') : '' }}>New
                                                Zealand
                                            </option>
                                            <option value="NI" {{ $cc ? ($cc == 'NI' ? 'selected' : '') : '' }}>
                                                Nicaragua
                                            </option>
                                            <option value="NE" {{ $cc ? ($cc == 'NE' ? 'selected' : '') : '' }}>Niger
                                            </option>
                                            <option value="NG" {{ $cc ? ($cc == 'NG' ? 'selected' : '') : '' }}>
                                                Nigeria
                                            </option>
                                            <option value="NU" {{ $cc ? ($cc == 'NU' ? 'selected' : '') : '' }}>Niue
                                            </option>
                                            <option value="NF" {{ $cc ? ($cc == 'NF' ? 'selected' : '') : '' }}>
                                                Norfolk Island
                                            </option>
                                            <option value="MP" {{ $cc ? ($cc == 'MP' ? 'selected' : '') : '' }}>
                                                Northern
                                                Mariana Islands
                                            </option>
                                            <option value="NO" {{ $cc ? ($cc == 'NO' ? 'selected' : '') : '' }}>
                                                Norway</option>
                                            <option value="OM" {{ $cc ? ($cc == 'OM' ? 'selected' : '') : '' }}>Oman
                                            </option>
                                            <option value="PK" {{ $cc ? ($cc == 'PK' ? 'selected' : '') : '' }}>
                                                Pakistan
                                            </option>
                                            <option value="PW" {{ $cc ? ($cc == 'PW' ? 'selected' : '') : '' }}>Palau
                                            </option>
                                            <option value="PS" {{ $cc ? ($cc == 'PS' ? 'selected' : '') : '' }}>
                                                Palestinian
                                                Territory,
                                                Occupied
                                            </option>
                                            <option value="PA" {{ $cc ? ($cc == 'PA' ? 'selected' : '') : '' }}>
                                                Panama</option>
                                            <option value="PG" {{ $cc ? ($cc == 'PG' ? 'selected' : '') : '' }}>Papua
                                                New
                                                Guinea</option>
                                            <option value="PY" {{ $cc ? ($cc == 'PY' ? 'selected' : '') : '' }}>
                                                Paraguay
                                            </option>
                                            <option value="PE" {{ $cc ? ($cc == 'PE' ? 'selected' : '') : '' }}>Peru
                                            </option>
                                            <option value="PH" {{ $cc ? ($cc == 'PH' ? 'selected' : '') : '' }}>
                                                Philippines
                                            </option>
                                            <option value="PN" {{ $cc ? ($cc == 'PN' ? 'selected' : '') : '' }}>
                                                Pitcairn
                                            </option>
                                            <option value="PL" {{ $cc ? ($cc == 'PL' ? 'selected' : '') : '' }}>
                                                Poland</option>
                                            <option value="PT" {{ $cc ? ($cc == 'PT' ? 'selected' : '') : '' }}>
                                                Portugal
                                            </option>
                                            <option value="PR" {{ $cc ? ($cc == 'PR' ? 'selected' : '') : '' }}>
                                                Puerto Rico
                                            </option>
                                            <option value="QA" {{ $cc ? ($cc == 'QA' ? 'selected' : '') : '' }}>Qatar
                                            </option>
                                            <option value="RE" {{ $cc ? ($cc == 'RE' ? 'selected' : '') : '' }}>
                                                Réunion
                                            </option>
                                            <option value="RO" {{ $cc ? ($cc == 'RO' ? 'selected' : '') : '' }}>
                                                Romania
                                            </option>
                                            <option value="RU" {{ $cc ? ($cc == 'RU' ? 'selected' : '') : '' }}>
                                                Russian
                                                Federation</option>
                                            <option value="RW" {{ $cc ? ($cc == 'RW' ? 'selected' : '') : '' }}>
                                                Rwanda</option>
                                            <option value="BL" {{ $cc ? ($cc == 'BL' ? 'selected' : '') : '' }}>Saint
                                                Barthélemy</option>
                                            <option value="SH" {{ $cc ? ($cc == 'SH' ? 'selected' : '') : '' }}>Saint
                                                Helena,
                                                Ascension and
                                                Tristan da Cunha
                                            </option>
                                            <option value="KN" {{ $cc ? ($cc == 'KN' ? 'selected' : '') : '' }}>Saint
                                                Kitts and
                                                Nevis</option>
                                            <option value="LC" {{ $cc ? ($cc == 'LC' ? 'selected' : '') : '' }}>Saint
                                                Lucia
                                            </option>
                                            <option value="MF" {{ $cc ? ($cc == 'MF' ? 'selected' : '') : '' }}>Saint
                                                Martin
                                                (French part)
                                            </option>
                                            <option value="PM" {{ $cc ? ($cc == 'PM' ? 'selected' : '') : '' }}>Saint
                                                Pierre
                                                and Miquelon
                                            </option>
                                            <option value="VC" {{ $cc ? ($cc == 'VC' ? 'selected' : '') : '' }}>Saint
                                                Vincent
                                                and the
                                                Grenadines
                                            </option>
                                            <option value="WS" {{ $cc ? ($cc == 'WS' ? 'selected' : '') : '' }}>Samoa
                                            </option>
                                            <option value="SM" {{ $cc ? ($cc == 'SM' ? 'selected' : '') : '' }}>San
                                                Marino
                                            </option>
                                            <option value="ST" {{ $cc ? ($cc == 'ST' ? 'selected' : '') : '' }}>Sao
                                                Tome and
                                                Principe</option>
                                            <option value="SA" {{ $cc ? ($cc == 'SA' ? 'selected' : '') : '' }}>Saudi
                                                Arabia
                                            </option>
                                            <option value="SN" {{ $cc ? ($cc == 'SN' ? 'selected' : '') : '' }}>
                                                Senegal
                                            </option>
                                            <option value="RS" {{ $cc ? ($cc == 'RS' ? 'selected' : '') : '' }}>
                                                Serbia</option>
                                            <option value="SC" {{ $cc ? ($cc == 'SC' ? 'selected' : '') : '' }}>
                                                Seychelles
                                            </option>
                                            <option value="SL" {{ $cc ? ($cc == 'SL' ? 'selected' : '') : '' }}>
                                                Sierra Leone
                                            </option>
                                            <option value="SG" {{ $cc ? ($cc == 'SG' ? 'selected' : '') : '' }}>
                                                Singapore
                                            </option>
                                            <option value="SX" {{ $cc ? ($cc == 'SX' ? 'selected' : '') : '' }}>Sint
                                                Maarten
                                                (Dutch part)
                                            </option>
                                            <option value="SK" {{ $cc ? ($cc == 'SK' ? 'selected' : '') : '' }}>
                                                Slovakia
                                            </option>
                                            <option value="SI" {{ $cc ? ($cc == 'SI' ? 'selected' : '') : '' }}>
                                                Slovenia
                                            </option>
                                            <option value="SB" {{ $cc ? ($cc == 'SB' ? 'selected' : '') : '' }}>
                                                Solomon Islands
                                            </option>
                                            <option value="SO" {{ $cc ? ($cc == 'SO' ? 'selected' : '') : '' }}>
                                                Somalia
                                            </option>
                                            <option value="ZA" {{ $cc ? ($cc == 'ZA' ? 'selected' : '') : '' }}>South
                                                Africa
                                            </option>
                                            <option value="GS" {{ $cc ? ($cc == 'GS' ? 'selected' : '') : '' }}>South
                                                Georgia
                                                and the South
                                                Sandwich Islands
                                            </option>
                                            <option value="SS" {{ $cc ? ($cc == 'SS' ? 'selected' : '') : '' }}>South
                                                Sudan
                                            </option>
                                            <option value="ES" {{ $cc ? ($cc == 'ES' ? 'selected' : '') : '' }}>Spain
                                            </option>
                                            <option value="LK" {{ $cc ? ($cc == 'LK' ? 'selected' : '') : '' }}>Sri
                                                Lanka
                                            </option>
                                            <option value="SD" {{ $cc ? ($cc == 'SD' ? 'selected' : '') : '' }}>Sudan
                                            </option>
                                            <option value="SR" {{ $cc ? ($cc == 'SR' ? 'selected' : '') : '' }}>
                                                Suriname
                                            </option>
                                            <option value="SJ" {{ $cc ? ($cc == 'SJ' ? 'selected' : '') : '' }}>
                                                Svalbard and
                                                Jan Mayen
                                            </option>
                                            <option value="SZ" {{ $cc ? ($cc == 'SZ' ? 'selected' : '') : '' }}>
                                                Swaziland
                                            </option>
                                            <option value="SE" {{ $cc ? ($cc == 'SE' ? 'selected' : '') : '' }}>
                                                Sweden</option>
                                            <option value="CH" {{ $cc ? ($cc == 'CH' ? 'selected' : '') : '' }}>
                                                Switzerland
                                            </option>
                                            <option value="SY" {{ $cc ? ($cc == 'SY' ? 'selected' : '') : '' }}>
                                                Syrian Arab
                                                Republic</option>
                                            <option value="TW" {{ $cc ? ($cc == 'TW' ? 'selected' : '') : '' }}>
                                                Taiwan,
                                                Province of China
                                            </option>
                                            <option value="TJ" {{ $cc ? ($cc == 'TJ' ? 'selected' : '') : '' }}>
                                                Tajikistan
                                            </option>
                                            <option value="TZ" {{ $cc ? ($cc == 'TZ' ? 'selected' : '') : '' }}>
                                                Tanzania,
                                                United Republic of
                                            </option>
                                            <option value="TH" {{ $cc ? ($cc == 'TH' ? 'selected' : '') : '' }}>
                                                Thailand
                                            </option>
                                            <option value="TL" {{ $cc ? ($cc == 'TL' ? 'selected' : '') : '' }}>
                                                Timor-Leste
                                            </option>
                                            <option value="TG" {{ $cc ? ($cc == 'TG' ? 'selected' : '') : '' }}>Togo
                                            </option>
                                            <option value="TK" {{ $cc ? ($cc == 'TK' ? 'selected' : '') : '' }}>
                                                Tokelau
                                            </option>
                                            <option value="TO" {{ $cc ? ($cc == 'TO' ? 'selected' : '') : '' }}>Tonga
                                            </option>
                                            <option value="TT" {{ $cc ? ($cc == 'TT' ? 'selected' : '') : '' }}>
                                                Trinidad and
                                                Tobago</option>
                                            <option value="TN" {{ $cc ? ($cc == 'TN' ? 'selected' : '') : '' }}>
                                                Tunisia
                                            </option>
                                            <option value="TR" {{ $cc ? ($cc == 'TR' ? 'selected' : '') : '' }}>
                                                Turkey</option>
                                            <option value="TM" {{ $cc ? ($cc == 'TM' ? 'selected' : '') : '' }}>
                                                Turkmenistan
                                            </option>
                                            <option value="TC" {{ $cc ? ($cc == 'TC' ? 'selected' : '') : '' }}>Turks
                                                and
                                                Caicos Islands
                                            </option>
                                            <option value="TV" {{ $cc ? ($cc == 'TV' ? 'selected' : '') : '' }}>
                                                Tuvalu</option>
                                            <option value="UG" {{ $cc ? ($cc == 'UG' ? 'selected' : '') : '' }}>
                                                Uganda</option>
                                            <option value="UA" {{ $cc ? ($cc == 'UA' ? 'selected' : '') : '' }}>
                                                Ukraine
                                            </option>
                                            <option value="AE" {{ $cc ? ($cc == 'AE' ? 'selected' : '') : '' }}>
                                                United Arab
                                                Emirates</option>
                                            <option value="GB" {{ $cc ? ($cc == 'GB' ? 'selected' : '') : '' }}>
                                                United Kingdom
                                            </option>
                                            <option value="US" {{ $cc ? ($cc == 'US' ? 'selected' : '') : '' }}>
                                                United States
                                            </option>
                                            <option value="UM" {{ $cc ? ($cc == 'UM' ? 'selected' : '') : '' }}>
                                                United States
                                                Minor Outlying
                                                Islands
                                            </option>
                                            <option value="UY" {{ $cc ? ($cc == 'UY' ? 'selected' : '') : '' }}>
                                                Uruguay
                                            </option>
                                            <option value="UZ" {{ $cc ? ($cc == 'UZ' ? 'selected' : '') : '' }}>
                                                Uzbekistan
                                            </option>
                                            <option value="VU" {{ $cc ? ($cc == 'VU' ? 'selected' : '') : '' }}>
                                                Vanuatu
                                            </option>
                                            <option value="VE" {{ $cc ? ($cc == 'VE' ? 'selected' : '') : '' }}>
                                                Venezuela,
                                                Bolivarian Republic
                                                of
                                            </option>
                                            <option value="VN" {{ $cc ? ($cc == 'VN' ? 'selected' : '') : '' }}>Viet
                                                Nam
                                            </option>
                                            <option value="VG" {{ $cc ? ($cc == 'VG' ? 'selected' : '') : '' }}>
                                                Virgin Islands,
                                                British
                                            </option>
                                            <option value="VI" {{ $cc ? ($cc == 'VI' ? 'selected' : '') : '' }}>
                                                Virgin Islands,
                                                U.S.</option>
                                            <option value="WF" {{ $cc ? ($cc == 'WF' ? 'selected' : '') : '' }}>
                                                Wallis and
                                                Futuna</option>
                                            <option value="EH" {{ $cc ? ($cc == 'EH' ? 'selected' : '') : '' }}>
                                                Western Sahara
                                            </option>
                                            <option value="YE" {{ $cc ? ($cc == 'YE' ? 'selected' : '') : '' }}>Yemen
                                            </option>
                                            <option value="ZM" {{ $cc ? ($cc == 'ZM' ? 'selected' : '') : '' }}>
                                                Zambia</option>
                                            <option value="ZW" {{ $cc ? ($cc == 'ZW' ? 'selected' : '') : '' }}>
                                                Zimbabwe
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
                                                        src="{{ \App\CPU\Helpers::get_business_settings('company_web_logo') ? \App\CPU\Helpers::get_business_settings('logo') : '' }}"
                                                        alt="Web Logo" class="img-fluid mb-3" style="max-height: 200px;">
                                                    <label for="webLogoUpload" class="btn btn-primary">Choose
                                                        File</label>
                                                    <input type="file" id="webLogoUpload" name="web_logo"
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
                                                        src="{{ \App\CPU\Helpers::get_business_settings('company_mobile_logo') ? \App\CPU\Helpers::get_business_settings('logo') : '' }}"
                                                        alt="Mobile Logo" class="img-fluid mb-3"
                                                        style="max-height: 200px;">
                                                    <label for="customFileUpload2" class="btn btn-primary">Choose
                                                        File</label>
                                                    <input type="file" id="customFileUpload2" name="mobile_logo"
                                                        class="d-none"
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
                                                        src="{{ \App\CPU\Helpers::get_business_settings('web_footer_logo') ? \App\CPU\Helpers::get_business_settings('web_footer_logo') : '' }}"
                                                        alt="Web Footer Logo" class="img-fluid mb-3"
                                                        style="max-height: 200px;">
                                                    <label for="customFileUpload3" class="btn btn-primary">Choose
                                                        File</label>
                                                    <input type="file" id="customFileUpload3" name="web_footer_logo"
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
                                                <h5 class="card-title mb-0">Web Favicon</h5>
                                                <span class="text-danger">(Ratio 1:1)</span>
                                            </div>
                                            <div class="card-body">
                                                <div class="d-flex flex-column align-items-center">
                                                    <img id="viewer4"
                                                        src="{{ \App\CPU\Helpers::get_business_settings('web_favicon') ? \App\CPU\Helpers::get_business_settings('web_favicon') : '' }}"
                                                        alt="Web Favicon" class="img-fluid mb-3"
                                                        style="max-height: 200px;">
                                                    <label for="customFileUpload4" class="btn btn-primary">Choose
                                                        File</label>
                                                    <input type="file" id="customFileUpload4" name="web_favicon"
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
                                                        class="d-none"
                                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary w-100 d-block">Update
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
