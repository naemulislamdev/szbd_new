@extends('admin.layouts.app')
@section('title', 'Coupon Management')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Coupon</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->

                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">All Coupon</li>
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
                                <h4 class="card-title">All Coupon List</h4>
                            </div><!--end col-->
                            <div class="col-auto">
                                <div class="row mb-3">
                                    <div class="col-lg-12">
                                        <button class="btn btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#categoryModal"><i class="la la-plus-circle"></i> Add New
                                            Coupon</button>
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
                                        <th>SL#</th>
                                        <th>Start Date</th>
                                        <th>Expire Date</th>
                                        <th>Coupon Type</th>
                                        <th>Title</th>
                                        <th>Code</th>
                                        <th>Discount</th>
                                        <th>Discount Type</th>
                                        <th>Minimum Purchase</th>
                                        <th>Maximum Discount</th>
                                        <th>User Limit</th>
                                        <th>Allready Use</th>
                                        <th>Status</th>
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

    <!--coupon Edit Modal -->

    <div class="modal fade" id="editModal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Coupon</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="categoryId">

                        <div class="row">

                            {{-- Coupon Type --}}
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Coupon Type</label>
                                <select required class="form-select" name="coupon_type" id="coupon_type">
                                    <option value="" disabled>----Select----</option>
                                    <option value="delivery_charge_free">
                                        Delivery Charge Free
                                    </option>
                                    <option value="discount_on_purchase">
                                        Discount On Purchase
                                    </option>
                                </select>
                            </div>

                            {{-- Coupon Code --}}
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Coupon Code</label>
                                <a href="javascript:void(0)" class="float-end" onclick="generateCode()">Generate Code</a>
                                <input required type="text" name="code" id="code" class="form-control">
                            </div>

                            {{-- Title --}}
                            <div class="col-md-6 mb-2">
                                <label>Title</label>
                                <input required type="text" name="title" id="title" class="form-control"
                                    placeholder="Enter Coupon Title">
                            </div>

                            {{-- Discount Type --}}
                            <div class="col-md-6 mb-2">
                                <label>Discount Type</label>
                                <select required name="discount_type" id="discount_type2" class="form-control"
                                    onchange="checkDiscountType2(this.value)">
                                    <option value="amount">Amount</option>
                                    <option value="percentage">Percentage</option>
                                </select>
                            </div>

                            {{-- Start Date --}}
                            <div class="col-md-6 mb-2">
                                <label>Start Date</label>
                                <input required type="date" name="start_date" id="start_date2" class="form-control">
                            </div>

                            {{-- Expire Date --}}
                            <div class="col-md-6 mb-2">
                                <label>Expire Date</label>
                                <input required type="date" name="expire_date" id="expire_date2" class="form-control">
                            </div>

                            {{-- Discount --}}
                            <div class="col-md-6 mb-2">
                                <label>Discount</label>
                                <input type="number" min="1" max="1000000" name="discount" id="discount"
                                    class="form-control" placeholder="Discount" required>
                            </div>

                            {{-- Limit --}}
                            <div class="col-md-6 mb-2">
                                <label>Limit For Same User</label>
                                <input required type="number" name="limit" min="0" id="limit"
                                    class="form-control" placeholder="EX: 10">
                            </div>

                            {{-- Minimum Purchase --}}
                            <div class="col-md-6 mb-2">
                                <label>Minimum Purchase</label>
                                <input required type="number" name="min_purchase" id="min_purchase" min="1"
                                    max="1000000" class="form-control" placeholder="Minimum purchase">
                            </div>

                            {{-- Maximum Discount --}}
                            <div class="col-md-6 mb-2" id="max-discount2">
                                <label>Maximum Discount</label>
                                <input type="number" name="max_discount" id="max_discount" min="1"
                                    max="1000000" class="form-control" placeholder="Maximum discount">
                            </div>

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
    <!-- coupon Add Modal -->
    <div class="modal fade" id="categoryModal" data-bs-backdrop="static" tabindex="-1"
        aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="addForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="categoryModalLabel">Add New Coupon</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="row">

                            {{-- Coupon Type --}}
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Coupon Type</label>
                                <select required class="form-select @error('coupon_type') is-invalid @enderror"
                                    name="coupon_type" id="coupon_type">
                                    <option value="" disabled selected>----Select----</option>
                                    <option value="delivery_charge_free"
                                        {{ old('coupon_type') == 'delivery_charge_free' ? 'selected' : '' }}>
                                        Delivery Charge Free
                                    </option>
                                    <option value="discount_on_purchase"
                                        {{ old('coupon_type') == 'discount_on_purchase' ? 'selected' : '' }}>
                                        Discount On Purchase
                                    </option>
                                </select>
                                @error('coupon_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Coupon Code --}}
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Coupon Code</label>
                                <a href="javascript:void(0)" class="float-end" onclick="generateCode()">Generate Code</a>
                                <input required ="text" name="code" value="{{ old('code') }}"
                                    onclick="generateCode()"
                                    class="code form-control @error('code') is-invalid @enderror">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Title --}}
                            <div class="col-md-6 mb-2">
                                <label>Title</label>
                                <input required type="text" name="title" value="{{ old('title') }}"
                                    class="form-control @error('title') is-invalid @enderror"
                                    placeholder="Enter Coupon Title">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Discount Type --}}
                            <div class="col-md-6 mb-2">
                                <label>Discount Type</label>
                                <select required name="discount_type" id="discount_type"
                                    class="form-control @error('discount_type') is-invalid @enderror"
                                    onchange="checkDiscountType(this.value)">
                                    <option value="amount" {{ old('discount_type') == 'amount' ? 'selected' : '' }}>Amount
                                    </option>
                                    <option value="percentage"
                                        {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>
                                        Percentage</option>
                                </select>
                                @error('discount_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Start Date --}}
                            <div class="col-md-6 mb-2">
                                <label>Start Date</label>
                                <input id="start_date" required type="date" name="start_date"
                                    value="{{ old('start_date') }}"
                                    class="form-control @error('start_date') is-invalid @enderror">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Expire Date --}}
                            <div class="col-md-6 mb-2">
                                <label>Expire Date</label>
                                <input required type="date" name="expire_date" value="{{ old('expire_date') }}"
                                    id="expire_date" class="form-control @error('expire_date') is-invalid @enderror">
                                @error('expire_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="name">Discount</label>
                                <input type="number" min="1" max="1000000" name="discount"
                                    value="{{ old('discount') }}" class="form-control" id="discount"
                                    placeholder="Discount" required>
                            </div>
                            {{-- Limit --}}
                            <div class="col-md-6 mb-2">
                                <label>Limit For Same User</label>
                                <input required type="number" name="limit" min="0"
                                    value="{{ old('limit') }}" class="form-control @error('limit') is-invalid @enderror"
                                    placeholder="EX: 10">
                                @error('limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Minimum Purchase --}}
                            <div class="col-md-6 mb-2">
                                <label>Minimum Purchase</label>
                                <input required type="number" name="min_purchase" min="1" max="1000000"
                                    value="{{ old('min_purchase') }}"
                                    class="form-control @error('min_purchase') is-invalid @enderror"
                                    placeholder="Minimum purchase">
                                @error('min_purchase')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Maximum Discount --}}
                            <div class="col-md-6 mb-2" id="max-discount">
                                <label>Maximum Discount</label>
                                <input type="number" name="max_discount" min="1" max="1000000"
                                    value="{{ old('max_discount') }}" class="form-control "
                                    placeholder="Maximum discount">

                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
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
        console.log($('#addForm'));
        (function($) {
            "use strict";

            const table = $('#szbd-datatable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: false,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.coupon.datatables') }}",
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center p-1'
                    },
                    {
                        data: 'start_date'
                    },
                    {
                        data: 'expire_date'
                    },
                    {
                        data: 'coupon_type'
                    },
                    {
                        data: 'title'
                    },
                    {
                        data: 'code'
                    },
                    {
                        data: 'discount'
                    },
                    {
                        data: 'discount_type'
                    },
                    {
                        data: 'min_purchase'
                    },
                    {
                        data: 'max_discount'
                    },
                    {
                        data: 'limit',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'allready_use',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status'
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
                            url: "{{ route('admin.coupon.delete') }}",
                            method: 'POST',
                            data: {
                                id: id
                            },
                            success: function() {
                                toastr.success(
                                    'Coupon Deleted Sucessfully!'
                                );
                                table.ajax.reload();

                            }
                        });
                    }
                })
            });
            // store category
            $('#addForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.coupon.store-coupon') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {

                        // modal close
                        $('#categoryModal').modal('hide');

                        // form reset
                        $('#addForm')[0].reset();

                        // datatable reload (🔥 main part)
                        table.ajax.reload(null, false);

                        toastr.success(res.message ?? 'Coupon added successfully');
                    },
                    error: function(err) {
                        toastr.error(err.responseJSON.message ?? 'Something went wrong!');
                    }
                });
            });
            // Update form
            $('#editForm').on('submit', function(e) {
                e.preventDefault();

                let formData = new FormData(this);

                $.ajax({
                    url: "{{ route('admin.coupon.update') }}",
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

                        toastr.success(res.message ?? 'Category added successfully');
                    },
                    error: function(err) {
                        toastr.error(err.responseJSON.message ?? 'Something went wrong!');
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
        $(document).on('change', '.status', function() {
            var id = $(this).attr("data-id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('admin.coupon.status') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data.success == true) {
                        toastr.success('Status updated successfully');
                    }
                },
                error: function(err) {
                    toastr.error(err.responseJSON.message ?? 'Something went wrong!');
                }
            });
        });
    </script>

    <script>
        $(document).on('click', '.edit', function() {

            let button = $(this);

            $('#categoryId').val(button.data('id'));
            $('#coupon_type').val(button.data('coupon_type'));
            $('#code').val(button.data('code'));
            $('input[name="title"]').val(button.data('title'));
            $('#discount_type').val(button.data('discount_type'));
            $('input[name="start_date"]').val(button.data('start_date'));
            $('input[name="expire_date"]').val(button.data('expire_date'));
            $('#discount').val(button.data('discount'));
            $('input[name="limit"]').val(button.data('limit'));
            $('input[name="min_purchase"]').val(button.data('min_purchase'));
            $('input[name="max_discount"]').val(button.data('max_discount'));

            // show/hide max discount based on discount type
            // if (button.data('discount_type') != null) {
            //     toggleMaxDiscount(button.data('discount_type'));

            // }
            // ধর, button থেকে সব data নিচ্ছো
            let discountType = button.data('discount_type'); // amount বা percentage
            let maxDiscount = button.data('max_discount'); // null বা number

            if (maxDiscount) {
                $('#discount_type2').val('percentage'); // max_discount থাকলে percentage
                $('#max-discount2').show(); // field দেখাও
            } else {
                $('#discount_type2').val(discountType); // otherwise data থেকে নাও
                if (discountType === 'amount') {
                    $('#max-discount2').hide();
                } else {
                    $('#max-discount2').show();
                }
            }


            // form action dynamic
            $('#editForm').attr('action', '/admin/coupon/' + button.data('id') + '/update');
        });
    </script>
    <script>
        $(document).ready(function() {
            generateCode();

            let discount_type = $('#discount_type').val();
            if (discount_type == 'amount') {
                $('#max-discount').hide()
            } else if (discount_type == 'percentage') {
                $('#max-discount').show()
            }

            $('#start_date').attr('min', (new Date()).toISOString().split('T')[0]);
            $('#expire_date').attr('min', (new Date()).toISOString().split('T')[0]);
        });

        $("#start_date").on("change", function() {
            $('#expire_date').attr('min', $(this).val());
        });

        $("#expire_date").on("change", function() {
            $('#start_date').attr('max', $(this).val());
        });

        function checkDiscountType(val) {
            if (val == 'amount') {
                $('#max-discount').hide()
            } else if (val == 'percentage') {
                $('#max-discount').show()
            }
        }

        // second
        let discount_type2 = $('#discount_type2').val();
        if (discount_type2 == 'amount') {
            $('#max-discount2').hide()
        } else if (discount_type2 == 'percentage') {
            $('#max-discount2').show()
        }

        $('#start_date2').attr('min', (new Date()).toISOString().split('T')[0]);
        $('#expire_date2').attr('min', (new Date()).toISOString().split('T')[0]);


        $("#start_date2").on("change", function() {
            $('#expire_date2').attr('min', $(this).val());
        });

        $("#expire_date2").on("change", function() {
            $('#start_date2').attr('max', $(this).val());
        });

        function checkDiscountType2(val) {
            if (val == 'amount') {
                $('#max-discount2').hide()
            } else if (val == 'percentage') {
                $('#max-discount2').show()
            }
        }






        function generateCode() {
            let code = Math.random().toString(36).substring(2, 12);
            $('.code').val(code)
        }
    </script>
    <script>
        $(document).on("click", ".edit", function() {

            $("#categoryId").val($(this).data("id"));
            $("#coupon_type").val($(this).data("coupon_type"));
            $("#code").val($(this).data("code"));
            $("#title").val($(this).data("title"));
            $("#discount_type2").val($(this).data("discount_type"));
            $("#start_date2").val($(this).data("start_date"));
            $("#expire_date2").val($(this).data("expire_date"));
            $("#discount").val($(this).data("discount"));
            $("#limit").val($(this).data("limit"));
            $("#min_purchase").val($(this).data("min_purchase"));
            $("#max_discount").val($(this).data("max_discount"));

        });
    </script>
@endpush
