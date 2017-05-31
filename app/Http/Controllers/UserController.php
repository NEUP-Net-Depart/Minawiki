<?php

namespace App\Http\Controllers;

use App\CommentMessage;
use App\User;
use App\StarMessage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use App\Page;
use App\Comment;

class UserController extends Controller
{

    public function getMyComments(Request $request){
        //  获得自己的评论
        $comments = Comment::where('user_id', $request -> session() -> get('user.id'))
            -> orderBy('id', 'desc')
            -> paginate(2);
        return view('user-center.aComment', ['paginator' => $comments, 'canDelete' => true]);
    }


    public function loadCommentMe(Request $request)
    {
        //获取回复我的信息
        $user_id = session('user.id');
        $Paginator=CommentMessage::with('comment','comment.replyTarget', 'comment.page')
            ->where('user_id',$user_id)
            -> orderBy('is_read', 'desc')
            ->paginate(10);
        return view('user-center.aCommentMessage', ['paginator' => $Paginator]);
    }
    public function loadStarMe(Request $request)
    {
        //获取点赞的信息
        $user_id = session('user.id');
        $Paginator=StarMessage::with('comment')
            ->where('user_id',$user_id)
            -> orderBy('is_read', 'desc')
            ->paginate(10);
        return view('user-center.aStar', ['paginator' => $Paginator]);
    }
    function read(Request $request) {
        //  设置某个消息为已读
         $str=$request->input('id');
        $type=explode('_',$str)[0];
        $id=explode('_',$str)[1];
        if($type=='comment')
            CommentMessage::where('id',$id)->update(['is_read'=>1]);
        else
            StarMessage::where('id',$id)->update(['is_read'=>1]);

    }
}
