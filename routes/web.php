<?php

Auth::routes();

// rootページにアクセスがあったら、ArticleControllerのIndexアクションの処理が走る
Route::get('/', 'ArticleController@index');