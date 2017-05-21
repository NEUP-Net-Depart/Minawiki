<?php

namespace App\Http\Controllers;

use App\CommentMessage;
use function foo\func;
use Illuminate\Http\Request;
use App\Page;
use App\Comment;

class UserController extends Controller
{
    /**
     * 显示用户界面
     * @param:Request, $id
     * @return view
     */
    public function ShowUserCenter(Request $request, $subPage = 'userInfo') {
        // 验证是否登录
        if (!$request -> session() -> has('user.id')) {
            $request['continue'] = '/user/';
            return (new AuthController()) -> showLoginView($request);
        }

        // 获得登录的用户并跳转
        $userid = $request -> session() -> get('user.id');
        // TODO: 获得用户的积分
        return view('user-center.'.$subPage, ['uid' => $userid,
            'tel' => $request -> session() -> get('user.tel'), 'point' => 60, 'newMessageNumber' => '2']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMyComments(Request $request){
        // TODO: 获得自己的评论
        $this -> validate($request, ['page' => 'required']);
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
        // TODO: 获取我的评分
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

    public function loadMessages(Request $request) {
        // TODO: 新消息
        $paginator[0]['is_read'] = false;
        $paginator[0]['username'] = '匿名用户';
        $paginator[0]['type'] = 'star';
        $paginator[0]['commentText'] = 'HHHHH';
        $paginator[0]['times'] = 1;
        $paginator[0]['id'] = 1;
        $paginator[1]['is_read'] = false;
        $paginator[1]['username'] = '匿名用户';
        $paginator[1]['type'] = 'comment';
        $paginator[1]['commentText'] = 'HHHHH';
        $paginator[1]['id'] = 2;
        $paginator[2]['is_read'] = true;
        $paginator[2]['username'] = '匿名用户';
        $paginator[2]['type'] = 'star';
        $paginator[2]['commentText'] = 'HHHHH';
        $paginator[2]['times'] = 2;
        $paginator[2]['id'] = 3;
        $paginator[3]['is_read'] = true;
        $paginator[3]['username'] = '匿名用户';
        $paginator[3]['type'] = 'comment';
        $paginator[3]['commentText'] = 'HHHH';
        $paginator[3]['id'] = 4;
        $paginator[4]['is_read'] = true;
        $paginator[4]['username'] = '匿名用户';
        $paginator[4]['type'] = 'star';
        $paginator[4]['commentText'] = 'HHHHH';
        $paginator[4]['times'] = 1;
        $paginator[4]['id'] = 5;
        return view('user-center.aMessage', ['paginator' => $paginator]);
    }

    function loadAComment(Request $request) {
        $this -> validate($request, ['comment_id' => 'required']);
        $comment = Comment::where('id', $request -> comment_id) -> paginate(10);
        return view('user-center.aComment', ['paginator' => $comment, 'dontShowFooter' => true]);
    }

    function read(Request $request) {
        // TODO: 设置某个消息为已读
    }
}
