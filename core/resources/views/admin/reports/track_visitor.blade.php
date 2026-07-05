@extends('admin.layouts.app')
@section('title', 'Profit Report')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Track Visitor Report</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item"><a href="#">Reports</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Track Visitor Report</li>
                        </ol>
                    </div>
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Filter by Track Visitor Report</h4>
                    </div><!--end card-header-->
                    <div class="card-body">
                        <div class="row mb-3 align-items-center">
                            <div class="col-md-3">
                                <label for="track_type">Track Type</label>
                                <select id="track_type" class="form-control">
                                    <option value="today" selected>Today</option>
                                    <option value="yesterday">Yesterday</option>
                                    <option value="last_7_days">Last 7 days</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="yearly">Yearly</option>
                                    <option value="custom">Custom range</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="track_status">Track Status</label>
                                <select id="track_status" class="form-control" multiple>
                                    <option value="direct">Direct</option>
                                    <option value="facebook_ads">Facebook Ads</option>
                                    <option value="facebook_search">Facebook Search</option>
                                    <option value="google_search">Google Search</option>
                                    <option value="google_ads">Google Ads</option>
                                </select>
                            </div>

                            <div class="col-md-3 d-flex align-items-end mt-3">
                                <button type="button" id="filter_btn" class="btn btn-sm btn-primary me-2">Filter</button>
                                <button type="button" id="reset_btn" class="btn btn-sm btn-secondary me-2">Reset</button>
                                <button class="btn btn-sm btn-primary" onclick="exportTrackVisitorReport()"><i
                                        class="las la-file-excel"></i>
                                    Export</button>
                            </div>
                        </div>
                        <div id="customRangeBox" class="p-0 d-none mr-2">
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <input type="date" id="startDate" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <input type="date" id="endDate" class="form-control">
                                </div>
                                <input type="hidden" id="from_date">
                                <input type="hidden" id="to_date">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">Track Visitor Report</h4>
                            </div><!--end col-->
                        </div><!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body pt-0">
                        <div id="loader" style="display:none; text-align:center; margin-bottom:10px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>

                        <div class="table-responsive overflow-hidden">
                            <table class="table" id="szbd-datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>SL</th>
                                        <th>Source</th>
                                        <th>Total Visitors</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container -->

@endsection
@push('scripts')
    <script>
        $('#track_status').select2({
            placeholder: 'Select track status',
            allowClear: true,
            width: '100%'
        });

        $('#track_type').on('change', function() {
            let track_type = $(this).val();
            let startDate = $('#startDate').val();
            let endDate = $('#endDate').val();

            if (track_type === 'custom') {
                $('#customRangeBox').removeClass('d-none');
            } else {
                $('#customRangeBox').addClass('d-none');
            }
        });
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    </script>

    <script>
        (function($) {
            "use strict";

            var table = $('#szbd-datatable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: false,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.report.trackVisitorData') }}",
                    data: function(d) {
                        d.from_date = $('#startDate').val();
                        d.to_date = $('#endDate').val();
                        d.track_type = $('#track_type').val();
                        d.track_status = $('#track_status').val();
                    },
                    beforeSend: function() {
                        $('#loader').show();
                    },
                    complete: function() {
                        $('#loader').hide();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'source',
                        name: 'source'
                    },
                    {
                        data: 'total',
                        name: 'total',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
            // Filter button click
            $('#filter_btn').on('click', function() {

                let track_type = $('#track_type').val();
                let startDate = $('#startDate').val();
                let endDate = $('#endDate').val();
                let track_status = $('#track_status').val(); // array

                if (track_type === 'custom') {
                    if (!startDate || !endDate) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Date Required',
                            text: 'Please select both From and To date!'
                        });
                        return;
                    }
                }

                // set hidden values for ajax
                $('#from_date').val(startDate);
                $('#to_date').val(endDate);

                table.ajax.reload();
            });

            // Reset button click
            $('#reset_btn').on('click', function() {
                $('#track_type').val('today').trigger('change');
                $('#track_status').val(null).trigger('change');

                $('#startDate').val('');
                $('#endDate').val('');

                $('#from_date').val('');
                $('#to_date').val('');

                table.ajax.reload();
            });


        })(jQuery);
    </script>
    <script>
        // Export Daily Sales
        function exportTrackVisitorReport() {
            let from_date = $('#from_date').val();
            let to_date = $('#to_date').val();

            if (!from_date || !to_date) {
                toastr.error('Please select both From Date and To Date.');
                return;
            }

            let url = "{{ route('admin.report.trackVisitorExport') }}?from_date=" + from_date + "&to_date=" + to_date +
                "&export=1";
            window.open(url, '_blank');
        }
    </script>
@endpush
