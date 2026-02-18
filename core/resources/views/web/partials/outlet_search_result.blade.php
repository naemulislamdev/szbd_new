@if ($outlets->count() > 0)
    <h6>Search Result:</h6>
    @foreach ($outlets as $branch)
        <div class="card border-0 outlet-card mb-2">
            <div class="card-body py-2">
                <div class="row align-items-center">

                    <!-- Outlet Name -->
                    <div class="col-md-9">
                        <h6 style="color: #f26d21" class="mb-1 font-weight-bold">
                            {{ $branch->name }}
                        </h6>
                        <p class="text-small mb-2">
                            {{ $branch->address }}
                        </p>
                    </div>

                    <!-- Address -->
                    <div class="col-md-3">


                        <!-- Map Button -->
                        @if ($branch->map_url)
                            <a class="btn btn-sm btn-primary" target="_blank" href="{{ $branch->map_url }}"
                                title="View on Google Map" style="background:#f26d21; border:none;">

                                <img src="{{ asset('assets/frontend/img/google_map.png') }}"
                                    style="max-width:18px; height:auto;">

                                <span class="ml-1">View on Map</span>
                            </a>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    @endforeach
@else
    <p class="text-muted px-2 py-2">No outlet found in: <strong>{{ $name }}</strong></p>
@endif
