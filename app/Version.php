<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    /**
     * 需要被转换成日期的属性。
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    public function page()
    {
        return $this->belongsTo('App\Page');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
