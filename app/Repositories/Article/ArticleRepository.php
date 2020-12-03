<?php

namespace App\Repositories\Article;

use App\Article;
use App\Http\Requests\ArticleRequest;
use App\Tag;

use Illuminate\Http\Request;

use Illuminate\Pagination\LengthAwarePaginator;

class ArticleRepository
{
    public function getAllArticle()
    {
        // $articles = Article::all()
        //     ->sortByDesc('created_at')
        //     ->load(['user', 'likes', 'tags', 'photos']);

        $articles = Article::query()
            ->with(['user', 'likes', 'tags', 'photos'])
            ->orderBy('created_at', 'desc')
            ->get();

        return $articles;
    }

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

    public function getAllTagNames()
    {
        $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        return $allTagNames;
    }
}
