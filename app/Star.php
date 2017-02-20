<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Star extends Model
{
    /**
     * 获取该星星的评论模型。
     */
    public function comment()
    {
        return $this->belongsTo('App\Comment');
    }

    /**
     * 获取该星星的用户模型。
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
