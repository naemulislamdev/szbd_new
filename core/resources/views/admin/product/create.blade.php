@extends('admin.layouts.app')
@section('title', 'Product Add - Admin Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <h4 class="page-title">Add New Product</h4>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="#">Dashboard</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}">Products</a>
                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Add New Product</li>
                        </ol>
                    </div>
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <div class="row">
            <div class="col-12">
                <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data"
                    class="position-relative" id="product_form">
                    @csrf
                    <div class="card">
                        <div class="card-body pt-0">
                            <div class="form-floating my-3">
                                <input type="text" class="form-control mb-1  @error('name') is-invalid @enderror "
                                    name="name" id="name" placeholder="Product Name" value="{{ old('name') }}"
                                    autofocus>
                                <label for="name">Enter product Name</label>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description">Enter Description</label>
                                <textarea class="form-control mb-1  @error('description') is-invalid @enderror" name="description" class="editor"
                                    cols="30" rows="60" placeholder="Description" id="description">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class=" mb-3">
                                <label for="shortDescription">Enter Short Description</label>
                                <textarea name="short_description" class="form-control" placeholder="Description" id="shortDescription"></textarea>
                                @error('short_description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>General Info</h4>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="name">Category <span class="text-danger">*</span></label>
                                    <select class="form-control form-control-lg" name="category_id" id="category_id">
                                        <option selected disabled>---Select---</option>

                                        @foreach ($categories as $c)
                                            <option value="{{ $c['id'] }}"
                                                {{ old('category_id') == $c['id'] ? 'selected' : '' }}>
                                                {{ $c['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="name">Sub Category</label>
                                    <select class="form-control form-control-lg" name="sub_category_id"
                                        id="sub_category_select">
                                        <option value="sub-category-1">sub-category 1</option>
                                        <option value="sub-category-2">sub-category 2</option>
                                        <option value="sub-category-3">sub-category 3</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="name">Child Category</label>
                                    <select class="form-control form-control-lg" name="child_category_id"
                                        id="child_category_select">
                                        <option value="child-category-1">child-category 1</option>
                                        <option value="child-category-2">child-category 2</option>
                                        <option value="child-category-3">child-category 3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    {{-- <div>
                                        <label class="input-label" for="exampleFormControlInput1">Product Code SKU
                                            <span class="text-danger">*</span>
                                            <a class="style-one-pro" style="cursor:pointer"
                                                onclick="document.getElementById('generate_number').value = getRndInteger()">
                                                Generate Code
                                            </a>

                                            <input type="text" minlength="4" id="generate_number" name="code"
                                                class="form-control form-control-lg @error('code') is-invalid @enderror"
                                                value="{{ old('code') }}" placeholder="Code">
                                            @error('code')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                    </div> --}}
                                    <div>
                                        <label class="input-label" for="exampleFormControlInput1">product_code_sku
                                            <span class="text-danger">*</span>
                                            <a class="style-one-pro" style="cursor: pointer;"
                                                onclick="document.getElementById('generate_number').value = getRndInteger()">generate
                                                code
                                            </a></label>
                                        <input type="text" minlength="4" id="generate_number" name="code"
                                            class="form-control" value="{{ old('code') }}" placeholder="code">
                                        @error('code')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <label for="name">Brand <span class="text-danger">*</span></label> <a
                                        class="badge bg-info" href="" target="_blank">Add New Brand</a>
                                    <select class="form-control form-control-lg" name="brand_id">
                                        <option value="{{ null }}" selected disabled>
                                            ---Select---</option>
                                        <option value="brand-1">Brand 1</option>
                                        {{-- @foreach ($br as $b)
                                                <option value="{{ $b['id'] }}">{{ $b['name'] }}</option>
                                            @endforeach --}}
                                    </select>
                                    @error('brand_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="name">Unit<span class="text-danger">*</span></label>
                                    <select class="form-control form-control-lg" name="unit">
                                        {{-- @foreach (\App\CPU\Helpers::units() as $x)
                                            <option value="{{ $x }}" {{ old('unit') == $x ? 'selected' : '' }}>
                                                {{ $x }}</option>
                                        @endforeach --}}
                                        <option value="unit-1">unit 1</option>
                                        <option value="unit-2">unit 2</option>
                                        <option value="unit-3">unit 3</option>
                                    </select>
                                    @error('unit')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card h-100">
                        <div class="card-header">
                            <h4>Variations</h4>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="d-flex gap-2 pb-3">
                                        <label for="colors">
                                            Colors:
                                        </label>
                                        {{-- <label class="switch">
                                        <input type="checkbox" class="status" value="{{ old('colors_active') }}"
                                            name="colors_active">
                                        <span class="slider round"></span>
                                    </label> --}}
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" value="{{ old('colors_active') }}"
                                                name="colors_active" type="checkbox" id="flexSwitchCheckDefault">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">
                                            </label>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                            data-bs-target="#colorAddModal">
                                            Add New Color
                                        </button>

                                    </div>

                                    <select class="form-select form-select-lg" name="colors[]" multiple
                                        id="colors-selector" disabled="true">
                                        <option value="red">red</option>
                                        <option value="green">green</option>
                                        <option value="blue">blue</option>

                                        {{-- @foreach (\App\Model\Color::orderBy('name', 'asc')->get() as $key => $color)
                                            <option value="{{ $color->code }}">
                                                {{ $color['name'] }}
                                            </option>
                                        @endforeach --}}
                                    </select>
                                </div>
                                <div class="col-md-6 pt-3">
                                    <label for="attributes" style="padding-bottom: 3px">
                                        Attributes :
                                    </label>
                                    <select class="form-select form-select-lg" name="choice_attributes[]"
                                        id="choice_attributes" multiple>
                                        <option value="size">Size</option>
                                        {{-- @foreach (\App\Model\Attribute::orderBy('name', 'asc')->get() as $key => $a)
                                                <option value="{{ $a['id'] }}">
                                                    {{ $a['name'] }}
                                                </option>
                                            @endforeach --}}
                                    </select>
                                </div>


                            </div>
                            <div class="row mt-3">
                                {{-- <div class="col-lg-3">
                                    <input class="form-control form-control-lg" readonly type="text" name=""
                                        id="" value="Size">
                                </div>
                                <div class="col-lg-9">
                                    <input class="form-control form-control-lg" type="text" name=""
                                        id="">
                                </div> --}}

                                <div class="col-md-12 mt-2 mb-2">
                                    <div class="customer_choice_options" id="customer_choice_options"></div>
                                </div>
                                <div class="pt-4 col-12 color_combination" id="color_combination">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card h-100">
                        <div class="card-header">
                            <h4>Product price & stock</h4>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">Unit price</label>
                                    <span class="text-danger">*</span>
                                    <input type="number" min="0" step="0.01" placeholder="Unit price"
                                        name="unit_price" value="{{ old('unit_price') }}"
                                        class="form-control form-control-lg @error('unit_price') is-invalid @enderror">
                                    @error('unit_price')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">Purchase price</label>
                                    <span class="text-danger">*</span>
                                    <input type="number" min="0" step="0.01" placeholder="Purchase price"
                                        value="{{ old('purchase_price') }}" name="purchase_price"
                                        class="form-control form-control-lg @error('purchase_price') is-invalid @enderror">
                                    @error('purchase_price')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row pt-4">
                                <div class="col-md-4">
                                    <label class="control-label">Tax<span class="text-danger">*</span></label>
                                    <label class="badge badge-info">Percent (%) </label>
                                    <input type="number" min="0" value="0" step="0.01"
                                        placeholder="Tax" name="tax" value="{{ old('tax') }}"
                                        class="form-control form-control-lg  @error('tax') is-invalid @enderror">
                                    <input name="tax_type" value="percent" style="display: none">
                                    @error('tax')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="control-label">Discount</label>
                                    <span class="text-danger">*</span>
                                    <input type="number" min="0" step="0.01" placeholder="Discount"
                                        name="discount"
                                        class="form-control form-control-lg  @error('discount') is-invalid @enderror"
                                        value="0">
                                    @error('discount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="control-label">Discount Type</label>

                                    <select style="width: 100%" class="form-control form-control-lg"
                                        name="discount_type">
                                        <option value="flat">Flat</option>
                                        <option value="percent">Percent</option>
                                    </select>
                                </div>
                                <div class="pt-4 col-12 sku_combination" id="sku_combination">

                                </div>
                                <div class="col-md-3" id="quantity">
                                    <label class="control-label">Total
                                        Quantity</label>
                                    <span class="text-danger">*</span>
                                    <input type="number" min="0" value="0" step="1"
                                        placeholder="Quantity" name="current_stock"
                                        class="form-control form-control-lg @error('current_stock') is-invalid @enderror">

                                    @error('current_stock')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-3" id="minimum_order_qty">
                                    <label class="control-label">
                                        minimum_order_quantity <span class="text-danger">*</span></label>
                                    <input type="number" min="1" value="1" step="1"
                                        placeholder="minimum_order_quantity" name="minimum_order_qty"
                                        class="form-control form-control-lg   @error('minimum_order_qty') is-invalid @enderror">
                                    @error('minimum_order_qty')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-3" id="shipping_cost">
                                    <label class="control-label">shipping_cost</label>
                                    <input type="number" min="0" value="0" step="1"
                                        placeholder="shipping_cost" name="shipping_cost"
                                        class="form-control form-control-lg">
                                </div>
                                <div class="col-md-3 d-flex flex-column mt-3" id="shipping_cost_multy">
                                    <div>
                                        <label class="control-label">shipping_cost_multiply_with_quantity
                                        </label>

                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input form-check-input cursor-pointer" type="checkbox"
                                            id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card h-100">
                        <div class="card-header">
                            <h4>Product campaign Discount</h4>
                        </div>
                        <div class="card-body pt-0">
                            <div class="set-form">
                                <table id="myTable" class="table table-bordered">
                                    <tr>
                                        <th>Start Day</th>
                                        <th>End Day</th>
                                        <th>Discount(%)</th>
                                        <th>Action</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input type="date" placeholder="start day" name="start_day[]"
                                                value="" class="form-control form-control-lg">
                                        </td>
                                        <td>
                                            <input class="form-control form-control-lg" type="date"
                                                placeholder="end day" name="end_day[]" value="">
                                        </td>
                                        <td>
                                            <input type="number" placeholder="Discount" name="discountCam[]"
                                                value="" class="form-control form-control-lg">
                                        </td>
                                    </tr>
                                </table>
                                <div class="set-form">
                                    <input type="button" id="more_fields" onclick="add_fields();" value="Add More"
                                        class="btn btn-info" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card h-100 mb-0">
                        <div class="card-header">
                            <h4>Seo section</h4>
                        </div>
                        <div class="card-body pt-0">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <label class="control-label">Meta Title</label>
                                    <input type="text" name="meta_title" placeholder="" class="form-control">
                                    @error('meta_title')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="col-md-8 mb-4">
                                    <label class="control-label">Meta Description</label>
                                    <textarea rows="10" type="text" id="metaDesc" name="meta_description" class="form-control"></textarea>
                                    @error('meta_description')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <div class="card mt-3">
                                        <div class="card-header pb-0">
                                            <h4 class="mb-0">Meta Image</h4>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class="d-grid">
                                                <p class="text-muted pt-3">Upload your Product Meta image here, Please
                                                    click "Upload
                                                    Image" Button.</p>
                                                <div
                                                    class="meta_img_preview_box d-block justify-content-center rounded  border-dashed border-theme-color overflow-hidden p-3">
                                                </div>
                                                <input type="file" id="meta_img" name="meta_image" accept="image/*"
                                                    onchange={handleMetaImgChange()} hidden />
                                                <label class="btn-upload btn btn-primary mt-3" for="meta_img">Upload
                                                    Image</label>
                                            </div>
                                        </div><!--end card-body-->
                                    </div><!--end card-->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8 mb-4">
                                    <label class="control-label">ALT Text</label>
                                    <input type="text" name="alt_text" placeholder="Product ALT Text"
                                        class="form-control">
                                    @error('alt_text')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-8 mb-4">
                                    <label class="control-label">Youtube video link</label>
                                    <small class="badge badge-soft-danger"> (
                                        optional, please provide embed link not direct link.
                                        )</small>
                                    <input type="text" name="video_link"
                                        placeholder="EX : https://www.youtube.com/embed/5R06LRdUCSE" class="form-control">
                                    @error('video_link')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <div>
                                        <label class="control-label">Video shopping
                                        </label>
                                    </div>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input form-check-input" type="checkbox"
                                            id="flexSwitchCheckDefault">
                                        <label class="form-check-label" for="flexSwitchCheckDefault">
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Upload thumbnail</label><small style="color: red">* ( ratio)
                                            1:1 </small>
                                    </div>
                                    <div class="card mt-2">
                                        <div class="card-body pt-0">
                                            <div class="d-grid">
                                                <p class="text-muted pt-3">Upload your Product Thumbnail image here, Please
                                                    click "Upload
                                                    Image" Button.</p>
                                                <div
                                                    class="thumbnail_preview-box d-block justify-content-center rounded  border-dashed border-theme-color overflow-hidden p-3">
                                                </div>
                                                <input type="file" id="thubnail" name="image" accept="image/*"
                                                    onchange={handleThumbnailChange()} hidden />
                                                <label class="btn-upload btn btn-primary mt-3" for="thubnail">Upload
                                                    Image</label>
                                            </div>
                                        </div><!--end card-body-->
                                    </div><!--end card-->
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Upload product images</label><small style="color: red">* 'ratio 1:1 </small>
                                    </div>
                                    <div class="card p-0 mt-2">
                                        <div class="card-body p-0">
                                            <div id="product_img"></div>
                                        </div><!--end card-body-->
                                    </div><!--end card-->

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Size Chart</label><small style="color: red"> ( Optional )</small>
                                    </div>
                                    <div class="card mt-2">
                                        <div class="card-body pt-0">
                                            <div class="d-grid">
                                                <p class="text-muted pt-3">Upload your Product Size-chart image here,
                                                    Please
                                                    click "Upload
                                                    Image" Button.</p>
                                                <div
                                                    class="size_chart_box d-block justify-content-center rounded  border-dashed border-theme-color overflow-hidden p-3">
                                                </div>
                                                <input type="file" id="size_chart" name="size_chart" multiple
                                                    accept="image/*" onchange={handleSizeChartChange()} hidden />
                                                <label class="btn-upload btn btn-primary mt-3" for="size_chart">Upload
                                                    Image</label>
                                            </div>
                                        </div><!--end card-body-->
                                    </div><!--end card-->
                                </div>


                            </div>

                        </div>
                    </div>
                    <div class="position-sticky card py-3 rounded-t-none rounded-b"
                        style="left: 10%; bottom: 0; width: 100%;">
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-lg btn-success w-50 ">Submit
                                Product</button>
                        </div>
                    </div>
                </form>
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div><!-- container -->

    <!-- Color Modal -->
    <div class="modal fade" id="colorAddModal" tabindex="-1" data-bs-backdrop="static"
        aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">
            <form action="" method="POST">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New Color</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">
                                Color Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="color_name" class="form-control" placeholder="Enter color name">
                            @error('color_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Color Code <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="color_code" class="form-control" placeholder="Ex: #21365e">
                            @error('color_code')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                        <button type="submit" class="btn btn-primary">
                            Save changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        function add_fields() {
            document.getElementById("myTable").insertRow(-1).innerHTML =
                '<tr><td><input type="date"  placeholder="start day" name="start_day[]" value="" class="form-control form-control-lg"></td><td><input type="date"  placeholder="end day" name="end_day[]" value="" class="form-control form-control-lg"></td><td><input type="number" placeholder="Discount" name="discountCam[]" value="" class="form-control form-control-lg"> </td> </tr>';
        }
    </script>
    <script>
        // when checkbox change colors selector disabled or enabled
        $('input[name="colors_active"]').on('change', function() {
            if (!$('input[name="colors_active"]').is(':checked')) {
                $('#colors-selector').prop('disabled', true);
            } else {
                $('#colors-selector').prop('disabled', false);
            }
        });

        // when choice attributes change
        $('#choice_attributes').on('change', function() {
            $('#customer_choice_options').html(null);
            $.each($("#choice_attributes option:selected"), function() {
                //console.log($(this).val());
                add_more_customer_choice_option($(this).val(), $(this).text());
            });
        });

        function add_more_customer_choice_option(i, name) {
            let n = name.replace(/\s+/g, '');

            $('#customer_choice_options').append(`
        <div class="row mt-2">
            <div class="col-md-3">
                <input type="hidden" name="choice_no[]" value="${i}">
                <input type="text" class="form-control form-control-lg" value="${n}" readonly>
            </div>

            <div class="col-lg-9">
                <select
                    class="form-control form-control-lg choice-options"
                    name="choice_options_${i}[]"
                    multiple="multiple"
                    data-placeholder="S, M, L, XL">
                </select>
            </div>
        </div>
    `);

            // init select2 only new element
            $('.choice-options').last().select2({
                    tags: true,
                    tokenSeparators: [','],
                    width: '100%',
                    placeholder: 'Enter sizes (comma separated)'
                })
                .on('select2:select select2:unselect', function() {
                    update_sku();
                });
        }
    </script>

    <script>
        function getRndInteger() {
            return Math.floor(100000 + Math.random() * 900000);
        }
    </script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#description').summernote();
            $('#shortDescription').summernote();
            $('#metaDesc').summernote();

        });
    </script>
    <script>
        $(document).ready(function() {
            $('#colors-selector').select2({
                placeholder: "Select Colors",
                allowClear: true,
                width: '100%'
            });
        });
        $(document).ready(function() {
            $('#choice_attributes').select2({
                placeholder: "Select Atributes",
                allowClear: true,
                width: '100%'
            });
        });
        $('#colors-selector').on('change', function() {
            console.log("hi");

            update_sku();
            // update_sku_color();
        });

        $('input[name="unit_price"]').on('keyup', function() {
            update_sku();
        });

        function update_sku() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '{{ route('admin.product.sku-combination') }}',
                data: $('#product_form').serialize(),
                success: function(data) {
                    $('#sku_combination').html(data.view);
                    if (data.length > 1) {
                        $('#quantity').hide();
                    } else {
                        $('#quantity').show();
                    }
                }
            });
        }
    </script>
@endpush
