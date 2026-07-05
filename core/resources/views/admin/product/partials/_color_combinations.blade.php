@if (count($combinations[0]) > 0)
    <table class="table table-bordered">
        <thead>
            <tr>
                <td class="text-center">
                    <label for="" class="control-label">Variant</label>
                </td>

                <td class="text-center">
                    <label for="" class="control-label">Image URL</label>
                </td>
            </tr>
        </thead>
        <tbody>
@endif
@foreach ($combinations as $key => $combination)
    @php
        $sku = '';
        foreach (explode(' ', $product_name) as $key => $value) {
            $sku .= substr($value, 0, 1);
        }

        $str = '';
        foreach ($combination as $key => $item) {
            if ($key > 0) {
                $str .= '-' . str_replace(' ', '', $item);
            } else {
                if ($colors_active == 1) {
                    $color_name = \App\Models\Color::where('code', $item)->first()->name;
                    $str .= $color_name;
                } else {
                    $str .= str_replace(' ', '', $item);
                }
            }
        }
    @endphp

    @if (strlen($str) > 0)
        <tr>
            <td>
                <label for="" class="control-label">{{ $color_name }}</label>
            </td>
            <td>
                <input type="text" name="color_image[]" value="" placeholder="Pest Image URL"
                    class="form-control" required>
            </td>
        </tr>
    @endif
@endforeach
</tbody>
</table>
