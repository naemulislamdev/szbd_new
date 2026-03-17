@extends('customer.layouts.master')

@section('title', 'My Order List')
@push('css_or_js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
@push('css_or_js')
    <style>
        .widget-categories .accordion-heading>a:hover {
            color: #FFD5A4 !important;
        }

        .widget-categories .accordion-heading>a {
            color: #FFD5A4;
        }

        body {
            font-family: 'Titillium Web', sans-serif;
        }

        .card {
            border: none
        }

        .totals tr td {
            font-size: 13px
        }

        .product-qty span {
            font-size: 14px;
            color: #6A6A6A;
        }

        .spandHeadO {
            color: #FFFFFF !important;
            font-weight: 600 !important;
            font-size: 14px;

        }

        .tdBorder {
            border- {{ Session::get('direction') === 'rtl' ? 'left' : 'right' }}: 1px solid #f7f0f0;
            text-align: center;
        }

        .bodytr {
            text-align: center;
            vertical-align: middle !important;
        }

        tr td {
            padding: 10px 8px !important;
        }

        td button {
            padding: 3px 13px !important;
        }

        @media (max-width: 600px) {
            .sidebar_heading {
                background: {{ $web_config['primary_color'] }};
            }

            .orderDate {
                display: none;
            }

            .sidebar_heading h1 {
                text-align: center;
                color: aliceblue;
                padding-bottom: 17px;
                font-size: 19px;
            }
        }
    </style>
@endpush

@section('customer_content')

    <div class="row mb-3">
        <div class="col-md-12 mt-2 sidebar_heading">
            <h1 class="h3  mb-0 p-3 headerTitle">
                My orders</h1>
        </div>
    </div>

    <div class="card box-shadow-sm">
        <div style="overflow: auto">
            <table class="table">
                <thead>
                    <tr style="background-color: #6b6b6b;">
                        <td class="tdBorder">
                            <div class="py-2"><span class="d-block spandHeadO ">Order#</span>
                            </div>
                        </td>

                        <td class="tdBorder orderDate">
                            <div class="py-2"><span class="d-block spandHeadO">Order
                                    Date</span>
                            </div>
                        </td>
                        <td class="tdBorder">
                            <div class="py-2"><span class="d-block spandHeadO"> Status</span>
                            </div>
                        </td>
                        <td class="tdBorder">
                            <div class="py-2"><span class="d-block spandHeadO"> Total</span>
                            </div>
                        </td>
                        <td class="tdBorder">
                            <div class="py-2"><span class="d-block spandHeadO"> action</span>
                            </div>
                        </td>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td class="bodytr font-weight-bold">
                                ID: {{ $order['order_number'] }}
                            </td>
                            <td class="bodytr orderDate"> <span>{{ $order['created_at']->format('d F Y, g:i A') }}</span>
                            </td>
                            <td class="bodytr">
                                @if ($order['order_status'] == 'failed' || $order['order_status'] == 'canceled')
                                    <span class="btn btn-sm btn-danger">
                                        {{ ucfirst($order['order_status']) }}
                                    </span>
                                @elseif(
                                    $order['order_status'] == 'confirmed' ||
                                        $order['order_status'] == 'processing' ||
                                        $order['order_status'] == 'delivered')
                                    <span class="btn btn-sm btn-success">
                                        {{ ucfirst($order['order_status']) }}
                                    </span>
                                @else
                                    <span class="btn btn-sm btn-info">
                                        {{ ucfirst($order['order_status']) }}
                                    </span>
                                @endif
                            </td>
                            <td class="bodytr">
                                {{ $order['order_amount'] }}
                            </td>
                            <td class="bodytr">
                                <a href="{{ route('account-order-details', ['id' => $order->id]) }}"
                                    class="btn btn-primary btn-sm p-2 mb-2">
                                    <i class="fa fa-eye"></i> view
                                </a>

                                @if ($order['payment_method'] == 'cash_on_delivery' && $order['order_status'] == 'pending')
                                    <button class="btn btn-danger p-2 btn-sm top-margin mb-2 cancelOrder"
                                        data-url="{{ route('order-cancel', [$order->id]) }}">
                                        <i class="fa fa-trash"></i> Cancel
                                    </button>
                                @elseif($order['order_status'] != 'canceled' && $order['order_status'] != 'pending')
                                    <button onclick="cancel_message()" class="btn btn-sm btn-danger p-2 top-margin mb-2">
                                        <i class="fa fa-trash"></i> Cancel
                                    </button>
                                @endif
                                @if ($order['order_status'] == 'confirmed' || $order['order_status'] == 'delivered')
                                    <a href="{{ route('submit-review', $order->sellerName->id) }}"
                                        class="btn btn-primary p-2 mb-2">
                                        <i class="fa fa-eye"></i> Write a Review
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($orders->count() == 0)
                <span class="mt-3 mb-2">No order found</span>
            @endif

            <div class="card-footer">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
@endsection


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    function cancel_message() {
        toastr.info('order can be canceled only when pending.', {
            CloseButton: true,
            ProgressBar: true
        });
    }
</script>

<script>
    $(document).on('click', '.cancelOrder', function(e) {
        e.preventDefault();
        var button = $(this);
        var url = button.data('url');

        // Step 1: Swal with input
        Swal.fire({
            title: "Are you sure?",
            text: "Do you really want to cancel this order?",
            input: 'text', // input type
            inputLabel: 'Cancel Reason',
            inputPlaceholder: 'Enter reason for cancellation',
            inputAttributes: {
                required: true
            },
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, cancel it!",
            preConfirm: (reason) => {
                if (!reason) {
                    Swal.showValidationMessage('Reason is required!');
                }
                return reason;
            }
        }).then((result) => {
            if (result.value) {
                var reason = result.value;

                // Step 2: Show Processing loading
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Step 3: AJAX call with reason
                $.ajax({
                    url: url,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        note: reason
                    }, // send reason to backend
                    success: function(data) {
                        Swal.close(); // close loading
                        if (data.success) {
                            toastr.success(data.success);
                            button.closest('tr').remove(); // remove order row
                        } else if (data.error) {
                            toastr.error(data.error);
                        }
                    },
                    error: function(err) {
                        Swal.close(); // close loading
                        toastr.error(err.responseJSON?.message || 'Something went wrong!');
                    }
                });
            }
        });
    });
</script>
