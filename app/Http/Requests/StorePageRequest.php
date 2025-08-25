<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];
        foreach (config('app.locales') as $locale) {
            $rules["{$locale}.title"] = 'required|string|max:255';
            $rules["{$locale}.slug"] = 'required|string|max:255|unique:page_translations,slug';
            $rules["{$locale}.desc"] = 'required|string';
            $rules["{$locale}.keywords"] = 'nullable|string';
        }

        // Get valid type IDs from config
        $validTypeIds = collect(config('pageTypes'))->pluck('id')->toArray();
        $rules['type_id'] = 'required|in:' . implode(',', $validTypeIds);
        $rules['active'] = 'nullable|boolean';

        return $rules;
    }

    public function messages()
    {
        return [
            'required' => __('This field is required.'),
            'string' => __('This field must be a string.'),
            'max' => __('This field must not exceed :max characters.'),
            'unique' => __('This slug has already been taken.'),
            'exists' => __('The selected type is invalid.'),
        ];
    }
}
