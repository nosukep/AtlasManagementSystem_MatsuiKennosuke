<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubCategoryFormRequest extends FormRequest
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
            'main_category_id' => 'required',
            'sub_category_name' => 'required|string|max:100|unique:sub_categories,sub_category'
        ];
    }

    public function messages(){
        return [
            'main_category_id.required' => 'メインカテゴリーを選択してください。',
            'sub_category_name.required' => 'サブカテゴリーを記入してください。',
            'sub_category_name.max' => 'サブカテゴリーは100字以内で記入してください。',
            'sub_category_name.unique' => 'このサブカテゴリーは登録済みです。'
        ];
    }
}
