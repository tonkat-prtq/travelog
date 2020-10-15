<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// この記述がなくても、下のuser()メソッド内でのbelongsToで関連付けはできる
// ただし、戻り値の型宣言をするのに必要(PHP7の機能)
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    protected $fillable = [
        'title',
        'content',
        'start_date',
        'end_date',
    ];

    // BelongsToという型で返ってくる宣言(それ以外だとTypeError発生)
    public function user(): BelongsTo 
    {
        // $thisはArticleクラスのインスタンス自身
        return $this->belongsTo('App\User'); 
    }

    public function photos(): HasMany
    {
        return $this->hasMany('App\Photo');
    }

}
