<?php

namespace App\Http\Controllers;
use App\Article;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * 全ての記事の中から、キーワードであいまい検索
     * 検索対象は
     *  Article->title,
     *  Article->content,
     *  tags->nameの3つ
     *
     * @param \Illuminate\Http\Request $request
     * @var string $keyword
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function search(Request $request)
    {
        $keyword = $request->input;

        // withでeager loadしている
        $articles = Article::with(['user', 'likes', 'tags', 'photos'])
            // もし検索キーワードはnullでなかったら以下のクエリを発行する
            ->when(!is_null($keyword), function ($query) use ($keyword) {
                $query
                    ->where('content', 'LIKE', "%{$keyword}%")
                    ->orWhere('title', 'LIKE', "%{$keyword}%")
                    ->orWhereHas('tags', function ($query) use ($keyword) {
                        $query->where('name', 'LIKE', "%{$keyword}%");
                    });
            })
            // orderByはgetの後だとCollectionの機能（つまり配列操作での並べ替え）になるので、SQLで実施したほうが処理的には早い
            ->orderBy('created_at', 'desc')
            ->get();
        return view('search.index', ['articles' => $articles]);
    }

    /**
     * 検索した結果、1件でもあればその結果を表示し、なければrootページ(Article#index)にリダイレクトする
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory | \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function index(Request $request)
    {
        if (isset($article)) {
            return view('search.index', ['articles' => $articles]);
        } else {
            return redirect('/');
        }
    }
}
