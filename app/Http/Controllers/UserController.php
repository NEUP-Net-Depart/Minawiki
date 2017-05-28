<?php

namespace App\Http\Controllers;

use App\CommentMessage;
use App\StarMessage;
use function foo\func;
use Illuminate\Http\Request;
use App\Page;
use App\Comment;
use Illuminate\Pagination\LengthAwarePaginator;

class UserController extends Controller
{
    /**
     * 显示用户界面
     * @param:Request, $id
     * @return view
     */
    public function ShowUserCenter(Request $request, $subPage = 'userInfo') {
        // 获得登录的用户并跳转
        $user_id = $request -> session() -> get('user.id');
        // TODO: 获得用户的积分
        // TODO: 获得用户的未读消息数
        $unReads = CommentMessage::where('user_id', $user_id) -> where('is_read', false) -> count();
        $unReads += StarMessage::where('user_id', $user_id) -> where('is_read', false) -> count();
        return view('user-center.'.$subPage, ['uid' => $user_id,
            'tel' => $request -> session() -> get('user.tel'), 'point' => 60, 'newMessageNumber' => $unReads]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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

    public function getMyInformation(Request $request) {
        return view('user-center.userInfo');
    }

    public function loadMyPointDetails(Request $request) {
        // TODO: 获得用户积分的详细信息(如果可以的话)
        $paginator = array(array());
        for($i = 0; $i <= 10; $i++) {
            $paginator[$i]['id'] = $i;
            $paginator[$i]['date'] = '2017-1-1';
            $paginator[$i]['context'] = '评论';
            $paginator[$i]['result'] = '+3';
        }
        return view('user-center.aPointDetailItem', ['paginator' => $paginator]);
    }

    public function loadMyRating(Request $request) {
        // TODO: 获取我的评分(数组仅做前段测试用)
        $paginator[0]['id'] = 1;
        $paginator[0]['page_id'] = 'NEUWIKI';
        $paginator[0]['rateitem'] = 'LALALA';
        $paginator[0]['mark'] = 98;
        $paginator[0]['full_mark'] = 100;
        $paginator[0]['time'] = '2017-1-1';
        $paginator[0]['last'] = true;
        $paginator[0]['detail'] = '这是这个评分项的详细信息';
        return view('user-center.aRate', ['paginator' => $paginator, 'noMore' => false, 'now'=> 1]);
    }

    public function loadCommentMe(Request $request) {
        // TODO: 收到的评论
        $myComments = CommentMessage::where('user_id', $request -> session() -> get('user.id'))
            -> pluck('comment_id');

        $comments = Comment::whereIn('reply_id', $myComments) -> paginate(10);

        return view('user-center.aComment', ['paginator' => $comments]);
    }

    public function loadMessages(Request $request)
    {
        $list=null;
        $user_id = session('user.id');
        $replyComments_id = CommentMessage::where('user_id', $user_id)->pluck('comment_id');
        // 找到该用户的评论消息
        $comments = Comment::whereIn('id', $replyComments_id)->get()->toArray();

        // 该用户的评论消息实例
        $reply_message= CommentMessage::where('user_id', $user_id)->get()->toArray();
        $replyCount=count($reply_message);
        // 合并数组
        for ($num=0;$num<$replyCount;$num++)
            $list['comment'.$num]=array_merge($comments[$num],$reply_message[$num]);

        $starComments_id= StarMessage::where('user_id',$user_id)->pluck('comment_id');
        $comments = Comment::whereIn('id', $starComments_id)->get()->toArray();
        $star_message=StarMessage::where('user_id',$user_id)->get()->toArray();
        $starCount=count($star_message);
        for($num=0;$num<$starCount;$num++)
            $list['star'.($num)]=array_merge($comments[$num],$star_message[$num]);

        array_multisort(array_column($list,'updated_at'),SORT_DESC,$list);
        $page =$request->get('page',1);
        $perPage = 3;
        $offset = ($page * $perPage) - $perPage;
//        $paginator = array_slice($list, $offset, $perPage, true);
        $paginator= new LengthAwarePaginator(array_slice($list,$offset,$perPage , true),count($list),$perPage,
            $page,['path' => $request->url(), 'query' => $request->query()]);

        return view('user-center.aMessage', ['paginator' => $paginator]);
    }

    function loadAComment(Request $request) {
        $this -> validate($request, ['comment_id' => 'required']);
        $comment = Comment::where('id', $request -> comment_id) -> paginate(10);
        foreach($comment as $c) {
            $c -> page_id = Page::where('id', $c -> page_id) -> first() -> title;
        }
        return view('user-center.aComment', ['paginator' => $comment, 'dontShowFooter' => true]);
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

