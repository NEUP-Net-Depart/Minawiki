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
        // TODO: 获得自己的评论

        $comments = Comment::where('user_id', $request -> session() -> get('user.id'))
            -> orderBy('id', 'desc')
            -> paginate(2);

        foreach($comments as $c) {
            $c -> page_id = Page::where('id', $c -> page_id) -> first() -> title;
        }
        return view('user-center.aComment', ['paginator' => $comments, 'canDelete' => true]);
    }


    public function loadCommentMe(Request $request)
    {
        // TODO: 收到的评论
        $user_id = session('user.id');
        $Paginator=CommentMessage::with('comment','comment.replyTarget')
            ->where('user_id',$user_id)
            -> orderBy('is_read', 'desc')
            ->paginate(2);
        dd($Paginator);
        return view('user-center.aComment', ['paginator' => $Paginator]);
    }

    public function loadStarMe(Request $request)
    {

        $user_id = session('user.id');
        $Paginator=StarMessage::with('comment')
            ->where('user_id',$user_id)
            -> orderBy('is_read', 'desc')
            ->paginate(2);
        dd($Paginator);
        return view('user-center.aMessage', ['Paginator' => $Paginator]);
    }
    function read(Request $request) {
         $str=$request->input('id');
        $type=explode('_',$str)[0];
        $id=explode('_',$str)[1];
        if($type=='comment')
            CommentMessage::where('id',$id)->update(['is_read'=>1]);
        else
            StarMessage::where('id',$id)->update(['is_read'=>1]);
        // TODO: 设置某个消息为已读



    }
}
