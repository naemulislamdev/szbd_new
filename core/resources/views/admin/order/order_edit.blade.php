@extends('admin.layouts.app')

@section('title', 'Order Edit')

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
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('admin.order.list') }}" class="btn btn-sm btn-primary"> <i
                                class="las la-arrow-left"></i> Back to Orders</a>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item"><a href="{{ route('admin.order.list') }}">Orders</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">All Order</li>
                        </ol>
                    </div>
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- Page Header -->

        <div class="row" id="printableArea">
            <div class="col-lg-12 mb-3 mb-lg-0">
                <!-- Card -->
                <div class="card mb-3 mb-lg-5">
                    <!-- Header -->

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
                                    <th>Code</th>
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
                                            <td>{{ $detail->product['code'] }}</td>
                                            <td>{{ $detail['price'] }}</td>
                                            <td>{{ $detail->qty }}</td>
                                            <td>{{ $detail['variant'] }}-{{ $detail['variation'] }}</td>
                                            <td>{{ $detail['discount'] }}</td>
                                            @php($subtotal = $detail['price'] * $detail->qty - $detail['discount'])
                                            <td>{{ $subtotal }}</td>
                                            <td>
                                                <a href="javascript:;" class="btn btn-danger btn-sm remove-item"
                                                    data-id="{{ $detail->id }}">
                                                    <i class="la la-trash"></i>
                                                </a>
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
                                        <strong>{{ $total }}</strong>
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
        function order_status(status) {
            var orderStatus = status ? status : 'pending';


            if (status === 'delivered') {
                Swal.fire({
                    title: 'Order is already delivered, and transaction amount has been disbursed, changing status can be the reason of miscalculation',
                    text: "Think before you proceed.",
                    html: '<br /> <form class="form-horizontal" action="{{ route('admin.order.status') }}" method="post"><input type="hidden" name="order_status" value="' +
                        status +
                        '"> <input type="hidden" name="id" value="{{ $order['id'] }}"> <input required class="form-control wedding-input-text wizard-input-pad" type="text" name="note" id="note" placeholder="For delivered note"> </form>',
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
                            url: "{{ route('admin.order.status') }}",
                            method: 'POST',
                            data: $("form").serialize(),
                            success: function(res) {
                                if (res.status && res.note) {
                                    $('#statusNote').html(`
                                    <p class="pl-1 order_note">
                                        ${res.note.note} — ${res.note.user}
                                        (${res.note.date})
                                    </p>
                                `);
                                }

                                toastr.success('Status Changed Successfully');
                            },
                            error: function(data) {
                                toastr.warning('Should Write Canceled Reason !');
                            }
                        });
                    }
                });
            } else if (status === 'canceled') {
                Swal.fire({
                    title: 'Are you sure Change this?',
                    text: "You won't be able to revert this!",
                    html: '<br /> <form class="form-horizontal" action="{{ route('admin.order.status') }}" method="post"><input type="hidden" name="order_status" value="canceled"> <input type="hidden" name="id" value="{{ $order['id'] }}"> <input required class="form-control wedding-input-text wizard-input-pad" type="text" name="note" id="note" placeholder="For cancel note"> </form>',
                    showCancelButton: true,
                    confirmButtonColor: '#377dff',
                    cancelButtonColor: 'secondary',
                    confirmButtonText: 'Yes, Change it!',
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('admin.order.status') }}",
                            method: 'POST',
                            data: $("form").serialize(),
                            success: function(res) {
                                if (res.status && res.note) {
                                    $('#statusNote').html(`
                                    <p class="pl-1 order_note">
                                        ${res.note.note} — ${res.note.user}
                                        (${res.note.date})
                                    </p>
                                `);
                                }

                                toastr.success('Status Changed Successfully');
                            },
                            error: function(data) {
                                toastr.warning('Should Write Canceled Reason !');
                            }
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: 'Are you sure Change this?',
                    text: "You won't be able to revert this!",
                    html: '<br /> <form class="form-horizontal" action="{{ route('admin.order.status') }}" method="post"><input type="hidden" name="order_status" value="' +
                        status +
                        '"> <input type="hidden" name="id" value="{{ $order['id'] }}"> <input required class="form-control wedding-input-text wizard-input-pad" type="text" name="note" id="note" placeholder="For ' +
                        status + ' note"> </form>',
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
                            url: "{{ route('admin.order.status') }}",
                            method: 'POST',
                            data: $("form").serialize(),
                            success: function(res) {
                                if (res.status && res.note) {
                                    $('#statusNote').html(`
                                    <p class="pl-1 order_note">
                                        ${res.note.note} — ${res.note.user}
                                        (${res.note.date})
                                    </p>
                                `);
                                }

                                toastr.success('Status Changed Successfully');
                            },
                            error: function(data) {
                                toastr.warning('Should Write Canceled Reason !');
                            }
                        });
                    }
                });
            }
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
        // $(document).on('click', '.remove-item', function() {
        //     let detail_id = $(this).data('id');

        //     Swal.fire({
        //         title: 'Are you sure?',
        //         text: "You want to remove this item!",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#d33',
        //         cancelButtonColor: '#6c757d',
        //         confirmButtonText: 'Yes, remove it!',
        //         cancelButtonText: 'Cancel'
        //     }).then((result) => {
        //         if (result.isConfirmed) {

        //             $.post('{{ route('admin.order.remove_product') }}', {
        //                 _token: '{{ csrf_token() }}',
        //                 detail_id: detail_id
        //             }, function() {

        //                 Swal.fire(
        //                     'Deleted!',
        //                     'Item has been removed.',
        //                     'success'
        //                 );

        //                 reloadTable();
        //             });

        //         }
        //     });
        // });
        $(document).on('click', '.remove-item', function() {
            let detail_id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure',
                text: "You will not be able to revert this!",
                showCancelButton: true,
                type: 'warning',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('admin.order.remove_product') }}",
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            detail_id: detail_id
                        },
                        success: function() {
                            toastr.success(
                                'Product deleted Successfully.'
                            );
                            reloadTable();

                        }
                    });
                }
            })
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
