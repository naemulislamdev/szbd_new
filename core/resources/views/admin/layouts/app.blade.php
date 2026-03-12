<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="light" data-bs-theme="light">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') - Dashboard Shopping Zobe BD</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Shopping Zobe BD" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/backend/img/favicon.ico') }}">


    <!-- App css -->
    @include('admin.layouts.partials.head_css')
    @stack('styles')
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

        /* Header Global Search */
        .search-result-box {
            position: absolute;
            background: #fff;
            width: 100%;
            z-index: 9999;
        }

        .search-item {
            padding: 8px 12px;
            cursor: pointer;
        }

        .search-item:hover {
            background: #f1f1f1;
        }

        #searchResultBox {
            position: absolute;
            width: 100%;
            background: #fff;
            z-index: 9999;

            max-height: 420px;
            /* 👈 scroll */
            overflow-y: auto;

            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, .1);
        }

        /* Header Global Search End*/
    </style>

</head>

<body>
    <!-- Top Bar Start -->
    @include('admin.layouts.partials.header')
    <!-- Top Bar End -->
    <!-- leftbar-tab-menu -->
    <div class="startbar d-print-none">
        <!--start brand-->
        <div class="brand">
            <a href="{{ route('home') }}" class="logo">
                <span>
                    <img src="{{ asset('assets/storage/company') }}/{{ $web_config['web_logo']->value }}"
                        alt="logo-small" class="logo-sm">
                </span>
                <span class="">
                    <img src="{{ asset('assets/storage/company') }}/{{ $web_config['web_logo']->value }}"
                        alt="logo-large" class="logo-lg logo-light">
                    <img src="{{ asset('assets/storage/company') }}/{{ $web_config['web_logo']->value }}"
                        alt="logo-large" class="logo-lg logo-dark">
                </span>
            </a>
        </div>
        <!--end brand-->
        <!--start startbar-menu-->
        <div class="startbar-menu">
            <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
                @include('admin.layouts.partials.sidebar')
            </div><!--end startbar-collapse-->
        </div><!--end startbar-menu-->
    </div><!--end startbar-->
    <div class="startbar-overlay d-print-none"></div>
    <!-- end leftbar-tab-menu-->

    <div class="page-wrapper">
        <!-- Page Content-->
        <div class="page-content">
            @yield('content')
            <!-- container -->
            <!--Start Rightbar-->
            <!--Start Rightbar/offcanvas-->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="Appearance" aria-labelledby="AppearanceLabel">
                <div class="offcanvas-header border-bottom justify-content-between">
                    <h5 class="m-0 font-14" id="AppearanceLabel">Appearance</h5>
                    <button type="button" class="btn-close text-reset p-0 m-0 align-self-center"
                        data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <h6>Account Settings</h6>
                    <div class="p-2 text-start mt-3">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="settings-switch1">
                            <label class="form-check-label" for="settings-switch1">Auto updates</label>
                        </div><!--end form-switch-->
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="settings-switch2" checked>
                            <label class="form-check-label" for="settings-switch2">Location Permission</label>
                        </div><!--end form-switch-->
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="settings-switch3">
                            <label class="form-check-label" for="settings-switch3">Show offline Contacts</label>
                        </div><!--end form-switch-->
                    </div><!--end /div-->
                    <h6>General Settings</h6>
                    <div class="p-2 text-start mt-3">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="settings-switch4">
                            <label class="form-check-label" for="settings-switch4">Show me Online</label>
                        </div><!--end form-switch-->
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="settings-switch5" checked>
                            <label class="form-check-label" for="settings-switch5">Status visible to all</label>
                        </div><!--end form-switch-->
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="settings-switch6">
                            <label class="form-check-label" for="settings-switch6">Notifications Popup</label>
                        </div><!--end form-switch-->
                    </div><!--end /div-->
                </div><!--end offcanvas-body-->
            </div>
            <!--end Rightbar/offcanvas-->
            <!--end Rightbar-->
            <!--Start Footer-->
            @include('admin.layouts.partials.footer')
            <!--end footer-->
        </div>
        <!-- end page content -->
    </div>
    <!-- end page-wrapper -->

    <!-- Javascript  -->
    <!-- vendor js -->
    @include('admin.layouts.partials.foot_js')
    <script>
        $(document).on('submit', '#szbd_request_form', function(e) {
            e.preventDefault();

            let form = this;
            let formData = new FormData(form);
            let url = $(form).attr('action');
            let method = $(form).attr('method');

            // Remove old errors
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            $.ajax({
                url: url,
                type: method,
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    if (response.success === true) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 800);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let firstInput = null;

                        $.each(errors, function(key, value) {
                            let input = $('[name="' + key + '"]');

                            input.addClass('is-invalid');
                            input.after('<div class="invalid-feedback">' + value[0] + '</div>');

                            if (!firstInput) {
                                firstInput = input;
                            }
                        });

                        // Smooth scroll & focus
                        if (firstInput) {
                            $('html, body').animate({
                                scrollTop: firstInput.offset().top - 120
                            }, 500);

                            firstInput.focus();
                        }
                    }
                }
            });
        });
    </script>
    <script>
        $('#globalSearch').on('keyup', function() {

            let q = $(this).val().trim();

            // empty input → hide result
            if (q.length === 0) {
                $('#searchResultBox').html('').hide();
                return;
            }

            // less than 3 char → hide
            if (q.length < 3) {
                $('#searchResultBox').hide();
                return;
            }

            $.ajax({
                url: "{{ route('admin.global.search') }}",
                data: {
                    q
                },
                success: function(res) {

                    let html = '';

                    //ORDER RESULT
                    if (res.type === 'order') {
                        const invoiceRoute = "{{ route('admin.order.generate-invoice', ':id') }}";
                        const orderDetailsRoute = "{{ route('admin.order.details', ':id') }}";
                        // Total orders
                        const totalOrders = res.data.length;

                        // Show total orders at the top
                        html += `
    <div class="mb-2 bg-primary text-white p-2">
            Orders Found <span class="badge bg-light text-dark">${totalOrders}</span>
    </div>
    `;

                        res.data.forEach(order => {
                            html += `
                    <div class="card mb-2 shadow-sm">
                        <div class="card-body d-flex justify-content-between">
                            <div>
                                <a href="${orderDetailsRoute.replace(':id', order.id)}"><strong>Order #${order.order_number}</strong></a>
                                <span class="badge bg-warning ms-2">${order.order_status}</span>

                                <p class="mb-1 mt-2">
                                    👤 ${order.customer?.name ?? 'N/A'} |
                                    📞 ${order.shipping_address?.phone ?? 'N/A'}
                                </p>

                                <p class="mb-1">
                                    📦 ${order.total_qty ?? 0} item(s)
                                </p>

                                <p class="mb-0 text-muted">
                                    💰 BDT ${order.order_amount}<br>
                                    🕒 ${order.created_at}
                                </p>
                            </div>

                            <div>
                                <a href="${invoiceRoute.replace(':id', order.id)}"
                                   class="btn btn-sm btn-outline-success">
                                    Invoice
                                </a>
                            </div>
                        </div>
                    </div>`;
                        });
                    }

                    // 🏷 PRODUCT RESULT
                    if (res.type === 'product') {
                        const productShow = "{{ route('admin.product.show', ':id') }}";

                        res.data.forEach(p => {
                            html += `
                    <div class="card mb-2 shadow-sm">
                        <div class="card-body">
                            <a href="${productShow.replace(':id', p.id)}"><strong>${p.name}</strong></a><br>
                            <span class="text-muted">Code: ${p.code}</span>
                        </div>
                    </div>`;
                        });
                    }

                    //No data → hide
                    if (html === '') {
                        $('#searchResultBox').hide();
                    } else {
                        $('#searchResultBox').html(html).show();
                    }
                }
            });
        });
    </script>

    @if (Session::has('success'))
        <script>
            toastr.success("{{ Session::get('success') }}")
        </script>
    @endif
    @if (Session::has('error'))
        <script>
            toastr.error("{{ Session::get('error') }}")
        </script>
    @endif
    @if (Session::has('info'))
        <script>
            toastr.info("{{ Session::get('info') }}")
        </script>
    @endif
    @if (Session::has('warning'))
        <script>
            toastr.warning("{{ Session::get('warning') }}")
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
    @stack('scripts')
</body>
<!--end body-->

</html>
