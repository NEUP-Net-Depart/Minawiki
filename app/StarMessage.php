<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StarMessage extends Model
{
    /**
     * 获取该星星通知的用户模型。
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
