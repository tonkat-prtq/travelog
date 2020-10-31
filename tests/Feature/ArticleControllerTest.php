<?php

namespace Tests\Feature;

use App\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    // データベースのリセットをする
    use RefreshDatabase;

    // phpユニットではテストのメソッド名の先頭にtestをつける必要がある
    public function testIndex()
    {
        // 引数に指定されたURLへGETリクエストを行い、そのレスポンスを返す
        $response = $this->get(route('articles.index'));

        // 正常レスポンスである200であれば合格
        $response->assertStatus(200)
            // また、$responseで使用されているビューが、articles.indexを使用しているかどうかを確認する
            ->assertViewIs('articles.index');
    }

    public function testGuestCreate()
    {
        // 特にログインするための処理を行っていないため、未ログイン状態で記事投稿画面にアクセスしている
        $response = $this->get(route('articles.create'));

        // 引数として渡されたURLへリダイレクトされたかどうかをテストしている
        $response->assertRedirect(route('login'));
    }

    public function testAuthCreate()
    {
        // まず、テストに必要なUserモデルを「準備」
        // factory関数を使うと、テストに必要なモデルのインスタンスを、ファクトリを利用して生成できる
        // RailsのFactoryBotと同じようなもの
        // userモデルのファクトリはLaravelインストール時に存在しているのでそれを利用している
        $user = factory(User::class)->create();

        // ログインして記事投稿画面にアクセスすることを「実行」
        // actingAsで、引数として渡したUserモデルでログインした状態を作り出せる
        $response = $this->actingAs($user)
            ->get(route('articles.create'));

        // レスポンスを「検証」
        $response->assertStatus(200)
            ->assertViewIs('articles.create');
    }
}
