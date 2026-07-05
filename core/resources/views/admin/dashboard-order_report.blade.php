<style>
    /* Premium Card Style */
    .order-card {
        border-radius: 16px !important;
        padding: 18px !important;
        border: none !important;
        transition: all 0.25s ease-in-out;
        color: #ffffff !important;
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.08);
        text-decoration: none;
        display: block;
    }

    .order-card:hover {
        transform: translateY(-3px);
        box-shadow: 0px 12px 26px rgba(0, 0, 0, 0.15);
    }

    /* Title */
    .order-card-title {
        font-size: 15px;
        font-weight: 600;
        letter-spacing: .3px;
        color: #ffffff !important;
        margin-bottom: 8px;
    }

    /* Quantity + Amount Text */
    .order-card-details {
        font-size: 16px;
        line-height: 22px;
        margin-top: 6px;
        font-weight: 500;
        color: #ffffff !important;
    }

    /* Background Colors (soft gradient look) */
    .bg-all-order {
        background: linear-gradient(135deg, #1A3263, #547792) !important;
    }

    .bg-pending {
        background: linear-gradient(135deg, #F77F00, #FCBF49) !important;
    }

    .bg-confirmed {
        background: linear-gradient(135deg, #285A48, #408A71) !important;
    }

    .bg-canceled {
        background: linear-gradient(135deg, #9B0F06, #fb7185) !important;
    }
</style>

<div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
    <!-- Card -->
    <a class="card order-card bg-all-order h-100" href="{{ route('admin.order.list') }}" style="color: #FFFFFF">
        <div class="card-body p-0">
            <h6 class="order-card-title">All Order</h6>
            <div class="order-card-details">
                Quantity: {{ $results->total_orders ?? 0 }} <br>
                Amount: {{ $results->total_amount ?? 0 }}
            </div>
        </div>
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
    <!-- Card -->
    <a class="card order-card bg-pending h-100" href="{{ route('admin.order.list', ['status' => 'pending']) }}"
        style="color: #FFFFFF">
        <div class="card-body p-0">
            <h6 class="order-card-title"> Pending </h6>
            <div class="order-card-details">
                Quantity: {{ $results->pending_qty ?? 0 }} <br>
                Amount: {{ $results->pending_amount ?? 0 }}
            </div>
        </div>
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
    <!-- Card -->
    <a class="card order-card bg-confirmed h-100" href="{{ route('admin.order.list', ['status' => 'confirmed']) }}"
        style="color: #FFFFFF">
        <div class="card-body p-0">
            <h6 class="order-card-title">Confirmed</h6>
            <div class="order-card-details">
                Quantity: {{ $results->confirmed_qty ?? 0 }} <br>
                Amount: {{ $results->confirmed_amount ?? 0 }}
            </div>
        </div>
    </a>
    <!-- End Card -->
</div>

<div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
    <!-- Card -->
    <a class="card order-card bg-canceled h-100" href="{{ route('admin.order.list', ['status' => 'canceled']) }}"
        style="color: #FFFFFF">
        <div class="card-body p-0">
            <h6 class="order-card-title">Canceled</h6>
            <div class="order-card-details">
                Quantity: {{ $results->canceled_qty ?? 0 }} <br>
                Amount: {{ $results->canceled_amount ?? 0 }}
            </div>
        </div>
    </a>
    <!-- End Card -->
</div>
