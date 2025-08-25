<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Assuming anyone can make a product request; you may adjust this as needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            // Base attributes
            'product_identify_id' => 'nullable|string|unique:products,product_identify_id,' . $this->route('product'),
            'category_id' => 'nullable|exists:categories,id',
            'size' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'active' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*' => 'file|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    
        // Validation rules for translatable attributes
        foreach (config('app.locales') as $locale) {
            $rules["{$locale}.title"] = 'required|max:255';
            $rules["{$locale}.slug"] = 'required|max:255';
            $rules["{$locale}.description"] = 'nullable|string|max:2000';
            $rules["{$locale}.brand"] = 'nullable|string|max:255';
            $rules["{$locale}.location"] = 'nullable|string|max:255';
            $rules["{$locale}.color"] = 'nullable|string|max:100';
        }
    
        return $rules;
    }
}
