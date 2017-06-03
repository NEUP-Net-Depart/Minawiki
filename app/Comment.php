<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    /**
     * 需要被转换成日期的属性。
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * 获取该评论的用户模型。
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * 获取该回复的原始评论模型。
     */
    public function replyTarget()
    {
        return $this->belongsTo('App\Comment', 'reply_id');
    }

    /**
     * 获取该评论的所有回复模型。
     */
    public function replies()
    {
        return $this->hasMany('App\Comment', 'reply_id');
    }

    /**
     * 获取该评论的所有星星模型。
     */
    public function stars()
    {
        return $this->hasMany('App\Star');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 获得该评论的页面模型
     */
    public function page()
    {
        return $this -> belongsTo('App\Page');
    }


}
