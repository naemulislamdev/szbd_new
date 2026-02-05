@extends('web.layouts.app')
@section('title', 'Careers')
<style>
    section.career .card-title {
        color: #ff5d00;
        font-family: var(--jost);
    }

    section.career .job-item {
        border: none;
        border-radius: 8px;
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px;
        transition: all 0.3s ease-in-out;
    }

    section.career .job-item:hover {
        background: rgba(255, 93, 0, 0.2);
    }

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
    <section class="py-3 career">
        <div class="container " style="min-height: 100vh;">

            <div class="row mb-3">
                <div class="col text-center">
                    <div class="section-heading-title">
                        <h3>Career</h3>

                        <div class="heading-border"></div>
                        <div class="row justify-content-center">
                            <p class="col-lg-7 mx-auto text-muted carear-para">Join our team and be a part of Bangladesh’s leading
                            clothing
                            brand. At
                            ShoppingZone BD, we’re
                            looking for passionate, creative, and dedicated individuals to help shape the future of fashion.
                        </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                @if ($careers->count() > 0)
                    @foreach ($careers as $career)
                        <div class="col-lg-2"></div>
                        <div class="col-lg-8 mb-4">
                            <div class="card job-item">
                                <a href="{{ route('career.details', $career->slug) }}">
                                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                        <h5 class="card-title">{{ $career->position }}</h5>
                                        <img style="width: 60px; height: auto;"
                                            src="{{ asset('assets/front-end/images/logo/images.png') }}" alt="logo">
                                    </div>
                                    <div class="card-body">


                                        <div class="d-flex g-5 align-items-center text-dark mb-2">
                                            <i class="fa fa-map-marker" style="font-size: 22px" aria-hidden="true"></i>
                                            <h6 class="mb-0 ml-2 ">{{ $career->location }}</h6>
                                        </div>
                                        <div class="card-text text-dark "
                                            style="height: 140px;  overflow: hidden; text-emphasis: wrap;">
                                            {{-- {!! Str::limit(strip_tags($career->description), 500, '...') !!} --}}
                                            {!! $career->description !!}
                                        </div>
                                        <div class="d-flex align-items-center text-dark mt-3">
                                            <p class="mb-0 mr-2"><strong>Deadline:</strong></p>
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                            <p class="mb-0 ml-2">
                                                {{ \Carbon\Carbon::parse($career->deadline)->format('d-M-Y') }}
                                            </p>
                                        </div>
                                        <div class="mt-4">
                                            <a href="{{ route('career.details', $career->slug) }}"
                                                class="btn btn-info stretched-link d-inline-block"> <i
                                                    class="fa fa-eye    "></i> View
                                                Details</a>
                                            <a href="{{ route('career.form', $career->slug) }}"
                                                class="ml-4 btn bg-orange d-inline-block">Apply
                                                Now <i class="fa fa-external-link" aria-hidden="true"></i></a>
                                        </div>

                                    </div>
                                </a>
                            </div>

                        </div>
                        <div class="col-lg-2"></div>
                    @endforeach
                @else
                    <div class="col-12 mt-5">
                        <h2 class="text-center">No Job avilable in this moment!</h2>
                    </div>
                @endif


            </div>
        </div>
    </section>
@endsection
