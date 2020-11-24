<?php

namespace App\Repositories\Article;

use App\Article;
use App\Photo;

class PhotoRepository
{
    private $article;
    public function __construct(Article $article)
    {
        $this->article = $article;
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
