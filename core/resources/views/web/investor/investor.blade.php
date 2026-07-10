@extends('web.layouts.app')
@section('title', 'Investor | ' . $web_config['name']->value)
@section('meta_description',
    'Understand the investment options available and team up with an established brand for
    long-term growth potential.')


    <style>
        @import url('https://fonts.maateen.me/solaiman-lipi/font.css');
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        :root {
            --inv-gold: #f59e0b;
            --inv-gold-dark: #d97706;
            --inv-gold-light: #fffbeb;
            --inv-gold-glow: rgba(245, 158, 11, 0.15);
            --inv-dark: #0f172a;
            --inv-dark2: #1e293b;
            --inv-text: #1e293b;
            --inv-muted: #64748b;
            --inv-border: #e2e8f0;
            --inv-bg: #f8fafc;
        }

        .inv-page {
            background: var(--inv-bg);
            min-height: 100vh;
            padding-bottom: 80px;
            font-family: 'Inter', 'SolaimanLipi', sans-serif;
        }

        /* ── Top Banner ── */
        .inv-banner {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 55%, #1d4ed8 100%);
            padding: 50px 0 44px;
            margin-bottom: 50px;
            position: relative;
            overflow: hidden;
        }

        .inv-banner::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 80% 50%, rgba(245, 158, 11, 0.12) 0%, transparent 65%);
        }

        .inv-banner::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 40px;
            background: var(--inv-bg);
            clip-path: ellipse(55% 100% at 50% 100%);
        }

        .inv-breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .inv-breadcrumb a {
            color: rgba(255, 255, 255, 0.55);
            text-decoration: none;
        }

        .inv-breadcrumb a:hover {
            color: #fff;
        }

        .inv-breadcrumb .sep {
            color: rgba(255, 255, 255, 0.25);
        }

        .inv-breadcrumb .cur {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 500;
        }

        .inv-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.35);
            color: #fbbf24;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 20px;
            margin-bottom: 16px;
        }

        .inv-tag i {
            font-size: 12px;
        }

        .inv-banner h1 {
            color: #fff;
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 12px;
        }

        .inv-banner h1 span {
            color: var(--inv-gold);
        }

        .inv-banner p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 15px;
            max-width: 420px;
            line-height: 1.7;
            font-family: 'SolaimanLipi', sans-serif;
        }

        /* ── Trust Chips ── */
        .inv-chips {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 24px;
        }

        .inv-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.75);
            font-size: 12px;
            padding: 6px 14px;
            border-radius: 20px;
            font-family: 'SolaimanLipi', sans-serif;
        }

        .inv-chip i {
            color: var(--inv-gold);
            font-size: 12px;
        }

        /* ── Image Section ── */
        .inv-img-wrap {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .inv-img-wrap img {
            width: 100%;
            height: 320px;
            object-fit: cover;
            display: block;
        }

        .inv-img-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(15, 23, 42, 0.85) 0%, transparent 100%);
            padding: 24px 20px 20px;
        }

        .inv-img-overlay p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 13px;
            font-family: 'SolaimanLipi', sans-serif;
            margin: 0;
        }

        .inv-img-overlay strong {
            color: var(--inv-gold);
        }

        /* ── Info Cards below image ── */
        .inv-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 16px;
        }

        .inv-info-box {
            background: #fff;
            border: 1px solid var(--inv-border);
            border-radius: 14px;
            padding: 16px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .inv-info-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--inv-gold-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--inv-gold-dark);
            font-size: 16px;
            flex-shrink: 0;
        }

        .inv-info-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--inv-text);
            margin-bottom: 2px;
        }

        .inv-info-desc {
            font-size: 12px;
            color: var(--inv-muted);
            font-family: 'SolaimanLipi', sans-serif;
            line-height: 1.4;
        }

        /* ── Form Card ── */
        .inv-card {
            background: #fff;
            border-radius: 22px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.06), 0 24px 48px -8px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--inv-border);
            overflow: hidden;
        }

        .inv-card-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
            padding: 26px 28px;
            position: relative;
            overflow: hidden;
        }

        .inv-card-header::before {
            content: '';
            position: absolute;
            right: -30px;
            top: -30px;
            width: 120px;
            height: 120px;
            background: rgba(245, 158, 11, 0.08);
            border-radius: 50%;
        }

        .inv-card-header::after {
            content: '';
            position: absolute;
            right: 20px;
            bottom: -40px;
            width: 80px;
            height: 80px;
            background: rgba(245, 158, 11, 0.06);
            border-radius: 50%;
        }

        .inv-card-header h4 {
            color: #fff;
            font-weight: 700;
            font-size: 18px;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .inv-card-header p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 13px;
            margin: 5px 0 0;
            font-family: 'SolaimanLipi', sans-serif;
            position: relative;
            z-index: 1;
        }

        .inv-card-header .inv-header-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(245, 158, 11, 0.2);
            border: 1px solid rgba(245, 158, 11, 0.4);
            color: #fbbf24;
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 10px;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .inv-card-body {
            padding: 28px;
        }

        /* ── Form ── */
        .inv-form-group {
            margin-bottom: 18px;
        }

        .inv-label {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 600;
            color: var(--inv-text);
            margin-bottom: 7px;
            flex-wrap: wrap;
        }

        .inv-label i {
            color: var(--inv-gold-dark);
            font-size: 13px;
        }

        .inv-label .req {
            color: #ef4444;
        }

        .inv-label .opt {
            font-size: 11px;
            font-weight: 400;
            color: var(--inv-muted);
            background: #f1f5f9;
            border-radius: 6px;
            padding: 1px 7px;
        }

        .inv-input {
            width: 100%;
            padding: 10px 14px;
            border: 2px solid var(--inv-border);
            border-radius: 10px;
            font-size: 14px;
            font-family: 'SolaimanLipi', sans-serif;
            color: var(--inv-text);
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
            outline: none;
            box-sizing: border-box;
        }

        .inv-input::placeholder {
            color: #cbd5e1;
        }

        .inv-input:focus {
            border-color: var(--inv-gold);
            box-shadow: 0 0 0 4px var(--inv-gold-glow);
        }

        .inv-input.is-invalid {
            border-color: #ef4444;
        }

        .inv-textarea {
            resize: none;
            height: 90px;
        }

        .inv-error {
            color: #ef4444;
            font-size: 12px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 4px;
            font-family: 'SolaimanLipi', sans-serif;
        }

        /* ── Radio Investment Options ── */
        .inv-radio-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .inv-radio-label {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border: 2px solid var(--inv-border);
            border-radius: 10px;
            cursor: pointer;
            font-size: 13px;
            font-family: 'SolaimanLipi', sans-serif;
            color: var(--inv-text);
            transition: border-color .2s, background .2s;
            user-select: none;
        }

        .inv-radio-label:hover {
            border-color: var(--inv-gold);
            background: var(--inv-gold-light);
        }

        .inv-radio-label input[type="radio"] {
            display: none;
        }

        .inv-radio-label.selected {
            border-color: var(--inv-gold);
            background: var(--inv-gold-light);
            color: var(--inv-gold-dark);
            font-weight: 600;
        }

        .inv-radio-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid var(--inv-border);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: border-color .2s;
        }

        .inv-radio-label.selected .inv-radio-dot {
            border-color: var(--inv-gold-dark);
            background: var(--inv-gold-dark);
        }

        .inv-radio-label.selected .inv-radio-dot::after {
            content: '';
            width: 6px;
            height: 6px;
            background: #fff;
            border-radius: 50%;
        }

        /* ── Bottom Row ── */
        .inv-bottom-row {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .inv-wa-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 11px 16px;
            background: #f0fdf4;
            border: 2px solid #bbf7d0;
            border-radius: 12px;
            text-decoration: none;
            color: #166534;
            font-size: 13px;
            font-weight: 600;
            font-family: 'SolaimanLipi', sans-serif;
            transition: background .2s, border-color .2s;
            flex-shrink: 0;
        }

        .inv-wa-btn:hover {
            background: #dcfce7;
            border-color: #86efac;
            color: #166534;
            display: flex;
            justify-content: center;
        }

        .inv-wa-btn img {
            width: 24px;
        }

        .inv-submit-btn {
            flex: 1;
            padding: 12px 20px;
            background: linear-gradient(135deg, #1d4ed8 0%, #1e3a8a 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: transform .15s, box-shadow .15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-width: 160px;
        }

        .inv-submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(29, 78, 216, 0.35);
        }

        .inv-submit-btn:active {
            transform: none;
            box-shadow: none;
        }

        .inv-submit-btn:disabled {
            opacity: .8;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        /* ── Spinner ── */
        .inv-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: inv-spin .7s linear infinite;
            vertical-align: middle;
        }

        @keyframes inv-spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .inv-banner {
                padding: 20px 0 54px;
                margin-bottom: 32px;
            }

            .inv-img-wrap img {
                width: 100%;
                height: auto;

            }

            .inv-radio-grid {
                grid-template-columns: 1fr;
            }

            .inv-info-grid {
                grid-template-columns: 1fr;
            }

            .inv-card-body {
                padding: 20px;
            }

            .inv-bottom-row {
                flex-direction: column;
            }

            .inv-submit-btn {
                width: 100%;
            }

            .inv-wa-btn {
                width: 100%;
                justify-content: center;
            }
        }

        .inv-chip.form-click-btn {
            cursor: pointer;
            z-index: 999;
            font-size: 17px;
            width: 100%;
        }

        .inv-chip.form-click-btn:hover {
            background: #101B2F !important;
            color: #fff;
        }
    </style>


@section('content')
    <div class="inv-page">

        {{-- ── Banner ── --}}
        <div class="inv-banner">
            <div class="container">
                <nav class="inv-breadcrumb">
                    <a href="{{ route('home') }}"><i class="bi bi-house-door-fill me-1"></i>Home</a>
                    <span class="sep">/</span>
                    <span class="cur">Investor</span>
                </nav>

                <div class="inv-tag">
                    <i class="bi bi-graph-up-arrow"></i> বিনিয়োগের সুযোগ
                </div>
                <h1>আজই বিনিয়োগ করুন,<br><span>ভবিষ্যৎ নিশ্চিত করুন</span></h1>
                <p>একটি প্রতিষ্ঠিত ব্র্যান্ডের সাথে অংশীদার হন এবং দীর্ঘমেয়াদী লাভজনক বিনিয়োগের সুযোগ নিন।</p>

                <div class="inv-chips">
                    <span class="inv-chip"><i class="bi bi-shield-fill-check"></i> নিরাপদ বিনিয়োগ</span>
                    <span class="inv-chip"><i class="bi bi-percent"></i> আকর্ষণীয় রিটার্ন</span>
                    <span class="inv-chip"><i class="bi bi-people-fill"></i> বিশ্বস্ত পার্টনারশিপ</span>
                    <span class="inv-chip"><i class="bi bi-clock-fill"></i> দ্রুত প্রসেসিং</span>
                    <button type="button" class="d-block d-lg-none inv-chip form-click-btn cursor-pointer mt-2"
                        onclick="scrollToForm()">
                        ফর্মটি পূরণ করতে ক্লিক করুন
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Main ── --}}
        <div class="container">
            <div class="row align-items-start g-4">

                {{-- Left: Form --}}
                <div class="col-lg-6 order-2 order-lg-1" id="FormSection">
                    <div class="inv-card">
                        <div class="inv-card-header">
                            <div class="inv-header-badge">
                                <i class="bi bi-star-fill"></i> Exclusive Program
                            </div>
                            <h4><i class="bi bi-file-earmark-text-fill me-2"></i>Investment আবেদন ফর্ম</h4>
                            <p>নিচের তথ্যগুলো সঠিকভাবে পূরণ করুন, আমরা যোগাযোগ করব</p>
                        </div>
                        <div class="inv-card-body">
                            <form id="investorForm" action="{{ route('investor.store') }}" method="POST">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <div class="inv-form-group mb-0">
                                            <label class="inv-label">
                                                <i class="bi bi-person-fill"></i> আপনার নাম <span class="req">*</span>
                                            </label>
                                            <input type="text" name="name" value="{{ old('name') }}"
                                                class="inv-input @error('name') is-invalid @enderror"
                                                placeholder="পূর্ণ নাম লিখুন">
                                            @error('name')
                                                <div class="inv-error"><i class="bi bi-exclamation-circle-fill"></i>
                                                    {{ ucwords($message) }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mt-2 mt-lg-0">
                                        <div class="inv-form-group mb-0">
                                            <label class="inv-label">
                                                <i class="bi bi-telephone-fill"></i> মোবাইল নম্বর <span
                                                    class="req">*</span>
                                            </label>
                                            <input type="text" name="mobile_number" value="{{ old('mobile_number') }}"
                                                class="inv-input @error('mobile_number') is-invalid @enderror"
                                                placeholder="01XXXXXXXXX">
                                            @error('mobile_number')
                                                <div class="inv-error"><i class="bi bi-exclamation-circle-fill"></i>
                                                    {{ ucwords($message) }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="inv-form-group mt-3">
                                    <label class="inv-label">
                                        <i class="bi bi-geo-alt-fill"></i> আপনার ঠিকানা <span class="req">*</span>
                                    </label>
                                    <textarea name="address" class="inv-input inv-textarea @error('address') is-invalid @enderror"
                                        placeholder="বিস্তারিত ঠিকানা লিখুন">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="inv-error"><i class="bi bi-exclamation-circle-fill"></i>
                                            {{ ucwords($message) }}</div>
                                    @enderror
                                </div>

                                <div class="inv-form-group">
                                    <label class="inv-label">
                                        <i class="bi bi-briefcase-fill"></i> পেশা
                                        <span class="opt">Optional</span>
                                    </label>
                                    <input type="text" name="occupation" value="{{ old('occupation') }}"
                                        class="inv-input @error('occupation') is-invalid @enderror"
                                        placeholder="যেমন: ব্যবসায়ী, চাকরিজীবী">
                                    @error('occupation')
                                        <div class="inv-error"><i class="bi bi-exclamation-circle-fill"></i>
                                            {{ ucwords($message) }}</div>
                                    @enderror
                                </div>

                                <div class="inv-form-group">
                                    <label class="inv-label">
                                        <i class="bi bi-currency-dollar"></i> বিনিয়োগের পরিমাণ <span
                                            class="req">*</span>
                                    </label>
                                    <div class="inv-radio-grid" id="inv-radio-grid">
                                        @php
                                            $amounts = [
                                                '1-2' => '১ থেকে ২ লাখ',
                                                '2-5' => '২ থেকে ৫ লাখ',
                                                '5-7' => '৫ থেকে ৭ লাখ',
                                                '7-10' => '৭ থেকে ১০ লাখ',
                                                '10+' => '১০ লাখের বেশি',
                                            ];
                                        @endphp
                                        @foreach ($amounts as $val => $label)
                                            <label
                                                class="inv-radio-label {{ old('investment_amount') == $val ? 'selected' : '' }}">
                                                <input type="radio" name="investment_amount" value="{{ $val }}"
                                                    {{ old('investment_amount') == $val ? 'checked' : '' }}>
                                                <span class="inv-radio-dot">
                                                    @if (old('investment_amount') == $val)
                                                        <span
                                                            style="width:6px;height:6px;background:#fff;border-radius:50%;display:block;margin:auto;"></span>
                                                    @endif
                                                </span>
                                                {{ $label }}
                                            </label>
                                        @endforeach
                                    </div>
                                    @error('investment_amount')
                                        <div class="inv-error mt-1"><i class="bi bi-exclamation-circle-fill"></i>
                                            {{ ucwords($message) }}</div>
                                    @enderror
                                </div>

                                <div class="inv-form-group">
                                    <label class="inv-label">
                                        <i class="bi bi-chat-left-text-fill"></i> মন্তব্য
                                        <span class="opt">Optional</span>
                                    </label>
                                    <textarea name="comment" class="inv-input inv-textarea @error('comment') is-invalid @enderror"
                                        placeholder="আপনার প্রশ্ন বা মন্তব্য লিখুন">{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <div class="inv-error"><i class="bi bi-exclamation-circle-fill"></i>
                                            {{ ucwords($message) }}</div>
                                    @enderror
                                </div>

                                <div class="inv-bottom-row">
                                    <a class="inv-wa-btn" target="_blank"
                                        href="https://wa.me/8801934657964?text=Hello%2C%20I%20am%20interested%20in%20your%20investment%20program.%20Please%20share%20the%20details.">
                                        <img src="{{ asset('assets/frontend/images/logo/whatsapp.png') }}"
                                            alt="WhatsApp">
                                        বিস্তারিত জানতে মেসেজ করুন
                                    </a>

                                    <button type="submit" class="inv-submit-btn" id="inv-submit-btn">
                                        <span id="inv-btn-default">
                                            <i class="bi bi-send-fill"></i> আবেদন জমা দিন
                                        </span>
                                        <span id="inv-btn-loading" style="display:none;">
                                            <span class="inv-spinner"></span> জমা হচ্ছে...
                                        </span>
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>

                {{-- Right: Image + Info --}}
                <div class="col-lg-6 order-1 order-lg-2">
                    <div class="inv-img-wrap">
                        <img src="{{ asset('assets/frontend/img/invest.jpeg') }}" alt="Investor">
                        <div class="inv-img-overlay">
                            <p><strong>বিশ্বস্ত বিনিয়োগকারীদের</strong> পছন্দের প্ল্যাটফর্ম</p>
                        </div>
                    </div>

                    <div class="inv-info-grid">
                        <div class="inv-info-box">
                            <div class="inv-info-icon"><i class="bi bi-graph-up-arrow"></i></div>
                            <div>
                                <div class="inv-info-title">উচ্চ রিটার্ন</div>
                                <div class="inv-info-desc">প্রতি মাসে আকর্ষণীয় মুনাফা</div>
                            </div>
                        </div>
                        <div class="inv-info-box">
                            <div class="inv-info-icon"><i class="bi bi-shield-fill-check"></i></div>
                            <div>
                                <div class="inv-info-title">নিরাপদ বিনিয়োগ</div>
                                <div class="inv-info-desc">চুক্তিভিত্তিক সুরক্ষা নিশ্চিত</div>
                            </div>
                        </div>
                        <div class="inv-info-box">
                            <div class="inv-info-icon"><i class="bi bi-people-fill"></i></div>
                            <div>
                                <div class="inv-info-title">অভিজ্ঞ টিম</div>
                                <div class="inv-info-desc">দক্ষ ম্যানেজমেন্ট টিম সবসময়</div>
                            </div>
                        </div>
                        <div class="inv-info-box">
                            <div class="inv-info-icon"><i class="bi bi-clock-history"></i></div>
                            <div>
                                <div class="inv-info-title">দ্রুত প্রসেস</div>
                                <div class="inv-info-desc">৪৮ ঘণ্টার মধ্যে যোগাযোগ</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Radio custom style
        document.querySelectorAll('.inv-radio-label').forEach(function(label) {
            label.addEventListener('click', function() {
                document.querySelectorAll('.inv-radio-label').forEach(function(l) {
                    l.classList.remove('selected');
                    var dot = l.querySelector('.inv-radio-dot');
                    dot.innerHTML = '';
                });
                this.classList.add('selected');
                this.querySelector('input[type="radio"]').checked = true;
                var dot = this.querySelector('.inv-radio-dot');
                dot.innerHTML =
                    '<span style="width:6px;height:6px;background:#fff;border-radius:50%;display:block;margin:auto;"></span>';
            });
        });

        // Submit loader
        document.getElementById('investorForm').addEventListener('submit', function() {
            var btn = document.getElementById('inv-submit-btn');
            document.getElementById('inv-btn-default').style.display = 'none';
            document.getElementById('inv-btn-loading').style.display = 'inline-flex';
            btn.disabled = true;
        });
    </script>
    <script>
        function scrollToForm() {
            document.getElementById("FormSection").scrollIntoView({
                behavior: "smooth",
                block: "start"
            });
        }
    </script>

@endsection

@push('scripts')
    <script>
        document.getElementById('investorForm').addEventListener('submit', function(e) {
            setTimeout(() => {
                let name = document.querySelector('[name="name"]').value;
                let mobile_number = document.querySelector('[name="mobile_number"]').value;
                let address = document.querySelector('[name="address"]').value;
                let occupation = document.querySelector('[name="occupation"]').value;
                let investment = document.querySelector('[name="investment_amount"]:checked')?.value;
                let comment = document.querySelector('[name="comment"]').value;

                let message =
                    `Investment Data:\n\nName: ${name}\nPhone: ${mobile_number}\nAddress: ${address}\nOccupation: ${occupation}\nInvestment: ${investment}\nComment: ${comment}`;

                let phone = "8801934657964";
                let url = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
                window.open(url, '_blank');
            }, 500);
        });
    </script>
@endpush
