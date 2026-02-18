@extends('web.layouts.app')
@section('title', 'Our outlets')

@section('content')
    <section class="offer-section py-3">
        <div class="container">
            <div class="row mb-4">
                <div class="col">
                    <div class="outlets-heading border-bottom-0">
                        <h2>Our <span style="color: #fb9b00;">Outlets</span></h2>
                        <p style="font-family: 'jost';" class="lead">Explore our outlet locations and drop by anytime. Our
                            team is
                            always ready
                            to
                            assist you with
                            care and quality service.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <form class="outlet_search_form">
                        <div class="input-group">
                            <input autocomplete="off" type="text"
                                class="form-control form-control-lg outlet-search-input" placeholder="Search Our Outlet"
                                name="search" id="OutletSearchInput">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-6 mx-auto">
                    <div class="search-card" style="display:none;">
                        <div style="box-shadow: rgba(9, 30, 66, 0.25) 0px 4px 8px -2px, rgba(9, 30, 66, 0.08) 0px 0px 0px 1px;"
                            class="search-result-box p-4 bg-white border rounded"></div>
                    </div>
                </div>

            </div>
            <div class="row">
                @foreach ($branchs as $branch)
                    <div class=" col-lg-4 mb-3">
                        <div class="card border-0 outlet-card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <!-- Outlet Info -->
                                    <div class="col-12">
                                        <h5 class="card-title mb-1 font-weight-bold">
                                            {{ $branch->name }}
                                        </h5>
                                    </div>
                                    <div class="col-12">

                                        <p class="text-small card-text">{{ $branch->address }}</p>
                                        <a class="btn btn-primary" target="_blank"
                                            href="{{ $branch->map_url ? $branch->map_url : '' }}" title="View on Google Map"
                                            style="max-width: 100%; background: #f26d21">

                                            <img style="max-width: 30px; height: auto;"
                                                src="{{ asset('assets/frontend/img/google_map.png') }}">
                                            <span>View on Map</span>
                                        </a>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
