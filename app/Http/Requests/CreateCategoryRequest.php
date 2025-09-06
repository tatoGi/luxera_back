<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
class CreateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
            'parent_id' => 'nullable|exists:categories,id',
            'active' => 'nullable|boolean',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];

        // Validation rules for translatable attributes
        foreach (config('app.locales') as $locale) {
            $rules["{$locale}.title"] = 'required|max:255';
            $rules["{$locale}.description"] = 'nullable';
        }

        return $rules;
    }
  
}
