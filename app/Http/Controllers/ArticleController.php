<?php

namespace App\Http\Controllers;

use App\Article;
use App\Photo;
use App\Tag;

// Intervention Imageの呼び出し
use Image;
use Storage;

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
        $articles = Article::all()->sortByDesc('created_at')
            ->load([
                'user',
                'likes',
                'tags',
                'photos'
            ]);

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
        if ($request->file('files')) {
            foreach ($request->file('files') as $index=>$e) {
                // $storage_key = $e['photo']->store('uploads', 'public');
                $photo = $e['photo'];
                $filename = $photo->getClientOriginalName();

                $filepath = Storage::disk('s3')->put('/', $photo, 'public');
                
                // dd($photo, $filepath);
                $article->photos()->create([
                    'name' => $filename,
                    'storage_key' => $filepath,
                    ]);
            }
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

        $articlePhotos = $article->photos;

        return view('articles.edit', [
            'article' => $article,
            'tagNames' => $tagNames,
            'allTagNames' => $allTagNames,
            'photos' => $articlePhotos
        ]);
    }

    public function update(ArticleRequest $request, Article $article)
    {
        // 変数$stored_photosに、formのhidden_fieldに持たせてたstored_photo_idsを格納
        // もともと記事に紐付いていた画像で、削除をしないもののidが配列で入っている
        $stored_photos = $request->stored_photo_ids;


        // 記事に紐付いていた画像があり、それをすべて削除した場合、$stored_photoには何も入らない。その時の処理を先にする
        // もし$stored_photosが空、かつ、元々の記事に紐付いていた画像があったなら
        if (empty($stored_photos) && $article->photos) {
            // 編集中の記事に紐付いた画像をすべて削除する(deleteとすることで複数削除)
            Photo::where('article_id', $article->id)->delete();
        }
        else {
            // もともと記事に紐付いていた複数の画像を取り出して変数$photoに格納
            foreach ($article->photos as $photo) {
                // in_arrayで、取り出した画像が削除されているかどうかを判断する
                $photo_delete_judge = in_array($photo->id, $stored_photos);
                // もし上の結果が偽ならば、それは削除されているので
                if (!$photo_delete_judge) {
                    // そのidの画像を削除する
                    Photo::destroy($photo->id);
                }
            }
        }

        if ($request->file('files')) {
            foreach ($request->file('files') as $index=>$e) {
                $storage_key = $e['photo']->store('uploads', 'public');
                $filename = $e['photo']->getClientOriginalName();
                $article->photos()->create([
                    'name' => $filename,
                    'storage_key' => $storage_key
                    ]);
            }
        }

        
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