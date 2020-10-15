<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\BelongsTo;

class Photo extends Model
{
    protected $fillable = [
        'name',
        'storage_key',
        'article_id',
    ];

    public function article() :BelongsTo
    {
        return $this->belongsTo('App\Article');
    }
}
