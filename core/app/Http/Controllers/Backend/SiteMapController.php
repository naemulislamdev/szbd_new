<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;


class SiteMapController extends Controller
{
    public function index()
    {
        return view('admin/sitemap/view');
    }
    // public function download()
    // {
    //     $sitemap = Sitemap::create();

    //     // ✅ Home Page
    //     $sitemap->add(
    //         Url::create(url('/'))
    //             ->setPriority(1.0)
    //             ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
    //     );

    //     // ✅ Product Categories
    //     $categories = Category::where('home_status', 1)->get();
    //     foreach ($categories as $category) {
    //         $sitemap->add(
    //             Url::create(url('/category/' . $category->slug))
    //                 ->setPriority(0.8)
    //                 ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
    //         );
    //     }

    //     // ✅ Products
    //     $products = Product::where('status', 1)->get();
    //     foreach ($products as $product) {
    //         $sitemap->add(
    //             Url::create(url('/product/' . $product->slug))
    //                 ->setPriority(0.7)
    //                 ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
    //         );
    //     }

    //     $path = public_path('sitemap.xml');
    //     $sitemap->writeToFile($path);

    //     return response()->download($path);
    // }

    // new SEO Friendly

    public function download()
    {
        $sitemap = Sitemap::create();

        // ✅ Home Page
        $sitemap->add(
            Url::create(url('/'))
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setLastModificationDate(now())
        );

        // ✅ Product Categories
        $categories = Category::where('home_status', 1)->get();
        foreach ($categories as $category) {
            $sitemap->add(
                Url::create(url('/category/' . $category->slug))
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setLastModificationDate($category->updated_at)
            );
        }

        // ✅ Products
        $products = Product::where('status', 1)->get();
        foreach ($products as $product) {

            $url = Url::create(url('/product/' . $product->slug))
                ->setPriority(0.7)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setLastModificationDate($product->updated_at);

            // Thumbnail থাকলে image add
            if ($product->thumbnail) {
                $url->addImage(asset('storage/' . $product->thumbnail));
            }

            $sitemap->add($url);
        }

        $path = public_path('sitemap.xml');
        $sitemap->writeToFile($path);

        return response()->download($path);
    }
}
