@extends('layouts.front-end.app')
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
    <section class="py-3 career">
        <div class="container position-relative">
            <div class="row">
                <div class="col-lg-2"></div>
                <div class="col-lg-8 mb-4">
                    <div class="card job-item">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center">
                            <h3 class="card-title">{{ $career->position }}</h3>
                            <img style="width: 70px; height: auto;"
                                src="{{ asset('assets/front-end/images/logo/images.png') }}" alt="logo">
                        </div>
                        <div class="card-body">


                            <div class="d-flex g-5 align-items-center text-dark">
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
