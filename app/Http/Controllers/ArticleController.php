<?php

namespace App\Http\Controllers;

use App\Article;

// フォームリクエストの使用
use App\Http\Requests\ArticleRequest;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::all()->sortByDesc('created_at');

        return view('articles.index', ['articles' => $articles]);
    }

    public function create()
    {
        return view('articles.create');
    }

    // $request, $articleの前のArticleRequestやArticleは引数の型宣言
    // それに加えDI(Dependency Injection)を行い、Articleクラスのインスタンスを自動生成し、メソッド内で使えるようにしている
    public function store(ArticleRequest $request, Article $article)
    {
        $article->fill($request->all());

        // 注意:ここの$request->user()はリレーションメソッドの呼び出しではなく、Requestクラスのインスタンス(ここでは$request)が持っているメソッドで、認証済みユーザーのインスタンスを返している
        $article->user_id = $request->user()->id;
        $article->save();
        return redirect()->route('articles.index');
    }

    public function edit(Article $article)
    {
        return view('articles.edit', ['article' => $article]);
    }

    public function update(ArticleRequest $request, Article $article)
    {
        $article->fill($request->all())->save();
        return redirect()->route('articles.index');
    }

}