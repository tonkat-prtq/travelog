<?php

namespace Tests\Unit\Repositories;

use App\Article;
use App\Repositories\Article\ArticleRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ArticleRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function 記事の順番を確認する()
    {
        // テストデータの準備
        Article::query()->delete();

        $article[] = factory(Article::class)->create([
            'created_at' => Carbon::yesterday(),
        ]);
        // ->load(['user', 'likes', 'tags', 'photos']);
        $article[] = factory(Article::class)->create([
            'created_at' => Carbon::tomorrow(),
        ]);
        // ->load(['user', 'likes', 'tags', 'photos']);
        $article[] = factory(Article::class)->create([
            'created_at' => Carbon::today(),
        ]);
        // ->load(['user', 'likes', 'tags', 'photos']);

        // 期待データの作成
        $expected = collect($article)->sortByDesc('created_at');

        // テスト対象の処理を実行
        $actual = (new ArticleRepository())->getAllArticle();

        // 確認
        // $this->assertEquals($expected->values()->toArray(), $actual->toArray());
        $this->assertEquals(
            $expected->pluck('id')->toArray(),
            $actual->pluck('id')->toArray(),
        );
    }

    /**
     * @test
     */
    public function 記事データが無い時()
    {
        // テストデータの準備
        Article::query()->delete();

        // 期待データの作成
        $expected = [];

        // テスト対象の処理を実行
        $actual = (new ArticleRepository())->getAllArticle();

        // 確認
        $this->assertEquals($expected, $actual->toArray());
    }
}
