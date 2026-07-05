@extends('admin.layouts.app')
@section('title', 'Batch Discount')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Edit Batch Discount <i class="las la-tags"></i></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb   mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.discount.batch') }}">Batch
                                    Discount</a></li>
                            <li class="breadcrumb-item active">Edit Batch Discount</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.discount.batch.update', $batchDiscount->id) }}" method="post">
                            @csrf
                            <div class="form-group">
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Title <span class="text-danger">*</span></label>
                                            <input type="text" name="title" class="form-control"
                                                value="{{ $batchDiscount->title }}">
                                            @error('title')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Product <span class="text-danger">*</span></label>
                                            <select name="product_ids[]" id="productSelect"
                                                class="js-example-responsive form-control" multiple>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                                                        data-code="{{ $product->code }}"
                                                        data-price="{{ $product['unit_price'] }}"
                                                        {{ in_array($product->id, json_decode($batchDiscount->product_ids, true)) ? 'selected' : '' }}>
                                                        {{ $product->code }}</option>
                                                @endforeach
                                            </select>
                                            @error('product_ids.*')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="table">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Product Name</th>
                                            <th>Product Code</th>
                                            <th>Product Price</th>
                                            <th>Discount</th>
                                            <th>Discount Type</th>
                                        </tr>
                                    </thead>
                                    @php
                                        $productIds = json_decode($batchDiscount->product_ids, true) ?? [];
                                        $discountAmounts = json_decode($batchDiscount->discount_amount, true) ?? [];
                                        $discountTypes = json_decode($batchDiscount->discount_type, true) ?? [];
                                    @endphp
                                    <tbody id="product-table-body"></tbody>

                                    </tbody>
                                </table>
                            </div>
                            <div class=" pl-0">
                                <button type="submit" class="btn btn-primary float-right">Save</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>


    @endsection
    @push('scripts')
        <script>
            $(document).ready(function() {
                const $select = $("#productSelect");
                const $tableBody = $("#product-table-body");

                const existingData = @json($discountAmounts);
                const existingTypes = @json($discountTypes);

                // Initialize Select2
                $select.select2({
                    width: "resolve",
                    placeholder: "Select products",
                });

                // Function to render table rows based on selected options
                function renderTable() {
                    const selectedOptions = $select.find("option:selected");
                    $tableBody.empty();

                    selectedOptions.each(function(index) {
                        const id = $(this).val();
                        const name = $(this).data("name");
                        const code = $(this).data("code");
                        const price = $(this).data("price");
                        const discountVal = existingData[id] ?? "";
                        const discountType = existingTypes[id] ?? "flat";

                        const row = `
                <tr data-id="${id}">
                    <td>${index + 1}</td>
                    <td>${name}</td>
                    <td>${code}</td>
                    <td>${price}</td>
                    <td>
                        <input type="number" name="discount_amounts[${id}]"
                            class="form-control"
                            value="${discountVal}" placeholder="Enter amount">
                    </td>
                    <td>
                        <select name="discount_types[${id}]" class="form-control">
                            <option value="flat" ${discountType === 'flat' ? 'selected' : ''}>Flat</option>
                            <option value="percent" ${discountType === 'percent' ? 'selected' : ''}>Percentage</option>
                        </select>
                    </td>
                </tr>`;
                        $tableBody.append(row);
                    });
                }

                // Run once on page load (for existing selections)
                renderTable();

                // Run again whenever selection changes
                $select.on("change", renderTable);
            });
        </script>
    @endpush
