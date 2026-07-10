@extends('web.layouts.app')
@section('title', 'আবেদন সফল | ' . $web_config['name']->value)

@push('css_or_js')
    <style>
        @import url('https://fonts.maateen.me/solaiman-lipi/font.css');
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

        :root {
            --ws-orange: #ff5d00;
            --ws-orange-dark: #d94e00;
            --ws-orange-light: #fff3ed;
            --ws-orange-glow: rgba(255, 93, 0, 0.12);
        }

        .ws-success-page {
            min-height: 100vh;
            background: #f9fafb;
            display: flex;
            align-items: center;
            font-family: 'Inter', 'SolaimanLipi', sans-serif;
            padding: 60px 0;
        }

        /* ── Animated Check ── */
        .ws-check-wrap {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--ws-orange) 0%, var(--ws-orange-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 28px;
            position: relative;
            box-shadow: 0 0 0 0 var(--ws-orange-glow);
            animation: ws-pulse 2s ease-out infinite;
        }

        .ws-check-wrap i {
            font-size: 38px;
            color: #fff;
            animation: ws-pop .5s cubic-bezier(.175, .885, .32, 1.275) .1s both;
        }

        @keyframes ws-pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 93, 0, .35);
            }

            70% {
                box-shadow: 0 0 0 18px rgba(255, 93, 0, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 93, 0, 0);
            }
        }

        @keyframes ws-pop {
            from {
                transform: scale(0);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* ── Main Card ── */
        .ws-success-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.06), 0 20px 48px -8px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            padding: 52px 48px;
            text-align: center;
            max-width: 560px;
            margin: 0 auto;
            animation: ws-slide-up .5s ease-out both;
        }

        @keyframes ws-slide-up {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .ws-success-title {
            font-size: 26px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .ws-success-subtitle {
            font-size: 15px;
            color: #6b7280;
            line-height: 1.7;
            margin-bottom: 32px;
            font-family: 'SolaimanLipi', sans-serif;
        }

        /* ── Info Steps ── */
        .ws-steps {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-bottom: 36px;
            flex-wrap: wrap;
        }

        .ws-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            flex: 1;
            min-width: 100px;
            padding: 16px 10px;
            background: var(--ws-orange-light);
            border-radius: 14px;
            border: 1px solid rgba(255, 93, 0, 0.12);
        }

        .ws-step-num {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: var(--ws-orange);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .ws-step-text {
            font-size: 12px;
            color: #7c3410;
            font-family: 'SolaimanLipi', sans-serif;
            line-height: 1.4;
            text-align: center;
        }

        /* ── Divider ── */
        .ws-divider {
            border: none;
            border-top: 1px solid #f3f4f6;
            margin: 0 0 28px;
        }

        /* ── Action Buttons ── */
        .ws-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .ws-btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 12px 28px;
            background: linear-gradient(135deg, var(--ws-orange) 0%, var(--ws-orange-dark) 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: transform .15s, box-shadow .15s;
            font-family: 'Inter', sans-serif;
        }

        .ws-btn-primary:hover {
            color: #fff;

            box-shadow: 0 8px 24px rgba(255, 93, 0, 0.3);
        }

        .ws-btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 12px 28px;
            background: transparent;
            color: #4b5563;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: border-color .15s, color .15s;
            font-family: 'Inter', sans-serif;
        }

        .ws-btn-ghost:hover {
            border-color: var(--ws-orange);
            color: var(--ws-orange);
        }

        /* ── Footer Note ── */
        .ws-note {
            margin-top: 24px;
            font-size: 12px;
            color: #9ca3af;
            font-family: 'SolaimanLipi', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .ws-note i {
            color: #22c55e;
        }

        @media (max-width: 576px) {
            .ws-success-card {
                padding: 36px 24px;
            }

            .ws-success-title {
                font-size: 22px;
            }

            .ws-steps {
                gap: 8px;
            }

            .ws-step {
                min-width: 80px;
                padding: 12px 8px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="ws-success-page">
        <div class="container">
            <div class="ws-success-card">

                {{-- Animated Check Icon --}}
                <div class="ws-check-wrap">
                    <i class="bi bi-check-lg"></i>
                </div>

                <h1 class="ws-success-title">আবেদন সফলভাবে জমা হয়েছে! 🎉</h1>
                <p class="ws-success-subtitle">
                    আপনার Wholesale আবেদন আমরা পেয়েছি। আমাদের টিম শীঘ্রই আপনার সাথে যোগাযোগ করবে।

                </p>

                {{-- Next Steps --}}
                <div class="ws-steps">
                    <div class="ws-step">
                        <div class="ws-step-num">১</div>
                        <div class="ws-step-text">আবেদন রিভিউ হবে</div>
                    </div>
                    <div class="ws-step">
                        <div class="ws-step-num">২</div>
                        <div class="ws-step-text">টিম যোগাযোগ করবে</div>
                    </div>
                    <div class="ws-step">
                        <div class="ws-step-num">৩</div>
                        <div class="ws-step-text">চুক্তি সম্পন্ন করুন</div>
                    </div>
                    <div class="ws-step">
                        <div class="ws-step-num">৪</div>
                        <div class="ws-step-text">অর্ডার শুরু করুন</div>
                    </div>
                </div>

                <hr class="ws-divider">

                {{-- Action Buttons --}}
                <div class="ws-actions">
                    <a href="{{ route('home') }}" class="ws-btn-primary">
                        <i class="bi bi-house-door-fill"></i> হোম পেজে যান
                    </a>
                    <a href="{{ route('wholesale.crate') }}" class="ws-btn-ghost">
                        <i class="bi bi-arrow-repeat"></i> নতুন আবেদন করুন
                    </a>
                </div>

                <div class="ws-note">
                    <i class="bi bi-envelope-check-fill"></i>
                    প্রয়োজনে আমাদের সাথে সরাসরি যোগাযোগ করুন
                </div>

            </div>
        </div>
    </div>
@endsection
