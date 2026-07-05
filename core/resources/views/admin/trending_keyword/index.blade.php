@extends('admin.layouts.app')
@section('title', 'Trending Keywords')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Trending Keywords</h4>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Trending Keywords</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row align-items-center">
                            <div class="col"><h4 class="card-title">Search chips</h4></div>
                            <div class="col-auto">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                                    <i class="la la-plus-circle"></i> Add Keyword
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div id="loader" style="display:none;text-align:center;margin-bottom:10px;">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                        <div class="table-responsive overflow-hidden">
                            <table class="table" id="szbd-datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>SL</th>
                                        <th>Keyword</th>
                                        <th>Sort Order</th>
                                        <th>Active</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="addForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add Keyword</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Keyword</label>
                            <input required type="text" name="keyword" class="form-control" placeholder="e.g. Saree">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control" value="0" placeholder="0,1,2...">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editForm">
                    @csrf
                    <input type="hidden" name="id" id="editId">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Keyword</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label class="form-label">Keyword</label>
                            <input required type="text" name="keyword" id="editKeyword" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Sort Order</label>
                            <input type="number" name="sort_order" id="editOrder" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
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
                ajax: { url: "{{ route('admin.trending-keyword.datatables') }}" },
                columns: [
                    { data: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center p-1' },
                    { data: 'keyword' },
                    { data: 'sort_order' },
                    { data: 'is_active' },
                    { data: 'action', orderable: false, searchable: false }
                ],
                order: [[2, 'asc']]
            });

            table.on('processing.dt', function (e, s, p) { p ? $('#loader').fadeIn(100) : $('#loader').fadeOut(100); });

            $('#addForm').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.trending-keyword.store') }}", type: 'POST',
                    data: new FormData(this), processData: false, contentType: false,
                    success: function (res) {
                        $('#addModal').modal('hide'); $('#addForm')[0].reset();
                        table.ajax.reload(null, false);
                        toastr.success(res.message ?? 'Added');
                    },
                    error: function (err) { toastr.error(err.responseJSON?.message ?? 'Something went wrong!'); }
                });
            });

            $(document).on('click', '.edit', function () {
                $('#editId').val($(this).data('id'));
                $('#editKeyword').val($(this).data('keyword'));
                $('#editOrder').val($(this).data('order'));
            });

            $('#editForm').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.trending-keyword.update') }}", type: 'POST',
                    data: new FormData(this), processData: false, contentType: false,
                    success: function (res) {
                        $('#editModal').modal('hide');
                        table.ajax.reload(null, false);
                        toastr.success(res.message ?? 'Updated');
                    },
                    error: function (err) { toastr.error(err.responseJSON?.message ?? 'Something went wrong!'); }
                });
            });

            $(document).on('change', '.status', function () {
                var id = $(this).data('id');
                var status = $(this).prop('checked') ? 1 : 0;
                $.ajax({
                    url: "{{ route('admin.trending-keyword.status') }}", method: 'POST',
                    data: { id: id, is_active: status },
                    success: function () { toastr.success('Status updated'); },
                    error: function () { toastr.error('Something went wrong!'); }
                });
            });

            $(document).on('click', '.delete', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure', text: 'You will not be able to revert this!',
                    showCancelButton: true, type: 'warning', confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33', confirmButtonText: 'Yes, delete it!', reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: "{{ route('admin.trending-keyword.delete') }}", method: 'POST',
                            data: { id: id },
                            success: function () { toastr.success('Deleted'); table.ajax.reload(); },
                            error: function () { toastr.error('Something went wrong!'); }
                        });
                    }
                });
            });
        })(jQuery);
    </script>
@endpush
