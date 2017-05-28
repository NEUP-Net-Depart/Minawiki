<?php

namespace Tests\Feature;

use App\User;
use Carbon\Carbon;
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
            ->visit('/user/loadMyComments')
            ->seePageIs('/auth/login?continue=%2Fuser%2FloadMyComments')
            ->visit('/user/loadCommentMe')
            ->seePageIs('/auth/login?continue=%2Fuser%2FloadCommentMe')
            ->visit('/user/loadMessages')
            ->seePageIs('/auth/login?continue=%2Fuser%2FloadMessages');
    }
    public function testLoadMyComments()
    {
        factory(\App\Comment::class,5)->create(['page_id'=>1]);
        $commentTest=factory(\App\Comment::class)->create(['user_id'=>9,'page_id'=>1,'reply_id'=>18,'star_num'=>5,'updated_at'=>Carbon::now()]);
        $replyText=Comment::where('id',$commentTest->reply)->value('content');
        $this->withSession(['user.id'=>9])
        ->visit('/user/loadMyComments')
        ->see($commentTest->content)
        ->see($commentTest->star_num)
        ->see($replyText);
    }
    public function testLoadCommentMe_And_LoadMessages()
    {
        factory(\App\CommentMessage::class)->create(['user_id'=>9,'is_read'=>1,'updated_at'=>Carbon::now()]);
        $reply=factory(\App\CommentMessage::class)->create(['user_id'=>9,'is_read'=>0,'updated_at'=>Carbon::now()]);
        $star=factory(\App\StarMessage::class)->create(['user_id'=>9,'is_read'=>0,'updated_at'=>Carbon::now()]);
        $replyComment=Comment::find($reply->comment_id);
        $starComment=Comment::find($star->comment_id);
        $replyText=Comment::where('id',$replyComment->reply_id)->value('content');
        $this->withSession(['user.id'=>9])
            ->visit('user/loadCommentMe')
            ->see($replyComment->content)
            ->see($replyComment->star_num)
            ->see($replyText);
        $this->visit('user/loadMessages')
            ->see($replyComment->content)
            ->see($starComment->content);
        $this->visit('user/read/?id=comment_'.$reply->id)
            ->visit('user/read/?id=star_'.$star->id)
            ->visit('user/loadMessages')
            ->dontSee('已读');


    }



}
