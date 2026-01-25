<?php

namespace App\Services;

use App\Models\Product;
use App\CPU\BackEndHelper;
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
            $p->colors = null;
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
                foreach ($combination as $k => $item) {
                    if ($k > 0) {
                        $str .= '-' . str_replace(' ', '', $item);
                    } else {
                        if ($r->has('colors_active') && $r->has('colors') && count($r->colors) > 0) {
                            $color_name = Color::where('code', $item)->first()->name;
                            //$str .= $color_name;
                        } else {
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }
                $item = [];
                $item['type'] = $str;
                $item['price'] = abs($r['price_' . str_replace('.', '_', $str)]);
                $item['sku'] = $r['sku_' . str_replace('.', '_', $str)];
                $item['qty'] = abs($r['qty_' . str_replace('.', '_', $str)]);
                array_push($variations, $item);
                $stock_count += $item['qty'];
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
        dd($stock_count);

        $p->color_variant = json_encode($colorVariations);
        $p->variation = json_encode($variations);
        $p->attributes = json_encode($r->choice_attributes);
        $p->current_stock = abs($stock_count);
        $p->minimum_order_qty = $r->minimum_order_qty;
    }

    private static function mediaInfo($p, $r)
    {
        $path = 'assets/storage/';
        if ($r->file('images')) {
            foreach ($r->file('images') as $img) {
                $images[] = FileManager::uploadFile($path . 'product/', 300, $img);
            }
            $p->images = json_encode($images);
        }

        $p->thumbnail = FileManager::uploadFile($path . 'product/thumbnail/', 300, $r->image, $r->alt_text);
        $p->size_chart = FileManager::uploadFile($path . 'product/thumbnail/', 300, $r->size_chart);
    }

    private static function seoInfo($p, $r)
    {
        $path = 'assets/storage/';
        $p->meta_title = $r->meta_title;
        $p->meta_description = $r->meta_description;
        $p->meta_image = FileManager::uploadFile($path . 'product/meta/', 300, $r->meta_image);
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
