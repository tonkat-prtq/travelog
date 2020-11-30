<?php

namespace App\Http\Controllers;

use App\Article;
use App\Http\Requests\ArticleRequest;
use App\Photo;

use App\Repositories\Article\ArticleRepository;
use App\Repositories\Article\PhotoRepository;
use App\Tag;
// フォームリクエストの使用
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
// Intervention Imageの呼び出し
use Image;
use Storage;

class ArticleController extends Controller
{
    private $articleRepo;
    private $photoRepo;

    public function __construct(
        PhotoRepository $photoRepo,
        ArticleRepository $articleRepo
    ) {
        $this->authorizeResource(Article::class, 'article');
        $this->photoRepo = $photoRepo;
        $this->articleRepo = $articleRepo;
    }

    /**
     * Travelog上に投稿されている記事を取得し、表示する
     *
     * @param \App\Http\Requests\ArticleRequest $request
     *
     * @var collection $articles
     *  Articlesテーブルに登録された全レコードがcreated_atの降順に入っている
     * @var LengthAwarePaginator $articlePaginate
     *  $articlesから5件ずつ取得
     *
     * @return Illuminate\View\View
     *  @link /
     */
    public function index(Request $request)
    {
        $articles = $this->articleRepo->getAllArticle();
        $articlePaginate = $this->articleRepo->paginate($request, $articles);
        return view('articles.index', ['articles' => $articlePaginate]);
    }

    /**
     * 記事の新規作成ページ（フォーム）を表示する
     *
     * @var collection $allTagNames
     *  Tagテーブルから全てのタグ情報を取得し、bladeに変数$allTagNamesとして渡す
     *  タグ自動補完に必要
     *
     * @return Illuminate\View\View
     *  @link articles/create
     * @todo 登録済みタグが増えてきたら、取得する数を絞る
     */
    public function create()
    {
        $allTagNames = $this->articleRepo->getAllTagNames();
        return view('articles.create', [
            'allTagNames' => $allTagNames,
        ]);
    }

    // $request, $articleの前のArticleRequestやArticleは引数の型宣言
    // それに加えDI(Dependency Injection)を行い、Articleクラスのインスタンスを自動生成し、メソッド内で使えるようにしている

    /**
     * Articleをデータベースに登録する
     * 画像がアップロードされていたら画像も登録する
     *
     * @param \App\Http\Requests\ArticleRequest $request
     * @param \App\Article $article
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function store(ArticleRequest $request, Article $article)
    {
        $article->fill($request->all());

        // 注意:ここの$request->user()はリレーションメソッドの呼び出しではなく、Requestクラスのインスタンス(ここでは$request)が持っているメソッドで、認証済みユーザーのインスタンスを返している
        $article->user_id = $request->user()->id;

        $article->save();

        // 画像アップロード
        if ($request->file('files')) {
            foreach ($request->file('files') as $index => $e) {
                // 配列をそのまま受け取って、それぞれの変数に格納するlist
                [$filename, $filepath] = $this->photoRepo->upload($e['photo']);

                $this->photoRepo->store($filename, $filepath, $article);
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
     * 記事の編集
     *
     * @param \App\Article $article
     *
     * @var object $tagNames
     *  記事に紐付いているタグを格納
     * @var object $allTagNames
     *  登録されている全てのタグを取得し格納、自動補完に使う
     * @var object $articlePhotos
     *  記事に紐付いている画像を格納
     *
     * @return Illuminate\View\View
     *  @link /articles/{article}/edit
     */
    public function edit(Article $article)
    {
        $tagNames = $article->tags->map(function ($tag) {
            return ['text' => $tag->name];
        });

        $allTagNames = $this->articleRepo->getAllTagNames();

        $articlePhotos = $article->photos;

        return view('articles.edit', [
            'article' => $article,
            'tagNames' => $tagNames,
            'allTagNames' => $allTagNames,
            'photos' => $articlePhotos,
        ]);
    }

    /**
     * 記事を更新し、データベースの値を書き換える処理
     * 画像の削除・追加も可能となっている
     *
     * @param \App\Http\Requests\ArticleRequest $request
     * @param \App\Article $article
     *
     * @var array $stored_photos
     *  formのhidden_fieldに持たせてたstored_photo_idsを格納
     *  もともと記事に紐付いていた画像で、削除をしないもののidが配列で入っている
     * @var boolean $photo_delete_judge
     *  もともと記事に紐付いていた画像が、削除されているかどうかを判断
     *  @see $photo->id, $stored_photos
     *
     * @return Illuminate\Http\RedirectResponse
     */
    public function update(ArticleRequest $request, Article $article)
    {
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
                [$filename, $filepath] = $this->photoRepo->upload($e['photo']);

                $this->photoRepo->store($filename, $filepath, $article);
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

    /**
     * 記事を削除する
     * 記事が削除されると、紐付いていた画像も一緒に削除される
     *
     * @param \App\Article $article
     * @return Illuminate\Http\RedirectResponse
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('articles.index');
    }

    /**
     * 記事の詳細ページに遷移する
     * @param \App\Article $article
     * @return Illuminate\Http\RedirectResponse
     */
    public function show(Article $article)
    {
        return view('articles.show', ['article' => $article]);
    }

    /**
     * 記事をいいねする
     * 一度detachしてからattachすることで、二重登録を防止している
     *
     * @param \App\Http\Requests\ArticleRequest $request
     * @param \App\Article $article
     *
     * @return array
     */
    public function like(Request $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);
        $article->likes()->attach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }

    /**
     * 記事のいいねを解除する
     * @param \App\Http\Requests\ArticleRequest $request
     * @param \App\Article $article
     *
     * @return array
     */
    public function unlike(Request $request, Article $article)
    {
        $article->likes()->detach($request->user()->id);

        return [
            'id' => $article->id,
            'countLikes' => $article->count_likes,
        ];
    }
}
