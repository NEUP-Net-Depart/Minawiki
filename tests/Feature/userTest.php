<?php

namespace Tests\Feature;

use App\User;
use Tests\BrowserKitTestCase;
use App\Comment;
use App\CommentMessage;
use App\StarMessage;
use App\Star;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class userTest extends BrowserKitTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testNoSession()
    {
        $this->withSession(['user.id'=>null])
            ->visit('/user')
            ->seePageIs('auth/login?continue=%2Fuser');
    }


    public function testComment()
    {
        $this->withSession(['user.id'=>'1'])
            ->visit('/user/loadMyComments')
            ->see('rere1');
    }
}
