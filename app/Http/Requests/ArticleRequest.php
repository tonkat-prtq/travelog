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
            'title' => 'required|string|max:50',
            'content' => 'required|string|max:10000',
            // 下、バリデーションかけるのはどちらか片方で良い
            'start_date' => 'required|date',
            'end_date' => 'required|after_or_equal:start_date',

            // 画像ファイル
            'files.*.photo' => 'image|mimes:jpeg,bmp,png', # 追記

            // タグ
            'tags' => 'json|regex:/^(?!.*\s).+$/u|regex:/^(?!.*\/).*$/u',
        ];
    }

    // バリデーションエラーメッセージに表示される項目名を設定
    public function attributes()
    {
        return [
            'title' => 'タイトル',
            'content' => '本文',
            'start_date' => '開始日',
            'end_date' => '終了日',
            'tag' => 'タグ',
        ];
    }

    // エラーメッセージのカスタマイズ
    public function messages()
    {
        return [
            // attribute名 . 引っかかったバリデーションルール => 出したいメッセージ
            'end_date.after_or_equal' => '開始日または終了日を確認してください',
        ];
    }

    public function passedValidation()
    {
        // $this（記事オブジェクト）のtags（タグ）をJSON形式から連想配列に変換、それをcollectでコレクションに変換
        $this->tags = collect(json_decode($this->tags))
            // sliceで、タグの許容数を設定（ここでいう第二引数の5）
            ->slice(0, 5)
            // 残ったコレクションに変換されたタグ情報を順番に1つずつ処理し、新しいコレクションを作成する
            // $requestTagにはタグの情報が入っている
            // その中のtext情報だけ取り出している
            ->map(function ($requestTag) {
                return $requestTag->text;
            });
    }
}
