@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('My Shopping Cart'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="{{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{!! substr(strip_tags($web_config['about']->value), 0, 100) !!}">

    <meta property="twitter:card" content="{{asset('storage/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="{{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{!! substr(strip_tags($web_config['about']->value),0,100) !!}">
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/front-end')}}/css/shop-cart.css"/>
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
        .address-title{
            font-size:22px;
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
    </style>
@endpush

@section('content')
    <div class="container pb-5 mb-2 mt-3" id="cart-summary">
        @include('web.layouts.partials.cart_details')
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
@endpush
