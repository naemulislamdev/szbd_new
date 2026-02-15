@extends('layouts.front-end.app')
@section('title', $blog->title)
@section('meta_title', $blog->meta_title ?? $blog->title)
@section('meta_keywords', $blog->meta_keywords)
@section('meta_description', $blog->meta_description)

<style>
    .text-orange {
        color: #ff5d00 !important;
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
</style>
@section('content')
    <section class="py-3 career">
        <div class="container-fluid " style="min-height: 100vh;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent">
                    <li class="breadcrumb-item"><a style="color: #ff5d00;" href="/">Home</a></li>
                    <li class="breadcrumb-item"><a style="color: #ff5d00;" href="{{ route('blogs') }}">Blogs</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Blog Details</li>
                </ol>
            </nav>
            <div class="row">
                <div class="col-lg-9">
                    <img class=" rounded img-thumbnail details-img" src="{{ asset('storage/blogs') }}/{{ $blog->image }}"
                        onerror="this.src='{{ asset('assets/frontend/img/placeholder.jpg') }}'" alt="{{ $blog->title }}">
                    <div class="blog-content-body mt-4 border-right">
                        <h3 class="text-orange h3">{{ $blog->title }}</h3>
                        <div class="upload-info my-4">
                            <span class="text-muted fw-bold border-right mr-2">📅 {{ $blog->created_at->format('d M Y') }}
                            </span>
                            <span class="text-muted fw-bold border-right border-right-2 mr-2"> <i
                                    class="fa fa-user text-orange mr-1" aria-hidden="true"></i>
                                {{ ucfirst($blog->uploader) }}
                            </span>
                            <span class="text-muted fw-bold"> <i class="fa fa-table text-orange mr-1"
                                    aria-hidden="true"></i> {{ $blog->blogCategory->name }}
                            </span>
                        </div>
                        <div class="text-muted">{!! $blog->description !!}</div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <aside class=" p-2">
                        <div>
                            <h5 class="border-bottom pb-2 mb-2 fw-normal">Categories</h5>
                            <div class="d-flex align-items-center mt-3">
                                <span style="width: 15px; height: 15px;"
                                    class="rounded d-inline-block border border-2 lead mr-2"></span>
                                {{ $blog->blogCategory->name }}
                            </div>
                        </div>
                        <div class="mt-4">
                            <h5 class="border-bottom pb-2 mb-2 fw-lighter">Latest Posts</h5>

                            @foreach ($latest_blogs as $lBlog)
                                <div class="row mt-4">
                                    <div class="col-4">
                                        <a href="{{ route('blog.details', $lBlog->slug) }}">
                                            <img class="rounded" style="max-width: 100%;"
                                                src="{{ asset('storage/blogs') }}/{{ $lBlog->image }}"
                                                alt="{{ $lBlog->title }}">
                                        </a>
                                    </div>
                                    <div class="col-8 pl-0 ml-0">
                                        <a class="text-dark" href="{{ route('blog.details', $lBlog->slug) }}">
                                            <h6>{{ $lBlog->title }}</h6>
                                        </a>
                                        <span class=" mr-2">📅 {{ $lBlog->created_at->format('d M Y') }}
                                        </span>
                                    </div>
                                </div>
                                {{-- end item --}}
                            @endforeach
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>
@endsection
