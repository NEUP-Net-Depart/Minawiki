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
        $this->withSession(['user.id' => null])
            ->visit('/user/loadMyComments')
            ->seePageIs('/auth/login?continue=%2Fuser%2FloadMyComments')
            ->visit('/user/loadCommentMe')
            ->seePageIs('/auth/login?continue=%2Fuser%2FloadCommentMe')
            ->visit('/user/loadStarMe')
            ->seePageIs('/auth/login?continue=%2Fuser%2FloadStarMe');
    }

    public function testLoadMyComments()
    {
        factory(\App\Comment::class, 5)->create(['page_id' => 1]);
        $commentTest = factory(\App\Comment::class)->create(['user_id' => 9, 'page_id' => 1, 'reply_id' => 18, 'star_num' => 5, 'updated_at' => Carbon::now()]);
        $replyText = Comment::where('id', $commentTest->reply)->value('content');
        $this->withSession(['user.id' => 9])
            ->visit('/user/loadMyComments')
            ->see($commentTest->content)
            ->see($commentTest->star_num)
            ->see($replyText);
    }


    public function testLoadCommentMe()
    {
        $reply = factory(\App\CommentMessage::class)->create(['user_id' => 9, 'is_read' => 0, 'updated_at' => Carbon::now()]);
        $replyComment = Comment::find($reply->comment_id);
        $replyText = Comment::where('id', $replyComment->reply_id)->value('content');
        $this->withSession(['user.id' => 9])
            ->visit('/user/loadCommentMe')
            ->see($replyComment->content)
            ->see($replyText);

    }

    public function testLoadStarMe()
    {
        $star = factory(\App\StarMessage::class)->create(['user_id' => 9, 'is_read' => 0, 'updated_at' => Carbon::now()]);
        $starComment = Comment::find($star->comment_id);
        $this->withSession(['user.id'=>9])
            ->visit('/user/loadStarMe')
            ->see($starComment->content);
    }

    public function testRead()
    {
        $this->json('POST', '/user/read', ['id' => 'comment_2']);
        $read=CommentMessage::find(2)->is_read;
        $this->assertEquals(1,$read);
        $this->json('POST', '/user/read', ['id' => 'star_2']);
        $read=StarMessage::find(2)->is_read;
        $this->assertEquals(1,$read);

    }



    public function testFrontend() {
        $this -> withSession(['user.id' => 1])
            -> visit('/user')
            -> see('个人中心');
    }

}
