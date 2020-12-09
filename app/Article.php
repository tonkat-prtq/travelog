<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// この記述がなくても、下のuser()メソッド内でのbelongsToで関連付けはできる
// ただし、戻り値の型宣言をするのに必要(PHP7の機能)
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    protected $fillable = ['title', 'content', 'start_date', 'end_date'];

    protected $dates = ['start_date', 'end_date'];

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

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany('App\User', 'likes')->withTimestamps();
    }

    public function isLikedBy(?User $user): bool
    {
        /**
         * $thisでArticleモデルを取得
         * $this->likesでArticleに紐付いたUserモデルが返る
         * whereメソッドの第一引数にキー名、第二引数に値を渡すと、その条件に一致するコレクションが返る
         * この記事に紐付いたUserモデル（いいねしたユーザー）の中に、引数として渡された$userがいるかどうかを調べている
         */
        return $user
            ? (bool) $this->likes->where('id', $user->id)->count()
            : false;
    }

    public function getCountLikesAttribute(): int
    {
        // $this->likesでこの記事にいいねをした全ユーザーモデルが返る
        // countでその数を数えている
        return $this->likes->count();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany('App\Tag')->withTimestamps();
    }
}
