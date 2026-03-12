@extends('web.layouts.app')
@section('title', 'career-details')
<style>
    .bg-orange {
        background: #ff5d00 !important;
        color: #fff !important;
    }

    .btn.bg-orange:focus {
        outline: 0;
        box-shadow: 0 0 0 .2rem rgba(255, 93, 0, .25);
    }
</style>
@section('content')
    <section class="career">
        <div class="container position-relative">
            {{-- Bredcrumb start  --}}
            <nav class="breadcrumb custom-breadcrumb mt-3">
                <a class="breadcrumb-item" href="{{ route('home') }}">Home</a>
                <a class="breadcrumb-item" href="{{ route('careers') }}">Career</a>

                <span class="breadcrumb-item active" aria-current="page">{{ $career->position }} </span>
            </nav>
            {{--  Bredcrumb End --}}
            <div class="row mt-3">
                <div class="col-lg-2"></div>
                <div class="col-lg-8 mb-4">
                    <div class="card job-item">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h3 class="card-title">{{ $career->position }}</h3>

                        </div>
                        <div class="card-body ">
                            @if (!$career->image)
                                <div class="d-flex g-5 align-items-center text-dark p-3">
                                    <i class="fa fa-map-marker" style="font-size: 22px" aria-hidden="true"></i>
                                    <h6 class="mb-0 ml-2 ">{{ $career->location }}</h6>
                                </div>
                                <div class="card-text text-dark mt-5">
                                    {!! $career->description !!}

                                </div>
                                <div class="d-flex align-items-center text-dark mt-3">
                                    <p class="mb-0 mr-2"><strong>Deadline:</strong></p>
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                    <p class="mb-0 ml-2">{{ \Carbon\Carbon::parse($career->deadline)->format('d-M-Y') }}
                                    </p>
                                </div>
                            @else
                                <img class="glightbox" style="max-width: 100%; height: auto;"
                                    src="{{ asset('assets/storage/career/' . $career->image) }}"
                                    alt="{{ $career->position }}">
                            @endif
                        </div>
                    </div>

                </div>
                <div class="col-lg-2"></div>
            </div>

            <div class=" position-sticky d-flex justify-content-center" style="left: 50%; bottom: 20px;">
                <a class="btn bg-orange px-5" href="{{ route('career.form', $career->slug) }}">Apply Now <i
                        class="fa fa-external-link" aria-hidden="true"></i></a>
            </div>

        </div>
    </section>
@endsection
