<?php

namespace App\Services;

use App\Models\Product;
use App\CPU\FileManager;
use App\CPU\Helpers;
use App\Models\Color;
use Illuminate\Support\Str;

class ProductService
{
    public static function store($request)
    {
        $product = new Product();

        self::basicInfo($product, $request);
        self::categoryInfo($product, $request);
        self::priceInfo($product, $request);
        self::variationInfo($product, $request);
        self::mediaInfo($product, $request);
        self::seoInfo($product, $request);

        $product->save();

        return $product;
    }
    public static function update($request, $id)
    {
        $product = Product::findOrFail($id);
        self::basicInfo($product, $request);
        self::categoryInfo($product, $request);
        self::priceInfo($product, $request);
        self::variationInfo($product, $request);
        self::mediaInfoUpdate($product, $request);
        self::seoInfoUpdate($product, $request);

        $product->save();

        return $product;
    }


    private static function basicInfo($p, $r)
    {
        $p->added_by = 'admin';
        $p->user_id = auth('admin')->id();
        $p->name = $r->name;
        $p->code = $r->code;
        $p->slug = Str::slug($r->name) . '-' . $r->code;
        $p->details = $r->description;
        $p->short_description = $r->short_description;
        $p->unit = $r->unit;
        $p->minimum_order_qty = $r->minimum_order_qty;
        $p->video_url = $r->video_link;
        $p->video_provider = str_contains($r->video_link, 'facebook') ? 'facebook' : 'youtube';
        $videoShopping = $r->has('video_shopping');
        if ($videoShopping == 1) {
            $p->video_shopping = true;
        } else {
            $p->video_shopping = false;
        }
        $p->request_status = 1;
        $p->multiply_qty = $r->multiplyQTY == 'on' ? 1 : 0;
    }

    private static function categoryInfo($p, $r)
    {
        $p->category_id = $r->category_id;
        $p->sub_category_id = $r->sub_category_id;
        $p->child_category_id = $r->child_category_id;
        $p->brand_id = $r->brand_id;
        $p->category_ids = null;
    }

    private static function priceInfo($p, $r)
    {
        $p->unit_price = $r->unit_price;
        $p->purchase_price = $r->purchase_price;
        $p->shipping_cost = $r->shipping_cost;

        $p->tax = $r->tax_type == 'flat'
            ? $r->tax
            : $r->tax;

        $r->discount_type = $r->discount_type ? $r->discount_type : null;


        $p->discount = $r->discount;

        $p->tax_type = $r->tax_type;
        $p->discount_type = $r->discount_type;
    }

    private static function variationInfo($p, $r)
    {
        if ($r->has('colors_active') && $r->has('colors') && count($r->colors) > 0) {
            $p->colors = json_encode($r->colors);
        } else {
            $colors = [];
            $p->colors = json_encode($colors);
        }

        $choice_options = [];
        if ($r->has('choice')) {
            foreach ($r->choice_no as $key => $no) {
                $str = 'choice_options_' . $no;
                $item['name'] = 'choice_' . $no;
                $item['title'] = $r->choice[$key];
                $item['options'] = explode(',', implode('|', $r[$str]));
                array_push($choice_options, $item);
            }
        }
        $p->choice_options = json_encode($choice_options);
        //combinations start
        $options = [];
        if ($r->has('colors_active') && $r->has('colors') && count($r->colors) > 0) {
            $colors_active = 1;
            array_push($options, $r->colors);
        }
        if ($r->has('choice_no')) {
            foreach ($r->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $my_str = implode('|', $r[$name]);
                array_push($options, explode(',', $my_str));
            }
        }
        //Generates the combinations of customer choice options

        $combinations = Helpers::combinations($options);

        $variations = [];
        $colorVariations = [];
        $stock_count = 0;
        if (count($combinations[0]) > 0) {
            foreach ($combinations as $key => $combination) {

                $str = '';
                $i = 0;

                foreach ($combination as $item) {

                    if ($i == 0 && $r->has('colors_active') && $r->has('colors')) {
                        // color code → color name
                        $color = Color::where('code', $item)->first();
                        if ($color) {
                            $str .= str_replace(' ', '', $color->name);
                        }
                    } else {
                        $str .= '-' . str_replace(' ', '', $item);
                    }

                    $i++;
                }

                $itemArr = [];
                $itemArr['type']  = $str;
                $itemArr['price'] = abs($r['price_' . $str] ?? 0);
                $itemArr['sku']   = $r['sku_' . $str] ?? '';
                $itemArr['qty']   = abs($r['qty_' . $str] ?? 0);

                $variations[] = $itemArr;
                $stock_count += $itemArr['qty'];
            }

            if ($r->colors) {
                foreach ($r->colors as $key => $color) {
                    $colorName = Color::where('code', $color)->first();
                    $imageValu = $r->color_image[$key];

                    $ColorV = [];
                    $ColorV['color'] = $colorName->name;
                    $ColorV['code'] = $colorName->code;
                    $ColorV['image'] = $imageValu;
                    array_push($colorVariations, $ColorV);
                }
            }
        } else {
            $stock_count = (int)$r['current_stock'];
        }

        $p->color_variant = json_encode($colorVariations);
        $p->variation = json_encode($variations);
        $p->attributes = json_encode($r->choice_attributes ?? []);
        $p->current_stock = abs($stock_count);
        $p->minimum_order_qty = $r->minimum_order_qty;
    }

    private static function mediaInfo($p, $r)
    {
        $path = 'assets/storage/';
        $images = [];

        // ✅ multiple images
        if ($r->hasFile('images')) {
            foreach ($r->file('images') as $img) {
                if ($img && $img->isValid()) {
                    $name = FileManager::uploadFile(
                        $path . 'product/',
                        300,
                        $img
                    );
                    if ($name) {
                        $images[] = $name;
                    }
                }
            }
            $p->images = json_encode($images);
        }

        // ✅ thumbnail
        if ($r->hasFile('image')) {
            $p->thumbnail = FileManager::uploadFile(
                $path . 'product/thumbnail/',
                300,
                $r->file('image'),
                $r->alt_text
            );
        }

        // ✅ size chart
        if ($r->hasFile('size_chart')) {
            $p->size_chart = FileManager::uploadFile(
                $path . 'product/thumbnail/',
                300,
                $r->file('size_chart')
            );
        }
    }
    private static function mediaInfoUpdate($p, $r)
    {
        $path = 'assets/storage/';

        // 🔁 old images
        $oldImages = json_decode($p->images, true) ?? [];
        $images = $oldImages;

        // ✅ new images add
        if ($r->hasFile('images')) {
            foreach ($r->file('images') as $img) {
                if ($img && $img->isValid()) {
                    $name = FileManager::uploadFile(
                        $path . 'product/',
                        300,
                        $img
                    );
                    if ($name) {
                        $images[] = $name;
                    }
                }
            }
        }

        $p->images = json_encode($images);

        // ✅ thumbnail replace
        if ($r->hasFile('image')) {
            $p->thumbnail = FileManager::updateFile(
                $path . 'product/thumbnail/',
                $p->thumbnail,
                $r->file('image'),
                $r->alt_text
            );
        }

        // ✅ size chart replace
        if ($r->hasFile('size_chart')) {
            $p->size_chart = FileManager::updateFile(
                $path . 'product/thumbnail/',
                $p->size_chart,
                $r->file('size_chart')
            );
        }
    }

    private static function seoInfo($p, $r)
    {
        $path = 'assets/storage/';
        $p->meta_title = $r->meta_title;
        $p->meta_description = $r->meta_description;
        $p->meta_image = FileManager::uploadFile($path . 'product/meta/', 300, $r->meta_image);
    }
    private static function seoInfoUpdate($p, $r)
    {
        $path = 'assets/storage/';

        $p->meta_title = $r->meta_title;
        $p->meta_description = $r->meta_description;

        if ($r->hasFile('meta_image')) {
            $p->meta_image = FileManager::updateFile(
                $path . 'product/meta/',
                $p->meta_image,
                $r->meta_image
            );
        }
    }

    // private static function campainProduct($p, $r)
    // {
    //     if ($r->start_day) {
    //         $campaing_detalie = [];
    //         for ($i = 0; $i < count($r->start_day); $i++) {
    //             $campaing_detalie[] = [
    //                 'product_id' => $p->id,
    //                 'start_day' => $r['start_day'][$i],
    //                 'discountCam' => $r['discountCam'][$i],
    //                 'auth_id' => auth('admin')->id(),
    //             ];
    //         }
    //         campaing_detalie::insert($campaing_detalie);
    //     }
    // }
}
