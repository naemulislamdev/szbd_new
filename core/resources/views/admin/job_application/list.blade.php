@extends('admin.layouts.app')
@section('title', 'Job Applications ')

@push('styles')
    <style>
        td.change_status {
            padding: 0
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Job Applicatins</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->

                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Job Applications</li>
                        </ol>
                    </div>
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">Job Applications List</h4>
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
                                        <th>SL#</th>
                                        <th>Name</th>
                                        {{-- <th>Email</th> --}}
                                        <th>Phone</th>
                                        <th>Applyed Position</th>
                                        <th>CV</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Change Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container -->

    <!--view Modal -->
    <div class="modal fade" id="editModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content">
                <form id="editForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Application Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th scope="row">Full Name</th>
                                    <td id="view_name"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Email</th>
                                    <td id="view_email"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Phone</th>
                                    <td id="view_phone"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Applyed Position</th>
                                    <td id="view_current_position"></td>
                                </tr>
                                <tr>
                                    <th scope="row">CV</th>
                                    <td id="view_resume"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Experience</th>
                                    <td id="view_experience_level"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Portfolio</th>
                                    <td id="view_portfolio_link"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Status</th>
                                    <td id="view_status"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Message</th>
                                    <td id="view_message"></td>
                                </tr>
                                <tr>
                                    <th scope="row">Applied At</th>
                                    <td id="view_created_at"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
                    </div>
                </form>
            </div>
        </div>
    </div>




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

            const table = $('#szbd-datatable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: false,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.application.datatables') }}",
                },

                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center p-1'
                    },
                    {
                        data: 'name'
                    },
                    // {
                    //     data: 'email'
                    // },
                    {
                        data: 'phone'
                    },
                    {
                        data: 'career_id'
                    },
                    {
                        data: 'resume'
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'change_status',
                        searchable: false,
                        orderable: false,
                        className: 'p-0 change_status'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],

                order: [
                    [1, 'desc']
                ]
            });

            // ✅ Loader handling (CORRECT)
            table.on('processing.dt', function(e, settings, processing) {
                if (processing) {
                    $('#loader').fadeIn(100);
                } else {
                    $('#loader').fadeOut(100);
                }
            });


            // category delete
            $(document).on('click', '.delete', function() {
                var id = $(this).attr("data-id");
                Swal.fire({
                    title: 'Are you sure',
                    text: "You will not be able to revert this!",
                    showCancelButton: true,
                    type: 'warning',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('admin.application.delete') }}",
                            method: 'POST',
                            data: {
                                id: id
                            },
                            success: function() {
                                toastr.success(
                                    'Department Deleted Successfully.'
                                );
                                table.ajax.reload();

                            }
                        });
                    }
                })
            });

            // Update form
            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.career.departmentUpdate') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {

                        // modal close
                        $('#editModal').modal('hide');

                        // form reset
                        $('#editForm')[0].reset();

                        // datatable reload (🔥 main part)
                        table.ajax.reload(null, false);

                        toastr.success(res.message ?? 'Job Department Updated successfully');
                    },
                    error: function(err) {
                        toastr.error(err.responseJSON.message ?? 'Something went wrong!');
                    }
                });
            });
            $(document).on('change', '.status', function() {
                var id = $(this).attr("data-id");
                var status = $(this).val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "{{ route('admin.application.status') }}",
                    method: 'POST',
                    data: {
                        id: id,
                        status: status
                    },
                    success: function(data) {
                        toastr.success(
                            'Application Status Updated Successfully'
                        );
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        toastr.error('Something went wrong!');
                    }
                });
            });


        })(jQuery);
    </script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content')
            }
        });
    </script>


    <script>
        $(document).on('click', '.edit', function() {
            let data = $(this);
            console.log(data.data())

            // Table এর td-তে data set করা
            $('#view_name').text(data.data('name'));
            $('#view_email').text(data.data('email'));
            $('#view_phone').text(data.data('phone'));
            $('#view_current_position').text(data.data('position'));
            $('#view_experience_level').text(data.data('experience_level'));
            $('#view_portfolio_link').html(
                data.data('portfolio_link') ?
                `<a href="${data.data('portfolio_link')}" target="_blank">${data.data('portfolio_link')}</a>` :
                '-'
            );
            $('#view_status').html(
                `<span class="badge ${data.data('status') === 'pending' ? 'bg-warning' : 'bg-success'}">${data.data('status')}</span>`
            );
            $('#view_created_at').text(data.data('created_at'));
            $('#view_message').text(data.data('message') || '-');

            // Resume button
            var resumeBaseUrl = "{{ asset('assets/storage/files/job_resume') }}";
            if (data.data('resume')) {
                let resumeUrl = resumeBaseUrl + '/' + data.data('resume');
                $('#view_resume').html(
                    `<a href="${resumeUrl}" target="_blank" class="btn btn-sm btn-primary">View Resume</a>`
                );
            } else {
                $('#view_resume').html('-');
            }

            // Modal show
            $('#viewModal').modal('show');
        });
    </script>
@endpush
