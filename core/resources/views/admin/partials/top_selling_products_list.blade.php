@if($topSellingProducts->count() > 0)
    @foreach($topSellingProducts as $product)
        <tr>
            <td class="px-0">
                <div class="d-flex align-items-center">
                    <img src="{{ $product->thumbnail ? asset('assets/storage/product/thumbnail/' . $product->thumbnail) : asset('assets/images/default-product.png') }}"
                         class="me-2 align-self-center thumb-md rounded"
                         alt="{{ $product->name }}">

                    <div class="flex-grow-1 text-truncate">
                        <h6 class="m-0 text-truncate">{{ $product->name }} || {{ $product->code }}</h6>
                        <div class="d-flex align-items-center">
                            <div class="progress bg-primary-subtle w-100"
                                 style="height:12px;"
                                 role="progressbar"
                                 aria-valuenow="{{ round($product->progress) }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100">
                                <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" style="width: {{ round($product->progress) }}%">{{ round($product->progress) }}%</div>
                            </div>
                            <small class="flex-shrink-1 ms-1"><b>QTY:</b> {{ $product->total_qty }}</small>
                        </div>
                    </div>
                </div>
            </td>

            <td class="px-0 text-end">
                <span class="text-body ps-2 align-self-center text-end fw-medium">
                    <b>Price:</b> {{ number_format($product->unit_price, 2) }}
                </span>
            </td>
            <td class="px-0 text-end">
                <span class="text-body ps-2 align-self-center text-end fw-medium">
                    <b>Total:</b> {{ number_format($product->total_amount, 2) }}
                </span>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="3" class="text-center py-3">No data found</td>
    </tr>
@endif
