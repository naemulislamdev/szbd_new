<?php

namespace App\Http\Controllers\api\v1;
use App\Models\Product;
use App\CPU\Helpers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LandingPagesController extends Controller
{

  public function landing_view(){
        $landPages=DB::table('landing_pages')->where(['status' => 1])->get();
        return response()->json([
            'landPages' => $landPages,
        ], 200);
}
  public function landpagesdeal($landing_slug){
      $landing_page = DB::table('landing_pages')->where(['slug' => $landing_slug])->where('status',1)->first();
      $landing_page_pro = DB::table('landing_pages_products')->where('landing_id',$landing_page->id)->pluck('product_id')->toArray();
      $product=Helpers::product_data_formatting(Product::with(['rating'])->whereIn('id', $landing_page_pro)->orderBy('id', 'DESC')->get(), true);
      return response()->json([
          'LandingPages'=>$landing_page,
          'Product'=>$product,
      ],200);
  }


}
