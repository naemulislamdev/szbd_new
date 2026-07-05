@extends('web.layouts.app')
@section('title', $blog->title)
@section('meta_title', $blog->meta_title ?? $blog->title)
@section('meta_keywords', $blog->meta_keywords)
@section('meta_description', strip_tags($blog->meta_description))

@section('style')
    <style>
        .text-orange {
            color: #ff5d00 !important;
        }

        a {
            display: inline-block;
        }

        .blog-card {
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.15);
            transition: 0.5s ease-in-out;
        }

        .blog-img {
            position: relative;
        }

        .blog-img div {
            border-radius: 10px 10px 0 0;
            overflow: hidden;
        }

        .blog-img img {
            max-width: 100%;
            height: auto;
            overflow: hidden;
            transition: all 0.4s ease;
        }

        .blog-title {
            font-size: 18px;
            font-weight: 600;
            color: #222;
        }

        .blog-text {
            font-size: 14px;
            color: #555;
        }

        .read-more {
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            color: #ff5d00;
            transition: 0.3s;
        }

        .read-more:hover {
            color: #ff5d00;
            text-decoration: underline;
        }

        .blog-card:hover .blog-img img {
            transform: scale(1.1);
        }

        .blog-card .category-btn {
            border-radius: 6px;
            border: 2px solid #ff5d00;
            background-color: #ff5d00;
            color: #fff;
            position: absolute;
            right: 18px;
            bottom: -15px;
            padding: 3px 12px;
            font-weight: 500;
        }

        .category-btn.btn:focus {
            outline: 0;
            box-shadow: 0 0 0 .2rem rgba(255, 93, 0, 0.25);
        }

        .details-img {
            width: 100%;
        }

        @media (min-width: 992px) {
            .details-img {
                width: 60%;
            }
        }

        .blog-container {
            width: 100%;
            padding-right: 12px;
            padding-left: 12px;
            margin-right: auto;
            margin-left: auto;
        }

        @media (min-width: 576px) {
            .blog-container {
                max-width: 540px;
            }
        }

        @media (min-width: 768px) {
            .blog-container {
                max-width: 720px;
            }
        }

        @media (min-width: 992px) {
            .blog-container {
                max-width: 960px;
            }
        }

        @media (min-width: 1200px) {
            .blog-container {
                max-width: 1140px;
            }
        }

        @media (min-width: 1400px) {
            .blog-container {
                max-width: 1320px;
            }
        }

        /* image bottom shadow */
        .details-img-wrapper {
            position: relative;
            display: block;
            overflow: visible;
        }

        .details-img-wrapper::after {
            position: absolute;
            content: "";
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(0deg, #ffffff 5%, rgba(255, 255, 255, 0.35) 30%, rgba(255, 255, 255, 0) 50%);
            pointer-events: none;
        }

        .details-img-wrapper .details-img {
            width: 100%;
            display: block;
        }

        /* title image er upore */
        .blog-content-body {
            position: relative;
            z-index: 2;
            margin-top: -60px;
            padding: 0 12px;
        }

        /* Sidebar style */

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

        .blog-sidebar-heading {

            font-weight: 700;
            font-size: 28px;
            line-height: 1.5;
            color: #1f2933;
        }

        * {
            font-family: 'inter', sans-serif;
        }

        aside .link h6 {
            font-size: 0.8rem;
            color: #1f2933;
            transition: all 0.3s;
        }

        aside .link h6:hover {
            color: #ff5d00
        }

        aside .date-time {
            font-size: 0.7rem;
        }
    </style>
@endsection
@section('content')
    <section class="py-3 career">
        <div class="blog-container" style="min-height: 100vh;">

            {{-- Breadcrumb start --}}
            <nav class="breadcrumb custom-breadcrumb mt-3 bg-white pl-0">
                <a class="breadcrumb-item" href="{{ route('home') }}">Home</a>
                <a class="breadcrumb-item" href="{{ route('blogs') }}">Blogs</a>
                <span class="breadcrumb-item active" aria-current="page">Blog Details</span>
            </nav>
            {{-- Breadcrumb End --}}

            <div class="row">
                <div class="col-lg-9 px-0 pr-4 pl-3 pl-lg-0">
                    <div class="details-img-wrapper">
                        <img class="details-img rounded-top" src="{{ asset('assets/storage/blogs') }}/{{ $blog->image }}"
                            onerror="this.src='{{ asset('assets/frontend/img/placeholder.jpg') }}'"
                            alt="{{ $blog->title }}">
                    </div>

                    <div class="blog-content-body px-0">
                        <h3 class="text-orange h3">{{ $blog->title }}</h3>
                        <div class="upload-info my-4">
                            <span class="text-muted fw-bold mr-2">
                                📅 {{ $blog->created_at->format('d M Y') }}
                            </span>
                            <span class="text-muted fw-bold border-right border-right-2 mr-2">
                                <i class="fa fa-user text-orange mr-1" aria-hidden="true"></i>
                                {{ ucfirst($blog->uploader) }}
                            </span>
                            <span class="text-muted fw-bold">
                                <i class="fa fa-table text-orange mr-1" aria-hidden="true"></i>
                                {{ $blog->blogCategory->name }}
                            </span>
                        </div>
                        <div class="text-muted pr-3">{!! $blog->description !!}</div>
                    </div>
                </div>

                <div class="col-lg-3 pr-0">
                    <aside>
                        <div>
                            <a class="d-inline-block" href="{{ $contentPromotion->add_url }}" target="_blank"
                                rel="noopener noreferrer">
                                <img class="img-fluid rounded-top"
                                    src="{{ asset('assets/storage/blogs/adds/' . $contentPromotion->add_img) }}"
                                    alt="side image">
                            </a>
                        </div>
                        {{-- <div class="sidebar-search my-4">
                            <div class="input-group ">
                                <input type="text" name="search" class="form-control" id="">
                                <button class="btn search-btn">Search</button>
                            </div>
                        </div> --}}
                        <div class="mt-3">
                            <h2 class="border-bottom pb-2 mb-2 fw-lighter blog-sidebar-heading">Trending Products</h2>
                            @foreach ($contentProducts as $product)
                                <div class="row mt-3 align-items-center ">
                                    <div class="col-4">
                                        <a target="_blank" href="{{ route('product', $product->slug) }}">
                                            <img class="rounded border" style="max-width: 100%;"
                                                src="{{ asset('assets/storage/product/thumbnail') }}/{{ $product->thumbnail }}"
                                                alt="{{ $product->name }}">
                                        </a>
                                    </div>
                                    <div class="col-8 pl-0 pr-0 ml-0">
                                        <a target="_blank" class="link" href="{{ route('product', $product->slug) }}">
                                            <h6>{{ $product->name }}</h6>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            <h2 class="border-bottom pb-2 mb-2 fw-lighter blog-sidebar-heading">Recent Posts</h2>

                            @foreach ($latest_blogs as $lBlog)
                                <div class="row mt-4 align-items-center">
                                    <div class="col-4">
                                        <a href="{{ route('blog.details', $lBlog->slug) }}">
                                            <img class="rounded" style="max-width: 100%;"
                                                src="{{ asset('assets/storage/blogs') }}/{{ $lBlog->image }}"
                                                alt="{{ $lBlog->title }}">
                                        </a>
                                    </div>
                                    <div class="col-8 pl-0 ml-0">
                                        <a class="link" href="{{ route('blog.details', $lBlog->slug) }}">
                                            <h6>{{ $lBlog->title }}</h6>
                                        </a>
                                        <span class="mr-2 date-time">📅 {{ $lBlog->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </aside>
                </div>
            </div>

        </div>
    </section>
@endsection
