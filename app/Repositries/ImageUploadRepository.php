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

    public function upload($photo)
    {
        $extension = $photo->getClientOriginalExtension();
        $filename = $photo->getClientOriginalName();
        $resize_photo = Image::make($photo)
            ->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->encode($extension);
        Storage::disk('s3')->put(
            '/' . $filename,
            (string) $resize_photo,
            'public',
        );
        $filepath = Storage::disk('s3')->url($filename);

        return [$filename, $filepath];
    }
}
