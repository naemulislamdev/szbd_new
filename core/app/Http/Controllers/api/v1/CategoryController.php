<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function get_categories()
    {
        try {
            $categories = Category::with(['childes'])->get();
            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    public function get_subcategories($id)
    {
        try {
            $subCategories = SubCategory::with(['childes'])->get();
            return response()->json($subCategories, 200);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    public function get_products(Request $request, $id)
    {
        $limit = $request->input('limit', 10);
        $offset = $request->input('offset', 1);

        $products = Product::with(['rating'])
            ->active()
            ->where('category_id', $id)
            ->latest()
            ->paginate($limit, ['*'], 'page', $offset);

        $formattedProducts = Helpers::product_data_formatting($products->items(), true);

        return response()->json([
            'total_size' => $products->total(),
            'limit' => (int) $limit,
            'offset' => (int) $offset,
            'products' => $formattedProducts,
        ], 200);
    }

    public function get_products_slug($slug)
    {
        $categoryInfo = Category::where('slug', $slug)->first();

        if (!$categoryInfo) {
            return response()->json([], 200);
        }

        $id = $categoryInfo->id;
        $products =  Product::active()
            ->where('category_id', $id)->get();

        return response()->json(Helpers::product_data_formatting($products, true), 200);
    }
}
