<?php

namespace App\Http\Controllers;

use App\Tag;

use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * $nameで受け取った名前のタグがついている記事のみ表示する
     *
     * @param string $name
     * @var collection $tag
     *  $nameと一致するタグのモデルを取得する
     *
     * @return \Illuminate\View\View
     */
    public function show(string $name)
    {
        $tag = Tag::where('name', $name)->first();
        return view('tags.show', ['tag' => $tag]);
    }
}
