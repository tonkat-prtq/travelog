<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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

    // バリデーションルールを設定
    public function rules()
    {
        return [
            'title' => 'required|max:50',
            'content' => 'required|max:10000',
        ];
    }

    // バリデーションエラーメッセージに表示される項目名を設定
    public function attributes()
    {
        return [
            'title' => 'タイトル',
            'content' => '本文'
        ];
    }
}
