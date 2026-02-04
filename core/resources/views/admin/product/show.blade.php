@extends('admin.layouts.app')
@section('title', 'Product Details - Admin Dashboard')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Product Details</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}">Products</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Product Details</li>
                        </ol>
                    </div>
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->

        <!-- Card -->
        <div class="card mb-3 mb-lg-5">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="page-header-title">{{ $product['name'] }}</h3>
                    </div>
                    <div class="col-md-6">
                        <div>
                            <a href="{{ url()->previous() }}" class="btn btn-primary float-right">
                                <i class="tio-back-ui"></i> Back
                            </a>
                            <a href="#" class="btn btn-primary " target="_blank"><i class="tio-globe"></i> View
                                fro mWebsite
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Nav -->
                <ul class="nav nav-tabs page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:">
                            Product reviews
                        </a>
                    </li>
                </ul>
                <!-- End Nav -->
            </div>
            <!-- Body -->
            <div class="card-body">
                <div class="row align-items-md-center gx-md-5">

                    {{-- Product Rating Summary --}}
                    <div class="col-md-auto mb-3 mb-md-0">
                        <div class="d-flex align-items-center">
                            <img class="avatar avatar-xxl avatar-4by3" style="width:200px;"
                                src="{{ asset('assets/storage/product/thumbnail/' . $product->thumbnail) }}"
                                alt="Product Thumbnail">

                            <div class="d-block ml-3">
                                <h4 class="display-2 text-dark mb-0">
                                    {{ $product->rating->count() ? number_format($product->rating[0]->average, 2) : 0 }}
                                </h4>
                                <p>
                                    of {{ $product->reviews->count() }} reviews
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Rating Progress --}}
                    <div class="col-md">
                        @php
                            $total = $product->reviews->count();

                            $five = \App\CPU\Helpers::rating_count($product->id, 5);
                            $four = \App\CPU\Helpers::rating_count($product->id, 4);
                            $three = \App\CPU\Helpers::rating_count($product->id, 3);
                            $two = \App\CPU\Helpers::rating_count($product->id, 2);
                            $one = \App\CPU\Helpers::rating_count($product->id, 1);

                            $ratings = [
                                5 => $five,
                                4 => $four,
                                3 => $three,
                                2 => $two,
                                1 => $one,
                            ];
                        @endphp

                        <ul class="list-unstyled list-unstyled-py-2 mb-0">
                            @foreach ($ratings as $star => $count)
                                <li class="d-flex align-items-center font-size-sm">
                                    <span class="{{ session('direction') === 'rtl' ? 'ml-3' : 'mr-3' }}">
                                        {{ $star }} star
                                    </span>

                                    <div class="progress flex-grow-1">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: {{ $total ? ($count / $total) * 100 : 0 }}%;"
                                            aria-valuenow="{{ $total ? ($count / $total) * 100 : 0 }}" aria-valuemin="0"
                                            aria-valuemax="100">
                                        </div>
                                    </div>

                                    <span class="{{ session('direction') === 'rtl' ? 'mr-3' : 'ml-3' }}">
                                        {{ $count }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="col-12">
                        <hr>
                    </div>

                    {{-- Product Info --}}
                    <div class="col-4 pt-2">
                        <h4 class="border-bottom">{{ $product->name }}</h4>

                        <p>Price: {{ $product->unit_price }}</p>
                        <p>TAX: {{ $product->tax }}%</p>
                        <p>
                            Discount:
                            {{ $product->discount_type === 'flat' ? $product->discount : $product->discount . '%' }}
                        </p>
                        <p>Shipping Cost: {{ $product->shipping_cost }}</p>
                        <p>Current Stock: {{ $product->current_stock }}</p>
                    </div>
                    {{-- Colors & Images --}}
                    @php
                        $colors = $colors = $product->colors ?? [];
                        $images = json_decode($product->images ?? '[]', true);
                    @endphp

                    <div class="col-8 pt-2 border-left">

                        {{-- Colors --}}
                        @if (count($colors))
                            <div class="row no-gutters mb-3">
                                <div class="col-2">
                                    <strong>Available color:</strong>
                                </div>
                                <div class="col-10">
                                    <ul class="list-inline checkbox-color">
                                        @foreach ($colors as $key => $color)
                                            <li class="list-inline-item">
                                                <span
                                                    style="
                                        display:inline-block;
                                        width:25px;
                                        height:25px;
                                        background:{{ $color }};
                                        border-radius:50%;">
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        {{-- Images --}}
                        <h6>Product Images</h6>
                        <div class="row">
                            @foreach ($images as $photo)
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <div class="card-body p-2">
                                            <img class="img-fluid" src="{{ asset('assets/storage/product/' . $photo) }}"
                                                alt="Product Image">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

            <!-- End Body -->
        </div>
        <!-- End Card -->

        <!-- Card -->
        <div class="card">
            <!-- Table -->
            <div class="table-responsive datatable-custom">
                <table class="table table-borderless table-thead-bordered table-nowrap card-table"
                    style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
                    <thead class="thead-light">
                        <tr>
                            <th>Reviewer</th>
                            <th>Review</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reviews as $review)
                            @if (isset($review->customer))
                                <tr>
                                    <td>
                                        <a class="d-flex align-items-center" href="#">
                                            <div class="avatar avatar-circle">
                                                <img class="avatar-img"
                                                    src="{{ asset('storage/profile/') }}{{ $review->customer->image ?? '' }}"
                                                    alt="Image Description">
                                            </div>
                                            <div class="{{ Session::get('direction') === 'rtl' ? 'mr-3' : 'ml-3' }}">
                                                <span
                                                    class="d-block h5 text-hover-primary mb-0">{{ $review->customer['f_name'] . ' ' . $review->customer['l_name'] }}
                                                    <i class="tio-verified text-primary" data-toggle="tooltip"
                                                        data-placement="top" title="Verified Customer"></i></span>
                                                <span
                                                    class="d-block font-size-sm text-body">{{ $review->customer->email ?? '' }}</span>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="text-wrap" style="width: 28rem;">
                                            <div class="d-flex mb-2">
                                                <label class="badge badge-soft-info">
                                                    <span style="font-size: .9rem;">{{ $review->rating }} <i
                                                            class="tio-star"></i> </span>
                                                </label>
                                            </div>

                                            <p>
                                                {{ $review['comment'] }}
                                            </p>
                                            @foreach (json_decode($review->attachment) as $img)
                                                <a class="float-left"
                                                    href="{{ asset('asstes/storage/review') }}/{{ $img }}"
                                                    data-lightbox="mygallery">
                                                    <img style="width: 60px;height:60px;padding:10px; "
                                                        src="{{ asset('storage/review') }}/{{ $img }}"
                                                        alt="">
                                                </a>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td>
                                        {{ date('d M Y H:i:s', strtotime($review['created_at'])) }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End Table -->
            @if (count($reviews) == 0)
                <div class="text-center p-4">
                    <img class="mb-3" src="{{ asset('assets/back-end') }}/svg/illustrations/sorry.svg"
                        alt="Image Description" style="width: 7rem;">
                    <p class="mb-0">No data to show</p>
                </div>
            @endif
            <!-- Footer -->
            <div class="card-footer">
                {!! $reviews->links() !!}
            </div>
            <!-- End Footer -->
        </div>
        <!-- End Card -->
    </div>

@endsection
@push('scripts')
    <script>
        $('input[name="colors_active"]').on('change', function() {
            if (!$('input[name="colors_active"]').is(':checked')) {
                $('#colors-selector').prop('disabled', true);
            } else {
                $('#colors-selector').prop('disabled', false);
            }
        });
        $(document).ready(function() {
            $('.color-var-select').select2({
                templateResult: colorCodeSelect,
                templateSelection: colorCodeSelect,
                escapeMarkup: function(m) {
                    return m;
                }
            });

            function colorCodeSelect(state) {
                var colorCode = $(state.element).val();
                if (!colorCode) return state.text;
                return "<span class='color-preview' style='background-color:" + colorCode + ";'></span>" + state
                    .text;
            }
        });
    </script>
@endpush
