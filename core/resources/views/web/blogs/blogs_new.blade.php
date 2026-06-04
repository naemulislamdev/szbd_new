@extends('web.layouts.app')
@section('title', 'Blogs | ' . $web_config['name']->value)
@section('meta_description', 'Read our latest blogs on fashion, skincare, and helpful tips for everyday life.')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@400;500&display=swap');

    /* ── Base ── */
    .blogs-section {
        font-family: 'DM Sans', sans-serif;
        color: #1f2933;
    }

    /* ── Page header ── */
    .blogs-page-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 38px;
        font-weight: 700;
        letter-spacing: -0.5px;
        color: #1f2933;
        margin-bottom: 4px;
    }

    .blogs-page-header .sub-heading {
        font-size: 15px;
        color: #627282;
        margin-bottom: 0;
    }

    /* ── Category filter strip ── */
    .category-filter {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
        margin-bottom: 1.75rem;
    }

    .category-filter .filter-label {
        font-size: 13px;
        color: #627282;
        margin-right: 4px;
    }

    .cat-pill {
        font-size: 12px;
        font-weight: 500;
        padding: 5px 14px;
        border-radius: 30px;
        border: 1px solid #dde3e9;
        background: #fff;
        color: #627282;
        text-decoration: none;
        transition: all 0.2s;
    }

    .cat-pill:hover,
    .cat-pill.active {
        background: #e85d04;
        color: #fff;
        border-color: #e85d04;
        text-decoration: none;
    }

    /* ── Blog Card ── */
    .blog-card {
        background: #fff;
        border: 1px solid #eaeef2;
        border-radius: 12px;
        overflow: hidden;
        transition: border-color 0.2s, transform 0.2s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .blog-card:hover {
        border-color: #c8d0d8;
        transform: translateY(-2px);
    }

    .blog-card-img {
        position: relative;
        height: 200px;
        overflow: hidden;
        background: #f0f4f8;
    }

    .blog-card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .blog-card:hover .blog-card-img img {
        transform: scale(1.05);
    }

    .cat-badge {
        position: absolute;
        bottom: 12px;
        left: 14px;
        background: #e85d04;
        color: #fff;
        font-size: 11px;
        font-weight: 500;
        padding: 4px 12px;
        border-radius: 30px;
        letter-spacing: 0.3px;
        z-index: 1;
    }

    .blog-card-body {
        padding: 1.1rem 1.2rem;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .blog-card-title {
        font-family: 'Playfair Display', serif;
        font-size: 16px;
        font-weight: 600;
        color: #1f2933;
        line-height: 1.45;
        margin-bottom: 8px;
        transition: color 0.2s;
        text-decoration: none;
        display: block;
    }

    .blog-card:hover .blog-card-title {
        color: #e85d04;
    }

    .blog-card-text {
        font-size: 13px;
        color: #627282;
        line-height: 1.65;
        flex: 1;
    }

    .blog-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px solid #eaeef2;
    }

    .blog-card-date {
        font-size: 12px;
        color: #9aabb8;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .blog-read-more {
        font-size: 13px;
        font-weight: 500;
        color: #e85d04;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: gap 0.2s;
    }

    .blog-card:hover .blog-read-more {
        gap: 8px;
    }

    .blog-read-more svg {
        transition: transform 0.2s;
    }

    .blog-card:hover .blog-read-more svg {
        transform: translateX(3px);
    }

    /* ── Sidebar ── */
    .blog-sidebar {}

    .sidebar-search .input-group input {
        border: 1px solid #dde3e9;
        border-right: none;
        border-radius: 8px 0 0 8px;
        padding: 9px 14px;
        font-size: 13px;
        color: #1f2933;
        background: #fff;
        box-shadow: none;
    }

    .sidebar-search .input-group input:focus {
        border-color: #e85d04;
        box-shadow: none;
        outline: none;
    }

    .sidebar-search .search-btn {
        background: #e85d04 !important;
        color: #fff !important;
        border: none;
        border-radius: 0 8px 8px 0 !important;
        padding: 0 16px;
        font-size: 13px;
    }

    .sidebar-widget {
        margin-bottom: 1.75rem;
    }

    .sidebar-widget-title {
        font-family: 'Playfair Display', serif;
        font-size: 18px;
        font-weight: 600;
        color: #1f2933;
        margin-bottom: 1rem;
        padding-bottom: 8px;
        border-bottom: 2px solid #e85d04;
        display: inline-block;
    }

    /* ── Category pills in sidebar ── */
    .sidebar-cats {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
    }

    /* ── Recent posts ── */
    .recent-post-item {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        padding: 10px 0;
        border-bottom: 1px solid #eaeef2;
    }

    .recent-post-item:last-child {
        border-bottom: none;
    }

    .recent-thumb {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
        background: #f0f4f8;
    }

    .recent-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .recent-post-text a {
        font-size: 13px;
        color: #1f2933;
        line-height: 1.45;
        text-decoration: none;
        display: block;
        font-weight: 500;
        transition: color 0.2s;
    }

    .recent-post-text a:hover {
        color: #e85d04;
    }

    .recent-post-date {
        font-size: 11px;
        color: #9aabb8;
        margin-top: 4px;
    }

    /* ── Trending products ── */
    .trending-item {
        display: flex;
        gap: 12px;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #eaeef2;
    }

    .trending-item:last-child {
        border-bottom: none;
    }

    .trending-thumb {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
        background: #f0f4f8;
    }

    .trending-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .trending-item a {
        font-size: 13px;
        color: #e85d04;
        line-height: 1.45;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
    }

    .trending-item a:hover {
        color: #c44d00;
    }

    /* ── Ad Banner ── */
    .blog-ad-banner {
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.75rem;
    }

    .blog-ad-banner img {
        width: 100%;
        height: auto;
        display: block;
    }

    /* ── Pagination ── */
    .blog-pagination .page-link {
        color: #1f2933;
        border: 1px solid #eaeef2;
        border-radius: 8px !important;
        margin: 0 3px;
        font-size: 13px;
        padding: 6px 13px;
        transition: all 0.2s;
    }

    .blog-pagination .page-link:hover,
    .blog-pagination .page-item.active .page-link {
        background: #e85d04;
        border-color: #e85d04;
        color: #fff;
    }

    .blog-pagination .page-item.active .page-link {
        box-shadow: none;
    }

    /* ── Breadcrumb ── */
    .custom-breadcrumb .breadcrumb-item a {
        color: #627282;
        text-decoration: none;
        font-size: 13px;
    }

    .custom-breadcrumb .breadcrumb-item a:hover {
        color: #e85d04;
    }

    .custom-breadcrumb .breadcrumb-item.active {
        font-size: 13px;
        color: #9aabb8;
    }

    /* ── No blogs state ── */
    .no-blogs {
        text-align: center;
        padding: 3rem 1rem;
        color: #627282;
    }

    .no-blogs svg {
        opacity: 0.3;
        margin-bottom: 1rem;
    }

    /* ── Responsive ── */
    @media (max-width: 991.98px) {
        .blog-sidebar {
            margin-top: 2rem;
        }

        .blogs-page-header h1 {
            font-size: 28px;
        }
    }

    @media (max-width: 575.98px) {
        .blog-card-img {
            height: 180px;
        }
    }
</style>

@section('content')
    <section class="py-3 blogs-section">
        <div class="container" style="min-height: 100vh;">

            {{-- Breadcrumb --}}
            <nav class="breadcrumb custom-breadcrumb bg-white my-1 py-0" aria-label="breadcrumb">
                <a class="breadcrumb-item" href="{{ route('home') }}">Home</a>
                <span class="breadcrumb-item active" aria-current="page">Blogs</span>
            </nav>

            {{-- Page Header --}}
            <div class="blogs-page-header mb-3 mt-2">
                <h1>Our Blog</h1>
                <p class="sub-heading">Fashion, skincare, and tips for everyday life</p>
            </div>

            {{-- Category Filter Strip --}}
            <div class="category-filter">
                <span class="filter-label">Filter:</span>
                <a href="{{ route('blogs') }}" class="cat-pill {{ !request('category') ? 'active' : '' }}">All</a>
                @foreach ($blogCategories ?? [] as $category)
                    <a href="{{ route('blogs', ['category' => $category->slug]) }}"
                        class="cat-pill {{ request('category') == $category->slug ? 'active' : '' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            <div class="row">
                {{-- Blog Grid --}}
                <div class="col-lg-8">
                    @if ($blogs->count())
                        <div class="row g-3">
                            @foreach ($blogs as $blog)
                                <div class="col-sm-6">
                                    <div class="blog-card">
                                        <div class="blog-card-img">
                                            <img src="{{ asset('assets/storage/blogs/' . $blog->image) }}"
                                                onerror="this.src='{{ asset('assets/frontend/img/placeholder.jpg') }}'"
                                                alt="{{ strip_tags($blog->title) }}">
                                            <span class="cat-badge">{{ $blog->blogCategory->name }}</span>
                                        </div>
                                        <div class="blog-card-body">
                                            <a href="{{ route('blog.details', $blog->slug) }}" class="blog-card-title">
                                                {{ Str::limit(strip_tags($blog->title), 60) }}
                                            </a>
                                            <p class="blog-card-text">
                                                {{ Str::limit(strip_tags($blog->description), 120) }}
                                            </p>
                                            <div class="blog-card-footer">
                                                <span class="blog-card-date">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <rect x="3" y="4" width="18" height="18" rx="2"
                                                            ry="2" />
                                                        <line x1="16" y1="2" x2="16" y2="6" />
                                                        <line x1="8" y1="2" x2="8" y2="6" />
                                                        <line x1="3" y1="10" x2="21" y2="10" />
                                                    </svg>
                                                    {{ $blog->created_at->format('d M Y') }}
                                                </span>
                                                <a href="{{ route('blog.details', $blog->slug) }}" class="blog-read-more">
                                                    Read more
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M5 12l14 0" />
                                                        <path d="M15 16l4 -4" />
                                                        <path d="M15 8l4 4" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        @if ($blogs->hasPages())
                            <div class="mt-4">
                                {{ $blogs->links('pagination::bootstrap-4') }}
                            </div>
                        @endif
                    @else
                        <div class="no-blogs">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                fill="none" stroke="#627282" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                                <line x1="16" y1="13" x2="8" y2="13" />
                                <line x1="16" y1="17" x2="8" y2="17" />
                                <polyline points="10 9 9 9 8 9" />
                            </svg>
                            <p class="mt-2">No blogs found.</p>
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4">
                    <aside class="blog-sidebar">

                        {{-- Ad Banner --}}
                        <div class="blog-ad-banner">
                            <img src="{{ asset('assets/frontend/img/blog-add.jpeg') }}" alt="Advertisement">
                        </div>

                        {{-- Search --}}
                        <div class="sidebar-widget sidebar-search">
                            <div class="input-group">
                                <input type="text" class="form-control" id="blog-search-input"
                                    placeholder="Search blogs..." value="{{ request('search') }}">
                                <button class="btn search-btn" type="button" id="blog-search-btn">Search</button>
                            </div>
                        </div>

                        {{-- Categories --}}
                        @if (!empty($blogCategories) && $blogCategories->count())
                            <div class="sidebar-widget">
                                <span class="sidebar-widget-title">Categories</span>
                                <div class="sidebar-cats mt-3">
                                    @foreach ($blogCategories as $category)
                                        <a href="{{ route('blogs', ['category' => $category->slug]) }}"
                                            class="cat-pill {{ request('category') == $category->slug ? 'active' : '' }}">
                                            {{ $category->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Trending Products --}}
                        @if (!empty($trendingProducts) && $trendingProducts->count())
                            <div class="sidebar-widget">
                                <span class="sidebar-widget-title">Trending Products</span>
                                <div class="mt-3">
                                    @foreach ($trendingProducts as $product)
                                        <div class="trending-item">
                                            <div class="trending-thumb">
                                                <img src="{{ asset('assets/storage/products/thumbnail/' . $product->thumbnail) }}"
                                                    onerror="this.src='{{ asset('assets/frontend/img/placeholder.jpg') }}'"
                                                    alt="{{ $product->name }}">
                                            </div>
                                            <a href="{{ route('product', $product->slug) }}">
                                                {{ Str::limit($product->name, 60) }}
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Recent Posts --}}
                        @if (!empty($recentBlogs) && $recentBlogs->count())
                            <div class="sidebar-widget">
                                <span class="sidebar-widget-title">Recent Posts</span>
                                <div class="mt-3">
                                    @foreach ($recentBlogs as $recent)
                                        <div class="recent-post-item">
                                            <div class="recent-thumb">
                                                <img src="{{ asset('assets/storage/blogs/' . $recent->image) }}"
                                                    onerror="this.src='{{ asset('assets/frontend/img/placeholder.jpg') }}'"
                                                    alt="{{ strip_tags($recent->title) }}">
                                            </div>
                                            <div class="recent-post-text">
                                                <a href="{{ route('blog.details', $recent->slug) }}">
                                                    {{ Str::limit(strip_tags($recent->title), 55) }}
                                                </a>
                                                <div class="recent-post-date">
                                                    {{ $recent->created_at->format('d M Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </aside>
                </div>
            </div>

        </div>
    </section>

    <script>
        document.getElementById('blog-search-btn').addEventListener('click', function() {
            const q = document.getElementById('blog-search-input').value.trim();
            if (q) {
                window.location.href = "{{ route('blogs') }}?search=" + encodeURIComponent(q);
            }
        });

        document.getElementById('blog-search-input').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('blog-search-btn').click();
            }
        });
    </script>
@endsection

{{-- <div class="trending-products-section">
                        <h2 class="mb-3">Trending Products</h2>
                        <div class="trending-products">
                            <div class="prods d-flex align-items-center mb-4">
                                <img class="img-fluid" src="{{ asset('assets/frontend/images/product/p1.jpeg') }}"
                                    alt="product name">
                                <a class="pl-3" href="#">Unstice Three Lorem ipsum dolor sit amet consectetur
                                    adipisicing elit.
                                    o enim quaerat sed! Laudantium.</a>
                            </div>
                            <div class="prods d-flex align-items-center mb-4">
                                <img class="img-fluid" src="{{ asset('assets/frontend/images/product/p2.jpg') }}"
                                    alt="product name">
                                <a class="pl-3" href="#">Unstice Three Lorem ipsum dolor sit amet consectetur
                                    adipisicing elit.
                                </a>
                            </div>
                            <div class="prods d-flex align-items-center mb-4">
                                <img class="img-fluid" src="{{ asset('assets/frontend/images/product/p3.jpg') }}"
                                    alt="product name">
                                <a class="pl-3" href="#">Unstice Three Lorem ipsum dolor sit amet consectetur
                                    adipisicing elit.
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="recent-products">
                        <h2 class="mb-3">Recent Posts</h2>
                        <div class="trending-products">
                            <div class="prods d-flex mb-4">
                                <img class="img-fluid" src="{{ asset('assets/frontend/images/product/p1.jpeg') }}"
                                    alt="product name">
                                <a class="pl-3" href="#">Soundcore R60i NC Review: Longer Listening With Total
                                    Comfort</a>
                            </div>
                            <div class="prods d-flex mb-4">
                                <img class="img-fluid" src="{{ asset('assets/frontend/images/product/p2.jpg') }}"
                                    alt="product name">
                                <a class="pl-3" href="#">বাজার সেরা ৫টি থার্মাল প্রিন্টার। কোন Thermal Printer ভালো?
                                </a>
                            </div>
                            <div class="prods d-flex mb-4">
                                <img class="img-fluid" src="{{ asset('assets/frontend/images/product/p4.jpeg') }}"
                                    alt="product name">
                                <a class="pl-3" href="#">৩ হাজার টাকার মধ্যে বাজার সেরা স্মার্ট ওয়াচ!
                                </a>
                            </div>
                        </div>
                    </div> --}}
