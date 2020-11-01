<?php

namespace App\Http\Controllers;
use App\Article;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->input;
        $query = Article::query()
            ->when(isset($keyword), function($query) use ($keyword){
                $query
                    ->where('content', 'LIKE', "%{$keyword}%")
                    ->orwhere('title', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('tags',function($query) use($keyword) {
                        $query->where('name', 'LIKE', "%{$keyword}%");
                    });
            });

        $articles = $query->get()->sortByDesc('created_at')
            ->load([
                'user',
                'likes',
                'tags',
                'photos'
            ]);
        return view('search.index', ['articles' => $articles]);
    }

    public function index(Request $request)
    {
        if (isset($article)) {
            return view('search.index', ['articles' => $articles]);
        } else {
            return redirect('/');
        }
    }
}
