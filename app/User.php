<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $guarded = ['geetest_challenge', 'geetest_validate', 'geetest_seccode'];

    /**
     * 获取该用户的评论模型。
     */
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    /**
     * 获取该用户的回复消息模型。
     */
    public function comment_messages()
    {
        return $this->hasMany('App\CommentMessage');
    }

    /**
     * 获取该用户的评论模型。
     */
    public function stars()
    {
        return $this->hasMany('App\Star');
    }

    /**
     * 获取该用户的星星消息模型。
     */
    public function star_messages()
    {
        return $this->hasMany('App\StarMessage');
    }
}
