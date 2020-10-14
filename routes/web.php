<?php

Auth::routes();

// rootページにアクセスがあったら、ArticleControllerのIndexアクションの処理が走る
Route::get('/', 'ArticleController@index')->name('articles.index');

Route::resource('/articles', 'ArticleController')->except(['index'])->middleware('auth');

// 上書きして、authミドルウェアを外す
Route::resource('/articles', 'ArticleController')->only(['show']);