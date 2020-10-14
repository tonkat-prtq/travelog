<?php

Auth::routes();

// rootページにアクセスがあったら、ArticleControllerのIndexアクションの処理が走る
Route::get('/', 'ArticleController@index')->name('articles.index');

Route::resource('/articles', 'ArticleController')->except(['index']);