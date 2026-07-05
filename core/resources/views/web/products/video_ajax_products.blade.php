@foreach ($products as $product)
@include('web.products.product_box', ['dataCategory' => 'category'])
@endforeach
