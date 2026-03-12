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

    .job-card {
        border-radius: 12px;
        transition: 0.3s;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }

    .job-card:hover {
        transform: translateY(-4px);

    }
</style>
@section('content')
    <section class="career">
        <div class="container " style="min-height: 100vh;">
            {{-- Bredcrumb start  --}}
            <nav class="breadcrumb custom-breadcrumb mt-3">
                <a class="breadcrumb-item" href="{{ route('home') }}">Home</a>
                <span class="breadcrumb-item active" aria-current="page">Career</span>
            </nav>
            {{--  Bredcrumb End --}}
            <div class="row mb-3">
                <div class="col text-center">
                    <div class="section-heading-title">
                        <h1>Career</h1>

                        <div class="heading-border"></div>
                        <div class="row justify-content-center">
                            <p class="col-lg-7 mx-auto text-muted carear-para">Join our team and be a part of Bangladesh’s
                                leading
                                clothing
                                brand. At
                                ShoppingZone BD, we’re
                                looking for passionate, creative, and dedicated individuals to help shape the future of
                                fashion.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">

                @if ($careers->count() > 0)
                    @foreach ($careers as $career)
                        <div class="col-md-4 mb-4">
                            <div class="card border-0 job-card h-100">
                                <div class="card-body d-flex flex-column">

                                    <a href="{{ route('career.details', $career->slug) }}">
                                        <h5 class="card-title fw-bold mb-2">
                                            {{ $career->position }}
                                        </h5>
                                    </a>

                                    <p class="text-muted mb-2">
                                        <strong>Company:</strong> Shopping Zone BD
                                    </p>

                                    @if ($career->experience)
                                        <div class="d-flex align-items-center mb-2 text-secondary">
                                            <i class="bi bi-briefcase-fill mr-2"></i>
                                            <span>{{ $career->experience }}</span>
                                        </div>
                                    @endif

                                    <div class="d-flex align-items-center mb-3 text-secondary">
                                        <i class="bi bi-geo-alt-fill mr-2"></i>
                                        <span>Mohammadpur, Dhaka</span>
                                    </div>

                                    <div class="mt-auto d-flex gap-2">
                                        <a href="{{ route('career.details', $career->slug) }}" class="btn btn-dark btn-sm">
                                            View Details <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('career.form', $career->slug) }}"
                                            class=" btn bg-orange btn-sm ml-2">
                                            Apply Now <i class="bi bi-link-45deg"></i>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 mt-5">
                        <h2 class="text-center">No Job available at this moment!</h2>
                    </div>
                @endif

            </div>
        </div>
    </section>
@endsection
