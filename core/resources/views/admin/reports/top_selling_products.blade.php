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
                        <div class="row mb-2">
                            <div class="col d-flex justify-content-end">
                                <div id="customRangeBox" class="p-0 border-top d-none mr-2">
                                    <div class="row g-2">
                                        <div class="col-md-5">
                                            <input type="date" id="startDate" class="form-control">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="date" id="endDate" class="form-control">
                                        </div>
                                        <input type="hidden" name="filter" id="filterInput">
                                        <input type="hidden" name="from_date" id="fromDateInput">
                                        <input type="hidden" name="to_date" id="toDateInput">
                                    </div>
                                </div>
                                <div class="dropdown">
                                    <a href="#" class="btn bt btn-light dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false" id="topSellingFilterBtn">
                                        <i class="las la-calendar fs-5 me-1"></i>
                                        <span id="filterLabel">This Month</span>
                                        <i class="las la-angle-down ms-1"></i>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item top-selling-filter" href="#" data-filter="today"
                                            data-label="Today">Today</a>
                                        <a class="dropdown-item top-selling-filter" href="#" data-filter="yesterday"
                                            data-label="Yesterday">Yesterday</a>
                                        <a class="dropdown-item top-selling-filter" href="#" data-filter="last_week"
                                            data-label="Last Week">Last Week</a>
                                        <a class="dropdown-item top-selling-filter" href="#" data-filter="last_month"
                                            data-label="Last Month">Last Month</a>
                                        <a class="dropdown-item top-selling-filter" href="#" data-filter="this_year"
                                            data-label="This Year">This Year</a>
                                        <a class="dropdown-item top-selling-filter" href="#" data-filter="this_month"
                                            data-label="This Month">This Month</a>
                                        <a class="dropdown-item top-selling-filter" href="#"
                                            data-filter="custom_range" data-label="Custom Range">Custom Range</a>
                                    </div>
                                </div>
                                <div class="ml-2">
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
                        </div>
                        <div class="row">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Summary Card -->

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
                                <th>#SL</th>
                                <th>Thumbnail</th>
                                <th>Product Name</th>suecal.
                                <th>Code</th>
                                <th>Price</th>
                                <th>Quantity Sold</th>
                                <th>Total Orders</th>
                                <th>Sales Amount</th>
                                <th>% of Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div><!-- container -->

@endsection
@push('scripts')
    <script>
        let table;

        document.addEventListener("DOMContentLoaded", function() {

            const filterItems = document.querySelectorAll('.top-selling-filter');
            const filterLabel = document.getElementById('filterLabel');
            const filterInput = document.getElementById('filterInput');

            const customBox = document.getElementById('customRangeBox');

            const fromInput = document.getElementById('fromDateInput');
            const toInput = document.getElementById('toDateInput');

            const startDate = document.getElementById('startDate');
            const endDate = document.getElementById('endDate');

            // ✅ DataTable init
            table = $('#topSellingTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.report.topSellingProductsData') }}",
                    data: function(d) {
                        d.filter = filterInput.value;
                        d.from_date = fromInput.value;
                        d.to_date = toInput.value;
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'thumbnail',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'products.name'
                    },
                    {
                        data: 'code',
                        name: 'products.code'
                    },
                    {
                        data: 'price',
                        name: 'products.unit_price'
                    },
                    {
                        data: 'qty',
                        name: 'total_quantity',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'orders',
                        name: 'order_count',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'sales',
                        name: 'total_sales',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'percentage',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            // ✅ Dropdown click
            filterItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();

                    let filter = this.dataset.filter;
                    let label = this.dataset.label;

                    filterInput.value = filter;
                    filterLabel.innerText = label;

                    if (filter === 'custom_range') {
                        customBox.classList.remove('d-none');
                    } else {
                        customBox.classList.add('d-none');
                        setDateRange(filter);
                    }
                });
            });

            // ✅ Filter button click
            document.querySelector('.btn-primary').addEventListener('click', function(e) {
                e.preventDefault();

                if (filterInput.value === 'custom_range') {
                    if (!startDate.value || !endDate.value) {
                        alert('Please select date range');
                        return;
                    }

                    fromInput.value = startDate.value;
                    toInput.value = endDate.value;
                }

                table.ajax.reload(); // 🔥 MAIN ACTION
            });

            // ✅ Date calculation
            function setDateRange(filter) {

                let today = new Date();
                let from = new Date();
                let to = new Date();

                switch (filter) {
                    case 'today':
                        break;

                    case 'yesterday':
                        from.setDate(today.getDate() - 1);
                        to.setDate(today.getDate() - 1);
                        break;

                    case 'last_week':
                        from.setDate(today.getDate() - 7);
                        break;

                    case 'last_month':
                        from.setMonth(today.getMonth() - 1);
                        break;

                    case 'this_month':
                        from = new Date(today.getFullYear(), today.getMonth(), 1);
                        break;

                    case 'this_year':
                        from = new Date(today.getFullYear(), 0, 1);
                        break;
                }

                fromInput.value = formatDate(from);
                toInput.value = formatDate(to);
            }

            function formatDate(date) {
                let d = new Date(date),
                    m = '' + (d.getMonth() + 1),
                    day = '' + d.getDate(),
                    y = d.getFullYear();

                if (m.length < 2) m = '0' + m;
                if (day.length < 2) day = '0' + day;

                return [y, m, day].join('-');
            }

        });
    </script>

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
