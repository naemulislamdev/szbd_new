@php
    $colors = is_array($product->colors)
        ? $product->colors
        : json_decode($product->colors ?? '[]', true);

    $colorVariants = is_array($product->color_variant)
        ? $product->color_variant
        : json_decode($product->color_variant ?? '[]', true);
    $choiceOptions = is_array($product->choice_options)
        ? $product->choice_options
        : json_decode($product->choice_options ?? '[]', true);
@endphp

<div class="product-column" data-category="{{ $dataCategory ?? '' }}">
    <div class="product-box product-box-col-2" data-category="{{ $dataCategory ?? '' }}">
        <input type="hidden" name="quantity" value="{{ $product->minimum_order_qty ?? 1 }}"
            min="{{ $product->minimum_order_qty ?? 1 }}" max="100">
        <div class="product-image2 product-image2-col-2" data-category="{{ $dataCategory ?? '' }}">
            @if ($product->discount > 0)
                <div class="discount-box float-end">
                    <span>
                        @if ($product->discount_type == 'percent')
                            {{ $product->discount }}%
                        @elseif($product->discount_type == 'flat')
                            {{ $product->discount }}৳
                        @endif
                    </span>
                </div>
            @endif
            <a href="{{ route('product', $product->slug) }}">
                <img class="img-fluid lazy-image" loading="lazy"
                    src="data:image/svg+xml,%3Csvg width='300' height='300' xmlns='http://www.w3.org/2000/svg'%3E%3C/svg%3E"
                    data-src="{{ asset('assets/storage/product/thumbnail') }}/{{ $product['thumbnail'] }}"
                    alt="{{ $product['name'] }}">
            </a>
            <ul class="social">
                <li><a href="{{ route('product', $product->slug) }}" data-tip="Quick View"><i
                            class="fa fa-eye"></i></a></li>

                <li><a style="cursor: pointer" data-toggle="modal" data-target="#addToCartModal_{{ $product->id }}"
                        data-tip="Add to Cart"><i class="fa fa-shopping-cart"></i></a>
                </li>
            </ul>
        </div>
        <div class="product-content" style="height: 140px">
            <div class="h-100 d-flex flex-column justify-content-between ">
                <h3 class="title"><a
                    href="{{ route('product', $product->slug) }}">{{ Str::limit($product['name'], 35) }}</a>
                </h3>
                <div class="price d-flex  align-content-center">
                    @if ($product->discount > 0)
                        <span
                            class="mr-2">৳{{
                                $product->unit_price - \App\CPU\Helpers::get_product_discount($product, $product->unit_price),
                            }}</span>
                        <del>৳ {{ $product->unit_price }}</del>
                    @else
                        <span>৳ {{ $product->unit_price }}</span>
                    @endif
                </div>
                <button type="button" style="cursor: pointer;" class="btn btn-primary btn btn-primary align-self-center" @if (!empty($colorVariants) || count($choiceOptions) > 0) data-toggle="modal" data-target="#addToCartModal_{{ $product->id }}" @else
                    onclick="buy_now('form-{{ $product->id }}')" @endif>অর্ডার করুন</button>
            </div>
        </div>
    </div>
</div>

