@extends('admin.layouts.app')
@section('title', 'Blog Content & Addvertisment')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Update Blog Page Addvertisment and Promotions</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item"><a href="{{ route('admin.blog.contentPromotion') }}">Addvertisment &
                                    Promotions</a>
                            </li><!--end nav-item-->

                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active"></li>
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
                                <h4 class="card-title"></h4>
                            </div><!--end col-->

                        </div><!--end row-->
                    </div><!--end card-header-->
                    <div class="card-body pt-0">
                        <form action="{{ route('admin.blog.updateContentPromotion') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="add_url">Addvertisment URL <span class="text-danger">*</span></label>
                                    <input class="form-control @error('add_url') is-invalid @enderror" type="text"
                                        name="add_url" value="{{ $contentPromotion->add_url }}" id="add_url">

                                    @error('add_url')
                                        <div class="invalid-feedback">
                                            {{ ucfirst($message) }}
                                        </div>
                                    @enderror

                                </div>

                                <div class="col-md-6 mt-3">
                                    <label for="add_img">Addvertisment Image <span class="text-danger">*</span></label>
                                    <input type="file" name="add_img" id="add_img"
                                        value="{{ $contentPromotion->add_img }}"
                                        class="form-control @error('add_img') is-invalid @enderror" value="">
                                    @error('add_img')
                                        <div class="invalid-feedback">
                                            {{ ucfirst($message) }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mt-3">
                                    <img style="width: 20%; border: 1px solid; border-radius: 10px;" id="viewer"
                                        src="{{ asset('assets/storage/blogs/adds/' . $contentPromotion->add_img) }}"
                                        alt="add img" />
                                </div>
                                <!-- Product Select -->
                                <div class="col-md-12 mt-3">
                                    <label for="products" class="form-label">
                                        Product <span class="text-danger">*</span>
                                    </label> <br>

                                    @php
                                        $selectedProducts = json_decode($contentPromotion->products, true) ?? [];
                                    @endphp

                                    <select name="products[]" id="productSelect" class="js-example-responsive form-select"
                                        multiple>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                {{ in_array($product->id, $selectedProducts) ? 'selected' : '' }}
                                                data-name="{{ $product->name }}" data-code="{{ $product->code }}"
                                                data-price="{{ $product->unit_price }}">
                                                {{ $product->code }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('products.*')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mx-auto mt-4">
                                    <div class="w-50">
                                        <button class="btn btn-success w-100">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container -->

@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#description').summernote();
            $('#meta_description').summernote();
        });
    </script>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#add_img").change(function() {
            readURL(this);
        });
    </script>
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
