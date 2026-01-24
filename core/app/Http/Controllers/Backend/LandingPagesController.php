<?php

namespace App\Http\Controllers\Admin;

use App\CPU\ImageManager;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\LandingPages;
use App\Model\Product;
use App\ProductLandingPage;
use App\ProductLandingPageSection;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



class LandingPagesController extends Controller
{


    public function landing_index(Request $request)
    {
        $landing_page = DB::table('landing_pages')->latest()->get();
        return view('admin-views.landingpages.landing-index', compact('landing_page'));
    }



    public function landing_submit(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'images' => 'nullable',
            'mid_banner' => 'nullable',
            'left_side_banner' => 'nullable',
            'right_side_banner' => 'nullable',
        ]);

        $images = null;
        if ($request->file('images')) {
            foreach ($request->file('images') as $img) {
                $main_banner_images[] = Helpers::uploadWithCompress('main-banner/', 300, $img);
            }
            $images = json_encode($main_banner_images);
        }

        $flash_deal_id = DB::table('landing_pages')->insertGetId([
            'title' => $request['title'],
            'product_id' => $request['product_id'],
            'main_banner' => $images,
            'mid_banner' => Helpers::uploadWithCompress('deal/', 300, $request->file('mid_banner')),
            // 'left_side_banner' =>  Helpers::uploadWithCompress('deal/', 300, $request->file('left_side_banner')),
            // 'right_side_banner' => Helpers::uploadWithCompress('deal/', 300, $request->file('right_side_banner')),
            // 'meta_title' => $request['title'],
            // 'meta_description' => $request['meta_description'],
            'slug' => Str::slug($request['title']),
            'product_id' => $request->product_id,
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Toastr::success('Land-Pages added successfully!');
        return back();
    }



    public function status_update(Request $request)
    {
        DB::table('landing_pages')
            ->where('id', $request->id)
            ->update(['status' => 1]);

        DB::table('landing_pages')
            ->where('id', '!=', $request->id)
            ->update(['status' => 0]);
        return response()->json([
            'success' => 1,
        ], 200);
    }

    public function edit($landing_id)
    {
        $landing_pages = DB::table('landing_pages')->find($landing_id);
        return view('admin-views.landingpages.landing-pages-update', compact('landing_pages'));
    }
    public function remove_image(Request $request)
    {
        ImageManager::delete('/deal/main-banner/' . $request['image']);
        $landingPage = LandingPages::find($request['id']);
        $array = [];
        foreach (json_decode($landingPage['main_banner']) as $image) {
            if ($image != $request['name']) {
                array_push($array, $image);
            }
        }
        LandingPages::where('id', $request['id'])->update([
            'main_banner' => json_encode($array),
        ]);
        Toastr::success('Main banner image removed successfully!');
        return back();
    }

    public function update(Request $request, $deal_id)
    {
        $deal = DB::table('landing_pages')->find($deal_id);

        $images = json_decode($deal->main_banner, true) ?? []; // Decode existing images or start with an empty array

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $uploaded_image = Helpers::uploadWithCompress('deal/main-banner/', 300, $img);
                $images[] = $uploaded_image;
            }
        }
        $finalImage = json_encode($images);

        if ($request->mid_banner) {
            $deal->mid_banner = Helpers::updateWithCompress('deal/', $deal->mid_banner, $request->file('mid_banner'));
        }
        // if ($request->left_side_banner) {
        //     $deal->left_side_banner = Helpers::updateWithCompress('deal/', $deal->left_side_banner, $request->file('left_side_banner'));
        // }
        // if ($request->right_side_banner) {
        //     $deal->right_side_banner = Helpers::updateWithCompress('deal/', $deal->right_side_banner, $request->file('right_side_banner'));
        // }

        DB::table('landing_pages')->where(['id' => $deal_id])->update([
            'title' => $request['title'],
            'main_banner' => $finalImage,
            'mid_banner' => $deal->mid_banner,
            'slug' => Str::slug($request['title']),
            'product_id' => $request->product_id,
            'status' => $deal->status,
            'updated_at' => now(),
        ]);
        Toastr::success('Landing pages  updated successfully!');
        return back();
    }

    public function add_product($deal_id)
    {
        $flash_deal_products = DB::table('landing_pages_products')->where('landing_id', $deal_id)->pluck('product_id');

        $products = Product::whereIn('id', $flash_deal_products)->paginate(Helpers::pagination_limit());

        $deal = DB::table('landing_pages')->where('id', $deal_id)->first();

        return view('admin-views.landingpages.add-product', compact('deal', 'products', 'flash_deal_products'));
    }

    public function add_product_submit(Request $request, $deal_id)
    {

        $flash_deal_products = DB::table('landing_pages_products')->where('landing_id', $deal_id)->where('product_id', $request['product_id'])->first();


        if (!isset($flash_deal_products)) {
            $campaing_detalie = [];
            for ($i = 0; $i < count($request->product_id); $i++) {
                $campaing_detalie[] = [
                    'product_id' => $request['product_id'][$i],
                    'landing_id' => $deal_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('landing_pages_products')->insert($campaing_detalie);
            Toastr::success('Product added successfully!');
            return back();
        } else {
            Toastr::info('Product already added!');
            return back();
        }
    }

    public function delete_product(Request $request)
    {
        DB::table('landing_pages_products')->where('product_id', $request->id)->delete();

        return response()->json();
    }

    public function index()
    {
        $productLandingpage =  ProductLandingPage::latest('created_at')->get();

        return view('admin-views.landingpages.sign_product.index', compact('productLandingpage'));
    }
    public function create()
    {
        return view('admin-views.landingpages.sign_product.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'images' => 'required',
            'description' => 'required|string',
            'product_id' => 'required',
            'feature_title' => 'required',
            'feature_image' => 'required',
            'video_url' => 'required',
        ]);

        $images = null;
        if ($request->file('images')) {
            foreach ($request->file('images') as $img) {
                $main_slider_images[] = Helpers::uploadWithCompress('landingpage/slider/', 300, $img);
            }
            $images = json_encode($main_slider_images);
        }
        $featureList = null;
        if ($request->feature_title) {
            foreach ($request->feature_title as $title) {
                $feature_list[] = $title;
            }
            $featureList = json_encode($feature_list);
        }
        $productLandingpage = ProductLandingPage::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'slider_img' => $images,
            'product_id' => $request->product_id,
            'description' => $request->description,
            'feature_list' => $featureList,
            'feature_img' => Helpers::uploadWithCompress('landingpage/', 300, $request->file('feature_image')),
            'video_url' => $request->video_url,
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $sectionTitles = $request->section_title;
        if ($sectionTitles) {
            foreach ($sectionTitles as $key => $val) {

                $requestImg = $request->section_img[$key];
                $sectionImg = Helpers::uploadWithCompress('landingpage/', 300, $requestImg);
                $sectionDirection = $request->section_direction[$key];
                $orderButton = $request->order_button[$key];
                App\Http\Controllers\Admin\ProductLandingPageSection::create([
                    'product_landing_page_id' => $productLandingpage->id,
                    'section_title' => $val,
                    'section_description' => $request->section_description[$key],
                    'section_img' => $sectionImg,
                    'section_direction' => $sectionDirection,
                    'order_button' => $orderButton,
                ]);
            }
        }
        Toastr::success('Landing pages  created is successfully!');
        return back();
    }
    public function LandingPageStatus(Request $request)
    {
        ProductLandingPage::where(['id' => $request['id']])->update([
            'status' => $request['status'],
        ]);
        return response()->json([
            'success' => 1,
        ], 200);
    }
    public function LandingPageWithSlide(Request $request)
    {
        DB::table("landing_pages")->where('id', $request['id'])->update([
            'with_slide' => $request['with_slide'],
        ]);

        return response()->json([
            'success' => 1,
        ], 200);
    }

    public function SingleProductEdit($id)
    {
        $productLandingpage = ProductLandingPage::find($id);
        //dd($productLandingpage->landingPageSection);
        return view('admin-views.landingpages.sign_product.edit', compact('productLandingpage'));
    }
    public function removeImage(Request $request)
    {
        ImageManager::delete('/landingpage/slider/' . $request['image']);
        $landingPage = ProductLandingPage::find($request['id']);
        $array = [];
        if (count(json_decode($landingPage->slider_img)) == 2) {
            Toastr::warning('You cannot delete all images!');
            return back();
        }
        foreach (json_decode($landingPage['slider_img']) as $image) {
            if ($image != $request['name']) {
                array_push($array, $image);
            }
        }
        ProductLandingPage::where('id', $request['id'])->update([
            'slider_img' => json_encode($array),
        ]);
        Toastr::success('Slider image removed successfully!');
        return back();
    }


    public function removeFeatureList(Request $request)
    {
        $landingPage = App\Http\Controllers\Admin\ProductLandingPage::find($request['id']);
        $array = [];
        if (count(json_decode($landingPage->feature_list)) == 2) {
            Toastr::warning('You cannot delete all feature list!');
            return back();
        }
        foreach (json_decode($landingPage['feature_list']) as $list) {
            if ($list != $request['name']) {
                array_push($array, $list);
            }
        }
        ProductLandingPage::where('id', $request['id'])->update([
            'feature_list' => json_encode($array),
        ]);
        Toastr::success('Feature list removed successfully!');
        return back();
    }
    public function removePageSection(Request $request)
    {
        $pageSection = ProductLandingPageSection::find($request['id']);
        if ($pageSection->section_img) {
            @unlink('storage/landingpage/' . $pageSection->section_img);
        }

        $pageSection->delete();
        Toastr::success('Landing page section remove successfully!');
        return back();
    }
    public function SingleProductUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'product_id' => 'required',
            'feature_title' => 'required',
            'video_url' => 'required',
        ]);
        $productLandingpage = ProductLandingPage::find($id);

        $images = json_decode($productLandingpage->slider_img, true) ?? [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $img) {
                $uploaded_image = Helpers::uploadWithCompress('landingpage/slider/', 300, $img);
                $images[] = $uploaded_image;
            }
        }
        $finalImage = json_encode($images);
        //Feature Title
        $featureList = json_decode($productLandingpage->feature_list, true) ?? [];

        // Handle deletion of features
        if ($request->deleted_features) {
            foreach ($request->deleted_features as $deletedFeature) {
                if (($key = array_search($deletedFeature, $featureList)) !== false) {
                    unset($featureList[$key]);
                }
            }
        }
        // Add new or updated feature titles
        if ($request->feature_title) {
            foreach ($request->feature_title as $submittedFeature) {
                if (!in_array($submittedFeature, $featureList)) {
                    $featureList[] = $submittedFeature;
                }
            }
        }
        $FinalFeatureList = json_encode(array_values($featureList));


        if ($request->feature_image) {
            $featureImage = Helpers::updateWithCompress('landingpage/', $productLandingpage->feature_img, $request->file('feature_image'));
        }
        $productLandingpage->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'slider_img' => $finalImage,
            'product_id' => $request->product_id,
            'description' => $request->description,
            'feature_list' => $FinalFeatureList,
            'feature_img' => $featureImage ?? $productLandingpage->feature_img,
            'video_url' => $request->video_url,
        ]);

        // Handle existing sections
        if ($request->existing_section_id) {
            foreach ($request->existing_section_id as $sectionId) {
                $section = ProductLandingPageSection::where('id', $sectionId)->first();
                $requestImg = $request->file('section_img')[$sectionId] ?? null;
                $sectionImg = Helpers::updateWithCompress('landingpage/', $section->section_img, $requestImg);
                $section->update([
                    'section_title' => $request->section_title[$sectionId],
                    'section_description' => $request->section_description[$sectionId],
                    'section_img' => $sectionImg,
                    // 'section_img' => $this->handleImageUpload($request->file('section_img')[$sectionId]),
                    'section_direction' => $request->section_direction[$sectionId],
                    'order_button' => $request->order_button[$sectionId],
                ]);
            }
        }

        // Handle new sections
        if ($request->has('new_section_title')) {
            foreach ($request->new_section_title as $key => $title) {

                $requestImg = $request->file('new_section_img')[$key] ?? null;
                $sectionImg = Helpers::uploadWithCompress('landingpage/', 300, $requestImg);
                ProductLandingPageSection::create([
                    'section_title' => $title,
                    'section_description' => $request->new_section_description[$key],
                    'section_img' => $sectionImg,
                    'section_direction' => $request->new_section_direction[$key],
                    'order_button' => $request->new_order_button[$key],
                    'product_landing_page_id' => $productLandingpage->id,
                ]);
            }
        }


        Toastr::success('Landing pages  created is successfully!');
        return back();
    }
    public function removeLandingPage($id)
    {
        $productLandingpage = ProductLandingPage::find($id);
        if ($productLandingpage->slider_img) {
            foreach (json_decode($productLandingpage->slider_img) as $img) {
                @unlink('storage/landingpage/slider/' . $img);
            }
        }
        if ($productLandingpage->feature_img) {
            @unlink('storage/landingpage/' . $productLandingpage->feature_img);
        }

        $sections = $productLandingpage->landingPageSection;
        if ($sections) {
            foreach ($sections as $item) {
                //dd($item->section_img);
                if ($item->section_img) {
                    @unlink('storage/landingpage/' . $item->section_img);
                }
                $item->delete();
            }
        }

        $productLandingpage->delete();
        Toastr::success('Landing pages  deleted is successfully!');
        return back();
    }
}
