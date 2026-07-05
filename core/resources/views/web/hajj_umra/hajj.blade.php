@extends('web.layouts.app')
@section('title', 'ফ্রি উমরাহ/হজ নিবন্ধন | ' . $web_config['name']->value)

<link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">
<style>
    :root {
        --green-dark: #0a4a32;
        --green-mid: #0f6e4a;
        --green-light: #1a9e6a;
        --green-pale: #e8f5ee;
        --gold: #c9a84c;
        --gold-light: #f0d98a;
        --gold-pale: #fdf8ec;
        --text-dark: #1a1a1a;
        --text-mid: #444;
        --text-soft: #777;
        --border: #d4e8dd;
        --white: #ffffff;
        --radius: 10px;
        --shadow: 0 4px 20px rgba(10, 74, 50, 0.1);
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Hind Siliguri', sans-serif;
        background: #f0f7f3;
        color: var(--text-dark);
        min-height: 100vh;
    }

    /* ===== HERO BANNER ===== */
    .hero {
        background: linear-gradient(160deg, #062b1e 0%, #0a4a32 45%, #0f6e4a 100%);
        position: relative;
        overflow: hidden;
        padding: 0;
    }

    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(ellipse 60% 80% at 80% 50%, rgba(201, 168, 76, 0.12) 0%, transparent 70%),
            url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23c9a84c' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .hero-inner {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: 1fr auto;
        align-items: center;
        max-width: 1100px;
        margin: 0 auto;
        padding: 36px 40px 32px;
        gap: 20px;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: rgba(201, 168, 76, 0.18);
        border: 1px solid rgba(201, 168, 76, 0.4);
        color: var(--gold-light);
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: 20px;
        margin-bottom: 14px;
    }

    .hero-title {
        font-size: 42px;
        font-weight: 700;
        color: #fff;
        line-height: 1.15;
        margin-bottom: 8px;
    }

    .hero-title span {
        color: var(--gold-light);
    }

    .hero-subtitle {
        font-size: 14.5px;
        color: rgba(255, 255, 255, 0.75);
        line-height: 1.7;
        max-width: 480px;
    }

    .hero-benefits {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .benefit-chip {
        display: flex;
        align-items: center;
        gap: 5px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: rgba(255, 255, 255, 0.9);
        font-size: 12px;
        padding: 5px 12px;
        border-radius: 20px;
    }

    .benefit-chip svg {
        opacity: 0.75;
    }

    .hero-seal {
        background: linear-gradient(135deg, var(--gold) 0%, #e8c060 50%, var(--gold) 100%);
        color: var(--green-dark);
        border-radius: 50%;
        width: 100px;
        height: 100px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        font-weight: 700;
        box-shadow: 0 0 0 4px rgba(201, 168, 76, 0.3), 0 8px 24px rgba(0, 0, 0, 0.3);
        flex-shrink: 0;
        line-height: 1.2;
    }

    .hero-seal .big {
        font-size: 13px;
    }

    .hero-seal .small {
        font-size: 11px;
        font-weight: 600;
    }

    /* ===== LAYOUT ===== */
    .page-wrap {
        max-width: 1200px;
        margin: 0 auto;
        padding: 28px 20px 60px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 22px 24px;
        box-shadow: var(--shadow);
    }

    .card.full {
        grid-column: 1 / -1;
    }

    .card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 18px;
        padding-bottom: 14px;
        border-bottom: 1.5px solid var(--green-pale);
    }

    .card-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        background: linear-gradient(135deg, var(--green-mid), var(--green-light));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        flex-shrink: 0;
    }

    .card-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--green-dark);
        letter-spacing: 0.3px;
    }

    .card-num {
        margin-left: auto;
        background: var(--green-pale);
        color: var(--green-mid);
        border-radius: 6px;
        padding: 2px 9px;
        font-size: 12px;
        font-weight: 600;
    }

    /* ===== FIELDS ===== */
    .field-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 14px;
    }

    .field-row.col3 {
        grid-template-columns: 1fr 1fr 1fr;
    }

    .field-row.full {
        grid-template-columns: 1fr;
    }

    .field-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    label.field-label {
        font-size: 12.5px;
        font-weight: 600;
        color: var(--text-mid);
    }

    label.field-label .req {
        color: #c0392b;
        margin-left: 2px;
    }

    input[type=text],
    input[type=tel],
    input[type=email],
    input[type=number],
    input[type=date],
    select,
    textarea {
        font-family: 'Hind Siliguri', sans-serif;
        font-size: 14px;
        padding: 9px 12px;
        border: 1.5px solid #d0e8da;
        border-radius: 8px;
        background: #fafdfc;
        color: var(--text-dark);
        width: 100%;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
    }

    input:focus,
    select:focus,
    textarea:focus {
        border-color: var(--green-light);
        box-shadow: 0 0 0 3px rgba(26, 158, 106, 0.12);
        background: #fff;
    }

    input.error {
        border-color: #e74c3c !important;
    }

    select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%230f6e4a' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 32px;
    }

    textarea {
        resize: vertical;
        min-height: 80px;
    }

    /* ===== RADIO / CHECKBOX ===== */
    .radio-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 4px;
    }

    .radio-btn {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 7px 16px;
        border: 1.5px solid #d0e8da;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13.5px;
        color: var(--text-mid);
        background: #fafdfc;
        transition: all 0.15s;
        user-select: none;
    }

    .radio-btn input {
        display: none;
    }

    .radio-btn .dot {
        width: 14px;
        height: 14px;
        border: 2px solid #aac;
        border-radius: 50%;
        flex-shrink: 0;
        transition: all 0.15s;
    }

    .radio-btn.checked {
        border-color: var(--green-light);
        background: var(--green-pale);
        color: var(--green-dark);
        font-weight: 600;
    }

    .radio-btn.checked .dot {
        border-color: var(--green-light);
        background: var(--green-light);
        box-shadow: inset 0 0 0 3px #fff;
    }

    .checkbox-row {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 6px;
    }

    .check-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 11px 14px;
        border: 1.5px solid #d0e8da;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13.5px;
        color: var(--text-mid);
        background: #fafdfc;
        transition: all 0.15s;
        user-select: none;
    }

    .check-item input[type=checkbox] {
        display: none;
    }

    .check-box {
        width: 18px;
        height: 18px;
        border: 2px solid #aac;
        border-radius: 5px;
        flex-shrink: 0;
        margin-top: 1px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s;
    }

    .check-item.checked {
        border-color: var(--green-light);
        background: var(--green-pale);
        color: var(--green-dark);
    }

    .check-item.checked .check-box {
        border-color: var(--green-light);
        background: var(--green-light);
    }

    .check-item.checked .check-box::after {
        content: '✓';
        color: #fff;
        font-size: 11px;
        font-weight: 700;
    }

    /* ===== INFO BOXES ===== */
    .info-note {
        display: flex;
        gap: 8px;
        align-items: flex-start;
        background: #e8f4fd;
        border-left: 3px solid #3498db;
        border-radius: 0 8px 8px 0;
        padding: 9px 12px;
        font-size: 12.5px;
        color: #1a5276;
        margin-top: 10px;
    }

    .warn-note {
        display: flex;
        gap: 8px;
        align-items: flex-start;
        background: #fef9e7;
        border-left: 3px solid var(--gold);
        border-radius: 0 8px 8px 0;
        padding: 9px 12px;
        font-size: 12.5px;
        color: #7d6608;
        margin-top: 10px;
        display: none;
    }

    /* ===== SUBMIT ===== */
    .submit-area {
        grid-column: 1 / -1;
        text-align: center;
    }

    .submit-btn {
        background: linear-gradient(135deg, var(--green-mid) 0%, var(--green-light) 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        padding: 15px 48px;
        font-family: 'Hind Siliguri', sans-serif;
        font-size: 17px;
        font-weight: 700;
        cursor: pointer;
        box-shadow: 0 6px 20px rgba(15, 110, 74, 0.35);
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        letter-spacing: 0.3px;
    }

    .submit-btn:hover {
        background: linear-gradient(135deg, var(--green-dark) 0%, var(--green-mid) 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 28px rgba(15, 110, 74, 0.45);
    }

    .submit-btn:active {
        transform: translateY(0);
    }

    .privacy-note {
        font-size: 12px;
        color: var(--text-soft);
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    /* ===== SUCCESS ===== */
    .success-card {
        grid-column: 1 / -1;
        display: none;
        background: linear-gradient(135deg, #e8f5ee, #d4f0e3);
        border: 1.5px solid #5dcaa5;
        border-radius: 16px;
        padding: 40px 32px;
        text-align: center;
        box-shadow: var(--shadow);
    }

    .success-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, var(--green-mid), var(--green-light));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        box-shadow: 0 4px 16px rgba(15, 110, 74, 0.3);
    }

    /* ===== COLLAPSIBLE ===== */
    .collapsible {
        display: none;
        margin-top: 12px;
    }

    .collapsible.show {
        display: block;
    }

    /* ===== SAME AS MOBILE ===== */
    .same-check {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--text-soft);
        cursor: pointer;
        margin-top: 5px;
    }

    .same-check input {
        accent-color: var(--green-light);
    }

    /* ===== CHAR COUNT ===== */
    .char-count {
        font-size: 11px;
        color: var(--text-soft);
        text-align: right;
    }

    @media (max-width: 660px) {
        .hero-inner {
            grid-template-columns: 1fr;
            padding: 28px 20px 24px;
        }

        .hero-title {
            font-size: 30px;
        }

        .hero-seal {
            display: none;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .card.full {
            grid-column: 1;
        }

        .field-row {
            grid-template-columns: 1fr;
        }

        .field-row.col3 {
            grid-template-columns: 1fr 1fr;
        }

        .submit-area {
            grid-column: 1;
        }

        .success-card {
            grid-column: 1;
        }
    }
</style>
{{-- ===== Validation Error Style ===== --}}
<style>
    .invalid-msg {
        color: #e74c3c;
        font-size: 12px;
        margin-top: 4px;
        display: none;
    }

    input.is-invalid,
    select.is-invalid,
    textarea.is-invalid {
        border-color: #e74c3c !important;
        box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.12) !important;
    }

    .radio-error .radio-btn,
    .check-item.is-invalid {
        border-color: #e74c3c !important;
    }
</style>
<style>
    .submit-btn {
        position: relative;
        min-width: 220px;
    }

    .submit-btn:disabled {
        opacity: .8;
        cursor: not-allowed;
    }

    .d-none {
        display: none !important;
    }
</style>
@section('content')
    <!-- ===== HERO ===== -->
    <div class="hero">
        <div class="hero-inner">
            <div>
                <div class="hero-badge">
                    🕋 আল্লাহর ঘরে যাওয়ার সুযোগ
                </div>
                <h1 class="hero-title">ফ্রি <span>উমরাহ/হজ</span><br>নিবন্ধন</h1>
                <p class="hero-subtitle">যোগ্য প্রার্থীদের মধ্য থেকে নির্বাচিত ব্যক্তিদের সম্পূর্ণ বিনামূল্যে উমরাহ/হজ
                    পালনের
                    সুযোগ প্রদান করা হবে।</p>
                {{-- <div class="hero-benefits">
                    <span class="benefit-chip">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path
                                d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013 7.18a2 2 0 011.99-2.18h3" />
                        </svg>
                        এয়ার টিকিট
                    </span>
                    <span class="benefit-chip">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <rect x="1" y="4" width="22" height="16" rx="2" />
                            <line x1="1" y1="10" x2="23" y2="10" />
                        </svg>
                        ভিসা প্রসেসিং
                    </span>
                    <span class="benefit-chip">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        </svg>
                        হোটেল
                    </span>
                    <span class="benefit-chip">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8z" />
                        </svg>
                        খাবার
                    </span>
                    <span class="benefit-chip">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <rect x="1" y="3" width="15" height="13" />
                            <path
                                d="M16 8h4l3 4v3h-7V8zM5.5 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3zM18.5 21a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                        </svg>
                        যাতায়াত
                    </span>
                </div> --}}
            </div>
            {{-- <div class="hero-seal">
                <div class="big">কোনো</div>
                <div class="big">টাকা</div>
                <div class="small">লাগবে না</div>
            </div> --}}
        </div>
    </div>

    <!-- ===== FORM ===== -->
    <div class="page-wrap">
        <form id="mainForm" action="{{ route('umrah-haj.store') }}" method="POST" novalidate>
            @csrf
            <div class="form-grid">

                <!-- 1. ব্যক্তিগত তথ্য -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">👤</div>
                        <span class="card-title">ব্যক্তিগত তথ্য</span>
                        <span class="card-num">১</span>
                    </div>

                    <div class="field-row full" style="margin-bottom:14px;">
                        <div class="field-group">
                            <label class="field-label">পূর্ণ নাম (পাসপোর্ট অনুযায়ী) <span class="req">*</span></label>
                            <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}"
                                placeholder="যেমন: MD. RAHIM UDDIN" oninput="updateCount(this,'nc1',60)" maxlength="60"
                                required>
                            <div class="char-count" id="nc1">0 / 60</div>
                            <div class="invalid-msg">পূর্ণ নাম লিখুন।</div>
                        </div>
                    </div>

                    <div class="field-row" style="margin-bottom:14px;">
                        <div class="field-group">
                            <label class="field-label">মোবাইল নম্বর <span class="req">*</span></label>
                            <input type="tel" id="phone" name="mobile_number" value="{{ old('mobile_number') }}"
                                placeholder="+880 1X XX XXX XXX" oninput="syncWhatsapp()" required>
                            <div class="invalid-msg">মোবাইল নম্বর লিখুন।</div>
                        </div>
                        <div class="field-group">
                            <label class="field-label">WhatsApp নম্বর</label>
                            <input type="tel" id="whatsapp" name="whatsapp_number"
                                value="{{ old('whatsapp_number') }}">
                            {{-- <label class="same-check">
                                <input type="checkbox" id="same_mobile" onchange="toggleSameMobile(this)"> মোবাইলের মতোই
                            </label> --}}
                        </div>
                    </div>

                    <div class="row" style="margin-bottom:14px;">
                        <div class="field-group col-12">
                            <label class="field-label">ইমেইল ঠিকানা (ঐচ্ছিক)</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                placeholder="example@gmail.com">
                        </div>
                    </div>

                    <div class="row" style="margin-bottom:14px;">
                        <div class="field-group col-12">
                            <label for="address" class="field-label">আপনার ঠিকানা <span class="req">*</span></label>
                            <textarea name="address" id="address" cols="30" rows="4" placeholder="এখানে আপনার পুরো ঠিকানা লিখুন"
                                required>{{ old('address') }}</textarea>
                            <div class="invalid-msg">ঠিকানা লিখুন।</div>
                        </div>
                    </div>

                    <div class="field-row full" style="margin-bottom:0;">
                        <div class="field-group">
                            <label class="field-label">বর্তমান পেশা <span class="req">*</span></label>
                            <select id="occupation" name="occupation" required>
                                <option value="">-- পেশা বেছে নিন --</option>
                                <option value="চাকরিজীবী" {{ old('occupation') == 'চাকরিজীবী' ? 'selected' : '' }}>
                                    চাকরিজীবী</option>
                                <option value="ব্যবসায়ী" {{ old('occupation') == 'ব্যবসায়ী' ? 'selected' : '' }}>
                                    ব্যবসায়ী</option>
                                <option value="কৃষক" {{ old('occupation') == 'কৃষক' ? 'selected' : '' }}>কৃষক
                                </option>
                                <option value="শিক্ষক" {{ old('occupation') == 'শিক্ষক' ? 'selected' : '' }}>শিক্ষক
                                </option>
                                <option value="শিক্ষার্থী" {{ old('occupation') == 'শিক্ষার্থী' ? 'selected' : '' }}>
                                    শিক্ষার্থী</option>
                                <option value="গৃহিণী" {{ old('occupation') == 'গৃহিণী' ? 'selected' : '' }}>গৃহিণী
                                </option>
                                <option value="অবসরপ্রাপ্ত" {{ old('occupation') == 'অবসরপ্রাপ্ত' ? 'selected' : '' }}>
                                    অবসরপ্রাপ্ত</option>
                                <option value="অন্যান্য" {{ old('occupation') == 'অন্যান্য' ? 'selected' : '' }}>
                                    অন্যান্য</option>
                            </select>
                            <div class="invalid-msg">পেশা বেছে নিন।</div>
                        </div>
                    </div>
                </div>

                <!-- 2. আবেদনকারীর তথ্য -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">📋</div>
                        <span class="card-title">আবেদনকারীর তথ্য</span>
                        <span class="card-num">২</span>
                    </div>

                    <div class="field-row" style="margin-bottom:14px;">
                        <div class="field-group">
                            <label class="field-label">বয়স <span class="req">*</span></label>
                            <select id="age" name="age" required>
                                <option value="">-- বয়স --</option>
                                @for ($i = 18; $i <= 80; $i++)
                                    <option value="{{ $i }}" {{ old('age') == $i ? 'selected' : '' }}>
                                        {{ $i }} বছর</option>
                                @endfor
                            </select>
                            <div class="invalid-msg">বয়স বেছে নিন।</div>
                        </div>
                        <div class="field-group">
                            <label class="field-label">বৈবাহিক অবস্থা <span class="req">*</span></label>
                            <div class="radio-row" style="margin-top:4px;">
                                <label class="radio-btn {{ old('marital_status') == 'অবিবাহিত' ? 'checked' : '' }}"
                                    onclick="selectRadio(this,'marital_status')">
                                    <input type="radio" name="marital_status" value="অবিবাহিত"
                                        {{ old('marital_status') == 'অবিবাহিত' ? 'checked' : '' }}>
                                    <span class="dot"></span> অবিবাহিত
                                </label>
                                <label class="radio-btn {{ old('marital_status') == 'বিবাহিত' ? 'checked' : '' }}"
                                    onclick="selectRadio(this,'marital_status')">
                                    <input type="radio" name="marital_status" value="বিবাহিত"
                                        {{ old('marital_status') == 'বিবাহিত' ? 'checked' : '' }}>
                                    <span class="dot"></span> বিবাহিত
                                </label>
                            </div>
                            <div class="invalid-msg" id="marital_status-err" style="display:none;">বৈবাহিক অবস্থা বেছে
                                নিন।
                            </div>
                        </div>
                    </div>

                    <div class="field-group" style="margin-bottom:14px;">
                        <label class="field-label">লিঙ্গ <span class="req">*</span></label>
                        <div class="radio-row">
                            <label class="radio-btn {{ old('gender') == 'পুরুষ' ? 'checked' : '' }}"
                                onclick="selectRadio(this,'gender'); toggleMahram(this)">
                                <input type="radio" name="gender" value="পুরুষ"
                                    {{ old('gender') == 'পুরুষ' ? 'checked' : '' }}>
                                <span class="dot"></span> পুরুষ
                            </label>
                            <label class="radio-btn {{ old('gender') == 'মহিলা' ? 'checked' : '' }}"
                                onclick="selectRadio(this,'gender'); toggleMahram(this)">
                                <input type="radio" name="gender" value="মহিলা"
                                    {{ old('gender') == 'মহিলা' ? 'checked' : '' }}>
                                <span class="dot"></span> মহিলা
                            </label>
                        </div>
                        <div class="invalid-msg" id="gender-err" style="display:none;">লিঙ্গ বেছে নিন।</div>
                    </div>

                    <div id="mahram-field" class="field-group collapsible {{ old('gender') == 'মহিলা' ? 'show' : '' }}"
                        style="margin-bottom:14px;">
                        <label class="field-label">মাহরামের নাম ও সম্পর্ক</label>
                        <input type="text" id="mahram" name="mahram" value="{{ old('mahram') }}"
                            placeholder="যেমন: স্বামী — মোঃ রফিকুল ইসলাম">
                        <div class="info-note">
                            ℹ️ মহিলাদের জন্য মাহরাম সঙ্গী বাধ্যতামূলক (সৌদি নিয়ম অনুযায়ী)
                        </div>
                    </div>

                    <div class="field-group">
                        <label class="field-label">পূর্বে উমরাহ বা হজ করেছেন? <span class="req">*</span></label>
                        <div class="radio-row">
                            <label class="radio-btn {{ old('has_done_umrah_or_haj_before') == 'none' ? 'checked' : '' }}"
                                onclick="selectRadio(this,'has_done_umrah_or_haj_before')">
                                <input type="radio" name="has_done_umrah_or_haj_before" value="none"
                                    {{ old('has_done_umrah_or_haj_before') == 'none' ? 'checked' : '' }} required>
                                <span class="dot"></span> না, প্রথমবার
                            </label>
                            <label class="radio-btn {{ old('has_done_umrah_or_haj_before') == 'umrah' ? 'checked' : '' }}"
                                onclick="selectRadio(this,'has_done_umrah_or_haj_before')">
                                <input type="radio" name="has_done_umrah_or_haj_before" value="umrah"
                                    {{ old('has_done_umrah_or_haj_before') == 'umrah' ? 'checked' : '' }} required>
                                <span class="dot"></span> উমরাহ করেছি
                            </label>
                            <label class="radio-btn {{ old('has_done_umrah_or_haj_before') == 'hajj' ? 'checked' : '' }}"
                                onclick="selectRadio(this,'has_done_umrah_or_haj_before')">
                                <input type="radio" name="has_done_umrah_or_haj_before" value="hajj"
                                    {{ old('has_done_umrah_or_haj_before') == 'hajj' ? 'checked' : '' }} required>
                                <span class="dot"></span> হজ করেছি
                            </label>
                        </div>
                        <div class="invalid-msg" id="prev-hajj-err" style="display:none;">এটি বেছে নিন।</div>
                    </div>
                </div>

                <!-- 3. পাসপোর্ট তথ্য -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">🛂</div>
                        <span class="card-title">পাসপোর্ট তথ্য</span>
                        <span class="card-num">৩</span>
                    </div>

                    <div class="field-group" style="margin-bottom:14px;">
                        <label class="field-label">আপনার বৈধ পাসপোর্ট আছে কি? <span class="req">*</span></label>
                        <div class="radio-row">
                            <label class="radio-btn {{ old('has_valid_passport') == 'yes' ? 'checked' : '' }}"
                                onclick="selectRadio(this,'has_valid_passport'); togglePassport('yes')">
                                <input type="radio" name="has_valid_passport" value="yes"
                                    {{ old('has_valid_passport') == 'yes' ? 'checked' : '' }}>
                                <span class="dot"></span> হ্যাঁ, আছে
                            </label>
                            <label class="radio-btn {{ old('has_valid_passport') == 'no' ? 'checked' : '' }}"
                                onclick="selectRadio(this,'has_valid_passport'); togglePassport('no')">
                                <input type="radio" name="has_valid_passport" value="no"
                                    {{ old('has_valid_passport') == 'no' ? 'checked' : '' }}>
                                <span class="dot"></span> না, নেই
                            </label>
                        </div>
                        <div class="invalid-msg" id="passport-err" style="display:none;">পাসপোর্টের তথ্য দিন।</div>
                    </div>

                    <div id="pp-yes" class="collapsible {{ old('has_valid_passport') == 'yes' ? 'show' : '' }}">
                        <div class="field-row" style="margin-bottom:10px;">
                            <div class="field-group">
                                <label class="field-label">পাসপোর্ট নম্বর</label>
                                <input type="text" id="passport_number" name="passport_number"
                                    value="{{ old('passport_number') }}" placeholder="BD1234567">
                            </div>
                            <div class="field-group">
                                <label class="field-label">মেয়াদ শেষের তারিখ</label>
                                <input type="date" id="passport_expiry_date" name="passport_expiry_date"
                                    value="{{ old('passport_expiry_date') }}" onchange="checkExpiry(this)">
                            </div>
                        </div>
                        <div class="warn-note" id="expiry-warn">
                            ⚠️ পাসপোর্টের মেয়াদ ৬ মাসের কম — রিনিউ করতে হবে
                        </div>

                        <div class="field-group" style="margin-top:10px;">
                            <label class="field-label">পাসপোর্টের মেয়াদ কমপক্ষে ৬ মাস আছে কি? <span
                                    class="req">*</span></label>
                            <div class="radio-row">
                                <label class="radio-btn {{ old('passport_validity_6_months') == 'yes' ? 'checked' : '' }}"
                                    onclick="selectRadio(this,'passport_validity_6_months')">
                                    <input type="radio" name="passport_validity_6_months" value="yes"
                                        {{ old('passport_validity_6_months') == 'yes' ? 'checked' : '' }}>
                                    <span class="dot"></span> হ্যাঁ
                                </label>
                                <label class="radio-btn {{ old('passport_validity_6_months') == 'no' ? 'checked' : '' }}"
                                    onclick="selectRadio(this,'passport_validity_6_months')">
                                    <input type="radio" name="passport_validity_6_months" value="no"
                                        {{ old('passport_validity_6_months') == 'no' ? 'checked' : '' }}>
                                    <span class="dot"></span> না
                                </label>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- 4. নির্বাচন সংক্রান্ত তথ্য -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon">📝</div>
                        <span class="card-title">নির্বাচন সংক্রান্ত তথ্য</span>
                        <span class="card-num">৪</span>
                    </div>

                    <div class="field-group" style="margin-bottom:14px;">
                        <label class="field-label">কেন আপনি ফ্রি উমরাহ/হজের জন্য নির্বাচিত হতে চান? <span
                                class="req">*</span></label>
                        <textarea id="reason" name="selected_reason" rows="4" placeholder="আপনার উত্তর লিখুন... (কমপক্ষে ৩০ অক্ষর)"
                            oninput="updateCount(this,'rc1',500)" maxlength="500" required>{{ old('reason') }}</textarea>
                        <div class="char-count" id="rc1">0 / 500</div>
                        <div class="invalid-msg" id="reason-err" style="display:none;">কমপক্ষে ৩০ অক্ষর লিখুন।</div>
                    </div>

                    <div class="field-group" style="margin-bottom:14px;">
                        <label class="field-label">আর্থিকভাবে নিজ খরচে উমরাহ/হজে যেতে সক্ষম কি না? <span
                                class="req">*</span></label>
                        <div class="radio-row">
                            <label class="radio-btn {{ old('can_self_finance') == 'yes' ? 'checked' : '' }}"
                                onclick="selectRadio(this,'can_self_finance')">
                                <input type="radio" name="can_self_finance" value="yes"
                                    {{ old('can_self_finance') == 'yes' ? 'checked' : '' }}>
                                <span class="dot"></span> হ্যাঁ
                            </label>
                            <label class="radio-btn {{ old('can_self_finance') == 'no' ? 'checked' : '' }}"
                                onclick="selectRadio(this,'can_self_finance')">
                                <input type="radio" name="can_self_finance" value="no"
                                    {{ old('can_self_finance') == 'no' ? 'checked' : '' }}>
                                <span class="dot"></span> না
                            </label>
                        </div>
                        <div class="invalid-msg" id="afford-err" style="display:none;">এটি বেছে নিন।</div>
                    </div>

                    <div class="field-group">
                        <label id="source" class="field-label">কীভাবে জানলেন?</label>
                        <select id="source" name="application_source">
                            <option value="">-- উৎস বেছে নিন --</option>
                            <option value="ফেসবুক পোস্ট" {{ old('source') == 'ফেসবুক পোস্ট' ? 'selected' : '' }}>
                                ফেসবুক পোস্ট</option>
                            <option value="সহকর্মীর কাছ থেকে"
                                {{ old('source') == 'সহকর্মীর কাছ থেকে' ? 'selected' : '' }}>সহকর্মীর কাছ থেকে</option>
                            <option value="কোম্পানির নোটিশ" {{ old('source') == 'কোম্পানির নোটিশ' ? 'selected' : '' }}>
                                কোম্পানির নোটিশ</option>
                            <option value="ম্যানেজারের কাছ থেকে"
                                {{ old('source') == 'ম্যানেজারের কাছ থেকে' ? 'selected' : '' }}>ম্যানেজারের কাছ থেকে
                            </option>
                            <option value="অন্যান্য" {{ old('source') == 'অন্যান্য' ? 'selected' : '' }}>
                                অন্যান্য</option>
                        </select>
                    </div>
                </div>

                <!-- 5. সম্মতি -->
                <div class="card full">
                    <div class="card-header">
                        <div class="card-icon">🤝</div>
                        <span class="card-title">সম্মতি</span>
                        <span class="card-num">৫</span>
                    </div>

                    <div class="consent-box">

                        <div class="form-check mb-3">
                            <input class="form-check-input consent-check" type="checkbox" id="consent1"
                                name="terms_accepted" value="1" {{ old('terms_accepted') ? 'checked' : '' }}>

                            <label class="form-check-label" for="consent1">
                                আমি প্রদত্ত তথ্য সঠিক বলে ঘোষণা করছি।
                            </label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input consent-check" type="checkbox" id="consent2"
                                name="selection_decision_accepted" value="1"
                                {{ old('selection_decision_accepted') ? 'checked' : '' }}>

                            <label class="form-check-label" for="consent2">
                                নির্বাচন প্রক্রিয়ার চূড়ান্ত সিদ্ধান্ত আয়োজক কর্তৃপক্ষের হবে।
                            </label>
                        </div>

                        <div class="invalid-msg mt-2 text-danger" id="consent-err" style="display:none;">
                            উভয় সম্মতি দিন।
                        </div>

                    </div>
                </div>

                <!-- SUBMIT -->
                <div class="submit-area">
                    <button type="button" id="submitBtn" class="submit-btn" onclick="submitForm()">
                        <span class="btn-text">
                            আবেদন জমা দিন <i class="fa fa-paper-plane"></i>
                        </span>

                        <span class="btn-loader d-none">
                            <i class="fa fa-spinner fa-spin"></i>
                            জমা হচ্ছে...
                        </span>
                    </button>

                    <p class="privacy-note">
                        🔒 সকল তথ্য গোপনীয় থাকবে। নির্বাচিত প্রার্থীদের ফোনে জানানো হবে।
                    </p>
                </div>

            </div><!-- /.form-grid -->
        </form>
    </div>



    <script>
        // ---- Radio button UI ----
        function selectRadio(el, name) {
            document.querySelectorAll('.radio-btn').forEach(b => {
                if (b.querySelector('input[name="' + name + '"]')) b.classList.remove('checked');
            });
            el.classList.add('checked');
            el.querySelector('input').checked = true;
            // error clear
            const errEl = document.getElementById(name + '-err');
            if (errEl) errEl.style.display = 'none';
        }

        // ---- Checkbox UI ----
        function toggleCheck(el) {
            el.classList.toggle('checked');
            el.querySelector('input[type=checkbox]').checked = el.classList.contains('checked');
            // consent error clear
            if (!document.getElementById('consent1').checked || !document.getElementById('consent2').checked) return;
            const errEl = document.getElementById('consent-err');
            if (errEl) errEl.style.display = 'none';
        }

        // ---- Char counter ----
        function updateCount(el, countId, max) {
            document.getElementById(countId).textContent = el.value.length + ' / ' + max;
        }

        // ---- WhatsApp same as mobile ----
        function syncWhatsapp() {
            if (document.getElementById('same_mobile').checked) {
                document.getElementById('whatsapp').value = document.getElementById('phone').value;
            }
        }

        function toggleSameMobile(cb) {
            const wa = document.getElementById('whatsapp');
            if (cb.checked) {
                wa.value = document.getElementById('phone').value;
                wa.disabled = true;
            } else {
                wa.disabled = false;
                wa.value = '';
            }
        }

        // ---- Passport toggle ----
        function togglePassport(val) {
            const ppYes = document.getElementById('pp-yes');
            if (ppYes) ppYes.classList.toggle('show', val === 'yes');

            const errEl = document.getElementById('passport-err');
            if (errEl) errEl.style.display = 'none';
        }

        // ---- Expiry warning ----
        function checkExpiry(el) {
            const exp = new Date(el.value);
            const limit = new Date();
            limit.setMonth(limit.getMonth() + 6);
            document.getElementById('expiry-warn').style.display = exp < limit ? 'flex' : 'none';
        }

        // ---- Mahram field ----
        function toggleMahram(el) {
            const isFemale = el.querySelector('input').value === 'মহিলা';
            document.getElementById('mahram-field').classList.toggle('show', isFemale);
        }

        // ---- helper: show/hide error ----
        function setError(el, errId, show) {
            if (show) {
                el.classList.add('is-invalid');
                if (errId) {
                    const e = document.getElementById(errId);
                    if (e) e.style.display = 'block';
                }
            } else {
                el.classList.remove('is-invalid');
                if (errId) {
                    const e = document.getElementById(errId);
                    if (e) e.style.display = 'none';
                }
            }
        }

        // ---- Submit ----
        function submitForm() {
            let ok = true;

            // Text / tel inputs
            const textFields = [{
                    id: 'full_name',
                    err: null
                },
                {
                    id: 'phone',
                    err: null
                },
                {
                    id: 'address',
                    err: null
                },
            ];
            textFields.forEach(({
                id,
                err
            }) => {
                const el = document.getElementById(id);
                if (!el) return;
                if (!el.value.trim()) {
                    setError(el, err, true);
                    // show the sibling invalid-msg via next element
                    const msg = el.parentElement.querySelector('.invalid-msg');
                    if (msg) msg.style.display = 'block';
                    ok = false;
                } else {
                    setError(el, err, false);
                    const msg = el.parentElement.querySelector('.invalid-msg');
                    if (msg) msg.style.display = 'none';
                }
            });

            // Select fields
            const selectFields = ['occupation', 'age'];
            selectFields.forEach(id => {
                const el = document.getElementById(id);
                if (!el) return;
                if (!el.value) {
                    setError(el, null, true);
                    const msg = el.parentElement.querySelector('.invalid-msg');
                    if (msg) msg.style.display = 'block';
                    ok = false;
                } else {
                    setError(el, null, false);
                    const msg = el.parentElement.querySelector('.invalid-msg');
                    if (msg) msg.style.display = 'none';
                }
            });

            // Radio groups
            const radioGroups = [{
                    name: 'gender',
                    errId: 'gender-err'
                },
                {
                    name: 'marital_status',
                    errId: 'marital_status-err'
                },
                {
                    name: 'has_valid_passport',
                    errId: 'passport-err'
                },
                {
                    name: 'has_done_umrah_or_haj_before',
                    errId: 'prev-hajj-err'
                },
                {
                    name: 'can_self_finance',
                    errId: 'afford-err'
                },
            ];
            radioGroups.forEach(({
                name,
                errId
            }) => {
                const checked = document.querySelector('input[name="' + name + '"]:checked');
                const errEl = document.getElementById(errId);
                if (!checked) {
                    if (errEl) errEl.style.display = 'block';
                    ok = false;
                } else {
                    if (errEl) errEl.style.display = 'none';
                }
            });

            // Reason textarea — min 30 chars
            const reason = document.getElementById('reason');
            const reasonErr = document.getElementById('reason-err');
            if (reason.value.trim().length < 30) {
                setError(reason, null, true);
                if (reasonErr) reasonErr.style.display = 'block';
                ok = false;
            } else {
                setError(reason, null, false);
                if (reasonErr) reasonErr.style.display = 'none';
            }

            // Consent checkboxes
            const c1 = document.getElementById('consent1').checked;
            const c2 = document.getElementById('consent2').checked;
            const consentErr = document.getElementById('consent-err');
            if (!c1 || !c2) {
                if (consentErr) consentErr.style.display = 'block';
                ok = false;
            } else {
                if (consentErr) consentErr.style.display = 'none';
            }

            if (!ok) {
                // Scroll to first error
                const firstErr = document.querySelector('.is-invalid, [style*="display: block"].invalid-msg');
                if (firstErr) firstErr.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                else window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                return;
            }
            // Loader Start
            const btn = document.getElementById('submitBtn');

            btn.disabled = true;

            btn.querySelector('.btn-text').classList.add('d-none');
            btn.querySelector('.btn-loader').classList.remove('d-none');

            // Submit
            document.getElementById('mainForm').submit();

            // ✅ Submit form
            document.getElementById('mainForm').submit();
        }
    </script>

@endsection
