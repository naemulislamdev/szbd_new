<div class="col-sm-6 col-lg-3 mb-3 ">
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

<div class="col-sm-6 col-lg-3 mb-3 ">
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

<div class="col-sm-6 col-lg-3 mb-3 ">
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

<div class="col-sm-6 col-lg-3 mb-3 ">
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
{{-- second row --}}
<div class="col-sm-6 col-lg-3 mb-3 ">
    <!-- Card -->
    <a style="background: #1F6F5F;;" class="card  card-hover-shadow h-100"
        href="{{ route('admin.order.list', ['status' => 'delivered']) }}">
        <div class="card-body pb-0">
            <div class="flex-between align-items-center gx-2">
                <div style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                    <h6 class="card-subtitle" style="color: #ffffff!important;">
                        Delivered</h6>
                    <span class="card-title h2" style="color: #ffffff!important;">
                        {{ $data['delivered'] }}
                    </span>
                </div>

                <div class="mt-2">
                    <i class="las la-cart-arrow-down border rounded-circle p-2"
                        style="font-size: 30px;color: #ffffff"></i>
                </div>
            </div>
            <!-- End Row -->
        </div>
    </a>
    <!-- End Card -->
</div>
<div class="col-sm-6 col-lg-3 mb-3 ">
    <!-- Card -->
    <a style="background: #CE2626;" class="card  card-hover-shadow h-100"
        href="{{ route('admin.order.list', ['status' => 'canceled']) }}">
        <div class="card-body pb-0">
            <div class="flex-between align-items-center gx-2">
                <div style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                    <h6 class="card-subtitle" style="color: #ffffff!important;">
                        Cancelled</h6>
                    <span class="card-title h2" style="color: #ffffff!important;">
                        {{ $data['out_for_delivery'] }}
                    </span>
                </div>

                <div class="mt-2">
                    <i class="las la-trash-alt border rounded-circle p-2" style="font-size: 30px;color: #ffffff"></i>
                </div>
            </div>
            <!-- End Row -->
        </div>
    </a>
    <!-- End Card -->
</div>
<div class="col-sm-6 col-lg-3 mb-3 ">
    <!-- Card -->
    <a style="background: #5E0006;" class="card  card-hover-shadow h-100"
        href="{{ route('admin.order.list', ['status' => 'returned']) }}">
        <div class="card-body pb-0">
            <div class="flex-between align-items-center gx-2">
                <div style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                    <h6 class="card-subtitle" style="color: #ffffff!important;">
                        Returned</h6>
                    <span class="card-title h2" style="color: #ffffff!important;">
                        {{ $data['out_for_delivery'] }}
                    </span>
                </div>

                <div class="mt-2">
                    <i class="las la-undo border rounded-circle p-2" style="font-size: 30px;color: #ffffff"></i>
                </div>
            </div>
            <!-- End Row -->
        </div>
    </a>
    <!-- End Card -->
</div>
<div class="col-sm-6 col-lg-3 mb-3 ">
    <!-- Card -->
    <a style="background: #3E2C23;" class="card  card-hover-shadow h-100"
        href="{{ route('admin.order.list', ['status' => 'failed']) }}">
        <div class="card-body pb-0">
            <div class="flex-between align-items-center gx-2">
                <div style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                    <h6 class="card-subtitle" style="color: #ffffff!important;">
                        Failed</h6>
                    <span class="card-title h2" style="color: #ffffff!important;">
                        {{ $data['out_for_delivery'] }}
                    </span>
                </div>

                <div class="mt-2">
                    <i class="iconoir-message-alert border rounded-circle p-2"
                        style="font-size: 30px;color: #ffffff"></i>
                </div>
            </div>
            <!-- End Row -->
        </div>
    </a>
    <!-- End Card -->
</div>
