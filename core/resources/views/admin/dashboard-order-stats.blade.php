<div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
    <!-- Card -->
    <a class="card card-hover-shadow h-100" href="{{ route('admin.order.list', ['status' => 'pending']) }}"
        style="color: #FFFFFF; background: #2FA4D7;">
        <div class="card-body pb-0">
            <div class="flex-between align-items-center mb-1">
                <div style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                    <h6 class="card-subtitle" style="color: #ffffff!important;">Pending</h6>
                    <span class="card-title h2" style="color: #ffffff!important;">
                        {{ $data['pending'] }}
                    </span>
                </div>
                <div class="mt-2">
                    <i class="iconoir-cart border rounded-circle p-2" style="font-size: 30px;color: #ffffff;"></i>
                </div>
            </div>
            <!-- End Row -->
        </div>
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
    <!-- Card -->
    <a style="background: #2F6B3F;" class="card card-hover-shadow h-100"
        href="{{ route('admin.order.list', ['status' => 'confirmed']) }}">
        <div class="card-body pb-0">
            <div class="flex-between align-items-center">
                <div style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                    <h6 class="card-subtitle" style="color: #ffffff!important;">Confirmed
                    </h6>
                    <span class="card-title h2" style="color: #ffffff!important;">
                        {{ $data['confirmed'] }}
                    </span>
                </div>
                <div class="mt-2">
                    <i class="las la-check-circle border rounded-circle p-2" style="font-size: 30px;color: #ffffff"></i>
                </div>
            </div>
            <!-- End Row -->
        </div>
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
    <!-- Card -->
    <a style="background: #E76F2E;" class="card card-hover-shadow h-100"
        href="{{ route('admin.order.list', ['status' => 'processing']) }}">
        <div class="card-body pb-0">
            <div class="flex-between align-items-center gx-2">
                <div style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                    <h6 class="card-subtitle" style="color: #ffffff!important;">Processing
                    </h6>
                    <span class="card-title h2" style="color: #ffffff!important;">
                        {{ $data['processing'] }}
                    </span>
                </div>

                <div class="mt-2">
                    <i class="las la-recycle border rounded-circle p-2" style="font-size: 30px;color: #ffffff"></i>
                </div>
            </div>
            <!-- End Row -->
        </div>
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
    <!-- Card -->
    <a style="background: #003049;" class="card  card-hover-shadow h-100"
        href="{{ route('admin.order.list', ['status' => 'out_for_delivery']) }}">
        <div class="card-body pb-0">
            <div class="flex-between align-items-center gx-2">
                <div style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                    <h6 class="card-subtitle" style="color: #ffffff!important;">
                        Out for Delivery</h6>
                    <span class="card-title h2" style="color: #ffffff!important;">
                        {{ $data['out_for_delivery'] }}
                    </span>
                </div>

                <div class="mt-2">
                    <i class="iconoir-delivery-truck border rounded-circle p-2"
                        style="font-size: 30px;color: #ffffff"></i>
                </div>
            </div>
            <!-- End Row -->
        </div>
    </a>
    <!-- End Card -->
</div>

<div class="col-12 ">
    <div class="card card-body p-0" style="background: #FFFFFF!important;">
        <div class="row">
            <div class="col-sm-6 col-lg-3 p-4 text-white" style="background: #346739; border-radius: 10px 0 0 10px;">
                <div class="media d-flex justify-content-between align-items-center" style="cursor: pointer"
                    onclick="location.href='{{ route('admin.order.list', ['status' => 'delivered']) }}'">
                    <div class="media-body"
                        style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                        <h6 class="card-subtitle">Delivered</h6>
                        <span class="card-title h3">{{ $data['delivered'] }}</span>
                    </div>
                    <div>
                        <span class="icon icon-sm icon-soft-secondary rounded-circle p-1 border">
                            <i class="las la-cart-arrow-down fs-4"></i>
                        </span>
                    </div>
                </div>
                <div class="d-lg-none">
                    <hr>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 border-right p-4 text-white" style="background: #CE2626;">
                <div class="media d-flex justify-content-between align-items-center" style="cursor: pointer"
                    onclick="location.href='{{ route('admin.order.list', ['status' => 'canceled']) }}'">
                    <div class="media-body"
                        style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                        <h6 class="card-subtitle">Canceled</h6>
                        <span class="card-title h3">{{ $data['canceled'] }}</span>
                    </div>
                    <span class="icon icon-sm icon-soft-secondary rounded-circle p-1 border">
                        <i class="las la-trash-alt fs-4"></i>
                    </span>
                </div>
                <div class="d-lg-none">
                    <hr>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 column-divider-lg p-4 text-white" style="background: #5E0006;">
                <div class="media d-flex justify-content-between align-items-center" style="cursor: pointer"
                    onclick="location.href='{{ route('admin.order.list', ['status' => 'returned']) }}'">
                    <div class="media-body"
                        style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                        <h6 class="card-subtitle">Returned</h6>
                        <span class="card-title h3">{{ $data['returned'] }}</span>
                    </div>
                    <span class="icon icon-sm icon-soft-secondary rounded-circle p-1 border">
                        <i class="las la-undo fs-4"></i>
                    </span>
                </div>
                <div class="d-lg-none">
                    <hr>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3 column-divider-sm p-4 overflow-hidden"
                style="border-radius: 0 8px 8px 0; background: #3E2C23; color: #fff;">
                <div class="media d-flex  align-items-center justify-content-between" style="cursor: pointer"
                    onclick="location.href='{{ route('admin.order.list', ['status' => 'failed']) }}'">
                    <div class="media-body"
                        style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                        <h6 class="card-subtitle">Failed</h6>
                        <span class="card-title h3">{{ $data['failed'] }}</span>
                    </div>
                    <span class="icon icon-sm icon-soft-secondary rounded-circle p-1 border">
                        <i class="iconoir-message-alert fs-4"></i>
                    </span>
                </div>
                <div class="d-lg-none">
                    <hr>
                </div>
            </div>
        </div>
    </div>
</div>
