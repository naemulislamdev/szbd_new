@extends('admin.layouts.app')
@section('title', 'Shipping Methods & Cost')

@push('styles')
    <style>
        .shipping-type-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px solid #dee2e6;
            border-radius: 12px;
            padding: 20px 10px;
            cursor: pointer;
            text-align: center;
            transition: all .2s;
            height: 100%;
            width: 100%;
            gap: 8px;
        }

        .shipping-type-card input[type=radio] {
            display: none;
        }

        .shipping-type-card i {
            font-size: 28px;
        }

        .shipping-type-card span {
            font-size: 13px;
            font-weight: 500;
        }

        .shipping-type-card.selected {
            border-color: #5b73e8;
            background: #eef0fd;
            color: #5b73e8;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Shipping Methods And Cost</h4>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Shipping Methods</li>
                    </ol>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════
         Shipping Configuration Card
    ══════════════════════════════════════════ --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">⚙️ Shipping Configuration</h4>
                    </div>
                    <div class="card-body">
                        <form id="shippingConfigForm">
                            @csrf

                            {{-- 4 Shipping Type --}}
                            <div class="row g-3 mb-3">
                                @php
                                    $types = [
                                        'free_shipping' => ['icon' => 'la la-gift', 'label' => 'Free Shipping'],
                                        'order_wise' => ['icon' => 'la la-shopping-cart', 'label' => 'Order Wise'],
                                        'category_wise' => ['icon' => 'la la-list-alt', 'label' => 'Category Wise'],
                                        'product_wise' => ['icon' => 'la la-box', 'label' => 'Product Wise'],
                                    ];
                                @endphp

                                @foreach ($types as $value => $info)
                                    <div class="col-6 col-md-3">
                                        <label
                                            class="shipping-type-card w-100 {{ $config->shipping_type == $value ? 'selected' : '' }}"
                                            id="card_{{ $value }}">
                                            <input type="radio" name="shipping_type" value="{{ $value }}"
                                                {{ $config->shipping_type == $value ? 'checked' : '' }}
                                                onchange="onShippingTypeChange('{{ $value }}')">
                                            <i class="{{ $info['icon'] }}"></i>
                                            <span>{{ $info['label'] }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Free Shipping sub-option --}}
                            <div id="freeShippingOptions"
                                class="{{ $config->shipping_type == 'free_shipping' ? '' : 'd-none' }} p-3 rounded mb-3"
                                style="background: #f8f9fa; border: 1px dashed #5b73e8;">
                                <p class="mb-2 fw-semibold text-muted">Free Shipping Type:</p>
                                <div class="d-flex gap-4 mb-3">
                                    <label class="d-flex align-items-center gap-2" style="cursor:pointer">
                                        <input type="checkbox" id="free_all" name="free_shipping_type" value="all_products"
                                            {{ $config->free_shipping_type == 'all_products' ? 'checked' : '' }}
                                            onchange="handleFreeShippingCheck(this)">
                                        সব পণ্যে Free
                                    </label>
                                    <label class="d-flex align-items-center gap-2" style="cursor:pointer">
                                        <input type="checkbox" id="free_without_discount" name="free_shipping_type"
                                            value="without_discount_product"
                                            {{ $config->free_shipping_type == 'without_discount_product' ? 'checked' : '' }}
                                            onchange="handleFreeShippingCheck(this)">
                                        Discount পণ্য বাদে Free
                                    </label>
                                </div>

                                {{-- without_discount_product select হলে এই field দেখাবে --}}
                                <div id="withoutDiscountMinAmount"
                                    class="{{ $config->free_shipping_type == 'without_discount_product' ? '' : 'd-none' }}">
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">
                                                Minimum Order Amount (৳)
                                                <small class="text-muted fw-normal">— এই পরিমাণের উপরে order করলে shipping
                                                    free</small>
                                            </label>
                                            <input type="number" name="free_shipping_min_amount" class="form-control"
                                                value="{{ $config->free_shipping_min_amount ?? '' }}"
                                                placeholder="e.g. 500 — খালি রাখলে সবসময় free নয়">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="p-2 rounded mt-4"
                                                style="background:#fff8e1; border:1px solid #ffc107; font-size:13px;">
                                                💡 উদাহরণ: ৫০০ টাকা set করলে — discount ছাড়া পণ্যে ৫০০+ টাকার order এ
                                                shipping free হবে।
                                                Discount পণ্য থাকলে shipping charge যোগ হবে।
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Order Wise info --}}
                            <div id="orderWiseInfo"
                                class="{{ $config->shipping_type == 'order_wise' ? '' : 'd-none' }} p-3 rounded mb-3"
                                style="background: #f0fff4; border: 1px dashed #28a745;">
                                <p class="mb-0 text-success">
                                    <i class="la la-info-circle"></i>
                                    নিচের <strong>Shipping Methods Table</strong> থেকে method add করুন।
                                    Customer checkout এ shipping area select করবে।
                                </p>
                            </div>

                            {{-- Category/Product wise info --}}
                            <div id="categoryWiseInfo"
                                class="{{ $config->shipping_type == 'category_wise' ? '' : 'd-none' }} p-3 rounded mb-3"
                                style="background: #fff8e1; border: 1px dashed #ffc107;">
                                <p class="mb-0 text-warning">
                                    <i class="la la-info-circle"></i>
                                    Category Wise shipping — নিচের table এ method add করুন এবং প্রতিটা method এ category
                                    assign করতে পারবেন (upcoming)।
                                </p>
                            </div>

                            <div id="productWiseInfo"
                                class="{{ $config->shipping_type == 'product_wise' ? '' : 'd-none' }} p-3 rounded mb-3"
                                style="background: #fff8e1; border: 1px dashed #ffc107;">
                                <p class="mb-0 text-warning">
                                    <i class="la la-info-circle"></i>
                                    Product Wise shipping — product edit page থেকে shipping cost set করা যাবে (upcoming)।
                                </p>
                            </div>

                            <hr>

                            {{-- Shipping Discount --}}
                            <p class="fw-semibold mb-2">
                                🎁 Shipping Discount (Order Amount Based)
                                <small class="text-muted fw-normal">— optional, যেকোনো shipping type এ কাজ করবে</small>
                            </p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Minimum Order Amount (৳)</label>
                                    <input type="number" name="min_amount_for_discount" class="form-control"
                                        value="{{ $web_config['free_shipping_min_amount']->value ?? '' }}"
                                        placeholder="e.g. 1000 — খালি রাখলে discount হবে না" min="0">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Discount Amount (৳)</label>
                                    <input type="number" name="shipping_discount_amount" class="form-control"
                                        value="{{ $web_config['free_shipping_discount']->value ?? '' }}"
                                        placeholder="e.g. 60" min="0">
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="la la-save"></i> Save Configuration
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════════
         Shipping Methods Table
    ══════════════════════════════════════════ --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">Shipping Methods Table</h4>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                                    <i class="la la-plus-circle"></i> Add New Method
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div id="loader" style="display:none; text-align:center; margin-bottom:10px;">
                            <div class="spinner-border text-primary" role="status"></div>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="szbd-datatable">
                                <thead class="table-light">
                                    <tr>
                                        <th>SL</th>
                                        <th>Title</th>
                                        <th>Cost</th>
                                        <th>Duration</th>
                                        <th>Status</th>
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

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="editForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Shipping Method</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="shippingID">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" id="sTtile" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Shipping Cost (৳)</label>
                            <input type="number" id="sCost" name="cost" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Duration</label>
                            <input type="text" id="sDuration" name="duration" class="form-control">
                        </div>
                        <hr>
                        <p class="text-muted mb-2 fw-semibold">Shipping Discount (optional)</p>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount Amount</label>
                                <input type="number" id="sDiscount" name="discount_amount" class="form-control"
                                    placeholder="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount Type</label>
                                <select id="sDiscountType" name="discount_type" class="form-select">
                                    <option value="flat">Flat (৳)</option>
                                    <option value="percent">Percent (%)</option>
                                </select>
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

    {{-- Add Modal --}}
    <div class="modal fade" id="categoryModal" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="categoryForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Shipping Method</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. ঢাকার ভেতরে"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Shipping Cost (৳)</label>
                            <input type="number" name="cost" class="form-control" placeholder="e.g. 80" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Duration</label>
                            <input type="text" name="duration" class="form-control"
                                placeholder="e.g. Inside Dhaka 1 Day">
                        </div>
                        <hr>
                        <p class="text-muted mb-2 fw-semibold">Shipping Discount (optional)</p>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Discount Amount</label>
                                <input type="number" name="discount_amount" class="form-control" placeholder="0" </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Discount Type</label>
                                    <select name="discount_type" class="form-select">
                                        <option value="flat">Flat (৳)</option>
                                        <option value="percent">Percent (%)</option>
                                    </select>
                                </div>
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
@endsection

@push('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        // ── Shipping Type Card UI ─────────────────────────────────────
        function onShippingTypeChange(type) {
            document.querySelectorAll('.shipping-type-card').forEach(c => c.classList.remove('selected'));
            document.getElementById('card_' + type).classList.add('selected');

            ['freeShippingOptions', 'orderWiseInfo', 'categoryWiseInfo', 'productWiseInfo'].forEach(id => {
                document.getElementById(id).classList.add('d-none');
            });

            const map = {
                free_shipping: 'freeShippingOptions',
                order_wise: 'orderWiseInfo',
                category_wise: 'categoryWiseInfo',
                product_wise: 'productWiseInfo',
            };
            if (map[type]) document.getElementById(map[type]).classList.remove('d-none');
        }

        // ── Config Form ───────────────────────────────────────────────
        $('#shippingConfigForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('admin.shipping_method.config.save') }}",
                type: "POST",
                data: $(this).serialize(),
                success: function(res) {
                    toastr.success(res.message ?? 'Saved!');
                },
                error: function() {
                    toastr.error('Something went wrong!');
                }
            });
        });

        // ── DataTable ─────────────────────────────────────────────────
        (function($) {
            const table = $('#szbd-datatable').DataTable({
                processing: true,
                serverSide: true,
                scrollX: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('admin.shipping_method.datatables') }}"
                },
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title'
                    },
                    {
                        data: 'cost'
                    },
                    {
                        data: 'duration'
                    },
                    {
                        data: 'status'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-end'
                    }
                ],
                order: [
                    [1, 'desc']
                ]
            });

            table.on('processing.dt', function(e, s, processing) {
                $('#loader').toggle(processing);
            });

            // Delete
            $(document).on('click', '.delete', function() {
                const id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This cannot be reverted!",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then(result => {
                    if (result.value) {
                        $.post("{{ route('admin.shipping_method.delete') }}", {
                            id
                        }, function() {
                            toastr.success('Deleted successfully!');
                            table.ajax.reload();
                        });
                    }
                });
            });

            // Edit button — data fill
            $(document).on('click', '.edit', function() {
                const b = $(this);
                $('#shippingID').val(b.data('id'));
                $('#sTtile').val(b.data('title'));
                $('#sCost').val(b.data('cost'));
                $('#sDuration').val(b.data('duration'));
                $('#sDiscount').val(b.data('discount'));
                $('#sDiscountType').val(b.data('discounttype'));
            });

            // Store
            $('#categoryForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.shipping_method.store') }}",
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        $('#categoryModal').modal('hide');
                        $('#categoryForm')[0].reset();
                        table.ajax.reload(null, false);
                        toastr.success(res.message ?? 'Added successfully');
                    },
                    error: function(err) {
                        toastr.error(err.responseJSON?.message ?? 'Error!');
                    }
                });
            });

            // Update
            $('#editForm').on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "{{ route('admin.shipping_method.update') }}",
                    type: "POST",
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        $('#editModal').modal('hide');
                        table.ajax.reload(null, false);
                        toastr.success('Updated successfully');
                    },
                    error: function(err) {
                        toastr.error(err.responseJSON?.message ?? 'Error!');
                    }
                });
            });

            // Status toggle
            $(document).on('change', '.status', function() {
                $.post("{{ route('admin.shipping_method.status') }}", {
                    id: $(this).data('id'),
                    status: $(this).prop('checked') ? 1 : 0
                }, function() {
                    toastr.success('Status updated!');
                });
            });
        })(jQuery);
    </script>
    <script>
        function handleFreeShippingCheck(el) {
            document.querySelectorAll('input[name="free_shipping_type"]').forEach(cb => {
                if (cb !== el) cb.checked = false;
            });

            // without_discount_product select হলে min amount field দেখাও
            const minAmountDiv = document.getElementById('withoutDiscountMinAmount');
            if (el.value === 'without_discount_product' && el.checked) {
                minAmountDiv.classList.remove('d-none');
            } else {
                minAmountDiv.classList.add('d-none');
            }
        }
    </script>
@endpush
