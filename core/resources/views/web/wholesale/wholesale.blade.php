@extends('web.layouts.app')
@section('title', 'Wholesale | ' . $web_config['name']->value)
@section('meta_description',
    'Check out our wholesale offers and purchase premium clothing and skincare at unbeatable
    prices in bulk.')
    @push('css_or_js')
        <style>
            @import url('https://fonts.maateen.me/solaiman-lipi/font.css');
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

            :root {
                --ws-orange: #ff5d00;
                --ws-orange-dark: #d94e00;
                --ws-orange-light: #fff3ed;
                --ws-orange-glow: rgba(255, 93, 0, 0.12);
                --ws-text: #1a1a1a;
                --ws-muted: #6b7280;
                --ws-border: #e5e7eb;
                --ws-bg: #f9fafb;
            }

            .ws-section {
                background: var(--ws-bg);
                min-height: 100vh;
                padding-bottom: 80px;
                font-family: 'Inter', 'SolaimanLipi', sans-serif;
            }

            /* ── Hero Banner ── */
            .ws-hero {
                background: linear-gradient(135deg, #1a1a1a 0%, #2d1a0e 60%, #ff5d00 100%);
                padding: 0px 0 10px;
                margin-bottom: 25px;
                position: relative;
                overflow: hidden;
            }

            .ws-hero::before {
                content: '';
                position: absolute;
                inset: 0;
                background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            }

            .ws-hero-badge {
                display: inline-block;
                background: rgba(255, 93, 0, 0.15);
                border: 1px solid rgba(255, 93, 0, 0.4);
                color: #ff8c4a;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: 2px;
                text-transform: uppercase;
                padding: 6px 16px;
                border-radius: 20px;
                margin-bottom: 18px;
            }

            .ws-hero h1 {
                color: #fff;
                font-size: clamp(2rem, 4vw, 3rem);
                font-weight: 700;
                line-height: 1.2;
                margin-bottom: 14px;
            }

            .ws-hero h1 span {
                color: var(--ws-orange);
            }

            .ws-hero p {
                color: rgba(255, 255, 255, 0.65);
                font-size: 16px;
                max-width: 440px;
                line-height: 1.7;
                font-family: 'SolaimanLipi', sans-serif;
            }

            /* ── Stats Strip ── */
            .ws-stats {
                display: flex;
                gap: 32px;
                margin-top: 32px;
                flex-wrap: wrap;
            }

            .ws-stat-item .val {
                font-size: 24px;
                font-weight: 700;
                color: #fff;
                line-height: 1;
            }

            .ws-stat-item .lbl {
                font-size: 12px;
                color: rgba(255, 255, 255, 0.5);
                margin-top: 4px;
            }

            .ws-stat-divider {
                width: 1px;
                background: rgba(255, 255, 255, 0.15);
                align-self: stretch;
            }

            /* ── Breadcrumb ── */
            .ws-breadcrumb {
                display: flex;
                align-items: center;
                gap: 6px;
                padding: 12px 0;
                font-size: 13px;
                margin-bottom: 16px;
            }

            .ws-breadcrumb a {
                color: rgba(255, 255, 255, 0.6);
                text-decoration: none;
            }

            .ws-breadcrumb a:hover {
                color: #fff;
            }

            .ws-breadcrumb .sep {
                color: rgba(255, 255, 255, 0.3);
            }

            .ws-breadcrumb .cur {
                color: rgba(255, 255, 255, 0.9);
                font-weight: 500;
            }

            /* ── Benefits ── */
            .ws-benefits {
                display: flex;
                flex-direction: column;
                gap: 18px;
                margin-top: 8px;
            }

            .ws-benefit-item {
                display: flex;
                align-items: flex-start;
                gap: 14px;
            }

            .ws-benefit-icon {
                width: 42px;
                height: 42px;
                border-radius: 11px;
                background: var(--ws-orange-light);
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                color: var(--ws-orange);
                font-size: 18px;
            }

            .ws-benefit-title {
                font-weight: 600;
                font-size: 14px;
                color: var(--ws-text);
                margin-bottom: 3px;
            }

            .ws-benefit-desc {
                font-size: 13px;
                color: var(--ws-muted);
                line-height: 1.5;
                font-family: 'SolaimanLipi', sans-serif;
            }

            /* ── Form Card ── */
            .ws-card {
                background: #fff;
                border-radius: 20px;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 20px 40px -8px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                border: 1px solid var(--ws-border);
            }

            .ws-card-header {
                background: linear-gradient(135deg, var(--ws-orange) 0%, var(--ws-orange-dark) 100%);
                padding: 24px 28px;
                position: relative;
                overflow: hidden;
            }

            .ws-card-header::after {
                content: '';
                position: absolute;
                right: -20px;
                top: -20px;
                width: 100px;
                height: 100px;
                background: rgba(255, 255, 255, 0.08);
                border-radius: 50%;
            }

            .ws-card-header h4 {
                color: #fff;
                font-weight: 700;
                font-size: 18px;
                margin: 0;
                position: relative;
                z-index: 1;
            }

            .ws-card-header p {
                color: rgba(255, 255, 255, 0.75);
                font-size: 13px;
                margin: 4px 0 0;
                font-family: 'SolaimanLipi', sans-serif;
                position: relative;
                z-index: 1;
            }

            .ws-card-body {
                padding: 28px;
            }

            /* ── Form Elements ── */
            .ws-form-group {
                margin-bottom: 20px;
            }

            .ws-label {
                display: flex;
                align-items: center;
                gap: 6px;
                font-size: 13px;
                font-weight: 600;
                color: var(--ws-text);
                margin-bottom: 7px;
                flex-wrap: wrap;
            }

            .ws-label i {
                color: var(--ws-orange);
                font-size: 13px;
            }

            .ws-label .req {
                color: #ef4444;
            }

            .ws-label .ws-hint {
                font-size: 11px;
                font-weight: 500;
                color: #ef4444;
                background: #fef2f2;
                border: 1px solid #fecaca;
                border-radius: 6px;
                padding: 2px 8px;
                font-family: 'SolaimanLipi', sans-serif;
                margin-left: 2px;
            }

            .ws-input {
                width: 100%;
                padding: 10px 14px;
                border: 2px solid var(--ws-border);
                border-radius: 10px;
                font-size: 14px;
                font-family: 'SolaimanLipi', sans-serif;
                color: var(--ws-text);
                background: #fff;
                transition: border-color .2s, box-shadow .2s;
                outline: none;
                box-sizing: border-box;
            }

            .ws-input::placeholder {
                color: #c4c9d4;
            }

            .ws-input:focus {
                border-color: var(--ws-orange);
                box-shadow: 0 0 0 4px var(--ws-orange-glow);
            }

            .ws-input.is-invalid {
                border-color: #ef4444;
            }

            .ws-input.is-invalid:focus {
                box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
            }

            .ws-textarea {
                resize: none;
                height: 100px;
            }

            .ws-error {
                color: #ef4444;
                font-size: 12px;
                margin-top: 5px;
                display: flex;
                align-items: center;
                gap: 4px;
                font-family: 'SolaimanLipi', sans-serif;
            }

            /* ── Submit Button ── */
            .ws-btn-submit {
                width: 100%;
                padding: 13px 20px;
                background: linear-gradient(135deg, var(--ws-orange) 0%, var(--ws-orange-dark) 100%);
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
                margin-top: 8px;
            }

            .ws-btn-submit:hover {
                transform: translateY(-1px);
                box-shadow: 0 8px 24px rgba(255, 93, 0, 0.35);
            }

            .ws-btn-submit:active {
                transform: translateY(0);
                box-shadow: none;
            }

            /* ── Trust Note ── */
            .ws-trust {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                color: var(--ws-muted);
                font-size: 12px;
                margin-top: 14px;
                font-family: 'SolaimanLipi', sans-serif;
            }

            .ws-trust i {
                color: #22c55e;
            }

            /* ── Responsive ── */
            @media (max-width: 768px) {
                .ws-hero {
                    padding: 44px 0 40px;
                    margin-bottom: 36px;
                }

                .ws-hero p {
                    max-width: 100%;
                }

                .ws-stats {
                    gap: 20px;
                }

                .ws-card-body {
                    padding: 20px;
                }

                .ws-stat-divider {
                    display: none;
                }
            }

            /* ── Submit Button Loader ── */
            .ws-spinner {
                display: inline-block;
                width: 16px;
                height: 16px;
                border: 2px solid rgba(255, 255, 255, 0.35);
                border-top-color: #fff;
                border-radius: 50%;
                animation: ws-spin .7s linear infinite;
                vertical-align: middle;
            }

            @keyframes ws-spin {
                to {
                    transform: rotate(360deg);
                }
            }

            #ws-submit-btn:disabled {
                opacity: .85;
                cursor: not-allowed;
                transform: none !important;
                box-shadow: none !important;
            }
        </style>
    @endpush
@section('content')
    <section class="ws-section">
        {{-- ── Hero ── --}}
        <div class="ws-hero">
            <div class="container">
                <nav class="ws-breadcrumb">
                    <a href="{{ route('home') }}" style="z-index: 99999;"><i class="bi bi-house-door-fill me-1"></i>Home</a>
                    <span class="sep">/</span>
                    <span class="cur">Wholesale</span>
                </nav>

                <div class="row align-items-center gy-4">
                    <div class="col-lg-7">
                        {{-- <div class="ws-hero-badge">হোলসেলঅফার</div> --}}
                        <h1>হোলসেল পণ্য কিনুন,<br><span>সাশ্রয়ী দামে</span> পান</h1>
                        <p>আমাদের প্রিমিয়াম পণ্য হোলসেল মূল্যে কিনুন এবং আপনার ব্যবসাকে এগিয়ে নিয়ে যান। নিচের ফর্মটি
                            পূরণ করুন — আমরা শীঘ্রই যোগাযোগ করব।</p>
                        {{-- <div class="ws-stats">
                            <div class="ws-stat-item">
                                <div class="val">৫০০+</div>
                                <div class="lbl">Wholesale Partners</div>
                            </div>


                            <div class="ws-stat-divider"></div>
                            <div class="ws-stat-item">
                                <div class="val">২৪ ঘণ্টা</div>
                                <div class="lbl">Response Time</div>
                            </div>
                        </div> --}}
                    </div>
                    <div class="col-lg-12 mt-3 d-block d-lg-none">
                        <button type="button" class="btn btn-danger btn-lg rounded-pill w-100" onclick="scrollToForm()">
                            ফর্মটি পূরণ করতে ক্লিক করুন
                        </button>
                    </div>

                </div>
            </div>
        </div>

        {{-- ── Main Content ── --}}
        <div class="container">
            <div class="row align-items-start g-5">

                {{-- Left: Image + Benefits --}}
                <div class="col-lg-6">
                    <img class="rounded-3 w-100 mb-4" style="height: auto; object-fit: cover;"
                        src="{{ asset('assets/frontend/img/wholesale.jpg') }}" alt="Wholesale">

                    <div class="ws-benefits">
                        <div class="ws-benefit-item">
                            <div class="ws-benefit-icon"><i class="bi bi-tags-fill"></i></div>
                            <div>
                                <div class="ws-benefit-title">বিশেষ হোলসেল মূল্য</div>
                                <div class="ws-benefit-desc">নির্দিষ্ট পরিমাণের উপরে অর্ডারে আকর্ষণীয় ছাড় পাবেন।</div>
                            </div>
                        </div>
                        <div class="ws-benefit-item">
                            <div class="ws-benefit-icon"><i class="bi bi-truck"></i></div>
                            <div>
                                <div class="ws-benefit-title">দ্রুত ডেলিভারি</div>
                                <div class="ws-benefit-desc">সারা বাংলাদেশে দ্রুত ও নিরাপদ ডেলিভারির ব্যবস্থা রয়েছে।</div>
                            </div>
                        </div>
                        <div class="ws-benefit-item">
                            <div class="ws-benefit-icon"><i class="bi bi-headset"></i></div>
                            <div>
                                <div class="ws-benefit-title">ডেডিকেটেড সাপোর্ট</div>
                                <div class="ws-benefit-desc">হোলসেল গ্রাহকদের জন্য আলাদা সাপোর্ট টিম সবসময় প্রস্তুত।</div>
                            </div>
                        </div>
                        <div class="ws-benefit-item">
                            <div class="ws-benefit-icon"><i class="bi bi-shield-check"></i></div>
                            <div>
                                <div class="ws-benefit-title">মান নিশ্চিত পণ্য</div>
                                <div class="ws-benefit-desc">প্রতিটি পণ্য কঠোর মান নিয়ন্ত্রণ প্রক্রিয়ার মধ্য দিয়ে যায়।
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right: Form Card --}}
                <div class="col-lg-6 mt-4 mt-lg-0" id="FormSection">
                    <div class="ws-card">
                        <div class="ws-card-header">
                            <h4><i class="bi bi-clipboard2-fill me-2"></i>Wholesale আবেদন ফর্ম</h4>
                            <p>নিচের তথ্যগুলো সঠিকভাবে পূরণ করুন</p>
                        </div>
                        <div class="ws-card-body">
                            <form action="{{ route('wholesale.store') }}" method="POST">
                                @csrf

                                {{-- Name --}}
                                <div class="ws-form-group">
                                    <label class="ws-label">
                                        <i class="bi bi-person-fill"></i>
                                        আপনার নাম <span class="req">*</span>
                                    </label>
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="ws-input @error('name') is-invalid @enderror"
                                        placeholder="আপনার পূর্ণ নাম লিখুন">
                                    @error('name')
                                        <div class="ws-error"><i class="bi bi-exclamation-circle-fill"></i>
                                            {{ ucwords($message) }}</div>
                                    @enderror
                                </div>

                                {{-- Phone --}}
                                <div class="ws-form-group">
                                    <label class="ws-label">
                                        <i class="bi bi-telephone-fill"></i>
                                        মোবাইল নম্বর <span class="req">*</span>
                                    </label>
                                    <input type="text" name="phone" value="{{ old('phone') }}"
                                        class="ws-input @error('phone') is-invalid @enderror" placeholder="01XXXXXXXXX">
                                    @error('phone')
                                        <div class="ws-error"><i class="bi bi-exclamation-circle-fill"></i>
                                            {{ ucwords($message) }}</div>
                                    @enderror
                                </div>

                                {{-- Address --}}
                                <div class="ws-form-group">
                                    <label class="ws-label">
                                        <i class="bi bi-geo-alt-fill"></i>
                                        আপনার ঠিকানা
                                    </label>
                                    <textarea name="address" class="ws-input ws-textarea @error('address') is-invalid @enderror"
                                        placeholder="বিস্তারিত ঠিকানা লিখুন">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="ws-error"><i class="bi bi-exclamation-circle-fill"></i>
                                            {{ ucwords($message) }}</div>
                                    @enderror
                                </div>

                                {{-- Occupation + Quantity side by side --}}
                                <div class="row g-3">
                                    <div class="col-lg-6">
                                        <div class="ws-form-group mb-0">
                                            <label class="ws-label">
                                                <i class="bi bi-briefcase-fill"></i> পেশা
                                            </label>
                                            <input type="text" name="occupation" value="{{ old('occupation') }}"
                                                class="ws-input @error('occupation') is-invalid @enderror"
                                                placeholder="যেমন: ব্যবসায়ী">
                                            @error('occupation')
                                                <div class="ws-error"><i class="bi bi-exclamation-circle-fill"></i>
                                                    {{ ucwords($message) }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mt-3 mt-lg-0">
                                        <div class="ws-form-group mb-0">
                                            <label class="ws-label">
                                                <i class="bi bi-box-seam-fill"></i>
                                                পণ্যের পরিমাণ
                                                <span class="ws-hint">সর্বনিম্ন ১২ পিস</span>
                                            </label>
                                            <input type="number" name="product_quantity"
                                                value="{{ old('product_quantity') }}"
                                                class="ws-input @error('product_quantity') is-invalid @enderror"
                                                placeholder="যেমন: ১২" min="12">
                                            @error('product_quantity')
                                                <div class="ws-error"><i class="bi bi-exclamation-circle-fill"></i>
                                                    {{ ucwords($message) }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="ws-btn-submit" id="ws-submit-btn">
                                    <span id="ws-btn-default">
                                        আবেদন জমা দিন <i class="bi bi-send-fill"></i>
                                    </span>
                                    <span id="ws-btn-loading" style="display:none;">
                                        জমা হচ্ছে... <span class="ws-spinner"></span>
                                    </span>
                                </button>

                                <div class="ws-trust">
                                    <i class="bi bi-lock-fill"></i>
                                    আপনার তথ্য সম্পূর্ণ সুরক্ষিত — আমরা কখনো শেয়ার করি না
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


@endsection
@push('scripts')
    <script>
        function scrollToForm() {
            document.getElementById("FormSection").scrollIntoView({
                behavior: "smooth",
                block: "start"
            });
        }
    </script>
    <script>
        document.querySelector('.ws-card form').addEventListener('submit', function() {
            var btn = document.getElementById('ws-submit-btn');
            document.getElementById('ws-btn-default').style.display = 'none';
            document.getElementById('ws-btn-loading').style.display = 'inline-flex';
            btn.disabled = true;
        });
    </script>
@endpush
