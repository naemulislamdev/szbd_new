<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Product;

class BrandController extends Controller
{
    public function get_brands()
    {
        try {
            $brands = Brand::active()->withCount('brandProducts')->latest()->get();;
        } catch (\Exception $e) {
        }

        return response()->json($brands,200);
    }

    public function get_products($brand_id)
    {
        try {
            $products = Helpers::product_data_formatting(Product::active()->where(['brand_id' => $brand_id])->get(), true);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }

        return response()->json($products,200);
    }
}
