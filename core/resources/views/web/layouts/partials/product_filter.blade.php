<div class="filter-box category-filter-box" id="filter-box">
    <a data-bs-toggle="offcanvas" href="#productFilter" role="button"
        aria-controls="productFilter"><i class="fa fa-filter"></i> Filter</a>
    <!------shopping cart canva-->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="productFilter"
        aria-labelledby="offcanvaProductFilterCard">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvaProductFilterCard">FILTER</h5>
            <i class="fa fa-close offcanvasClose" data-bs-dismiss="offcanvas" aria-label="Close"></i>
        </div>
        <div class="offcanvas-body">
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="wrapper">
                        <h4 class="color-heading">Price</h4>
                        <div class="price-input">
                            <div class="field">
                                <span>Min</span>
                                <input type="number" class="input-min" value="2500" id="min_price">
                            </div>
                            <div class="separator">-</div>
                            <div class="field">
                                <span>Max</span>
                                <input type="number" class="input-max" value="7500" id="max_price">
                            </div>
                        </div>
                        <div class="slider">
                            <div class="progress"></div>
                        </div>
                        <div class="range-input">
                            <input type="range" class="range-min" min="0" max="10000"
                                value="2500" step="100">
                            <input type="range" class="range-max" min="0" max="10000"
                                value="7500" step="100">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3 mx-auto">
                    <a href="javascript:void(0);" class="w-100 common-btn" onclick="searchByPrice()">Search</a>
                </div>
            </div>
            <div class="row">
               <div class="col-md-12">
                    @php $categories = \App\Models\Category::where('home_status', 1)->orderBy('order_number')->get(); @endphp
                    <h4 class="color-heading">Categories:</h4>
                    <!--start Filter Category-->
                    <div class="category-filter">
                        @foreach($categories as $category)
                        <div class="category">
                            <div class="category-header">
                                <a href="{{ route('category.products', $category->slug) }}">{{$category['name']}}</a>
                                <span class="toggle-icon" data-toggle="category_{{$category['id']}}">+</span>
                            </div>
                            <div class="sub-categories" id="category_{{$category['id']}}">
                                @foreach($category->subCategory as $subCat)
                                <div class="sub-category">
                                    <div class="sub-category-header">
                                        <a href="{{ route('category.products', [$category->slug, $subCat->slug]) }}">{{$subCat['name']}}</a>
                                        <span class="toggle-icon" data-toggle="subCategory_{{$subCat['name']}}">+</span>
                                    </div>
                                    <div class="child-categories" id="subCategory_{{$subCat['name']}}">
                                        @if ($subCat->childes->count() > 0)
                                        @foreach($subCat->childes as $childCat)
                                        <a href="{{ route('category.products', [$category->slug, $subCat->slug, $childCat->slug]) }}">{{$childCat['name']}}</a><br>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <!--end category filter-->
                </div>
            </div>
        </div>
    </div>
    <!------End shopping cart canva-->
</div>
