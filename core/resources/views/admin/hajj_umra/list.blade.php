@extends('admin.layouts.app')
@section('title', 'Umrah Hajj Applications')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row pr-4 pe-4">
            <div class="col-sm-12 ">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">All Umrah Hajj Applications</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->

                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">All Umrah Hajj Applications</li>
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
                                <h4 class="card-title">All Umrah Hajj Applications</h4>
                            </div><!--end col-->
                            <div class="col-auto">
                                <div class="row mb-3">


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
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Occupation</th>
                                        <th>Age</th>
                                        <th>Gender</th>
                                        <th>Done Hajj Before</th>
                                        <th width="100">Status</th>
                                        <th>Note</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container -->

    <!--application view Modal -->
    <div class="modal fade" id="editModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ti ti-file-text me-2"></i> আবেদনকারীর তথ্য
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">

                    {{-- ১ --}}
                    <p class="text-uppercase fw-bold small text-success border-bottom pb-1 mb-3">
                        <i class="ti ti-user me-1"></i> ব্যক্তিগত তথ্য
                    </p>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">পূর্ণ নাম</p>
                            <p class="fw-medium mb-0" id="fullName">—</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">পেশা</p>
                            <p class="fw-medium mb-0" id="occupation">—</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">মোবাইল নম্বর</p>
                            <p class="fw-medium mb-0" id="mobileNumber">—</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">হোয়াটসঅ্যাপ</p>
                            <p class="fw-medium mb-0" id="whatsappNumber">—</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">ইমেইল</p>
                            <p class="fw-medium mb-0" id="email">—</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">ঠিকানা</p>
                            <p class="fw-medium mb-0" id="address">—</p>
                        </div>
                    </div>

                    {{-- ২ --}}
                    <p class="text-uppercase fw-bold small text-success border-bottom pb-1 mb-3">
                        <i class="ti ti-id-badge me-1"></i> আবেদনকারীর তথ্য
                    </p>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <p class="text-muted small mb-1">বয়স</p>
                            <p class="fw-medium mb-0" id="age">—</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-1">লিঙ্গ</p>
                            <p class="fw-medium mb-0" id="gender">—</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-1">মাহরাম</p>
                            <p class="fw-medium mb-0" id="mahram">—</p>
                        </div>
                        <div class="col-md-4">
                            <p class="text-muted small mb-1">বৈবাহিক অবস্থা</p>
                            <p class="fw-medium mb-0" id="maritalStatus">—</p>
                        </div>
                        <div class="col-md-8">
                            <p class="text-muted small mb-1">আগে উমরাহ/হজ করেছেন?</p>
                            <p class="fw-medium mb-0" id="hasDoneUmrahOrHaj">—</p>
                        </div>
                    </div>

                    {{-- ৩ --}}
                    <p class="text-uppercase fw-bold small text-success border-bottom pb-1 mb-3">
                        <i class="ti ti-passport me-1"></i> পাসপোর্ট তথ্য
                    </p>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">বৈধ পাসপোর্ট আছে?</p>
                            <p class="fw-medium mb-0" id="hasValidPassport">—</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">৬ মাসের মেয়াদ আছে?</p>
                            <p class="fw-medium mb-0" id="passportValidity">—</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">পাসপোর্ট নম্বর</p>
                            <p class="fw-medium mb-0" id="passportNumber">—</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">মেয়াদ শেষের তারিখ</p>
                            <p class="fw-medium mb-0" id="passportExpiryDate">—</p>
                        </div>
                    </div>

                    {{-- ৪ --}}
                    <p class="text-uppercase fw-bold small text-success border-bottom pb-1 mb-3">
                        <i class="ti ti-notes me-1"></i> নির্বাচন সংক্রান্ত তথ্য
                    </p>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">নিজে অর্থায়ন করতে পারবেন?</p>
                            <p class="fw-medium mb-0" id="canSelfFinance">—</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">কীভাবে জানলেন?</p>
                            <p class="fw-medium mb-0" id="application_source">—</p>
                        </div>
                        <div class="col-12">
                            <p class="text-muted small mb-1">কেন নির্বাচিত হতে চান?</p>
                            {{-- ✅ id ঠিক করা হয়েছে: selcted_reason → selected_reason --}}
                            <p class="fw-medium mb-0 bg-light p-2 rounded border" id="selected_reason">—</p>
                        </div>
                    </div>

                    {{-- ৫ --}}
                    <p class="text-uppercase fw-bold small text-success border-bottom pb-1 mb-3">
                        <i class="ti ti-shield-check me-1"></i> অ্যাডমিন স্ট্যাটাস ও নোট
                    </p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">স্ট্যাটাস</p>
                            <p class="fw-medium mb-0" id="status">—</p>
                        </div>
                        <div class="col-12">
                            <p class="text-muted small mb-1">অ্যাডমিন নোট</p>
                            <p class="fw-medium mb-0" id="notes">—</p>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">বন্ধ করুন</button>
                </div>

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
                scrollX: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.free_hajj_umrah.datatables') }}",

                },

                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center p-1'
                    },

                    {
                        data: 'full_name'
                    },
                    {
                        data: 'mobile_number'
                    },
                    {
                        data: 'address'
                    },
                    {
                        data: 'occupation'
                    },
                    {
                        data: 'age'
                    },
                    {
                        data: 'gender'
                    },

                    {
                        data: 'has_done_umrah_or_haj_before'
                    },

                    {
                        data: 'status',
                        style: "width: 100px"
                    },
                    {
                        data: 'notes'
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
                            url: "{{ route('admin.free_hajj_umrah.delete') }}",
                            method: 'POST',
                            data: {
                                id: id
                            },
                            success: function() {
                                toastr.success(
                                    'Application Deleted Successfully.'
                                );
                                table.ajax.reload(null, false);

                            }
                        });
                    }
                })
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
            let btn = $(this);

            $('#fullName').text(btn.data('full_name') || '—');
            $('#occupation').text(btn.data('occupation') || '—');
            $('#mobileNumber').text(btn.data('mobile_number') || '—');
            $('#whatsappNumber').text(btn.data('whatsapp_number') || '—');
            $('#email').text(btn.data('email') || '—');
            $('#address').text(btn.data('address') || '—');
            $('#age').text(btn.data('age') || '—');
            $('#gender').text(btn.data('gender') || '—');
            $('#maritalStatus').text(btn.data('marital_status') || '—');
            $('#hasDoneUmrahOrHaj').text(btn.data('has_done_umrah_or_haj_before') || '—');
            $('#hasValidPassport').text(btn.data('has_valid_passport') || '—');
            $('#passportValidity').text(btn.data('passport_validity_6_months') || '—');
            $('#passportNumber').text(btn.data('passport_number') || '—');
            $('#passportExpiryDate').text(btn.data('passport_expiry_date') || '—');
            $('#preferredPackage').text(btn.data('preferred_package') || '—');
            $('#canSelfFinance').text(btn.data('can_self_finance') || '—');
            $('#status').text(btn.data('status') || '—');
            $('#mahram').text(btn.data('mahram') || '—');
            $('#application_source').text(btn.data('application_source') || '—');
            $('#selected_reason').text(btn.data('selected_reason') || '—');

            $('#editModal').modal('show');
        });
    </script>
    <script>
        window.order_status = function order_status(status, id) {
            var orderStatus = status ? status : 'pending';

            if (status === 'approved') {
                Swal.fire({
                    title: 'Are you sure Change this?!',
                    text: "'Think before you completed.",
                    html: `
                        <br />
                        <form class="form-horizontal" action="{{ route('admin.free_hajj_umrah.status') }}" method="post">
                            <input type="hidden" name="status" value=" ${status}">
                        <input type="hidden" name="id" value="${id}">
                            <input required
                                class="form-control wedding-input-text wizard-input-pad"
                                type="text"
                                name="note"
                                id="note"
                                placeholder="For  ${status} note">
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#377dff',
                    cancelButtonColor: 'secondary',
                    confirmButtonText: 'Yes, Change it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('admin.free_hajj_umrah.status') }}",
                            method: 'POST',
                            data: $("form").serialize(),
                            success: function(data) {

                                toastr.success('Status Change successfully');

                                $(`.note_${id}`).html(data.note);
                                $('#szbd-datatable').DataTable().ajax.reload(null, false);

                            },
                            error: function(data) {
                                toastr.warning('Something went wrong !');
                            }
                        });
                    }
                });
            } else if (status === 'rejected') {
                Swal.fire({
                    title: 'Are you sure Change this?',
                    text: "You won't be able to revert this!",
                    html: `
                        <br />
                        <form class="form-horizontal" action="{{ route('admin.free_hajj_umrah.status') }}" method="post">

                              <input type="hidden" name="status" value=" ${status}">
                        <input type="hidden" name="id" value="${id}">

                            <input required class="form-control wedding-input-text wizard-input-pad" type="text" name="note" id="note" placeholder="For ${status} note">
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#377dff',
                    cancelButtonColor: 'secondary',
                    confirmButtonText: 'Yes, Change it!',
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('admin.free_hajj_umrah.status') }}",
                            method: 'POST',
                            data: $("form").serialize(),
                            success: function(data) {
                                toastr.success('Status Change successfully');

                                $(`.note_${id}`).html(data.note);
                                $('#szbd-datatable').DataTable().ajax.reload(null, false);

                            },
                            error: function(data) {
                                toastr.warning('Something went wrong !');
                            }
                        });
                    }
                });
            } else if (status === 'reviewing') {
                Swal.fire({
                    title: 'Are you sure Change this?',
                    text: "You won't be able to revert this!",
                    html: `
                        <br />
                        <form class="form-horizontal" action="{{ route('admin.userinfo.status.update') }}" method="post">

                              <input type="hidden" name="status" value=" ${status}">
                        <input type="hidden" name="id" value="${id}">

                            <input required class="form-control wedding-input-text wizard-input-pad" type="text" name="note" id="note" placeholder="For ${status} note">
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonColor: '#377dff',
                    cancelButtonColor: 'secondary',
                    confirmButtonText: 'Yes, Change it!',
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('admin.free_hajj_umrah.status') }}",
                            method: 'POST',
                            data: $("form").serialize(),
                            success: function(data) {
                                toastr.success('Status Change successfully');

                                $(`.note_${id}`).html(data.note);
                                $('#szbd-datatable').DataTable().ajax.reload(null, false);

                            },
                            error: function(data) {
                                toastr.warning('Something went wrong !');
                            }
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: 'Are you sure Change this?',
                    text: "You won't be able to revert this!",
                    html: `
                            <br />
                            <form class="form-horizontal" action="{{ route('admin.free_hajj_umrah.status') }}" method="post">
                                 <input type="hidden" name="status" value=" ${status}">
                        <input type="hidden" name="id" value="${id}">

                                <input
                                    required
                                    class="form-control wedding-input-text wizard-input-pad"
                                    type="text"
                                    name="note"
                                    id="note"
                                    placeholder="For ${status}  note"
                                >
                            </form>
                        `,
                    showCancelButton: true,
                    confirmButtonColor: '#377dff',
                    cancelButtonColor: 'secondary',
                    confirmButtonText: 'Yes, Change it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('admin.free_hajj_umrah.status') }}",
                            method: 'POST',
                            data: $("form").serialize(),
                            success: function(data) {
                                toastr.success('Status Change successfully');

                                $(`.note_${id}`).html(data.note);
                                $('#szbd-datatable').DataTable().ajax.reload(null, false);
                            },
                            error: function(data) {
                                toastr.warning('Something went wrong !');
                            }
                        });
                    }
                });
            }

        };
    </script>
@endpush
