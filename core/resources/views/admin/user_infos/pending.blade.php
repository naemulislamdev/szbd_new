@extends('admin.layouts.app')
@section('title', 'Pending User Infos')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Pending User Infos <span class="badge bg-warning ms-2">Total:
                            {{ $userInfoCounts->pending ?? 0 }}</span></h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item"><a href="#">user info</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Pending user info</li>
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
                                <h4 class="card-title">User Info</h4>
                            </div><!--end col-->

                            <div class="row mb-3 justify-content-end">
                                <div class="col-md-3">
                                    <input type="date" id="from_date" class="form-control" placeholder="From Date">
                                </div>
                                <div class="col-md-3">
                                    <input type="date" id="to_date" class="form-control" placeholder="To Date">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id="filter_btn" class="btn btn-primary">Filter</button>
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id="reset_btn" class="btn btn-secondary">Reset</button>
                                </div>
                            </div>

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
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <th>Status</th>
                                        <th>Product</th>
                                        <th>Type</th>
                                        <th>Order Process</th>
                                        <th>Order Status</th>
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
                    url: "{{ route('admin.userinfo.datatables', 'pending') }}",
                    data: function(d) {
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    }
                },

                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center p-1'
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'phone'
                    },
                    {
                        data: 'address'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'product_details'
                    },
                    {
                        data: 'type'
                    },
                    {
                        data: 'order_process',
                        className: 'text-center'
                    },
                    {
                        data: 'order_status',
                        width: '120px'
                    },
                    {
                        data: 'order_note'
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

            $('#filter_btn').on('click', () => table.ajax.reload());

            $('#reset_btn').on('click', function() {
                $('#from_date, #to_date').val('');
                table.ajax.reload();
            });
            window.order_status = function order_status(status, id) {
                var orderStatus = status ? status : 'pending';
                console.log(status);
                console.log(id);


                if (status === 'confirmed') {
                    Swal.fire({
                        title: 'Are you sure Change this?!',
                        text: "'Think before you completed.",
                        html: `
                        <br />
                        <form class="form-horizontal" action="{{ route('admin.userinfo.status.update') }}" method="post">
                            <input type="hidden" name="order_status" value=" ${status}">
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
                                url: "{{ route('admin.userinfo.status.update') }}",
                                method: 'POST',
                                data: $("form").serialize(),
                                success: function(data) {

                                    toastr.success('Status Change successfully');
                                    table.ajax.reload();
                                    $(`.note_${id}`).html(data.note);

                                },
                                error: function(data) {
                                    toastr.warning('Something went wrong !');
                                }
                            });
                        }
                    });
                } else if (status === 'canceled') {
                    Swal.fire({
                        title: 'Are you sure Change this?',
                        text: "You won't be able to revert this!",
                        html: `
                        <br />
                        <form class="form-horizontal" action="{{ route('admin.userinfo.status.update') }}" method="post">

                              <input type="hidden" name="order_status" value=" ${status}">
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
                                url: "{{ route('admin.userinfo.status.update') }}",
                                method: 'POST',
                                data: $("form").serialize(),
                                success: function(data) {
                                    toastr.success('Status Change successfully');
                                    table.ajax.reload();
                                    $(`.note_${id}`).html(data.note);

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
                            <form class="form-horizontal" action="{{ route('admin.userinfo.status.update') }}" method="post">
                                 <input type="hidden" name="order_status" value=" ${status}">
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
                                url: "{{ route('admin.userinfo.status.update') }}",
                                method: 'POST',
                                data: $("form").serialize(),
                                success: function(data) {
                                    toastr.success('Status Change successfully');
                                    table.ajax.reload();
                                    $(`.note_${id}`).html(data.note);

                                },
                                error: function(data) {
                                    toastr.warning('Something went wrong !');
                                }
                            });
                        }
                    });
                }

            };

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
        $(document).on('submit', '#orderNoteForm', function(e) {
            console.log('submit');

            e.preventDefault();

            let form = $(this);

            // trim input
            let noteInput = form.find('input[name="multiple_note[]"]');
            let trimmedValue = noteInput.val().trim();

            if (trimmedValue === '') {
                toastr.warning("Note cannot be empty !");
                return;
            }

            noteInput.val(trimmedValue);

            $.ajax({
                url: "{{ route('admin.userinfo.multiple_note') }}",
                type: "POST",
                data: form.serialize(),
                success: function(res) {

                    if (res.status) {

                        $('#noteList').append(`
                    <li style="text-align:left; line-height:20px"
                        class="badge bg-primary text-dark d-inline-block mb-2 py-2">
                        ${res.note.note}
                        <span class="text-dark">
                            (${res.note.time} - Note by: ${res.note.user})
                        </span>
                    </li>
                `);

                        form[0].reset();
                        toastr.success('Note added Successfully !');
                    }
                },
                error: function() {
                    toastr.error('Something went wrong');
                }
            });
        });
    </script>
    <script>
        $(document).on('click', '.viewBtn', function() {
            let id = $(this).data('id');
            console.log(id);

            $.ajax({
                url: "{{ route('admin.userinfo.show') }}", // POST route
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                success: function(response) {
                    // Table–এ status update
                    if (response.status == 1 && response.seen_by != "") {
                        $(`.status_${id}`).replaceWith(
                            `
                            <span class="badge badge-success status_${id}">Seen</span>
                            <div><small>Seen by: <br/> ${response.seen_by}</small></div>
                            `
                        );

                    }

                    Swal.fire({
                        title: 'User Info',
                        html: response.html,
                        width: '800px',
                        showCloseButton: true,
                        showConfirmButton: false,
                    });

                },
                error: function() {
                    toastr.error('Something went wrong!');
                }
            });
        });
    </script>
@endpush
