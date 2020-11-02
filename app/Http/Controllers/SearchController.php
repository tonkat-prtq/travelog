<?php

namespace App\Http\Controllers;
use App\Article;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->input;

        // withでeager loadしている
        $articles = Article::with(['user', 'likes', 'tags', 'photos'])
            // もし検索キーワードはnullでなかったら以下のクエリを発行する
            ->when(!is_null($keyword), function($query) use ($keyword) {
                $query
                    ->where('content', 'LIKE', "%{$keyword}%")
                    ->orWhere('title', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('tags',function($query) use($keyword) {
                        $query->where('name', 'LIKE', "%{$keyword}%");
                    });
            })
            // orderByはgetの後だとCollectionの機能（つまり配列操作での並べ替え）になるので、SQLで実施したほうが処理的には早い
            ->orderBy('created_at', 'desc')
            ->get();
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
