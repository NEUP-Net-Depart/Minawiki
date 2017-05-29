<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentMessage extends Model
{
    /**
     * 获取该回复通知的用户模型。
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comment() {
        return $this -> belongsTo('App\Comment');
    }
}
