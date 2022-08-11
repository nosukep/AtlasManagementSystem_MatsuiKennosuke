<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MainCategoryFormRequest extends FormRequest
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
        return [
            'main_category_name' => 'required|string|max:100|unique:main_categories,main_category'
        ];
    }

    public function messages(){
        return [
            'main_category_name.required' => 'メインカテゴリーを記入してください。',
            'main_category_name.max' => 'メインカテゴリーは100字以内で記入してください。',
            'main_category_name.unique' => 'このメインカテゴリーは登録済みです。'
        ];
    }
}
