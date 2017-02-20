<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $guarded = ['geetest_challenge', 'geetest_validate', 'geetest_seccode'];

    /**
     * 获取该评论的用户模型。
     */
    public function comment()
    {
        return $this->hasMany('App\Comment');
    }
}
