<?php

namespace App\Repositries;

use App\Article;
use Illuminate\Support\Facades\Storage;
use Image;

class ImageUploadRepository
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
     *   ユーザーがアップロードしたい写真ファイル1枚の情報
     *
     * @return string $filename
     *   ユーザーがアップロードしたファイルの名前
     * @return string $filepath
     *   アップロードした画像が保存されているファイルパス
     *
     * @see http://image.intervention.io/
     */
    public function upload($photo)
    {
        $extension = $photo->getClientOriginalExtension();
        $filename = $photo->getClientOriginalName();

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
}
