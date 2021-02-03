<?php

namespace App\Repositories\Article;

use App\Article;
use App\Photo;

use Illuminate\Support\Facades\Storage;
use Image;

class PhotoRepository
{
    private $article;
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * ユーザーが選択した写真ファイルを、Intervention Imageを使って圧縮後に、s3にアップロードする。
     * そのファイルパスとファイルネームを返す
     *
     * @access public
     * @param object $photo
     *  ユーザーがアップロードしたい写真ファイル1枚の情報
     *
     * @var string $extension
     *  アップロードしたファイルの拡張子
     * @var string $filename
     *  ユーザーがアップロードしたファイルの名前
     * @var string $filepath
     *  アップロードした画像が保存されているファイルパス
     *
     * @return array
     *  photosテーブルのnameカラムとstorage_keyカラムに保存するために必要な情報を返している
     *  @see $filename, $filepath
     *
     * @see http://image.intervention.io/
     */
    public function upload($photo)
    {
        $extension = $photo->getClientOriginalExtension();

        // filenameをランダムで生成し、ユニークなものにする
        $random_str = substr(
            base_convert(hash('sha256', uniqid()), 16, 36),
            0,
            24,
        );
        // 2021年2月3日 21時56分29秒に投稿した場合、timestampには20210203215629が格納される
        $timestamp = date('YmdHis');

        // timestampとランダムな文字列をあわせることでユニークなファイル名を作成する
        $filename = $timestamp . $random_str;

        /**
         * Intervention Imageを使ってリサイズしている
         * widthは800に。アスペクト比は保ち、それに伴ってheightは自動で設定される
         *
         * @var $resize_photo object
         */
        $resize_photo = Image::make($photo)
            ->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->encode($extension);

        /**
         * リサイズ後の画像をS3にアップロードしている
         * $resize_photoをstring型にキャストしないと動かない
         *
         * @see https://laracasts.com/discuss/channels/laravel/image-intervention-with-laravel-53?page=1
         */
        Storage::disk('s3')->put(
            '/' . $filename,
            (string) $resize_photo,
            'public',
        );

        /** @var $filepath string */
        $filepath = Storage::disk('s3')->url($filename);

        return [$filename, $filepath];
    }

    /**
     * 画像をs3にアップロードしたあと、その画像の名前と保存先をデータベースに保存する処理
     *
     * @param string $filename
     * @param string $filepath
     * @param Article $article
     * @see PhotoUploadRepository::upload
     */
    public function store($filename, $filepath, $article)
    {
        $article->photos()->create([
            'name' => $filename,
            'storage_key' => $filepath,
        ]);
    }
}
