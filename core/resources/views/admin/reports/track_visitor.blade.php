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
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">Track Visitor Report</h4>
                            </div><!--end col-->
                            <div class="col-auto">
                                <div class="row mb-3 align-items-center">
                                    <div class="col-md-3">
                                        <label for="from_date">From Date</label>
                                        <input type="date" id="from_date" value="{{ date('Y-m-d') }}"
                                            class="form-control" placeholder="From Date">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="to_date">To Date</label>
                                        <input type="date" id="to_date" value="{{ date('Y-m-d') }}"
                                            class="form-control" placeholder="To Date">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" id="filter_btn" class="btn btn-primary">Filter</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" id="reset_btn" class="btn btn-secondary">Reset</button>
                                    </div>
                                    <div class="col-md-2">
                                        <button class="btn btn-sm btn-primary" onclick="exportTrackVisitorReport()"><i
                                                class="las la-file-excel"></i>
                                            Export</button>
                                    </div>
                                </div>

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
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
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
                table.ajax.reload();
            });

            // Reset button click
            $('#reset_btn').on('click', function() {
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
