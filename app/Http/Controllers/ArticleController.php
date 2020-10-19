<?php

namespace App\Http\Controllers;

use App\Article;
use App\Photo;
use App\Tag;

// フォームリクエストの使用
use App\Http\Requests\ArticleRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Article::class, 'article');
    }
    
    public function index()
    {
        $articles = Article::all()->sortByDesc('created_at');

        return view('articles.index', ['articles' => $articles]);
    }

    public function create()
    {
        $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        return view('articles.create', [
            'allTagNames' => $allTagNames,
        ]);
    }

    // $request, $articleの前のArticleRequestやArticleは引数の型宣言
    // それに加えDI(Dependency Injection)を行い、Articleクラスのインスタンスを自動生成し、メソッド内で使えるようにしている
    public function store(ArticleRequest $request, Article $article)
    {
        $article->fill($request->all());

        // dd($request, $request->file('files'));
        // 注意:ここの$request->user()はリレーションメソッドの呼び出しではなく、Requestクラスのインスタンス(ここでは$request)が持っているメソッドで、認証済みユーザーのインスタンスを返している
        $article->user_id = $request->user()->id;

        $article->save();
        
        // 画像アップロード
        foreach ($request->file('files') as $index=>$e) {
            $storage_key = $e['photo']->store('uploads', 'public');
            $filename = $e['photo']->getClientOriginalName();
            $article->photos()->create([
                'name' => $filename,
                'storage_key' => $storage_key
                ]);
        }

        // タグの追加
        // $requestからタグの情報を一つずつ取り出す
        // 無名関数の中で$articleを使うため、use ($article)とする
        $request->tags->each(function ($tagName) use ($article) {
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            $article->tags()->attach($tag);
        });

        return redirect()->route('articles.index');
    }

    public function edit(Article $article)
    {
        $tagNames = $article->tags->map(function ($tag) {
            return ['text' => $tag->name];
        });

        $allTagNames = Tag::all()->map(function ($tag) {
            return ['text' => $tag->name];
        });

        return view('articles.edit', [
            'article' => $article,
            'tagNames' => $tagNames,
            'allTagNames' => $allTagNames,
        ]);
    }

    public function update(ArticleRequest $request, Article $article)
    {
        $article->fill($request->all())->save();
        return redirect()->route('articles.index');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index');
    }

    public function show(Article $article)
    {
        return view('articles.show', ['article' => $article]);
    }

    public function like(Request $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);
        $article->likes()->attach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }

    public function unlike(Request $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }

}