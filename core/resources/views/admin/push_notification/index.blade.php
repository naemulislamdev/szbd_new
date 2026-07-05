@extends('admin.layouts.app')
@section('title', 'Push Notifications')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Push Notifications</h4>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Push Notifications</li>
                    </ol>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header"><h4 class="card-title">Compose</h4></div>
                    <div class="card-body">
                        <form id="pushForm" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-2">
                                <label class="form-label">Title</label>
                                <input required type="text" name="title" class="form-control" placeholder="Notification title">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Body</label>
                                <textarea required name="body" rows="3" class="form-control" placeholder="Message body"></textarea>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Target</label>
                                <select name="target" class="form-control" required>
                                    <option value="all">All devices</option>
                                    <option value="android">Android only</option>
                                    <option value="ios">iOS only</option>
                                </select>
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Tap link <small class="text-muted">(optional)</small></label>
                                <input type="text" name="link" class="form-control" placeholder="https://shoppingzonebd.com.bd/product/...">
                            </div>
                            <div class="mb-2">
                                <label class="form-label">Image <small class="text-muted">(optional)</small></label>
                                <input type="file" name="image" class="form-control" accept=".jpg,.png,.jpeg,.webp">
                            </div>
                            <button type="submit" class="btn btn-primary w-100" id="pushSendBtn">
                                <i class="la la-paper-plane"></i> Send
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header"><h4 class="card-title">History</h4></div>
                    <div class="card-body">
                        <div class="table-responsive overflow-hidden">
                            <table class="table" id="szbd-datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>SL</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Target</th>
                                        <th>Status</th>
                                        <th>Sent</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') } });

        (function ($) {
            "use strict";
            const table = $('#szbd-datatable').DataTable({
                processing: true, serverSide: true, scrollX: false, autoWidth: false,
                ajax: { url: "{{ route('admin.push-notification.datatables') }}" },
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center p-1' },
                    { data: 'image', orderable: false, searchable: false },
                    { data: 'title' },
                    { data: 'target' },
                    { data: 'status' },
                    { data: 'created_at' }
                ],
                order: [[0, 'desc']]
            });

            $('#pushForm').on('submit', function (e) {
                e.preventDefault();
                var $btn = $('#pushSendBtn');
                $btn.prop('disabled', true);
                $.ajax({
                    url: "{{ route('admin.push-notification.send') }}", type: 'POST',
                    data: new FormData(this), processData: false, contentType: false,
                    success: function (res) {
                        if (res.status) {
                            $('#pushForm')[0].reset();
                            table.ajax.reload(null, false);
                            toastr.success(res.message ?? 'Sent');
                        } else {
                            toastr.error(res.message ?? 'Send failed');
                            table.ajax.reload(null, false);
                        }
                    },
                    error: function (err) { toastr.error(err.responseJSON?.message ?? 'Something went wrong!'); },
                    complete: function () { $btn.prop('disabled', false); }
                });
            });
        })(jQuery);
    </script>
@endpush
