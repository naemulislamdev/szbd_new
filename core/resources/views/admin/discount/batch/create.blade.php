@extends('admin.layouts.app')
@section('title', 'Batch Discount')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Create Batch Discount <i class="las la-tags"></i></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb   mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.discount.batch') }}">Batch
                                    Discount</a></li>
                            <li class="breadcrumb-item active">Create Batch Discount</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.discount.batch.store') }}" method="post">
                            @csrf

                            <div class="row g-3">

                                <!-- Title -->
                                <div class="col-md-6 mb-2">
                                    <label class="form-label">
                                        Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" required name="title" value="{{ old('title') }}"
                                        class="form-control">

                                    @error('title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Product Select -->
                                <div class="col-md-12">
                                    <label class="form-label">
                                        Product <span class="text-danger">*</span>
                                    </label> <br>

                                    <select name="product_ids[]" id="productSelect"
                                        class="js-example-responsive form-select" multiple>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                                                data-code="{{ $product->code }}" data-price="{{ $product['unit_price'] }}">
                                                {{ $product->code }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('product_ids.*')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Table -->
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>SL</th>
                                                    <th>Product Name</th>
                                                    <th>Product Code</th>
                                                    <th>Product Price</th>
                                                    <th>Discount</th>
                                                    <th>Discount Type</th>
                                                </tr>
                                            </thead>
                                            <tbody id="product-table-body"></tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary">
                                        Save
                                    </button>
                                </div>

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

                // Initialize Select2
                $select.select2({
                    width: "resolve",
                    placeholder: "Select products",
                });

                // When selection changes
                $select.on("change", function() {
                    const selectedOptions = $(this).find("option:selected");
                    const selectedIds = selectedOptions.map((_, opt) => $(opt).val()).get();

                    // Clear current table rows
                    $tableBody.empty();

                    // Add rows for selected products
                    selectedOptions.each(function(index) {
                        const id = $(this).val();
                        const name = $(this).data("name");
                        const code = $(this).data("code");
                        const price = $(this).data("price");

                        const row = `
                <tr data-id="${id}">
                    <td>${index + 1}</td>
                    <td>${name}</td>
                    <td>${code}</td>
                    <td>${price}</td>
                    <td>
                        <input type="number" name="discount_amounts[${id}]" class="form-control" placeholder="Enter amount">
                    </td>
                    <td>
                        <select name="discount_types[${id}]" class="form-select">
                            <option value="flat">Flat</option>
                            <option value="percent">Percentage</option>
                        </select>
                    </td>
                </tr>
            `;
                        $tableBody.append(row);
                    });
                });
            });
        </script>
    @endpush
