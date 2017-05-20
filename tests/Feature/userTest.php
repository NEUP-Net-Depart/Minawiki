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
    public function testDate()
    {
        //make user
        $user=new User();
        $user->id=9;
        $user->save();
        //make comment
        $comment = new Comment();
        $comment->id=15;
        $comment->user_id = 9;
        $comment->content = "cirno";
        $comment->star_num = 1;
        $comment->save();
        //make reply
        $reply=new Comment();
        $reply->id=16;
        $reply->user_id=3;
        $reply->content="baka";
        $reply->reply_id=15;
        $reply->save();
        //make comment_message
        $comment_message=new CommentMessage();
        $comment_message->user_id=3;
        $comment_message->comment_id=16;
        $comment_message->save();
        //make Star
        $star=new Star();
        $star->id=1;
        $star->user_id=4;
        $star->comment_id=15;
        $star->save();
        //make Star_message
        $star_message=new StarMessage();
        $star_message->id=1;
        $star_message->user_id=4;
        $star_message->comment_id=15;
        $star_message->save();
    }
    public function testNoSession()
    {
        $this->withSession(['user.id'=>null])
            ->visit('/user')
            ->seePageIs('auth/login?continue=%2Fuser');
    }

    public function testWithSession()
    {
        $this->withSession(['user.id'=>'9'])
            ->visit('/user')
            ->seePageIs('/user')
            ->see('cirno')
            ->see('baka')
            ->see('1')
            ->see('3')
            ->see('4');
    }
}
