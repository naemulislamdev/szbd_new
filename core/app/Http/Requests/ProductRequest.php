<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $discount_type = 'nullable';
        if(request()->discount > 0){
            $discount_type = 'required|in:flat,percent';
        }
        $images = 'nullable|max:3072';
        $image = 'nullable|max:20480';
        $code = 'required|min:3|max:30|unique:products,code';
        if (request()->route()->id) {
            $images = 'nullable';
            $image = 'nullable';
            $pId = request()->route()->id;
            $code = 'required|min:3|max:30|unique:products,code,' . $pId;
        }
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string',
            'category_id' => 'required',
            'sub_category_id' => 'nullable',
            'child_category_id' => 'nullable',
            'code' => $code,
            'brand_id' => 'required',
            'unit' => 'required',
            'images' => $images,
            'image' => $image,
            'tax' => 'required|min:0',
            'unit_price' => 'required|numeric|min:1',
            'purchase_price' => 'required|numeric|min:1',
            'discount' => 'required|min:0',
            'discount_type' => $discount_type,
            'shipping_cost' => 'required|numeric|min:0',
            'minimum_order_qty' => 'required|numeric|min:1',
            'meta_title' => 'nullable',
            'meta_description' => 'nullable',
        ];
    }
    public function messages()
    {
        return [
            'images.required' => 'Product images is required!',
            'image.required' => 'Product thumbnail is required!',
            'category_id.required' => 'category  is required!',
            'brand_id.required' => 'brand  is required!',
            'unit.required' => 'Unit  is required!',
            'code.min' => 'The code must be minimum 4 digits!',
            'minimum_order_qty.required' => 'The minimum order quantity is required!',
            'minimum_order_qty.min' => 'The minimum order quantity must be positive!',
        ];
    }
}
