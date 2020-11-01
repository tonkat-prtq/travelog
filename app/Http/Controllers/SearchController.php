<?php

namespace App\Http\Controllers;
use App\Article;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->input;
        $query = Article::query();

        if (!empty($keyword)) {
            $query->where('content', 'LIKE', "%{$keyword}%")
            ->orwhere('title', 'LIKE', "%{$keyword}%");
        }

        $articles = $query->get();
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
