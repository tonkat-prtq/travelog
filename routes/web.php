<?php

Auth::routes();

// rootページにアクセスがあったら、ArticleControllerのIndexアクションの処理が走る
Route::get('/', 'ArticleController@index')
  ->name('articles.index');

Route::resource('/articles', 'ArticleController')
  ->except(['index'])
  ->middleware('auth');

// 上書きして、authミドルウェアを外す
Route::resource('/articles', 'ArticleController')
  ->only(['show']);

Route::prefix('articles')
  ->name('articles.')
  ->group(function() {
    Route::put('/{article}/like', 'ArticleController@like')
      ->name('like')
      ->middleware('auth');
    Route::delete('/{article}/like', 'ArticleController@unlike')
      ->name('unlike')
      ->middleware('auth');
  });

Route::get('/tags/{name}', 'TagController@show')->name('tags.show');

Route::prefix('users')
  ->name('users.')
  ->group(function() {
    Route::get('/{name}','UserController@show')
      ->name('show');
    Route::get('/{name}/likes', 'UserController@likes')
      ->name('likes');
    Route::middleware('auth')
      ->group(function() {
        Route::put('/{name}/follow', 'UserController@follow')
          ->name('follow');
        Route::delete('/{name}/follow', 'UserController@unfollow')
          ->name('unfollow');
      });
  });