@extends('web.layouts.app')
@section('title', 'আবেদন সফল | ' . $web_config['name']->value)

@section('style')
    <style>
        @import url('https://fonts.maateen.me/solaiman-lipi/font.css');
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');

        :root {
            --inv-gold: #f59e0b;
            --inv-gold-dark: #d97706;
            --inv-gold-light: #fffbeb;
            --inv-blue: #1d4ed8;
            --inv-dark: #0f172a;
        }

        .inv-success-page {
            min-height: 100vh;
            background: linear-gradient(160deg, #0f172a 0%, #1e3a5f 40%, #1e293b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 16px;
            font-family: 'Inter', 'SolaimanLipi', sans-serif;
            position: relative;
            overflow: hidden;
        }

        /* Background decoration */
        .inv-success-page::before {
            content: '';
            position: absolute;
            top: -100px;
            right: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .inv-success-page::after {
            content: '';
            position: absolute;
            bottom: -80px;
            left: -80px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(29, 78, 216, 0.15) 0%, transparent 70%);
            border-radius: 50%;
        }

        .inv-success-wrap {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 580px;
        }

        /* ── Trophy Icon ── */
        .inv-trophy-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 32px;
            position: relative;
        }

        .inv-trophy-outer {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 2px solid rgba(245, 158, 11, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: inv-ring 2.5s ease-out infinite;
        }

        .inv-trophy-inner {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--inv-gold) 0%, var(--inv-gold-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 30px rgba(245, 158, 11, 0.4);
            animation: inv-pop .5s cubic-bezier(.175, .885, .32, 1.275) .1s both;
        }

        .inv-trophy-inner i {
            font-size: 36px;
            color: #fff;
        }

        @keyframes inv-ring {
            0% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.3);
            }

            70% {
                box-shadow: 0 0 0 20px rgba(245, 158, 11, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(245, 158, 11, 0);
            }
        }

        @keyframes inv-pop {
            from {
                transform: scale(0) rotate(-10deg);
                opacity: 0;
            }

            to {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
        }

        /* ── Card ── */
        .inv-success-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 44px 40px;
            text-align: center;
            animation: inv-slide .5s ease-out both;
        }

        @keyframes inv-slide {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .inv-success-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: #fbbf24;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 20px;
            margin-bottom: 16px;
        }

        .inv-success-title {
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 10px;
            line-height: 1.2;
        }

        .inv-success-title span {
            color: var(--inv-gold);
        }

        .inv-success-sub {
            font-size: 15px;
            color: rgba(255, 255, 255, 0.6);
            line-height: 1.7;
            margin-bottom: 36px;
            font-family: 'SolaimanLipi', sans-serif;
        }

        /* ── Timeline Steps ── */
        .inv-timeline {
            display: flex;
            flex-direction: column;
            gap: 0;
            margin-bottom: 36px;
            text-align: left;
        }

        .inv-tl-item {
            display: flex;
            gap: 16px;
            position: relative;
        }

        .inv-tl-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 19px;
            top: 40px;
            bottom: 0;
            width: 2px;
            background: rgba(255, 255, 255, 0.08);
        }

        .inv-tl-left {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-shrink: 0;
        }

        .inv-tl-num {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: #fbbf24;
            font-size: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-bottom: 0;
        }

        .inv-tl-body {
            padding: 8px 0 24px;
        }

        .inv-tl-title {
            font-size: 14px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 2px;
        }

        .inv-tl-desc {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.5);
            font-family: 'SolaimanLipi', sans-serif;
            line-height: 1.5;
        }

        /* ── Divider ── */
        .inv-divider {
            border: none;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            margin: 0 0 28px;
        }

        /* ── Buttons ── */
        .inv-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .inv-btn-gold {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 28px;
            background: linear-gradient(135deg, var(--inv-gold) 0%, var(--inv-gold-dark) 100%);
            color: #0f172a;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: transform .15s, box-shadow .15s;
        }

        .inv-btn-gold:hover {
            color: #0f172a;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(245, 158, 11, 0.35);
        }

        .inv-btn-ghost {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 28px;
            background: transparent;
            color: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: border-color .15s, color .15s;
        }

        .inv-btn-ghost:hover {
            border-color: rgba(255, 255, 255, 0.35);
            color: #fff;
        }

        /* ── Bottom Note ── */
        .inv-note {
            margin-top: 24px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.3);
            font-family: 'SolaimanLipi', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .inv-note i {
            color: #fbbf24;
        }

        @media (max-width: 576px) {
            .inv-success-card {
                padding: 32px 20px;
            }

            .inv-success-title {
                font-size: 22px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="inv-success-page">
        <div class="inv-success-wrap">

            {{-- Trophy --}}
            <div class="inv-trophy-wrap">
                <div class="inv-trophy-outer">
                    <div class="inv-trophy-inner">
                        <i class="bi bi-trophy-fill"></i>
                    </div>
                </div>
            </div>

            <div class="inv-success-card">

                <div class="inv-success-tag">
                    <i class="bi bi-check2-circle"></i> সফলভাবে জমা হয়েছে
                </div>

                <h1 class="inv-success-title">অভিনন্দন! <span>আপনার আবেদন</span><br>গ্রহণ করা হয়েছে</h1>
                <p class="inv-success-sub">
                    আপনার বিনিয়োগ আবেদন আমাদের টিম পেয়েছে। আমরা সর্বোচ্চ ৪৮ ঘণ্টার মধ্যে আপনার সাথে যোগাযোগ করব এবং
                    বিস্তারিত আলোচনা করব।
                </p>

                {{-- Timeline --}}
                <div class="inv-timeline">
                    <div class="inv-tl-item">
                        <div class="inv-tl-left">
                            <div class="inv-tl-num">১</div>
                        </div>
                        <div class="inv-tl-body">
                            <div class="inv-tl-title">আবেদন রিভিউ</div>
                            <div class="inv-tl-desc">আমাদের টিম আপনার আবেদন যাচাই করবে</div>
                        </div>
                    </div>
                    <div class="inv-tl-item">
                        <div class="inv-tl-left">
                            <div class="inv-tl-num">২</div>
                        </div>
                        <div class="inv-tl-body">
                            <div class="inv-tl-title">ফোন কলে আলোচনা</div>
                            <div class="inv-tl-desc">একজন বিশেষজ্ঞ আপনাকে কল করবেন</div>
                        </div>
                    </div>
                    <div class="inv-tl-item">
                        <div class="inv-tl-left">
                            <div class="inv-tl-num">৩</div>
                        </div>
                        <div class="inv-tl-body">
                            <div class="inv-tl-title">চুক্তি সম্পাদন</div>
                            <div class="inv-tl-desc">আনুষ্ঠানিক চুক্তি ও কাগজপত্র সম্পন্ন</div>
                        </div>
                    </div>
                    <div class="inv-tl-item">
                        <div class="inv-tl-left">
                            <div class="inv-tl-num">৪</div>
                        </div>
                        <div class="inv-tl-body" style="padding-bottom: 0;">
                            <div class="inv-tl-title">বিনিয়োগ শুরু</div>
                            <div class="inv-tl-desc">আপনার বিনিয়োগ সক্রিয় হয় এবং রিটার্ন শুরু হয়</div>
                        </div>
                    </div>
                </div>

                <hr class="inv-divider">

                <div class="inv-actions">
                    <a href="{{ route('home') }}" class="inv-btn-gold">
                        <i class="bi bi-house-door-fill"></i> হোম পেজে যান
                    </a>
                    <a href="{{ route('investor') }}" class="inv-btn-ghost">
                        <i class="bi bi-arrow-repeat"></i> নতুন আবেদন
                    </a>
                </div>

                <div class="inv-note">
                    <i class="bi bi-envelope-check-fill"></i>
                    জরুরি প্রয়োজনে সরাসরি WhatsApp এ যোগাযোগ করুন
                </div>

            </div>
        </div>
    </div>
@endsection
