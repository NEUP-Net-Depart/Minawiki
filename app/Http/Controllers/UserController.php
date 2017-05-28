<?php

namespace App\Http\Controllers;

use App\CommentMessage;
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
        $list=null;
        $user_id = session('user.id');
        $replyComments_id = CommentMessage::where('user_id', $user_id)->pluck('comment_id');
        $comments = Comment::whereIn('id', $replyComments_id)->get()->toArray();
        $reply_message= CommentMessage::where('user_id', $user_id)->get()->toArray();
        $replyCount=count($comments);
        for ($num=0;$num<$replyCount;$num++)
            $list['comment'.$num]=array_merge($comments[$num],$reply_message[$num]);
        $starComments_id= StarMessage::where('user_id',$user_id)->pluck('comment_id');
        $comments = Comment::whereIn('id', $starComments_id)->get()->toArray();
        $star_message=StarMessage::where('user_id',$user_id)->get()->toArray();
        $starCount=count($comments);
        for($num=0;$num<$starCount;$num++)
            $list['star'.($num)]=array_merge($comments[$num],$star_message[$num]);
        array_multisort(array_column($list,'updated_at'),SORT_DESC,$list);
        $page =$request->get('page',1);
        $perPage = 10;
        $offset = ($page * $perPage) - $perPage;
        $paginator= new LengthAwarePaginator(array_slice($list,$offset,$perPage,true),count($list),$perPage,
            $page,['path' => $request->url(), 'query' => $request->query()]);
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
