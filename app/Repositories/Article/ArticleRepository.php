<?php

namespace App\Repositories\Article;

use App\Article;
use App\Http\Requests\ArticleRequest;
use App\Tag;

use Illuminate\Http\Request;

use Illuminate\Pagination\LengthAwarePaginator;

class ArticleRepository
{
    /**
     * 投稿されたArticle全件と、それに紐付いているuser, likes, tags, photosを取得し
     * Articleのcreated_atの降順で並び替えたものを返す
     *
     * @return collection $articles
     */
    public function getAllArticle()
    {
        $articles = Article::query()
            ->with(['user', 'likes', 'tags', 'photos'])
            ->orderBy('created_at', 'desc')
            ->get();

        return $articles;
    }

    /**
     * Article全件を取得し、それを5件ずつページネーションさせる
     *
     * @param \App\Http\Requests\ArticleRequest $request
     * @param collection $articles
     *
     * @return Illuminate\Pagination\LengthAwarePaginator $articlePaginator
     */
    public function paginate(Request $request, object $articles)
    {
        $articlePaginator = new LengthAwarePaginator(
            $articles->forPage($request->page, 5),
            $articles->count(),
            5,
            null,
            ['path' => $request->url()],
        );

        return $articlePaginator;
    }

    /**
     * データベースに登録されているTagのnameを全件取得し返す
     * 入力候補の表示に必要
     *
     * @return collection $allTagNames
     */
    public function getAllTagNames()
    {
        $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        return $allTagNames;
    }
}
