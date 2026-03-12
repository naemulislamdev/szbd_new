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

        .qty-btn {
            width: 27px;
            height: 27px;
            line-height: 15px;
            text-align: center;
            font-weight: bold;
        }

        .qty-input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            margin: 0 5px;
        }
    </style>
    <style>
        .otp-card {
            max-width: 500px;
            margin: 20px auto;
            padding: 30px 20px;
            background: #ffffff;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            border-radius: 10px;
            color: #020101;
            text-align: center;
        }

        .otp-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .otp-subtitle {
            font-size: 16px;
            margin-bottom: 14px;
        }

        .otp-timer {
            color: orange;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .otp-inputs {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .otp-box {
            width: 55px;
            height: 55px;
            text-align: center;
            font-size: 26px;
            border: 1px solid #555;
            background: #fff;
            color: #111;
            border-radius: 4px;
        }

        .otp-box:focus {
            outline: none;
            border-color: orange;
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
        $(document).ready(function() {

            let typingTimer;
            let doneTypingInterval = 1000;

            $(".otp-phone-save").on("input", function() {

                clearTimeout(typingTimer);

                let phoneValue = $(this).val();
                let sessionId = $('#session_id').val();

                console.log('phone:', phoneValue, 'session:', sessionId);

                typingTimer = setTimeout(function() {

                    $.ajax({
                        url: "{{ route('save.user.info') }}",
                        type: "POST",
                        data: {
                            phone: phoneValue,
                            session_id: sessionId,
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                console.log("Phone auto-saved successfully!");
                            } else {
                                console.log("Failed to save phone.");
                            }
                        },
                        error: function(xhr) {
                            console.log("Error:", xhr.responseText);
                        }
                    });

                }, doneTypingInterval);
            });

        });
    </script>
    <script>
        cartQuantityInitialize();
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const phoneRegex = /^(01[3-9]\d{8})$/;

            document.querySelectorAll('.check-phone').forEach(function(input) {

                const container = input.closest('.col-md-6');
                const feedback = container.querySelector('.phone-feedback');

                input.addEventListener('input', function() {

                    const value = this.value.trim();

                    feedback.classList.remove('text-danger', 'text-success');

                    if (value === '') {
                        feedback.textContent = '';
                        return;
                    }

                    if (!phoneRegex.test(value)) {
                        feedback.classList.add('text-danger');
                        feedback.textContent = 'Please enter valid BD number (017XXXXXXXX)';
                    } else {
                        feedback.classList.add('text-success');
                        feedback.textContent = 'Valid phone number!';
                    }
                });

                input.addEventListener('blur', function() {

                    const value = this.value.trim();

                    if (value === '') {
                        feedback.classList.remove('text-success');
                        feedback.classList.add('text-danger');
                        feedback.textContent = 'Phone number is required';
                    }
                });

            });

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
        let otpCountdownInterval = null;

        function getOtpBoxes() {
            return document.querySelectorAll('.otp-box');
        }

        function getOtpValue() {
            let otp = '';
            getOtpBoxes().forEach(box => otp += box.value);
            return otp;
        }

        function setOtpValue(code) {
            const digits = String(code).replace(/\D/g, '').slice(0, 4).split('');
            const otpBoxes = getOtpBoxes();

            otpBoxes.forEach((box, index) => {
                box.value = digits[index] || '';
            });

            $('#otp').val(digits.join(''));
        }

        function bindOtpInputs() {
            const otpBoxes = getOtpBoxes();

            otpBoxes.forEach((box, index) => {
                box.addEventListener('input', function() {
                    this.value = this.value.replace(/\D/g, '').slice(0, 1);
                    $('#otp').val(getOtpValue());

                    if (this.value && index < otpBoxes.length - 1) {
                        otpBoxes[index + 1].focus();
                    }
                });

                box.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value && index > 0) {
                        otpBoxes[index - 1].focus();
                    }
                });

                box.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pasted = (e.clipboardData || window.clipboardData).getData('text');
                    setOtpValue(pasted);
                });
            });
        }

        function updateOtpTimerText(secondsLeft) {
            let minutes = Math.floor(secondsLeft / 60);
            let seconds = secondsLeft % 60;
            seconds = seconds < 10 ? '0' + seconds : seconds;

            $('#otpTimer').text('Time remaining: ' + minutes + ':' + seconds);
        }

        function handleOtpExpired() {
            if (otpCountdownInterval) {
                clearInterval(otpCountdownInterval);
                otpCountdownInterval = null;
            }

            $('#otpTimer').addClass('d-none').text('');
            $('#otpExpiredMsg').removeClass('d-none');
            $('#verify_otp').addClass('d-none').prop('disabled', true);
            $('#resend_otp').removeClass('d-none');
        }

        function startOtpTimerFromExpiry(expiresAtTimestamp) {
            if (!expiresAtTimestamp) return;

            if (otpCountdownInterval) {
                clearInterval(otpCountdownInterval);
            }

            function tick() {
                const now = Math.floor(Date.now() / 1000);
                const remaining = parseInt(expiresAtTimestamp) - now;

                if (remaining <= 0) {
                    handleOtpExpired();
                    return;
                }

                $('#otpTimer').removeClass('d-none');
                $('#otpExpiredMsg').addClass('d-none');
                $('#verify_otp').removeClass('d-none').prop('disabled', false);
                $('#resend_otp').addClass('d-none');

                updateOtpTimerText(remaining);
            }

            tick();
            otpCountdownInterval = setInterval(tick, 1000);
        }

        function getPhoneNumber() {
            return $('#otp_phone').val() || $('#session_phone').val();
        }

        $(document).ready(function() {
            bindOtpInputs();

            const expiresAt = $('#otp_expires_at').val();
            if (expiresAt) {
                startOtpTimerFromExpiry(expiresAt);
            } else {
                $('#otpTimer').addClass('d-none');
            }

            $('#send_otp').on('click', function() {
                let phone = $('#otp_phone').val();

                if (!phone) {
                    $('.phone-feedback').text('ফোন নাম্বার দিন').addClass('text-danger');
                    return;
                }

                $(this).prop('disabled', true).text('OTP পাঠানো হচ্ছে...');

                $.post("{{ route('send.otp') }}", {
                    _token: "{{ csrf_token() }}",
                    phone: phone
                }, function(res) {
                    if (res.status === 'success') {
                        $('#session_phone').val(phone);
                        $('#phoneRow').addClass('d-none');
                        $('#otpRow').removeClass('d-none');
                        $('.phone-feedback').text(res.message).removeClass('text-danger').addClass(
                            'text-success');

                        if (res.otp_expires_at) {
                            $('#otp_expires_at').val(res.otp_expires_at);
                            startOtpTimerFromExpiry(res.otp_expires_at);
                        }
                    }
                }).fail(function(xhr) {
                    let msg = xhr.responseJSON?.message ?? 'OTP পাঠানো যায়নি';
                    $('.phone-feedback').text(msg).removeClass('text-success').addClass(
                        'text-danger');
                }).always(function() {
                    $('#send_otp').prop('disabled', false).text('ওটিপি পাঠান');
                });
            });

            $('#verify_otp').on('click', function() {
                $.post("{{ route('verify.otp') }}", {
                    _token: "{{ csrf_token() }}",
                    phone: getPhoneNumber(),
                    otp: getOtpValue()
                }, function(res) {
                    if (res.status === 'success') {
                        if (otpCountdownInterval) {
                            clearInterval(otpCountdownInterval);
                        }
                        $('#otpMessage').html(
                            '<span class="text-success">OTP verify সফল হয়েছে</span>');

                        setTimeout(function() {
                            window.location.reload();
                        }, 500); // 0.5 sec delay

                        $('#otpWrapper').hide();
                        $('.checkoutForm').removeClass('d-none');
                    }
                }).fail(function(xhr) {
                    let msg = xhr.responseJSON?.message ?? 'OTP verify হয়নি';
                    $('#otpMessage').html('<span class="text-danger">' + msg + '</span>');
                });
            });

            $('#resend_otp').on('click', function() {
                let phone = getPhoneNumber();

                $(this).prop('disabled', true).text('Sending...');

                $.post("{{ route('resend.otp') }}", {
                    _token: "{{ csrf_token() }}",
                    phone: phone
                }, function(res) {
                    if (res.status === 'success') {
                        getOtpBoxes().forEach(box => box.value = '');
                        $('#otp').val('');
                        $('#otpMessage').html(
                            '<span class="text-success">নতুন OTP পাঠানো হয়েছে</span>');

                        if (res.otp_expires_at) {
                            $('#otp_expires_at').val(res.otp_expires_at);
                            startOtpTimerFromExpiry(res.otp_expires_at);
                        }
                    }
                }).fail(function(xhr) {
                    let msg = xhr.responseJSON?.message ?? 'OTP আবার পাঠানো যায়নি';
                    $('#otpMessage').html('<span class="text-danger">' + msg + '</span>');
                }).always(function() {
                    $('#resend_otp').prop('disabled', false).text('Resend OTP');
                });
            });

            if ('OTPCredential' in window) {
                const ac = new AbortController();

                navigator.credentials.get({
                    otp: {
                        transport: ['sms']
                    },
                    signal: ac.signal
                }).then(otp => {
                    if (otp && otp.code) {
                        setOtpValue(otp.code);
                    }
                }).catch(() => {});
            }
        });
    </script>
@endpush
