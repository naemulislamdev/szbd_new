@foreach ($products as $product)
     @if (!empty($product['product_id']))
         @php($product = $product->product)
     @endif
     @if (!empty($product))
         @include('web.products.ajax_product_box', ['dataCategory' => 'category1'])
     @endif
 @endforeach
