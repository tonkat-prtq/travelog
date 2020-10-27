<?php

namespace Tests\Feature;

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
}
