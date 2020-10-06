<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{

    public function user(): BelongsTo // BelongsToという型で返ってくる宣言(それ以外だとTypeError発生)
    {
        return $this->belongsTo('App\User'); // $thisはArticleクラスのインスタンス自身
    }

}
