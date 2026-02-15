@extends('layouts.front-end.app')

@section('title', 'Track Order')
<?php
$order = \App\Model\OrderDetail::where('order_id', $orderDetails->id)->get();
?>

@push('css_or_js')
    <style>
        /* Breadcrumb */
        .breadcrumb-link {
            color: #f26d21;
            font-weight: 500;
            text-decoration: none;
            font-family: var(--jost);
        }

        .breadcrumb-link:hover {
            color: #d85e1c;
        }

        .breadcrumb-active {
            color: #f26d21;
            font-weight: 600;
        }

        /* Card Styling */
        .track-card {
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 85%;
            margin: 0 auto 30px;
            font-family: var(--jost);

        }

        .track-card-header {
            background: linear-gradient(90deg, #f26d21, #fc8c0b);
            color: #fff;
            font-weight: 600;
            font-size: 18px;
            padding: 15px 20px;
        }

        .track-card-body {
            padding: 20px 35px;
        }

        .track-info-row {
            border-bottom: 1px dashed #ddd;
            padding: 10px 0;
            font-size: 1.2rem;
        }

        .track-info-row:last-child {
            border-bottom: none;
        }

        /* Timeline */
        .timeline {
            position: relative;
        }

        .steps {
            display: flex;
            gap: 30px;
            position: relative;
        }

        .step {
            position: relative;
            flex: 1;
            text-align: center;
        }

        .step-number {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 4px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-weight: bold;
            font-size: 1.4rem;
            color: #94a3b8;
            background: white;
            transition: all 0.4s;
            position: relative;
            z-index: 2;
        }

        .step.completed .step-number {
            background: #10b981;
            border-color: #10b981;
            color: #fff;
        }

        .step.active .step-number {
            background: #3b82f6;
            border-color: #3b82f6;
            color: #fff;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.5);
            }

            70% {
                box-shadow: 0 0 0 15px rgba(59, 130, 246, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }

        .step-content {
            padding: 0;
            border-radius: 12px;
            background: #fff;
            box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
            height: 190px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            font-family: var(--jost);
            transition: all 0.3s;
            margin-top: 20px;
        }

        .step.active .step-content {
            transform: translateY(-6px);

        }



        .step.completed .step-content {
            border-color: #10b981;
            /* background: #10b981; */
            background: #fff;
        }

        .step-title {
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 6px;
            color: #0f172a;
        }

        .step-time {
            color: #64748b;
            font-size: 0.95rem;
            display: block;
            margin-bottom: 6px;
        }

        .step-description {
            color: #475569;
            font-size: 0.95rem;
        }

        /* Horizontal connector lines */
        .step:not(:last-child)::after {
            content: '';
            position: absolute;
            top: 30px;
            left: 12%;
            width: 100%;
            height: 3px;
            background: #ddd;
            z-index: 1;
            transform: translateX(50%);
        }

        .step.completed:not(:last-child)::after {
            background: #10b981;
        }

        /* Responsive Vertical Layout */
        @media (max-width: 900px) {
            .steps {
                flex-direction: column;
            }

            .step-number {
                margin: 0 0 12px;
            }



            .step:not(:last-child)::after {
                width: 3px;
                height: 121px;
                top: 60px;
                left: 30px;
                transform: translateX(0);
            }

            .step {
                display: flex;
                flex-direction: row;
            }

            .step-content {
                width: 80%;
                padding: 0 20px;
                flex-direction: row;
                justify-content: start;
                gap: 24px;
                height: 130px;
                text-align: left;
            }

            .number-div {
                width: 20%;
            }

            .track-card {

                width: 100%;
            }
        }

        .modal.show .modal-dialog {
            top: 20%;
            font-family: var(--jost);
        }
    </style>
@endpush

@section('content')




    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent px-0 mb-4">
                <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Home</a></li>
                <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Order Track</a></li>
                <li class="breadcrumb-item active breadcrumb-active" aria-current="page">Track Result</li>
            </ol>
        </nav>

        <h2 style="color: #f26d21; text-align: center; " class="mt-0">Track Result</h2>
        <!-- Order Information Card -->
        <div class="card track-card">
            <div class="card-header track-card-header">Order Information</div>
            <div class="card-body track-card-body">
                <div class="row">
                    <div class="col-md-6 track-info-row"><strong>Order Number:</strong> #{{ $orderDetails['id'] }}</div>
                    <div class="col-md-6 track-info-row"><strong>Order Date:</strong>
                        {{ \Carbon\Carbon::parse($orderDetails['created_at'])->format('d F Y : h:i A') }}
                    </div>
                    <div class="col-md-6 track-info-row">
                        <strong>Customer Name:</strong>
                        @if ($orderDetails->customer)
                            {{ $orderDetails->customer['f_name'] }}
                        @endif
                    </div>
                    <div class="col-md-6 track-info-row"><strong>Customer Phone:</strong>
                        @if ($orderDetails->customer)
                            {{ $orderDetails->customer['phone'] }}
                        @endif
                    </div>
                    <div class="col-md-6 track-info-row"><strong>Product Price:</strong>
                        {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($orderDetails->order_amount)) }}
                    </div>
                    <div class="col-md-6 track-info-row"><strong>Delivery Charge:</strong>
                        {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($orderDetails->shipping_cost)) }}
                    </div>
                    <div class="col-md-6 track-info-row"><strong>Total Amount:</strong>
                        {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($orderDetails->order_amount + $orderDetails->shipping_cost)) }}
                    </div>
                    <div class="col-md-6 track-info-row"><strong>Payment Method:</strong> Cash On Delivery</div>
                    <div class="col-md-6 track-info-row"><strong>Payment Status:</strong>
                        {{ ucfirst($orderDetails->payment_status) }}
                    </div>
                    <div class="col-md-6 track-info-row"><strong>Order Status:</strong> <span
                            style="background: #130f40; color: #fff;" class="btn btn-sm  disabled">
                            @if ($orderDetails->order_status == 'pending')
                                Placed
                            @else
                                {{ ucfirst($orderDetails->order_status) }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
            <div class="text-center pb-4">
                <button class="btn btn-info d-inline-block" data-toggle="modal" data-target="#order-details">
                    <i class="fa fa-eye" aria-hidden="true"></i> View Product
                </button>
            </div>

        </div>

        <!-- Timeline -->
        @php
            $statusSteps = ['placed', 'confirmed', 'processing', 'shipped', 'delivered'];
        @endphp

        @php
            $statusSteps = ['placed', 'confirmed', 'processing', 'shipped', 'delivered'];
            // Determine current step index
            $currentStepIndex = $orderDetails->order_status
                ? array_search($orderDetails->order_status, $statusSteps)
                : 0;
        @endphp

        <div class="mb-5">
            <div class="steps mb-4 ">
                @foreach ($statusSteps as $index => $status)
                    <div
                        class="step
        @if ($index < $currentStepIndex) completed
        @elseif($index == $currentStepIndex) active @endif">
                        <div class="number-div">
                            <div class="step-number">{{ $index + 1 }}</div>
                        </div>
                        <div class="step-content">
                            <div>
                                @if ($status == 'placed')
                                    <img style="width: 100px; height: auto;"
                                        src="{{ asset('assets/front-end/img/track-result/placed.gif') }}"
                                        alt="Order placed">
                                @elseif ($status == 'confirmed')
                                    <img style="width: 100px; height: auto;"
                                        src="{{ asset('assets/front-end/img/track-result/confirmed.gif') }}"
                                        alt="Order placed">
                                @elseif ($status == 'processing')
                                    <img style="width: 50px; height: auto;"
                                        src="{{ asset('assets/front-end/img/track-result/process.gif') }}"
                                        alt="Order placed">
                                @elseif ($status == 'shipped')
                                    <img style="width: 100px; height: auto;"
                                        src="{{ asset('assets/front-end/img/track-result/shipped.gif') }}"
                                        alt="Order placed">
                                @else
                                    <img style="width: 100px; height: auto;"
                                        src="{{ asset('assets/front-end/img/track-result/delivery.gif') }}"
                                        alt="Order placed">
                                @endif
                            </div>
                            <div>
                                <div class="step-title">Order {{ ucfirst($status) }}</div>
                                @if ($status == 'placed')
                                    <span class="step-time">{{ $orderDetails->created_at->format('d-M-Y') }}</span>
                                @elseif($status == 'processing')
                                    <span class="step-time">In Progress</span>
                                @endif
                                <p class="step-description">Status: {{ ucfirst($status) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Product info modal start --}}
        <!-- Order Details Modal -->
        <div class="modal fade" id="order-details" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ \App\CPU\translate('Order No') }} - #{{ $orderDetails['id'] }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body">

                        @php
                            $sub_total = 0;
                            $total_tax = 0;
                            $total_discount_on_product = 0;
                        @endphp

                        @foreach ($order as $product)
                            @php
                                $productDetails = App\Model\Product::find($product->product_id);
                            @endphp

                            <div class="d-flex align-items-start border-bottom pb-3 mb-3">

                                <!-- Product Image -->
                                <a style="width: 20%" href="{{ route('product', $productDetails->slug) }}" class="mr-3">
                                    <img src="{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/{{ $productDetails->thumbnail }}"
                                        onerror="this.src='{{ asset('assets/frontend/img/placeholder.jpg') }}'"
                                        style="width:80px;border-radius:6px;">
                                </a>

                                <!-- Product Info -->
                                <div style="width: 60%" class="flex-grow-1 ">
                                    <h6 class="mb-1">
                                        <a href="{{ route('product', $productDetails->slug) }}">
                                            {{ $productDetails['name'] }}
                                        </a>
                                    </h6>

                                    {{-- Variations --}}
                                    @if ($product->variation)
                                        @foreach (json_decode($product->variation, true) as $key => $value)
                                            <small class="text-muted d-block">
                                                {{ $key }} :
                                                {{ is_array($value) ? implode(', ', $value) : $value }}
                                            </small>
                                        @endforeach
                                    @endif

                                    <div class="text-primary font-weight-bold mt-1">
                                        ৳ {{ \App\CPU\Helpers::currency_converter($product->price) }}
                                    </div>
                                </div>

                                <!-- Qty / Tax / Subtotal -->
                                <div style="width: 20%" class="text-right">
                                    <small class="d-block text-muted">
                                        Quantity: {{ $product->qty }}
                                    </small>
                                    <small class="d-block text-muted">
                                        Tax: {{ \App\CPU\Helpers::currency_converter($product->tax) }}
                                    </small>
                                    <strong>
                                        ৳ {{ \App\CPU\Helpers::currency_converter($product->price * $product->qty) }}
                                    </strong>
                                </div>
                            </div>

                            @php
                                $sub_total += $product->price * $product->qty;
                                $total_tax += $product->tax;
                                $total_discount_on_product += $product->discount;
                            @endphp
                        @endforeach
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer justify-content-between">

                        <div>
                            <small class="text-muted d-block">
                                Subtotal: ৳ {{ \App\CPU\Helpers::currency_converter($sub_total) }}
                            </small>
                            <small class="text-muted d-block">
                                Shipping: ৳{{ \App\CPU\Helpers::currency_converter($orderDetails->shipping_cost) }}
                            </small>
                            <small class="text-muted d-block">
                                Tax: {{ \App\CPU\Helpers::currency_converter($total_tax) }}
                            </small>
                            <small class="text-muted d-block">
                                Discount: -{{ \App\CPU\Helpers::currency_converter($total_discount_on_product) }}
                            </small>
                        </div>

                        <div class="text-right">
                            <h5 class="mb-0">
                                {{ \App\CPU\translate('Total') }}:
                                <span class="text-primary">
                                    ৳
                                    {{ \App\CPU\Helpers::currency_converter(
                                        $sub_total + $total_tax + $orderDetails->shipping_cost - $total_discount_on_product - $orderDetails->discount,
                                    ) }}
                                </span>
                            </h5>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Product info modal end --}}


    </div>
@endsection
