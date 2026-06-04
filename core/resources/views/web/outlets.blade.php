@extends('web.layouts.app')
@section('title', 'Outlet | ' . $web_config['name']->value)

@section('meta_description', 'Explore our outlet locations and discover premium clothing at affordable prices.')

@push('css_or_js')
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Outfit:wght@400;500;600&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --orange: #ff5d00;
            --orange-dark: #e04d00;
            --orange-light: #fff2eb;
            --orange-mid: #ffc9a8;
            --ink: #1a0f00;
            --slate: #5c4a3a;
            --fog: #faf7f5;
            --line: #ede4dc;
            --white: #ffffff;
        }

        /* ── Breadcrumb ── */
        .outlet-top {
            background: var(--white);
            padding: 0;
            padding-top: 10px;
        }

        .custom-breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .custom-breadcrumb a {
            color: var(--orange);
            text-decoration: none;
            font-weight: 500;
        }

        .custom-breadcrumb a:hover {
            text-decoration: underline;
        }

        .custom-breadcrumb .separator {
            color: var(--slate);
            font-size: 10px;
        }

        .custom-breadcrumb .active {
            color: var(--slate);
        }



        /* ── Stats bar ── */
        .stats-bar {
            color: var(--orange) padding: 18px 0;
            margin-top: 20px;
        }

        .stats-bar .s-item {
            text-align: center;
            border-right: 1px solid rgba(255, 255, 255, 0.22);
            padding: 0 28px;
        }

        .stats-bar .s-item:last-child {
            border-right: none;
        }

        .stats-bar .s-num {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--orange);
            line-height: 1;
        }

        .stats-bar .s-lbl {
            font-family: 'Outfit', sans-serif;
            font-size: 10.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #111;
            margin-top: 3px;
        }

        /* ── Main section ── */
        .outlet-section {
            background: var(--fog);
            padding: 14px 0 76px;
            min-height: 60vh;
        }

        /* ── Search row ── */
        .search-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 34px;
            flex-wrap: wrap;
        }

        .total-label {
            font-family: 'Outfit', sans-serif;
            font-size: 13.5px;
            color: var(--slate);
        }

        .total-label strong {
            color: var(--ink);
        }

        .s-box {
            position: relative;
            flex: 1;
            max-width: 300px;
        }

        .s-box .s-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--slate);
            font-size: 14px;
            pointer-events: none;
        }

        .s-box input {
            width: 100%;
            background: var(--white);
            border: 1.5px solid var(--line);
            border-radius: 8px;
            padding: 10px 14px 10px 36px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: var(--ink);
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .s-box input:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(255, 93, 0, 0.11);
        }

        /* ── Cards ── */
        .outlet-card {
            background: var(--white);
            border-radius: 18px;
            border: 1.5px solid var(--line);
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: transform .27s ease, box-shadow .27s ease, border-color .27s;
            animation: riseUp .45s ease both;
        }

        .outlet-col:nth-child(2) .outlet-card {
            animation-delay: .08s;
        }

        .outlet-col:nth-child(3) .outlet-card {
            animation-delay: .16s;
        }

        .outlet-col:nth-child(4) .outlet-card {
            animation-delay: .24s;
        }

        .outlet-col:nth-child(5) .outlet-card {
            animation-delay: .32s;
        }

        .outlet-col:nth-child(6) .outlet-card {
            animation-delay: .40s;
        }

        @keyframes riseUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .outlet-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 18px 44px rgba(26, 15, 0, 0.10);
            border-color: var(--orange);
        }

        /* top gradient stripe */
        .card-stripe {
            height: 4px;
            background: linear-gradient(90deg, var(--orange-dark) 0%, var(--orange) 60%, var(--orange-mid) 100%);
        }

        .card-body-inner {
            padding: 22px 24px 18px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* outlet number badge */
        .outlet-num {
            display: inline-block;
            font-family: 'Outfit', sans-serif;
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--orange-dark);
            background: var(--orange-light);
            border-radius: 4px;
            padding: 3px 10px;
            margin-bottom: 12px;
        }

        .outlet-name-title {
            font-family: 'Outfit', serif;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--ink);
            margin: 0 0 18px;
            line-height: 1.25;
        }

        /* info rows */
        .info-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            flex: 1;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .info-icon {
            width: 28px;
            height: 28px;
            flex-shrink: 0;
            background: var(--orange-light);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info-icon i {
            font-size: 12px;
            color: var(--orange-dark);
        }

        .info-text {
            font-family: 'Outfit', sans-serif;
            font-size: 14.5px;
            color: var(--slate);
            line-height: 1.55;
            padding-top: 4px;
        }

        /* days chips */
        .days-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            padding-top: 4px;
        }

        .day-chip {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            font-weight: 600;
            color: var(--orange-dark);
            background: var(--orange-mid);
            border: 1px solid var(--orange-mid);
            border-radius: 4px;
            padding: 2px 8px;
            letter-spacing: .3px;
        }

        /* card footer */
        .card-foot {
            padding: 14px 24px;
            border-top: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }

        .open-badge {
            display: flex;
            align-items: center;
            gap: 6px;
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: #1a7a52;
        }

        .open-dot {
            width: 7px;
            height: 7px;
            background: #2ecc71;
            border-radius: 50%;
            animation: blink 1.8s infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .4;
                transform: scale(1.4);
            }
        }

        .map-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            font-weight: 600;
            color: var(--orange-dark);
            background: var(--orange-light);
            border: 1.5px solid var(--orange-mid);
            border-radius: 7px;
            padding: 7px 14px;
            text-decoration: none;
            transition: background .2s, color .2s, border-color .2s;
            transform: none;
        }

        .map-btn:hover {
            display: inline-flex;
            background: var(--orange);
            color: #fff;
            border-color: var(--orange);
            text-decoration: none;
            transform: none;
        }

        .map-btn i {
            font-size: 13px;
            transition: none;
        }

        /* ── Empty state ── */
        .empty-state {
            padding: 80px 20px;
            text-align: center;
        }

        .empty-icon {
            width: 86px;
            height: 86px;
            background: var(--orange-light);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 22px;
        }

        .empty-icon i {
            font-size: 34px;
            color: var(--orange);
        }

        .empty-state h4 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 8px;
        }

        .empty-state p {
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            color: var(--slate);
        }

        #no-results {
            display: none;
            text-align: center;
            padding: 40px 20px;
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            color: var(--slate);
        }

        /* ── Search Dropdown ── */
        .s-dropdown {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: var(--white);
            border: 1.5px solid var(--line);
            border-radius: 10px;
            overflow: hidden;
            z-index: 999;
            display: none;
            box-shadow: 0 8px 24px rgba(26, 15, 0, 0.10);
            max-height: 340px;
            overflow-y: auto;
        }

        .s-dropdown .sd-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 11px 14px;
            cursor: pointer;
            border-bottom: 1px solid var(--line);
            transition: background .15s;
            text-align: left;
        }

        .s-dropdown .sd-item:last-child {
            border-bottom: none;
        }

        .s-dropdown .sd-item:hover {
            background: var(--orange-light);
        }

        .s-dropdown .sd-item-icon {
            width: 32px;
            height: 32px;
            border-radius: 7px;
            background: var(--orange-light);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .s-dropdown .sd-item-icon i {
            font-size: 15px;
            color: var(--orange-dark);
        }

        .s-dropdown .sd-item-name {
            font-family: 'Outfit', sans-serif;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--ink);
            line-height: 1.3;
        }

        .s-dropdown .sd-item-addr {
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            color: var(--slate);
            margin-top: 3px;
            line-height: 1.4;
        }

        .s-dropdown .sd-footer {
            padding: 8px 14px;
            font-family: 'Outfit', sans-serif;
            font-size: 12px;
            color: var(--slate);
            background: var(--fog);
            border-top: 1px solid var(--line);
        }

        .s-dropdown .sd-empty {
            padding: 22px 14px;
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            color: var(--slate);
            text-align: center;
        }

        mark.s-highlight {
            background: rgba(255, 93, 0, 0.15);
            color: var(--orange-dark);
            border-radius: 3px;
            padding: 0 2px;
            font-weight: 600;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .hero-left-panel {
                display: none;
            }

            .outlet-hero {
                min-height: auto;
            }

            .hero-right-panel {
                padding: 32px 24px;
            }

            .stats-bar {
                padding: 10px 0;
            }

            .outlet-top {
                padding: 8px 0;
            }

            .outlet-section {
                padding: 30px 0 60px;
            }

            .stats-bar .s-num {
                font-size: 1.4rem;
            }

            .search-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .s-box {
                max-width: 100%;
                width: 100%;
            }
        }

        .ob-hero {
            position: relative;
            background: #ffffff;
            overflow: hidden;
            min-height: 320px;
            display: flex;
            align-items: center;
        }

        .hero-dots {
            position: absolute;
            top: 28px;
            left: 24px;
            width: 120px;
            height: 80px;
            background-image: radial-gradient(circle, rgba(255, 93, 0, 0.18) 1px, transparent 1px);
            background-size: 14px 14px;
            pointer-events: none;
        }

        .hero-geo {
            position: absolute;
            top: 28px;
            right: 28px;
            width: 70px;
            height: 70px;
            border: 1px solid rgba(255, 93, 0, 0.18);
            transform: rotate(22deg);
        }

        .hero-geo::before {
            content: '';
            position: absolute;
            inset: 10px;
            border: 1px solid rgba(255, 93, 0, 0.10);
        }

        .hero-bg-letter {
            position: absolute;
            right: -20px;
            top: 50%;
            transform: translateY(-52%);
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(220px, 28vw, 380px);
            font-weight: 700;
            line-height: 1;
            color: rgba(255, 93, 0, 0.06);
            user-select: none;
            pointer-events: none;
            letter-spacing: -10px;
        }

        .hero-content {
            position: relative;
            z-index: 4;

            max-width: 100%;
        }

        .hero-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-family: 'Outfit', sans-serif;
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: #ff5d00;
            margin-bottom: 18px;
        }

        .hero-eyebrow-line {
            display: inline-block;
            width: 28px;
            height: 1px;
            background: #ff5d00;
            opacity: .5;
        }

        .ob-hero h1 {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.4rem, 5.5vw, 3rem);
            font-weight: 700;
            line-height: 1.05;
            color: #140a00;
            margin: 0 0 6px;
        }

        .ob-hero h1 em {
            font-style: italic;
            color: #ff5d00;
        }

        .hero-sub {
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            color: #604530;
            line-height: 1.75;
            margin: 14px 0 0;
            max-width: 100%;
        }

        @media (max-width: 991px) {
            .hero-content {
                padding: 0 20px;
            }

            .hero-bg-letter {
                font-size: 160px;
            }
        }

        @media (max-width: 767px) {
            .ob-hero {
                min-height: auto;
            }

            .hero-content {
                padding: 0 20px;
            }

            .hero-bg-letter {
                display: none;
            }

            .hero-geo {
                display: none;
            }

            .stats-bar .s-item {
                text-align: center;
                border-right: 1px solid rgba(255, 255, 255, 0.22);
                padding: 0 10px;
            }

            .ob-hero h1 {
                font-size: 29px;
            }

            .search-row {
                margin-bottom: 22px;
            }
        }

        .day-chip.active {
            background: var(--slate);
            color: #fff;
            border-color: var(--slate);
        }
    </style>
@endpush

@section('content')

    {{-- ── Breadcrumb ── --}}
    <div class="outlet-top">
        <div class="container">
            <nav class="custom-breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span class="separator"><i class="fa fa-chevron-right"></i></span>
                <span class="active">Outlets</span>
            </nav>
        </div>
    </div>

    {{-- ── Hero ── --}}
    {{-- ═══════════════════ HERO ═══════════════════ --}}
    <section class="ob-hero">

        {{-- decorative elements --}}
        <div class="hero-accent-bar"></div>
        <div class="hero-dots"></div>
        <div class="hero-geo"></div>
        <div class="hero-rule-top"></div>
        <div class="hero-rule-bot"></div>
        <div class="hero-bg-letter">S</div>

        <div class="container">
            <div class="hero-content text-center">

                <div class="hero-eyebrow">
                    <span class="hero-eyebrow-line"></span>
                    Store Locations
                </div>

                <h1>
                    Find Your <em>Nearest</em><br>
                    Outlet Store
                </h1>

                <p class="hero-sub">
                    Premium fashion, curated collections and expert styling —
                    right around the corner from you.
                </p>
                {{-- ── Stats bar (only if outlets exist) ── --}}
                @if ($branchs->count() > 0)
                    <div class="stats-bar">
                        <div class="row g-3 g-lg-0 justify-content-center">
                            <div class="col-auto s-item pl-0">
                                <div class="s-num">{{ $branchs->count() }}</div>
                                <div class="s-lbl">Outlets</div>
                            </div>
                            <div class="col-auto s-item">
                                <div class="s-num">10K+</div>
                                <div class="s-lbl">Happy Customers</div>
                            </div>
                            <div class="col-auto s-item pr-0">
                                <div class="s-num">24/7</div>
                                <div class="s-lbl">Support</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </section>



    {{-- ── Outlets Grid ── --}}
    <section class="outlet-section">
        <div class="container">

            @if ($branchs->count() > 0)

                {{-- Search row --}}
                <div class="search-row">
                    <p class="total-label">
                        Showing <strong>{{ $branchs->count() }}</strong> outlet{{ $branchs->count() > 1 ? 's' : '' }}
                    </p>
                    <div class="s-box">
                        <i class="fa fa-search s-icon"></i>
                        <input type="text" id="outletSearch" placeholder="Search by name or address..."
                            autocomplete="off" oninput="filterOutlets(this.value)">
                        <div class="s-dropdown" id="sDropdown"></div>
                    </div>
                </div>

                {{-- Cards grid --}}
                <div class="row g-4" id="outletGrid">
                    @foreach ($branchs as $i => $branch)
                        <div class="col-lg-4 col-md-6 col-12 outlet-col mb-4"
                            data-search="{{ strtolower($branch->name . ' ' . strip_tags($branch->address)) }}">
                            <div class="outlet-card">

                                <div class="card-stripe"></div>

                                <div class="card-body-inner">

                                    <span class="outlet-num">Outlet &bull;
                                        {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>

                                    <h5 class="outlet-name-title">{{ $branch->name }}</h5>

                                    <div class="info-list">

                                        <div class="info-row">
                                            <div class="info-icon"><i class="bi bi-geo-alt-fill"></i></div>
                                            <span class="info-text">{!! $branch->address !!}</span>
                                        </div>

                                        <div class="info-row">
                                            <div class="info-icon"><i class="fa fa-calendar"></i></div>
                                            <div class="days-chips">
                                                <span
                                                    class="day-chip @if ($branch->closing_day === 'Sat') active @endif">Sat</span>
                                                <span
                                                    class="day-chip @if ($branch->closing_day === 'Sun') active @endif">Sun</span>
                                                <span
                                                    class="day-chip @if ($branch->closing_day === 'Mon') active @endif">Mon</span>
                                                <span
                                                    class="day-chip @if ($branch->closing_day === 'Tue') active @endif">Tue</span>
                                                <span
                                                    class="day-chip @if ($branch->closing_day === 'Wed') active @endif">Wed</span>
                                                <span
                                                    class="day-chip @if ($branch->closing_day === 'Thu') active @endif">Thu</span>
                                                <span
                                                    class="day-chip @if ($branch->closing_day === 'Fri') active @endif">Fri</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="card-foot">
                                    <span class="open-badge">
                                        <span class="open-dot"></span> Open {{ $branch->closing_day ? 6 : 7 }} Days
                                    </span>
                                    @if (!empty($branch->map_url))
                                        <a href="{{ $branch->map_url }}" target="_blank" class="map-btn">
                                            <i class="fa fa-map"></i><span>Google Map</span>
                                        </a>
                                    @endif
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                <p id="no-results">No outlets match your search.</p>
            @else
                <div class="empty-state">
                    <div class="empty-icon"><i class="fa fa-store"></i></div>
                    <h4>No Outlets Available</h4>
                    <p>We're expanding soon — check back later!</p>
                </div>

            @endif

        </div>
    </section>

@endsection

@push('scripts')
    <script>
        const allBranches = @json(
            $branchs->map(fn($b) => [
                    'name' => $b->name,
                    'address' => strip_tags($b->address),
                ]));

        function filterOutlets(val) {
            const q = val.trim().toLowerCase();
            const dropdown = document.getElementById('sDropdown');

            if (q.length < 1) {
                showCards('');
                dropdown.style.display = 'none';
                return;
            }

            showCards(q);

            const matches = allBranches.filter(b =>
                b.name.toLowerCase().includes(q) ||
                b.address.toLowerCase().includes(q)
            );

            if (matches.length === 0) {
                dropdown.innerHTML = `<div class="sd-empty">No outlets found</div>`;
            } else {
                dropdown.innerHTML = matches.map(b => `
                <div class="sd-item" onclick="selectOutlet('${b.name.replace(/'/g, "\\'")}')">
                    <div class="sd-item-icon"><i class="bi bi-geo-alt-fill"></i></div>
                    <div>
                        <div class="sd-item-name">${highlight(b.name, q)}</div>
                        <div class="sd-item-addr">${highlight(b.address, q)}</div>
                    </div>
                </div>
            `).join('') +
                    `<div class="sd-footer"><i class="bi bi-geo-alt-fill"></i> ${matches.length} outlet${matches.length > 1 ? 's' : ''} found</div>`;
            }

            dropdown.style.display = 'block';
        }

        function showCards(q) {
            const cols = document.querySelectorAll('#outletGrid .outlet-col');
            let visible = 0;
            cols.forEach(col => {
                const match = q === '' || col.dataset.search.includes(q);
                col.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            document.getElementById('no-results').style.display = visible === 0 ? 'block' : 'none';
        }

        function selectOutlet(name) {
            document.getElementById('outletSearch').value = name;
            document.getElementById('sDropdown').style.display = 'none';
            showCards(name.toLowerCase());
        }

        function highlight(text, q) {
            const re = new RegExp(`(${q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
            return text.replace(re, '<mark class="s-highlight">$1</mark>');
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.s-box')) {
                document.getElementById('sDropdown').style.display = 'none';
            }
        });
    </script>
@endpush
