@extends('web.layouts.app')

@section('title', 'My Shopping Cart')

@push('css_or_js')
    <meta property="og:image" content="{{ asset('assets/storage/company') }}/{{ $web_config['web_logo']->value }}" />
    <meta property="og:title" content="{{ $web_config['name']->value }} " />
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:description" content="{!! substr(strip_tags($web_config['about']->value), 0, 100) !!}">

    <meta property="twitter:card" content="{{ asset('assets/storage/company') }}/{{ $web_config['web_logo']->value }}" />
    <meta property="twitter:title" content="{{ $web_config['name']->value }}" />
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:description" content="{!! substr(strip_tags($web_config['about']->value), 0, 100) !!}">
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/front-end') }}/css/shop-cart.css" />
    <style>
        p,
        span,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        th,
        label {
            font-family: 'SolaimanLipi', sans-serif !important;
            font-weight: 400;
            font-size: 18px;
        }

        .address-title {
            font-size: 22px;
        }

        .card-header {
            padding: 6px 0px;
            margin-bottom: 0;
            border-bottom: 0px solid rgba(0, 0, 0, .125);
            background: #424242;
            color: #ffffff;
            text-align: center;
        }

        header {
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 9999;
            border-bottom: 1px solid hsla(0, 0%, 100%, .14);
            background: #fff;
            transition: 0.5s;
        }

        .menu-area>ul>li>a {
            text-decoration: none;
            color: #343a40;
        }

        .menu-icon {
            color: #504f4f;
        }

        .header-icon>a>.fa {
            color: #464545;
        }

        .shipping-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 10px 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .shipping-box input[type="radio"] {
            margin-right: 10px;
            accent-color: #33ad07;
            /* red accent */
        }

        .shipping-box:hover {
            border-color: #33ad07;
            background: #fff5f5;
        }

        .shipping-box input[type="radio"]:checked+.shipping-title {
            font-weight: bold;
            color: #f26d21;
        }

        .address-box {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 7px 10px;
            cursor: pointer;
            margin-bottom: 10px;
            position: relative;
        }

        .address-box.active {
            border-color: #0d6efd;
            background: #f5f9ff;
        }

        .address-box input {
            display: none;
        }

        .address-box>h4 {
            font-size: 14px;
            margin: 0px;
        }

        .edit-btn {
            position: absolute;
            bottom: 0px;
            right: 0px;
        }
    </style>
@endpush

@section('content')
    {{-- @dd(session('otp')) --}}
    <div class="container pb-5 mb-2 mt-3" id="cart-summary">
        @include('web.layouts.partials.cart_details')
    </div>
    <!-- Button trigger modal -->

    <!-- Modal -->
    <div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form method="POST" action="{{ route('address.update') }}">
                @csrf
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Address</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name" id="edit_name" class="form-control mb-2">
                        <input type="number" name="phone" id="edit_phone" class="form-control mb-2">
                        <textarea name="address" id="edit_address" class="form-control"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        cartQuantityInitialize();
    </script>
    <script>
        document.getElementById('phone').addEventListener('input', function() {
            const phoneInput = this.value;
            const phoneFeedback = document.getElementById('phoneFeedback');
            const regex = /^(01[3-9]\d{8})$/;

            if (phoneInput === '') {
                phoneFeedback.textContent = '';
            } else if (!regex.test(phoneInput)) {
                phoneFeedback.classList.add('text-danger');
                phoneFeedback.textContent = 'Please enter a valid Bangladeshi phone number (e.g. 0171XXXXXXX)';
            } else {
                phoneFeedback.textContent = 'Valid phone number!';
                phoneFeedback.classList.remove('text-danger');
                phoneFeedback.classList.add('text-success');
            }
        });

        // Also validate when the field loses focus
        document.getElementById('phone').addEventListener('blur', function() {
            const phoneInput = this.value;
            const phoneFeedback = document.getElementById('phoneFeedback');
            const regex = /^(01[3-9]\d{8})$/;

            if (phoneInput === '') {
                phoneFeedback.textContent = 'Phone number is required';
            } else if (!regex.test(phoneInput)) {
                phoneFeedback.textContent = 'Please enter a valid Bangladeshi phone number (e.g. 0171XXXXXXX)';
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            let typingTimer;
            let doneTypingInterval = 1000; // Time in milliseconds (1 second)


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(".auto-save").on("input", function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(saveUserData, doneTypingInterval);
            });

            function saveUserData() {
                let formData = $("#userInfoForm").serialize();

                $.ajax({
                    url: "{{ route('save.user.info') }}",
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            console.log("Data auto-saved successfully!");
                        } else {
                            console.log("Failed to save data.");
                        }
                    },
                    error: function(xhr) {
                        console.log("Error: ", xhr.responseText);
                    }
                });
            }
        });
    </script>
    <script>
        function selectAddress(showNew, el) {
            const form = document.getElementById('newAddressForm');
            if (showNew) {
                form.style.display = 'block';
                form.querySelectorAll('input,textarea').forEach(i => i.disabled = false);
            } else {
                form.style.display = 'none';
                form.querySelectorAll('input,textarea').forEach(i => i.disabled = true);
            }

            document.querySelectorAll('.address-box').forEach(b => b.classList.remove('active'));
            el.closest('.address-box').classList.add('active');
        }
    </script>
    <script>
        function openEditModal(address) {
            event.stopPropagation();

            document.getElementById('edit_id').value = address.id;
            document.getElementById('edit_name').value = address.contact_person_name;
            document.getElementById('edit_phone').value = address.phone;
            document.getElementById('edit_address').value = address.address;

            $('#editAddressModal').modal('show');
        }
    </script>
    <script>
        // Send OTP
        $('#send_otp').on('click', function() {
            let phone = $('#otp_phone').val();

            $.post("{{ route('send.otp') }}", {
                _token: "{{ csrf_token() }}",
                phone: phone
            }, function(res) {
                if (res.status === 'success') {
                    $('#otpInputRow').removeClass('d-none');
                }
            });
        });

        // Verify OTP
        $('#verify_otp').on('click', function() {
            $.post("{{ route('verify.otp') }}", {
                _token: "{{ csrf_token() }}",
                phone: $('#otp_phone').val(),
                otp: $('#otp').val()
            }, function(res) {
                if (res.status === 'success') {
                    $('#otpSection').hide();
                    $('.checkoutForm').removeClass('d-none');
                }
            });
        });
    </script>
@endpush
