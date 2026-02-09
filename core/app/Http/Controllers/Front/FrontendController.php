<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\BusinessSetting;
use App\Models\Career;
use App\Models\Category;
use App\Models\ChildCategory;
use App\Models\ClientReview;
use App\Models\DealOfTheDay;
use App\Models\FlashDeal;
use App\Models\FlashDealProduct;
use App\Models\HelpTopic;
use App\Models\LandingPages;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\ProductLandingPage;
use App\Models\Review;
use App\Models\ShippingAddress;
use App\Models\SubCategory;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FrontendController extends Controller
{
    public function home()
    {
        $home_categories = Category::where('home_status', true)->get();

        //feature products finding based on selling
        $featured_products = Product::with(['reviews'])->active()
            ->where('featured', 1)
            ->withCount(['order_details'])->orderBy('order_details_count', 'DESC')
            ->take(12)
            ->get();
        //end
        //Arrival products finding based on selling
        $arrival_products = Product::with(['reviews'])->active()
            ->where('arrival', 1)
            ->withCount(['order_details'])->orderBy('order_details_count', 'DESC')
            ->take(12)
            ->get();
        //end

        $latest_products = Product::with(['reviews'])->active()->orderBy('id', 'desc')->take(8)->get();
        $categories = Category::where('home_status', true)->take(11)->get();
        $brands = Brand::active()->take(15)->get();
        //best sell product
        $bestSellProduct = OrderDetail::with('product.reviews')
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('COUNT(product_id) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
            ->take(4)
            ->get();
        //Top rated
        $topRated = Review::with('product')
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('AVG(rating) as count'))
            ->groupBy('product_id')
            ->orderBy("count", 'desc')
            ->take(4)
            ->get();

        if ($bestSellProduct->count() == 0) {
            $bestSellProduct = $latest_products;
        }

        if ($topRated->count() == 0) {
            $topRated = $bestSellProduct;
        }

        $deal_of_the_day = DealOfTheDay::join('products', 'products.id', '=', 'deal_of_the_days.product_id')->select('deal_of_the_days.*', 'products.unit_price')->where('products.status', 1)->where('deal_of_the_days.status', 1)->first();

        // product count on category wise
        $products = Product::active()->get();
        $productCounts = [];
        foreach ($products as $product) {
            $categoryIds = json_decode($product->category_ids, true);

            if (is_array($categoryIds)) {
                foreach ($categoryIds as $category) {
                    $categoryId = $category['id'];
                    if (!isset($productCounts[$categoryId])) {
                        $productCounts[$categoryId] = 0;
                    }
                    $productCounts[$categoryId]++;
                }
            }
        }


        return view('web.home', compact('featured_products', 'arrival_products', 'topRated', 'bestSellProduct', 'latest_products', 'categories', 'brands', 'deal_of_the_day', 'home_categories', 'productCounts'));
    }
    //Products Search on ajax
    public function searchProducts(Request $request)
    {
        $query = $request->input('query');
        $products = Product::active()
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('code', 'LIKE', "%{$query}%")
                    ->orWhere('details', 'LIKE', "%{$query}%");
            })
            ->get();
        $categories = Category::where('name', 'LIKE', "%{$query}%")->get();

        $output = '';
        if (count($products) > 0) {
            foreach ($products as $product) {
                $image = asset('assets/storage/product/thumbnail/') . '/' . $product->thumbnail;
                $price = $product->unit_price;
                $output .= '
                <a href="' . route('product', $product->slug) . '">
                <div class="product-item d-flex">
                    <div class="mr-2 se-product-res"><img src="' . $image . '" alt="' . $product->name . '" /></div>
                   <div class="se-product-content-res">
                    <h5>' . $product->name . '</h5>
                    <p>' . $price . '</p>
                   </div>
                </div>
                </a>
                ';
            }
        } else {
            $output .= '<p>No products found</p>';
        }

        //categories loop
        $cates = '<div class="mb-2"><p class="text-center text-bold">Our popular categories</p></div>';
        if (count($categories) > 0) {
            foreach ($categories as $category) {
                $cates .= '
                <div class="category-item">
                    <div>
                    <a  href="' . route('category.products', $category->slug) . '">
                    <p>' . $category->name . '</p>
                    </a>
                    </div>
                </div>
                ';
            }
        } else {
            $cates .= '<p>No categories found</p>';
        }

        return response()->json([
            'products' => $output,
            'categories' => $cates
        ]);
    }
    public function homeSearch(Request $request)
    {
        $keyword = $request->search;

        $searchProducts = Product::where('status', 1)
            ->where(function ($query) use ($keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%")
                    ->orWhere('code', 'LIKE', "%{$keyword}%");
            })
            ->paginate(20);
        return view('web.home_search', compact('searchProducts', 'keyword'));
    }

    public function videoShopping(Request $request)
    {
        $allProducts = Product::active()->with(['reviews'])->where('video_shopping', true);

        $query = null;
        if ($request->get('min_price') !== null && $request->get('max_price') !== null) {
            $min_price = $request->get('min_price');
            $max_price = $request->get('max_price');
            $query = $allProducts->whereBetween('unit_price', [$min_price, $max_price])->get();
        }
        $products = $query;

        if ($request->ajax()) {
            return response()->json([
                'view' => view('web.products.video_ajax_products', compact('products'))->render()
            ], 200);
        }
        $videoProducts = $allProducts->paginate(30);

        return view('web.video_shopping', compact('videoProducts'));
    }
    public function careers()
    {
        $careers = Career::where('status', 1)->latest()->get();
        return view("web.careers", compact('careers'));
    }

    //shop function
    public function shop(Request $request)
    {
        $allProducts = Product::with(['reviews'])->latest()->active();

        $query = null;
        if ($request->get('min_price') !== null && $request->get('max_price') !== null) {
            $min_price = $request->get('min_price');
            $max_price = $request->get('max_price');
            $query = $allProducts->whereBetween('unit_price', [$min_price, $max_price])->get();
        }
        $products = $query;

        if ($request->ajax()) {
            return response()->json([
                'view' => view('web.products._ajax-products', compact('products'))->render()
            ], 200);
        }
        $shop_products = $allProducts->paginate(30);
        return view('web.products.all_products', compact('shop_products'));
    }
    public function specialProducts(Request $request)
    {
        $allProducts = Product::with(['reviews'])->where('discount', '>', 0)->active();

        $query = null;
        if ($request->get('min_price') !== null && $request->get('max_price') !== null) {
            $min_price = $request->get('min_price');
            $max_price = $request->get('max_price');
            $query = $allProducts->whereBetween('unit_price', [$min_price, $max_price])->get();
        }
        $products = $query;

        if ($request->ajax()) {
            return response()->json([
                'view' => view('web.products.selling_ajax_products', compact('products'))->render()
            ], 200);
        }
        $selling_products = $allProducts->paginate(30);
        return view('web.products.selling_products', compact('selling_products'));
    }
    //outlets function
    public function outlets()
    {
        $branchs = Branch::where('status', 1)->get();
        return view('web.outlets', compact('branchs'));
    }

    public function clientReview(Request $request)
    {
        if (auth('customer')->check()) {
            $request->validate([
                'client_name' => 'required|string|max:50',
                'client_gender' => 'required|string|max:50|in:male,female,other',
                'client_comment' => 'required|string|max:400',
                'rating' => 'required|string',
            ]);
            $ClientReview = ClientReview::create([
                'name' => $request->client_name,
                'gender' => $request->client_gender,
                'comment' => $request->client_comment,
                'ratings' => $request->rating,
                'customer_id' => auth('customer')->id(),
                'status' => false
            ]);
            if ($ClientReview) {
                return back()->with('success', 'Review is created successfully!');
            } else {
                return back()->with('warning', 'Something want wrong!');
            }
        } else {
            return back()->with('warning', 'Login first as a customer!');
        }
    }

    public function flash_deals($id)
    {
        $deal = FlashDeal::with(['products.product.reviews', 'products.product' => function ($query) {
            $query->active();
        }])
            ->where(['id' => $id, 'status' => 1])
            ->whereDate('start_date', '<=', date('Y-m-d'))
            ->whereDate('end_date', '>=', date('Y-m-d'))
            ->first();

        $discountPrice = FlashDealProduct::with(['product'])->whereHas('product', function ($query) {
            $query->active();
        })->get()->map(function ($data) {
            return [
                'discount' => $data->discount,
                'sellPrice' => $data->product->unit_price,
                'discountedPrice' => $data->product->unit_price - $data->discount,

            ];
        })->toArray();
        if (isset($deal)) {
            return view('web-views.deals', compact('deal', 'discountPrice'));
        }
        return back()->with('error', 'Not Found');
    }

    public function all_categories()
    {
        $categories = Category::all();
        return view('web.categories', compact('categories'));
    }

    public function all_brands()
    {
        $brands = Brand::active()->paginate(24);
        return view('web-views.brands', compact('brands'));
    }

    public function checkout()
    {
        if (session()->has('cart') && count(session('cart')) > 0) {
            $customer = auth('customer')->user();
            $shippingAddresses = [];
            if ($customer) {
                $shippingAddresses = ShippingAddress::where('customer_id', $customer->id)->get();
                $otpExists = User::whereNotNull('otp')->exists();
            }
            return view('web.checkout', compact('customer','shippingAddresses'));
        }
        return redirect('/')->with('error', 'No items in your basket!');
    }

    public function product($slug)
    {
        $product = Product::active()->with(['reviews'])->where('slug', $slug)->first();

        if ($product != null) {
            $countOrder = OrderDetail::where('product_id', $product->id)->count();
            $countWishlist = Wishlist::where('product_id', $product->id)->count();
            $deal_of_the_day = DealOfTheDay::where('product_id', $product->id)->where('status', 1)->first();

            $relatedProducts = collect();

            $query = Product::with('reviews')
                ->active()
                ->where('id', '!=', $product->id);

            if (!empty($product->child_category_id)) {
                $query->where('child_category_id', $product->child_category_id);
            } elseif (!empty($product->sub_category_id)) {
                $query->where('sub_category_id', $product->sub_category_id);
            } else {
                $query->where('category_id', $product->category_id);
            }

            $relatedProducts = $query->inRandomOrder()->limit(12)->get();

            return view('web.products.details', compact('product', 'relatedProducts', 'countWishlist', 'countOrder', 'deal_of_the_day'));
        }

        return back()->with('error', 'Product Not Found!');
    }

    public function products(
        Request $request,
        $category_slug = null,
        $subcategory_slug = null,
        $childcategory_slug = null
    ) {
        // Base product query
        $query = Product::active()->with('reviews');

        $data = [];

        // =========================
        // CATEGORY
        // =========================
        if ($category_slug) {
            $category = Category::where('slug', $category_slug)->firstOrFail();
            $data['cat'] = $category;

            // filter products by category
            $query->where('category_id', $category->id);
        }

        // =========================
        // SUB CATEGORY
        // =========================
        if ($subcategory_slug) {
            $subCategory = SubCategory::where('slug', $subcategory_slug)->firstOrFail();
            $data['subCat'] = $subCategory;

            $query->where('sub_category_id', $subCategory->id);
        }

        // =========================
        // CHILD CATEGORY
        // =========================
        if ($childcategory_slug) {
            $childCategory = ChildCategory::where('slug', $childcategory_slug)->firstOrFail();
            $data['childCat'] = $childCategory;

            $query->where('child_category_id', $childCategory->id);
        }

        // =========================
        // SORTING
        // =========================
        $sort_by = $request->get('sort_by', 'latest');

        if ($sort_by === 'low-high') {
            $query->orderBy('unit_price', 'ASC');
        } elseif ($sort_by === 'high-low') {
            $query->orderBy('unit_price', 'DESC');
        } elseif ($sort_by === 'a-z') {
            $query->orderBy('name', 'ASC');
        } elseif ($sort_by === 'z-a') {
            $query->orderBy('name', 'DESC');
        } else {
            $query->latest();
        }

        // =========================
        // PRICE FILTER
        // =========================
        if ($request->filled(['min_price', 'max_price'])) {
            $min = $request->min_price;
            $max = $request->max_price;
            $query->whereBetween('unit_price', [$min, $max]);
        }

        // =========================
        // PAGINATION
        // =========================
        $products = $query->paginate(20)->appends($request->query());

        // =========================
        // AJAX RESPONSE
        // =========================
        if ($request->ajax()) {
            return response()->json([
                'view' => view('web.products._ajax-products', compact('products'))->render()
            ]);
        }

        return view('web.category_wise_product', compact('products', 'data'));
    }
    public function multiCollection($slug)
    {
        $lpage =  LandingPages::where('status', 1)->where("slug", $slug)->first();
        if ($lpage) {
            $first_product = Product::find($lpage->product_id);
            $main_banners = json_decode($lpage->main_banner);
            $subProducts = [];
            foreach ($lpage->multiProducts as $i => $item) {
                $subProducts[$i] =  Product::find($item->product_id);
            }
            $withSlide = $lpage->with_slide;


            return view("web.products.collections", compact("first_product", "subProducts", "main_banners", "withSlide"));
        } else {
            return redirect()->route('home')->with('error', 'Page not available!');
        }
    }

    //for HelpTopic
    public function helpTopic()
    {
        $helps = HelpTopic::Status()->latest()->get();
        return view('web.help-topics', compact('helps'));
    }

    //for Contact US Page
    public function contacts()
    {
        return view('web.contacts');
    }

    public function about_us()
    {
        $about_us = BusinessSetting::where('type', 'about_us')->first();
        return view('web.about-us', [
            'about_us' => $about_us,
        ]);
    }

    public function termsandCondition()
    {
        $terms_condition = BusinessSetting::where('type', 'terms_condition')->first();
        return view('web.terms', compact('terms_condition'));
    }

    public function privacy_policy()
    {
        $privacy_policy = BusinessSetting::where('type', 'privacy_policy')->first();
        return view('web.privacy-policy', compact('privacy_policy'));
    }

    public function landingPage($landing_slug)
    {
        $landing_page = DB::table('landing_pages')->where(['slug' => $landing_slug])->where('status', 1)->first();
        if ($landing_page == null) {
            return view('errors.page_error');
        }
        $landing_page_pro = DB::table('landing_pages_products')->where('landing_id', $landing_page->id)->pluck('product_id')->toArray();
        $landing_products = Product::with(['rating'])->whereIn('id', $landing_page_pro)->orderBy('id', 'DESC')->active()->get();
        return view('web.landing-page.pages', compact('landing_products', 'landing_page'));
    }
    public function signleProductLandingPage($slug)
    {
        $productLandingPage = ProductLandingPage::where('slug', $slug)->where('status', true)->first();
        if ($productLandingPage) {
            return view('web.landing-page.signle_product', compact('productLandingPage'));
        } else {
            return redirect()->route('home')->with('warning', 'Landing page is not available!');
        }
    }

    // DB Modify Funciton for some time
    public function currency_convert()
    {
        $products = Product::all();

        $usd = 0.011904761904762;
        $my_currency = 1.0;
        //1.0
        $rate = $my_currency / $usd;


        foreach ($products as $product) {
            /* ===============================
         | 1️⃣ Convert Variation Price
         =============================== */
            $variations = [];

            if (!empty($product->variation)) {
                $decodedVariation = json_decode($product->variation, true);

                if (is_array($decodedVariation)) {
                    foreach ($decodedVariation as $variation) {

                        if (isset($variation['price'])) {
                            $variation['price'] = $variation['price'] * $rate;
                        }

                        $variations[] = $variation;
                    }
                }
            }


            $discount = 0;
            if ($product->discount_type === 'flat' && $product->discount > 0) {
                $discount = $product->discount * $rate;
            }
            $bdtUnitPrice = $product->unit_price * $rate;
            $bdtPurchasePrice = $product->purchase_price * $rate;

            $product->update([
                'unit_price'     => round($bdtUnitPrice, 2),
                'purchase_price' => round($bdtPurchasePrice, 2),
                'discount'       => round($discount, 2),
                'variation'      => json_encode($variations),
            ]);
        }
        return "Product Price Updated Successfully!";
    }
    //end
}
