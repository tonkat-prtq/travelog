<?php

namespace App\Http\Controllers;

use App\Article;
use App\Http\Requests\ArticleRequest;
use App\Photo;

use App\Repositories\Article\ArticleRepository;
use App\Repositories\Article\ImageUploadRepository;
use App\Tag;
// フォームリクエストの使用
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
// Intervention Imageの呼び出し
use Image;
use Storage;

class ArticleController extends Controller
{
    private $imageUploadRepo;
    private $getAllArticleRepo;

    public function __construct(
        ImageUploadRepository $imageUploadRepo,
        ArticleRepository $getAllArticleRepo,
        ArticleRepository $paginaterRepo
    ) {
        $this->authorizeResource(Article::class, 'article');
        $this->imageUploadRepo = $imageUploadRepo;
        $this->getAllArticleRepo = $getAllArticleRepo;
        $this->paginaterRepo = $paginaterRepo;
    }

    /**
     * Travelog上に投稿されている記事を取得し、表示する
     *
     * @param \App\Http\Requests\ArticleRequest $request
     * @return object $articlePaginate
     */

    public function index(Request $request)
    {
        $articles = $this->getAllArticleRepo->getAllArticle();
        $articlePaginate = $this->paginaterRepo->paginate($request, $articles);

        return view('articles.index', ['articles' => $articlePaginate]);
    }

    public function create()
    {
        /**
         * @var collection $allTagName
         *
         * Tagテーブルから全てのタグ情報を取得し、bladeに変数$allTagNamesとして渡す
         * タグ自動補完に必要
         *
         * @todo 登録済みタグが増えてきたら、取得する数を絞る
         */

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
            foreach ($request->file('files') as $index => $e) {
                // 配列をそのまま受け取って、それぞれの変数に格納するlist
                [$filename, $filepath] = $this->imageUploadRepo->upload(
                    $e['photo'],
                );

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

    /**
     * Undocumented function
     * @param \App\Article $article
     */
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
            'photos' => $articlePhotos,
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
        } else {
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
            foreach ($request->file('files') as $index => $e) {
                [$filename, $filepath] = $this->imageUploadRepo->upload(
                    $e['photo'],
                );

                $article->photos()->create([
                    'name' => $filename,
                    'storage_key' => $filepath,
                ]);
            }
        }

        $article->fill($request->all())->save();

        // タグの編集
        // 記事に紐付いているタグを一旦すべて削除
        $article->tags()->detach();
        // 送られてきたタグの情報を一つずつ取り出す
        $request->tags->each(function ($tagName) use ($article) {
            // 送られてきたタグの名前が、データベースに登録されていなかったら新しく作り、登録されていたらそれを探して変数$tagに入れる
            $tag = Tag::firstOrCreate(['name' => $tagName]);
            // Articleモデルのtagsリレーションで紐付けて登録する
            $article->tags()->attach($tag);
        });

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
