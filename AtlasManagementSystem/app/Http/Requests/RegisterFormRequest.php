<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterFormRequest extends FormRequest
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
            'over_name' => 'required|string|max:10',
            'under_name' => 'required|string|max:10',
            'over_name_kana' => 'required|string|max:30|regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u',
            'under_name_kana' => 'required|string|max:30|regex:/^[ア-ン゛゜ァ-ォャ-ョー]+$/u',
            'mail_address' => 'required|string|email|max:100|unique:users,mail_address,'.$this->id.',id',// usersテーブルで同一の値がないか
            'sex' => 'required',
            'role' => 'required',
            'old_year' => 'required',
            'old_month' => 'required',
            'old_day' => 'required',
            'password' => 'required|string|min:8|max:30|confirmed'
        ];
    }

    /**
     *  バリデーション項目名定義
     * @return array
     */
    public function attributes()
    {
        return [
            'over_name' => '姓',
            'under_name' => '名',
            'over_name_kana' => 'セイ',
            'under_name_kana' => 'メイ',
            'mail_address' => 'メールアドレス',
            'sex' => '性別',
            'role' => '権限',
            'old_year' => '生年月日',
            'old_month' => '生年月日',
            'old_day' => '生年月日',
            'password' => 'パスワード'
        ];
    }

     /**
     * バリデーションメッセージ
     * @return array
     */
    public function messages()
    {
        return [
            'over_name.required' => ':attributeは必須です。',
            'over_name.string' => ':attributeは文字列で入力してください。',
            'over_name.max' => ':attributeは10字以下で入力してください。',
            'under_name.required' => ':attributeは必須です。',
            'under_name.string' => ':attributeは文字列で入力してください。',
            'under_name.max' => ':attributeは10字以下で入力してください。',
            'over_name_kana.required' => ':attributeは必須です。',
            'over_name_kana.string' => ':attributeは文字列で入力してください。',
            'over_name_kana.regex' => ':attributeはカタカナで入力してください。',
            'over_name_kana.max' => ':attributeは30字以下で入力してください。',
            'under_name_kana.required' => ':attributeは必須です。',
            'under_name_kana.string' => ':attributeは文字列で入力してください。',
            'under_name_kana.regex' => ':attributeはカタカナで入力してください。',
            'under_name_kana.max' => ':attributeは30字以下で入力してください。',
            'mail_address.required' => ':attributeは必須です。',
            'mail_address.max' => ':attributeは100字以下で入力してください。',
            'mail_address.email' => ':attributeはメールアドレスの形式で入力してください。',
            'mail_address.unique' => 'この:attributeはすでに登録されています。',
            'sex.required' => ':attributeは必須です。',
            'role.required' => ':attributeは必須です。',
            'old_year.required' => ':attributeは必須です。',
            'old_month.required' => ':attributeは必須です。',
            'old_dey.required' => ':attributeは必須です。',
            'sex.required' => ':attributeは必須です。',
            'role.required' => ':attributeは必須です。',
            'password.required' => ':attributeは必須です。',
            'password.min' => ':attributeは8文字以上で入力してください。',
            'password.max' => ':attributeは30文字以上で入力してください。',
            'password.confirmed' => ':attributeが一致しません。',
        ];
    }
}
