<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function facebookFeed()
    {
        $products = Product::where("status", 1)->get(); // Fetch all products

        $xml = new \SimpleXMLElement('<rss/>');
        $xml->addAttribute('version', '2.0');
        $xml->addAttribute('xmlns:g', 'http://base.google.com/ns/1.0');

        $channel = $xml->addChild('channel');
        $channel->addChild('title', 'ShoppingZone BD');
        $channel->addChild('link', 'https://www.shoppingzonebd.com.bd');
        $channel->addChild('description', 'ShoppingZone BD Product Feed');

           foreach ($products as $product) {
            $item = $channel->addChild('item');
            $item->addChild('g:id', $product->id, 'http://base.google.com/ns/1.0');
            $item->addChild('g:title', htmlspecialchars($product->name), 'http://base.google.com/ns/1.0');
            $item->addChild('g:description', htmlspecialchars(strip_tags($product->details)), 'http://base.google.com/ns/1.0');
            $item->addChild('g:link', url('/product/' . $product->slug), 'http://base.google.com/ns/1.0');
            $item->addChild('g:image_link', asset('storage/product/thumbnail/' . $product->thumbnail), 'http://base.google.com/ns/1.0');
            $item->addChild('g:availability', $product->current_stock > 0 ? 'in stock' : 'out of stock', 'http://base.google.com/ns/1.0');
            $item->addChild('g:price', \App\CPU\Helpers::currency_converter($product->unit_price) . ' BDT', 'http://base.google.com/ns/1.0');
            $item->addChild('g:brand', 'ShoppingZone', 'http://base.google.com/ns/1.0');
            $item->addChild('g:quantity', $product->current_stock ?? 0, 'http://base.google.com/ns/1.0');
            $item->addChild('g:condition', 'new', 'http://base.google.com/ns/1.0');
        }

        return Response::make($xml->asXML(), 200)
            ->header('Content-Type', 'application/xml');
    }
}
