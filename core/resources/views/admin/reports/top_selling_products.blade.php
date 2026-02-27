@extends('admin.layouts.app')
@section('title', 'Products Report')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Products Report</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item"><a href="#">Reports</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Products Report</li>
                        </ol>
                    </div>
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <div class="row">
            <div class="col-12">
                <!-- Filter Card -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-header-title">Filter Options</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.report.topSellingProducts') }}" method="GET">
                            <div class="row">
                                {{-- dorpdown for product selection --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="product_id">Product</label>
                                        <select class="form-control js-select2-custom" id="product-select"
                                            name="product_id">
                                            <option value="">Select Product</option>
                                            @foreach ($productList as $product)
                                                <option value="{{ $product->id }}"
                                                    {{ $product->id == $selectedProductId ? 'selected' : '' }}>
                                                    {{ $product->name }} || {{ $product->code }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="from_date">From Date</label>
                                        <input type="date" class="form-control" id="from_date" name="from_date"
                                            value="{{ $from ? date('Y-m-d', strtotime($from)) : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="to_date">To Date</label>
                                        <input type="date" class="form-control" id="to_date" name="to_date"
                                            value="{{ $to ? date('Y-m-d', strtotime($to)) : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="limit">Number of Results</label>
                                        <select class="form-control" id="limit" name="limit">
                                            <option value="10" {{ $limit == 10 ? 'selected' : '' }}>Top 10</option>
                                            <option value="20" {{ $limit == 20 ? 'selected' : '' }}>Top 20</option>
                                            <option value="50" {{ $limit == 50 ? 'selected' : '' }}>Top 50</option>
                                            <option value="100" {{ $limit == 100 ? 'selected' : '' }}>Top 100</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary mr-2">
                                        <i class="tio-filter-list mr-1"></i> Filter
                                    </button>
                                    <a href="{{ route('admin.report.topSellingProducts') }}" class="btn btn-secondary">
                                        <i class="tio-clear mr-1"></i> Clear
                                    </a>
                                    <button type="button" class="btn btn-success float-right" onclick="exportToExcel()">
                                        <i class="tio-download-to mr-1"></i> Export Excel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Summary Card -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="media align-items-center">
                                    <div class="media-body">
                                        <h5 class="mb-0">Total Products Sold</h5>
                                        <span class="font-size-sm text-muted">In selected period</span>
                                    </div>
                                    <div class="ml-3">
                                        <span class="icon icon-sm icon-soft-secondary icon-circle">
                                            <i class="la la-shopping-basket"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <h2 class="mb-0">{{ $topSellingProducts->sum('total_quantity') }}</h2>
                                    <span class="text-muted">units</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="media align-items-center">
                                    <div class="media-body">
                                        <h5 class="mb-0">Total Sales Amount</h5>
                                        <span class="font-size-sm text-muted">In selected period</span>
                                    </div>
                                    <div class="ml-3">
                                        <span class="icon icon-sm icon-soft-success icon-circle">
                                            <i class="la la-money"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <h2 class="mb-0">{{ $total_sales_all }}</h2>
                                    <span class="text-muted">Top Selling Products</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="media align-items-center">
                                    <div class="media-body">
                                        <h5 class="mb-0">Top Products</h5>
                                        <span class="font-size-sm text-muted">Displaying top
                                            {{ $topSellingProducts->count() }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <span class="icon icon-sm icon-soft-warning icon-circle">
                                            <i class="la la-star"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <h2 class="mb-0">{{ $topSellingProducts->count() }}</h2>
                                    <span class="text-muted">products</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-header-title">Top Selling Products</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="topSellingTable">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Product Code</th>
                                <th class="text-right">Quantity Sold</th>
                                <th class="text-right">Total Orders</th>
                                <th class="text-right">Sales Amount</th>
                                <th class="text-right">% of Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topSellingProducts as $key => $item)
                                @php
                                    // Calculate percentage
                                    $percentage =
                                        $total_sales_all > 0
                                            ? round(($item->total_sales / $total_sales_all) * 100, 2)
                                            : 0;
                                    // Get product thumbnail URL
                                    $thumbnail = \App\Models\Product::find($item->id)->thumbnail ?? null;
                                    $thumbnailUrl = $thumbnail
                                        ? asset('assets/storage/product/thumbnail/' . $thumbnail)
                                        : asset('assets/admin/img/160x160/img1.jpg');
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <div class="media align-items-center">
                                            <img class="avatar avatar-lg mr-3"
                                                src="{{ asset('assets/storage/product/thumbnail') }}/{{ $item->thumbnail }}"
                                                alt="{{ $item->name ?? 'N/A' }}" style="width: 60px;">
                                            <div class="media-body">
                                                <a href="{{ route('admin.product.show', [$item->id]) }}"
                                                    class="text-hover-primary mb-0">
                                                    {{ $item->name ?? 'N/A' }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $item->code ?? 'N/A' }}</td>
                                    <td class="text-right">
                                        <strong>{{ $item->total_quantity }}</strong>
                                        <div class="text-muted small">units</div>
                                    </td>
                                    <td class="text-right">
                                        <strong>{{ $item->order_count ?? 0 }}</strong>
                                        <div class="text-muted small">orders</div>
                                    </td>
                                    <td class="text-right">
                                        <strong>{{ $item->total_sales }}</strong>
                                    </td>
                                    <td class="text-right">
                                        <div class="progress" style="height: 10px;">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}"
                                                aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span class="text-muted small">{{ $percentage }}%</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.product.show', [$item->id]) }}"
                                            class="btn btn-sm btn-primary" title="View Product">
                                            <i class="la la-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.product.edit', [$item->id]) }}"
                                            class="btn btn-sm btn-info" title="Edit Product">
                                            <i class="la la-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <div class="text-center p-4">
                                            <img class="mb-3" src="{{ asset('assets/admin/img/empty.svg') }}"
                                                alt="Empty" style="width: 200px">
                                            <p class="text-muted">No sales data found for the selected filters.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div><!-- container -->

@endsection
@push('scripts')
    <script>
        function exportToExcel() {
            // Simple table export
            const table = document.getElementById('topSellingTable');
            const html = table.outerHTML;
            const blob = new Blob([html], {
                type: 'application/vnd.ms-excel'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'top-selling-products-' + new Date().toISOString().slice(0, 10) + '.xls';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    </script>

    <script>
        // Export Daily Sales
        function exportProductReport() {
            let from_date = $('#from_date').val();
            let to_date = $('#to_date').val();

            if (!from_date || !to_date) {
                toastr.error('Please select both From Date and To Date.');
                return;
            }

            let url = "{{ route('admin.report.productReportExport') }}?from_date=" + from_date + "&to_date=" + to_date +
                "&export=1";
            window.open(url, '_blank');
        }

        $(document).ready(function() {
            $('#product-select').select2({
                placeholder: "Select Colors",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endpush
