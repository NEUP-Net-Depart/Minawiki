<?php

namespace Tests\Feature;

use Tests\BrowserKitTestCase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Page;
use App\Comment;

class CommentTest extends BrowserKitTestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBackend()
    {
        $this->post('/install', ['tel' => '12312312312', 'password' => 'admin', 'title' => 'Minawikiroot']);

        //Test add comment
        $page = new Page();
        $page->father_id = 1;
        $page->title = "CommentTest";
        $page->is_folder = false;
        $page->power = 2;
        $page->save();

        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('POST', '/CommentTest/comment', ['text' => 'comment1'])
            ->seeJson([
                'result' => 'true',
                'msg' => 'success',
            ]);

        $cnt = 0;
        while($cnt++ != 10)
        {
            $this->withSession(['user.id' => 1, 'user.power' => '3'])
                ->json('POST', '/CommentTest/comment', ['text' => 'comment2'])
                ->seeJson([
                    'result' => 'true',
                    'msg' => 'success',
                ]);
        }

        //Test show comment
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->visit('/CommentTest/comment?order=latest')
            ->see("comment2")
            ->see("âââ ææ°è¯è®º âââ");//最新评论 不知道是什么编码orz
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->visit('/CommentTest/comment?order=latest&page=2')
            ->dontsee('âââ ææ°è¯è®º âââ')//最新评论 不知道是什么编码orz
            ->see("comment1");

        /*$this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->visit('/CommentTest/comment')
            ->dontsee('âââ ææ°è¯è®º âââ'); //最新评论 不知道是什么编码orz

        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->visit('/CommentTest/comment?order=mostpopular')
            ->dontsee('âââ ææ°è¯è®º âââ'); //最新评论 不知道是什么编码orz*/

        //Test delete comment
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('DELETE', '/CommentTest233/comment/1')
            ->seeJson([
                'result' => 'false',
                'msg' => 'invalid title',
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('DELETE', '/CommentTest/comment/233333')
            ->seeJson([
                'result' => 'false',
                'msg' => 'invalid comment id',
            ]);
        $this->withSession(['user.id' => 123, 'user.power' => '0'])
            ->json('DELETE', '/CommentTest/comment/1')
            ->seeJson([
                'result' => 'false',
                'msg' => 'unauthorised',
            ]);
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('DELETE', '/CommentTest/comment/1')
            ->seeJson([
                'result' => 'true',
                'msg' => 'delete success',
            ]);
        $comment = new Comment();
        $comment->page_id = $page->id;
        $comment->user_id = 2;
        $comment->content = "Test comment.";
        $comment->signature = "匿名用户";
        $comment->position = "打酱油评论";
        $comment->ban = false;
        $comment->star_num = 0;
        $comment->save();
        $this->withSession(['user.id' => 1, 'user.power' => '3'])
            ->json('DELETE', '/CommentTest/comment/' . $comment->id)
            ->seeJson([
                'result' => 'true',
                'msg' => 'ban success',
            ]);
    }
}
