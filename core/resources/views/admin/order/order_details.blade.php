@extends('admin.layouts.app')

@section('title', 'Order Details')

@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .cinfo-box {
            box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
            padding: 17px 20px;
            margin: 7px 10px;
            border-radius: 7px;
            border-style: dotted;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Orders</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item"><a href="#">Orders</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">All Order</li>
                        </ol>
                    </div>
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- Page Header -->
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-print-none p-3" style="background: white">
                        <div class="col-sm-12 mb-2 mb-sm-0">

                            <div class="d-flex align-items-sm-center justify-between">
                                <h1 class="card-title me-3">Order #{{ $order['id'] }}</h1>

                                @if ($order['payment_status'] == 'paid')
                                    <span class="badge bg-success me-3">
                                        <span class="legend-indicator bg-success"></span>Paid
                                    </span>
                                @else
                                    <span class="badge bg-danger me-3">
                                        <span class="legend-indicator bg-danger"></span>Unpaid
                                    </span>
                                @endif

                                @if ($order['order_status'] == 'pending')
                                    <span class="badge bg-info me-3 text-capitalize">
                                        <span
                                            class="legend-indicator bg-info text"></span>{{ str_replace('_', ' ', $order['order_status']) }}
                                    </span>
                                @elseif($order['order_status'] == 'failed')
                                    <span class="badge bg-danger me-3 text-capitalize">
                                        <span
                                            class="legend-indicator bg-info"></span>{{ str_replace('_', ' ', $order['order_status']) }}
                                    </span>
                                @elseif($order['order_status'] == 'processing' || $order['order_status'] == 'out_for_delivery')
                                    <span class="badge bg-warning me-3 text-capitalize">
                                        <span
                                            class="legend-indicator bg-warning"></span>{{ str_replace('_', ' ', $order['order_status']) }}
                                    </span>
                                @elseif($order['order_status'] == 'delivered' || $order['order_status'] == 'confirmed')
                                    <span class="badge bg-success me-3 text-capitalize">
                                        <span
                                            class="legend-indicator bg-success"></span>{{ str_replace('_', ' ', $order['order_status']) }}
                                    </span>
                                @else
                                    <span class="badge bg-danger me-3 text-capitalize">
                                        <span
                                            class="legend-indicator bg-danger"></span>{{ str_replace('_', ' ', $order['order_status']) }}
                                    </span>
                                @endif
                                <span class="me-3">Date:
                                    <i class="la la-date"></i>
                                    {{ date('d M Y H:i:s', strtotime($order['created_at'])) }}
                                </span>

                            </div>
                            <div class="col-md-6 mt-2">
                                <a class="text-body mr-3 btn btn-sm btn-primary" target="_blank"
                                    href={{ route('admin.order.generate-invoice', [$order['id']]) }}>
                                    <i class="las la-print mr-1"></i> Print invoice
                                </a>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mt-4">
                                    <div class="mt-3">
                                        <h4>Moderator Note:</h4>
                                        @if ($order->order_note != null)
                                            <span class="font-weight-bold text-capitalize">
                                                Signle Note :
                                            </span>
                                            @php
                                                $note = json_decode($order->order_note, true);
                                            @endphp

                                            <p class="pl-1 order_note">
                                                {{ $note['note'] ?? '' }} — {{ $note['user'] ?? '' }}
                                                ({{ $note['date'] ?? '' }})
                                            </p>
                                        @endif

                                        <ul id="noteList" class="list-unstyled d-flex flex-column">
                                            <span class="font-weight-bold text-capitalize">
                                                Multiple Note :
                                            </span>
                                            @if ($order->multiple_note)
                                                @foreach (json_decode($order->multiple_note, true) as $note)
                                                    <li style="text-align: left;  text-wrap: wrap; line-height: 20px"
                                                        class="badge bg-primary mb-2 py-2">
                                                        {{ $note['note'] }}
                                                        <span class="">({{ $note['time'] }} -> Note by:
                                                            {{ $note['user'] }})</span>
                                                    </li>
                                                @endforeach

                                            @endif
                                        </ul>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label>Order Status</label>
                                                        <select name="order_status"
                                                            onchange="orderStatusChange(this.value, {{ $order->id }})"
                                                            class="form-control">

                                                            <option value="pending"
                                                                {{ $order->order_status == 'pending' ? 'selected' : '' }}>
                                                                Pending
                                                            </option>
                                                            <option value="confirmed"
                                                                {{ $order->order_status == 'confirmed' ? 'selected' : '' }}>
                                                                Confirmed</option>
                                                            <option value="processing"
                                                                {{ $order->order_status == 'processing' ? 'selected' : '' }}>
                                                                Processing </option>
                                                            <option class="text-capitalize" value="out_for_delivery"
                                                                {{ $order->order_status == 'out_for_delivery' ? 'selected' : '' }}>
                                                                out_for_delivery
                                                            </option>
                                                            <option value="delivered"
                                                                {{ $order->order_status == 'delivered' ? 'selected' : '' }}>
                                                                Delivered </option>
                                                            <option value="returned"
                                                                {{ $order->order_status == 'returned' ? 'selected' : '' }}>
                                                                Returned</option>
                                                            <option value="failed"
                                                                {{ $order->order_status == 'failed' ? 'selected' : '' }}>
                                                                Failed
                                                            </option>
                                                            <option value="canceled"
                                                                {{ $order->order_status == 'canceled' ? 'selected' : '' }}>
                                                                Canceled </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label>Payment Status</label>
                                                    <select name="payment_status" class="payment_status form-control"
                                                        data-id="{{ $order['id'] }}">
                                                        <option
                                                            onclick="route_alert('{{ route('admin.order.payment-status', ['id' => $order['id'], 'payment_status' => 'paid']) }}','Change status to paid ?')"
                                                            href="javascript:" value="paid"
                                                            {{ $order->payment_status == 'paid' ? 'selected' : '' }}>
                                                            Paid
                                                        </option>
                                                        <option value="unpaid"
                                                            {{ $order->payment_status == 'unpaid' ? 'selected' : '' }}>
                                                            Unpaid
                                                        </option>

                                                    </select>
                                                </div>
                                                <div class="col-12 mt-5">
                                                    <form id="orderNoteForm" style="padding-top: 20px;">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $order['id'] }}">
                                                        <label>Add Note</label>
                                                        <div>
                                                            <div class="input-group mb-3">
                                                                <input autofocus required type="text"
                                                                    class="form-control" name="multiple_note[]"
                                                                    placeholder="Enter New note">
                                                            </div>
                                                        </div>

                                                        @error('multiple_note')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                        <div class="d-flex justify-content-end">
                                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Unfold -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 border-bottom pb-0">
                                <h4 class="card-header-title">Customer Information</h4>
                            </div>
                        </div>
                    </div>
                    <!-- End Header -->
                    @if ($order->customer)
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="card-body cinfo-box">
                                    <div class="media align-items-center d-flex justify-content-center">
                                        <div class="avatar avatar-circle mr-3">
                                            @if ($order->customer->image != null && $order->customer->image != 'def.png')
                                                <img class="avatar-img" style="width: 75px;height: 42px"
                                                    src="{{ asset('assets/storage/profile/' . $order->customer->image) }}"
                                                    alt="Image">
                                            @else
                                                <img class="avatar-img" style="width: 75px;height: 42px"
                                                    src="{{ asset('assets/default/img/c-demo.jpg') }}" alt="Image">
                                            @endif
                                        </div>
                                        <div class="media-body">
                                            <h4 class="text-body text-hover-primary">{{ $order->customer['f_name'] }}</h4>
                                            <h5>Total: <span
                                                    class="badge bg-success rounded-circle ml-1">{{ \App\Models\Order::where('customer_id', $order['customer_id'])->count() }}</span>
                                                orders</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card-body cinfo-box">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5>Contact Information </h5>
                                    </div>

                                    <ul class="list-unstyled list-unstyled-py-2">
                                        <li>
                                            <i class="las la-envelope"></i>
                                            {{ $order->customer['email'] }}
                                        </li>
                                        <li>
                                            <i class="las la-phone"></i>
                                            {{ $order->customer['phone'] }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card-body cinfo-box">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5>Shipping Address</h5>
                                    </div>

                                    @if ($order->shippingAddress)
                                        @php $shipping_address = $order->shippingAddress @endphp
                                    @else
                                        @php $shipping_address = json_decode($order['shipping_address_data']) @endphp
                                    @endif

                                    <span class="d-block">Name :
                                        <strong>{{ $shipping_address ? $shipping_address->contact_person_name : 'empty' }}</strong><br>
                                        Address :
                                        <strong>{{ $shipping_address ? $shipping_address->address : 'empty' }}</strong><br>
                                        Phone :
                                        <strong>{{ $shipping_address ? $shipping_address->phone : 'empty' }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <!-- Body -->
                    @else
                        <div class="card-body cinfo-box">
                            <div class="media align-items-center">
                                <span>No Customer Found</span>
                            </div>
                        </div>
                    @endif
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>
        </div>
        <div class="row" id="printableArea">
            <div class="col-lg-12 mb-3 mb-lg-0">
                <!-- Card -->
                <div class="card mb-3 mb-lg-5">
                    <!-- Header -->
                    <div class="card-header" style="display: block!important;">
                        <div class="row">
                            <div class="col-12 pb-2 border-bottom">
                                <h4 class="card-header-title">
                                    Order details
                                    <span
                                        class="badge bg-success rounded-circle ml-1">{{ $order->details->count() }}</span>
                                </h4>
                            </div>

                            <div class="col-6 pt-2">
                                @if ($order->customer_note != null)
                                    <span class="font-weight-bold text-capitalize">
                                        Customer Note :
                                    </span>
                                    <p>{{ $order->customer_note }}</p>
                                @endif
                            </div>
                            <div class="col-6 pt-2">
                                <div class="text-right">
                                    <h6 class="" style="color: #8a8a8a;">
                                        Payment Method
                                        : {{ str_replace('_', ' ', $order['payment_method']) }}
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body">
                        <div class="product-search">
                            <label><span class="text-info">Product Search for Add Another Products</span></label>
                            <input type="text" id="product_search" class="form-control"
                                placeholder="Search product by name or code">
                            <div id="search_result" class="list-group mt-2"></div>


                        </div>
                        @php($subtotal = 0)
                        @php($total = 0)
                        @php($shipping = 0)
                        @php($discount = 0)
                        @php($tax = 0)
                        <table class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Variation</th>
                                    <th>Discount</th>
                                    <th>Subtotal</th>
                                    <th>Action</th>
                                    <th>Created By</th>
                                </tr>
                            </thead>
                            <tbody id="order_table">
                                @foreach ($order->details as $key => $detail)
                                    @if ($detail->product)
                                        <tr>
                                            <td>
                                                @if ($detail->color_image != null)
                                                    <img class="img-fluid" style="width: 70px;"
                                                        src="{{ $detail->color_image }}" alt="Image Description">
                                                @else
                                                    <img class="img-fluid" style="width: 70px;"
                                                        src="{{ asset('assets/storage/product/thumbnail/' . $detail->product['thumbnail']) }}"
                                                        alt="Image Description">
                                                @endif
                                            </td>
                                            <td>{{ substr($detail->product['name'], 0, 30) }}{{ strlen($detail->product['name']) > 10 ? '...' : '' }}
                                            </td>
                                            <td>{{ $detail['price'] }}</td>
                                            <td>{{ $detail->qty }}</td>
                                            <td>{{ $detail['variant'] }}-{{ $detail['variation'] }}</td>
                                            <td>{{ $detail['discount'] }}</td>
                                            @php($subtotal = $detail['price'] * $detail->qty - $detail['discount'])
                                            <td>{{ $subtotal }}</td>
                                            <td>
                                                <button class="btn btn-danger btn-sm remove-item"
                                                    data-id="{{ $detail->id }}">
                                                    <i class="la la-trash"></i>
                                                </button>
                                            </td>
                                            <td>
                                                {{ $detail->created_by ?? 'Customer' }}
                                            </td>
                                        </tr>
                                        @php($discount += $detail['discount'])
                                        @php($tax += $detail['tax'])
                                        @php($total += $subtotal)
                                    @endif
                                @endforeach
                            </tbody>
                        </table>

                        @php($shipping = $order['shipping_cost'])
                        @php($coupon_discount = $order['discount_amount'])
                        @php($advance_amount = $order['advance_amount'])
                        @php($advance_payment_method = $order['advance_payment_method'])
                        {{-- <div>

                        </div> --}}
                        <div class="row justify-content-md-end mb-3" id="order_totals">
                            <div class="col-md-9 col-lg-8">
                                <dl class="row text-sm-right">
                                    <dt class="col-sm-6">Subtotal</dt>
                                    <dd class="col-sm-6 border-bottom">
                                        <strong>{{ $subtotal }}</strong>
                                    </dd>
                                    <dt class="col-sm-6">Shipping</dt>
                                    <dd class="col-sm-6 border-bottom">
                                        <strong>{{ $shipping }}</strong>
                                    </dd>

                                    <dt class="col-sm-6">coupon_discount</dt>
                                    <dd class="col-sm-6 border-bottom">
                                        <strong>- {{ $coupon_discount }}</strong>
                                    </dd>
                                    <dt class="col-sm-6">Advance({{ $advance_payment_method }})</dt>
                                    <dd class="col-sm-6 border-bottom">
                                        <strong>- {{ $advance_amount }}</strong>
                                    </dd>
                                    <dt class="col-sm-6">Total</dt>
                                    <dd class="col-sm-6">
                                        <strong>{{ $total + $shipping - $coupon_discount - $advance_amount }}</strong>
                                    </dd>
                                </dl>
                                <!-- End Row -->
                            </div>
                        </div>
                        <!-- End Row -->
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>
        </div>
        <!-- End Row -->
    </div>
    <!-- End Page Header -->
    <div class="modal fade" data-bs-backdrop="static" tabindex="-1" aria-hidden="true" id="variationModal">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Select Product Variation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="modal_product_id">

                    <div id="color_area"></div>
                    <div id="size_area"></div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary" id="confirmAddProduct">
                        Add Product
                    </button>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).on('change', '.payment_status', function() {
            var id = $(this).attr("data-id");
            var value = $(this).val();
            Swal.fire({
                title: 'Are you sure Change this?',
                text: "You will not be able to revert this!",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'secondary',
                confirmButtonText: 'Yes, Change it!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('admin.order.payment-status') }}",
                        method: 'POST',
                        data: {
                            "id": id,
                            "payment_status": value
                        },
                        success: function(data) {
                            if (data.customer_status == 0) {
                                toastr.warning(
                                    'Account has been deleted, you can not change the status!!'
                                );
                                // location.reload();
                            } else {
                                toastr.success('Status Change successfully');
                                // location.reload();
                            }
                        }
                    });
                }
            })
        });
    </script>

    <script>
        function orderStatusChange(status, orderId) {

            let title = 'Are you sure?';
            let warning = "You won't be able to revert this!";
            let placeholder = 'Write note';

            if (status === 'delivered') {
                title = 'Order already delivered!';
                warning = 'Changing this may cause transaction miscalculation.';
                placeholder = 'Delivered note';
            }

            if (status === 'canceled') {
                placeholder = 'Cancel note';
            }

            Swal.fire({
                title: title,
                text: warning,
                input: 'textarea',
                inputPlaceholder: placeholder,
                inputAttributes: {
                    required: true
                },
                showCancelButton: true,
                confirmButtonText: 'Yes, Change it!',
                confirmButtonColor: '#377dff',
                cancelButtonColor: '#6c757d',
                preConfirm: (note) => {
                    if (!note) {
                        Swal.showValidationMessage('Note is required');
                        return false;
                    }
                    console.log(note);

                    return note;
                }
            }).then((result) => {

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    }
                });

                $.ajax({
                    url: "{{ route('admin.order.status') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    },
                    data: {
                        id: orderId,
                        order_status: status,
                        multiple_note: [result.value]
                    },
                    success: function(res) {
                        toastr.success('Order status updated successfully');
                        if (res.status) {
                            // frontend update
                            $('#noteList').append(`
                            <li style='text-align: left; text-wrap: wrap; line-height: 20px' class="badge bg-primary d-inline-block  mb-2 py-2">
                                ${res.note.note}
                                <span class="">(${res.note.time}- Note by: ${res.note.user})</span>
                            </li>
                        `);
                            form[0].reset();
                        }

                    },
                    error: function() {
                        toastr.error('Something went wrong');
                    }
                });
            });
        }
    </script>

    <script>
        $('#product_search').keyup(function() {

            let keyword = $(this).val();
            if (keyword.length < 2) return;

            $.get('{{ route('admin.order.product_search') }}', {
                keyword
            }, function(res) {

                let html = '';
                res.forEach(product => {
                    html += `
            <a href="javascript:;"
               class="list-group-item select-product"
               data-id="${product.id}">
               ${product.name}
            </a>`;
                });

                $('#search_result').html(html);
            });
        });
    </script>

    <script>
        $(document).on('click', '.select-product', function(e) {
            e.preventDefault();

            console.log('OK'); // ✅ এখন show করবে

            let product_id = $(this).data('id');
            $('#modal_product_id').val(product_id);

            let url = "{{ route('admin.order.product_variation', ':id') }}";
            url = url.replace(':id', product_id);

            $.get(url, function(res) {

                $('#color_area').html('');
                $('#size_area').html('');

                // ❌ No variation → direct add
                if (!res.has_variation) {
                    addProductAjax(product_id, null, null, null);
                    return;
                }

                // 🎨 COLOR
                if (res.colors.length > 0) {
                    let colorHtml = `<h6>Color</h6><div class="d-flex">`;
                    res.colors.forEach((color, i) => {
                        colorHtml += `
                <label class="me-2">
                    <input type="radio" name="modal_color"
                        value="${color.color}"
                        data-image="${color.image}"
                        ${i === 0 ? 'checked' : ''}>
                    <img src="${color.image}" width="35">
                </label>`;
                    });
                    colorHtml += `</div>`;
                    $('#color_area').html(colorHtml);
                }

                // 📏 SIZE
                if (res.sizes.length > 0) {
                    let sizeHtml = `<h6 class="mt-3">Size</h6><div class="d-flex">`;
                    res.sizes.forEach((size, i) => {
                        sizeHtml += `
                <label class="me-2">
                    <input type="radio" name="modal_size"
                        value="${size}"
                        ${i === 0 ? 'checked' : ''}>
                    ${size}
                </label>`;
                    });
                    sizeHtml += `</div>`;
                    $('#size_area').html(sizeHtml);
                }

                $('#variationModal').modal('show');
            });
        });
    </script>

    <script>
        $('#confirmAddProduct').click(function() {

            let product_id = $('#modal_product_id').val();
            let color = $('input[name="modal_color"]:checked').val() ?? null;
            let color_image = $('input[name="modal_color"]:checked').data('image') ?? null;
            let size = $('input[name="modal_size"]:checked').val() ?? null;

            addProductAjax(product_id, color, size, color_image);
        });
    </script>
    <script>
        function addProductAjax(product_id, color, size, color_image) {

            $.post('{{ route('admin.order.add_product') }}', {
                _token: '{{ csrf_token() }}',
                order_id: '{{ $order->id }}',
                product_id,
                color,
                size,
                color_image
            }, function() {

                $('#variationModal').modal('hide');
                toastr.success('Product added');
                reloadTable();
                setTimeout(() => {
                    location.reload();
                }, 800);
            });
        }
    </script>

    <script>
        $(document).on('click', '.remove-item', function() {
            let detail_id = $(this).data('id');

            $.post('{{ route('admin.order.remove_product') }}', {
                _token: '{{ csrf_token() }}',
                detail_id
            }, function() {
                reloadTable();
            });
        });
    </script>
    <script>
        function reloadTable() {
            $('#order_table').load(location.href + ' #order_table > *');
            $('#order_totals').load(location.href + ' #order_totals > *');
        }
    </script>
    <script>
        $('#orderNoteForm').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);

            // trim textarea value
            let noteInput = form.find('input[name="multiple_note[]"]');
            let trimmedValue = noteInput.val().trim();

            if (trimmedValue === '') {
                toastr.warning("Note cannot be empty !");
                return;
            }
            // replace the original value with trimmed
            noteInput.val(trimmedValue);
            $.ajax({
                url: "{{ route('admin.order.multiple_note') }}",
                type: "POST",
                data: form.serialize(),
                success: function(res) {
                    if (res.status) {
                        // frontend update
                        $('#noteList').append(`
                            <li style='text-align: left; text-wrap: wrap; line-height: 20px' class="badge bg-primary d-inline-block  mb-2 py-2">
                                ${res.note.note}
                                <span class="">(${res.note.time}- Note by: ${res.note.user})</span>
                            </li>
                        `);


                        form[0].reset();
                    }
                    toastr.success('Note added Successfully !');

                },
                error: function() {
                    toastr.error('Something went wrong');
                }
            });
        });
    </script>
@endpush
