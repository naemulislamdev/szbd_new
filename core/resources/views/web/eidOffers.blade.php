@extends('web.layouts.app')

@section('title', Str::limit($eidoffer->title, 60) . ' | ' . $web_config['name']->value)
@section('style')
    <style>
        /* .offer-bar {
                background: #111;
                color: #fff;
                font-size: 14px;
                overflow: hidden;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
            } */
        .offer-bar {
            background: #fff;
            box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);

            color: #000;
            font-size: 14px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 10px;
            padding: 8px 12px;

            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Badge */
        .badge-offer {
            background: red;
            padding: 5px 10px;
            border-radius: 5px 5px 0 0;
            font-weight: bold;
            animation: blink 1s infinite;
            white-space: nowrap;
        }

        /* Marquee */
        .marquee {
            flex: 1;
            overflow: hidden;
            white-space: nowrap;
            margin: 0 10px;
        }

        .marquee span {
            display: inline-block;
            padding-left: 100%;
            animation: scroll 12s linear infinite;
        }

        /* Countdown */
        /* .countdown {
                background: #222;
                padding: 5px 10px;
                border-radius: 5px;
                white-space: nowrap;
            } */
        .countdown {
            background: #000;
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            display: inline-block;
            color: #fff;
            padding: 5px 10px;
            border-radius: 8px;

            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        /* ANIMATIONS */
        @keyframes blink {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.3;
            }

            100% {
                opacity: 1;
            }
        }

        @keyframes scroll {
            0% {
                transform: translateX(0);
            }

            100% {
                transform: translateX(-100%);
            }
        }

        /* =========================
           📱 MOBILE RESPONSIVE
        ========================= */
        @media (max-width: 768px) {
            .offer-bar {
                flex-direction: column;
                text-align: center;
                padding: 8px;
            }

            .marquee {
                width: 100%;
                margin: 5px 0;
                font-size: 18px
            }

            .marquee span {
                font-size: 18px;
            }

            .countdown {
                width: 100%;
                text-align: center;
            }

            .badge-offer {

                border-radius: 5px;

            }
        }

        .marquee span {
            font-size: 17px;
            font-weight: 600;
            letter-spacing: 0.4px;
            display: inline-block;
            padding-left: 100%;
            animation: scroll 15s linear infinite;
            /* 👈 এখানে speed */
        }

        :root {
            --marquee-speed: 35s;
        }

        .marquee span {
            animation: scroll var(--marquee-speed) linear infinite;
        }



        /* Small mobile */
        @media (max-width: 480px) {
            .offer-bar {
                font-size: 12px;
            }

            .badge-offer,
            .countdown {
                padding: 4px 8px;
            }


        }
    </style>
@endsection

@push('css_or_js')
    <meta property="og:image" content="{{ asset('storage/company') }}/{{ $web_config['web_logo']->value }}" />
    <meta property="og:title" content="Premium Clothing & Original Skincare |" />
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:description" content="{!! substr(strip_tags($web_config['about']->value), 0, 100) !!}">

    <meta property="twitter:card" content="{{ asset('storage/company') }}/{{ $web_config['web_logo']->value }}" />
    <meta property="twitter:title" content="Welcome To {{ $web_config['name']->value }} Home" />
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:description" content="{!! substr(strip_tags($web_config['about']->value), 0, 100) !!}">
@endpush
@section('content')
    <section>
        <div class="container mt-4">
            {{-- Bredcrumb start  --}}
            <nav class="breadcrumb custom-breadcrumb mt-3">
                <a class="breadcrumb-item" href="{{ route('home') }}">Home</a>
                <span class="breadcrumb-item active" aria-current="page">{{ $eidoffer->title }}</span>
            </nav>
            {{--  Bredcrumb End --}}
            <!-- TOP OFFER BAR -->
            @if ($eidoffer->title == 'Buy 2 Get 1')
                <div class="offer-bar d-flex align-items-center justify-content-between rounded mb-3">
                    <!-- Blinking Badge -->
                    <span class="badge-offer rounded-none">📢 HOT OFFER</span>
                    <!-- Marquee Text -->
                    <div class="marquee">
                        <span>
                            🟢 ২টি পণ্য কিনলে ১টি একদম ফ্রি 🎁 |
                            🔴 Buy 2 Get 1 Free Offer (Limited Time) |
                            🟡 স্পেশাল ডিল: ২টি কিনুন, ১টি ফ্রি পান 🎉 |
                            🟠 হট অফার 🔥 ২টি প্রোডাক্ট নিন, ১টি ফ্রি নিন |
                            ⚫ Limited Offer: Buy 2 Get 1 Free চলছে এখনই! |
                            🟣 এক্সক্লুসিভ অফার: বেশি কিনলে বেশি সেভিং 🎁 |
                            🔵 Super Deal: 2 Products = 1 Free
                        </span>
                    </div>
                    <!-- Countdown -->
                    <div class="countdown">
                        ⏳ অফার শেষ সময়: <span id="timer">00:00:00</span>
                    </div>

                </div>
            @endif
            {{-- <div class="mb-4">
                <div class=" text-center">
                    <div class="section-heading-title position-relative z-30 ">
                        <h1>{{ $eidoffer->title }}</h1>
                        <div class="heading-border"></div>
                    </div>


                </div>
            </div> --}}
            <div class="row  ">
                @php $decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings'); @endphp
                <!-- Your product columns go here -->
                @if ($eidoffer->product_ids != null)
                    @php
                        $productIds = json_decode($eidoffer->product_ids, true) ?? [];
                        $eidOfferProducts = \App\Models\Product::whereIn('id', $productIds)
                            ->where('status', 1)
                            ->latest('created_at')
                            ->get();
                    @endphp
                    @foreach ($eidOfferProducts as $product)
                        @include('web.products.product_box', ['dataCategory' => 'category1'])
                    @endforeach
                @endif
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        // Countdown Timer (3 hours from now)
        let endTime = new Date().getTime() + (3 * 60 * 60 * 1000);

        function updateTimer() {
            let now = new Date().getTime();
            let diff = endTime - now;

            if (diff <= 0) {
                document.getElementById("timer").innerHTML = "EXPIRED";
                return;
            }

            let h = Math.floor(diff / (1000 * 60 * 60));
            let m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            let s = Math.floor((diff % (1000 * 60)) / 1000);

            document.getElementById("timer").innerHTML =
                String(h).padStart(2, '0') + ":" +
                String(m).padStart(2, '0') + ":" +
                String(s).padStart(2, '0');
        }

        setInterval(updateTimer, 1000);
        updateTimer();
    </script>
@endpush
