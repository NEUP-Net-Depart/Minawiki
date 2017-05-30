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
        $myComments = CommentMessage::where('user_id', $user_id)
            ->pluck('comment_id');
        $comments = Comment::whereIn('id', $myComments)->paginate(10);
        return view('user-center.aComment', ['paginator' => $comments]);
    }

    public function loadMessages(Request $request)
    {

        $user_id = session('user.id');
        $starPaginator=StarMessage::with('comment')
            ->where('user_id',$user_id)
            -> orderBy('updated_at', 'desc')
            ->paginate(2);
        $replyMessage=CommentMessage::with('comment')
            ->where('user_id',$user_id)
            -> orderBy('updated_at', 'desc')
            ->paginate(2);
        dd($starPaginator,$replyMessage);
        return view('user-center.aMessage', ['paginator' => $paginator]);
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
