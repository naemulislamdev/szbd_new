@extends('admin.layouts.app')
@section('title', 'Eid Offer create')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Create Eid New Offer </h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb   mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.discount.eid.offers') }}">Eid Offers</a>
                            </li>
                            <li class="breadcrumb-item active">Create Eid Offer</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.discount.eid-offers.store') }}" method="post"
                            enctype="multipart/form-data">
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
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Upload Offer Image</label><small style="color: red">*
                                                    (ratio') 1:1 </small>
                                                <div class="custom-file mt-2" style="text-align: left">
                                                    <input type="file" name="image" id="customFileEg1"
                                                        class="custom-file-input" accept="image/*"
                                                        onchange="previewImage(event)">
                                                </div>
                                                @error('image')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <img style="width: 100px;height:auto;border: .0625rem solid; border-radius: .625rem;"
                                                    id="preview" src="" />
                                            </div>
                                        </div>
                                    </div>
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

                </tr>
            `;
                        $tableBody.append(row);
                    });
                });
            });
        </script>
        <script>
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#preview').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#customFileEg1").change(function() {
                readURL(this);
            });
        </script>
    @endpush
