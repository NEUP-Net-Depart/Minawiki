<?php

namespace App\Http\Controllers;

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
        // 面包屑导航
        $path = collect([]);
        $page = Page::all();
//        $currentPage = $page -> where('id', 3) -> first();
        // 为了使用面包屑导航, 但是不能把用户中心加入Page表
        $currentPage = new Page();
        $currentPage -> title = "user";
        $currentPage -> father_id = '1';
        $currentPage -> id = '-1';

        while ($currentPage['id'] != 1) {
            $path -> prepend($currentPage);
            $currentPage = $currentPage -> where('id', $currentPage['father_id']) -> first();
        }
        $path -> prepend($currentPage);


        // 获得登录的用户并跳转
        $userid = $request -> session() -> get('user.id');
        // TODO: 获得用户的积分
        return view('user-center.'.$subPage, ['uid' => $userid, 'path' => $path,
            'tel' => $request -> session() -> get('user.tel'), 'point' => 60]);
    }

    public function getMyComments(Request $request){
        // TODO: 获得自己的评论

        $this -> validate($request, ['startIndex' => 'required']);
        $comments = Comment::where('user_id', $request -> session() -> get('user.id'))
            -> orderBy('id', 'desc')
            -> paginate(10);
        return view('user-center.aComment', ['paginator' => $comments]);
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
        $paginator[0]['id'] = 1;
        $paginator[0]['page_id'] = 'NEUWIKI';
        $paginator[0]['rateitem'] = 'LALALA';
        $paginator[0]['mark'] = 98;
        $paginator[0]['full_mark'] = 100;
        $paginator[0]['time'] = '2017-1-1';
        $paginator[0]['last'] = true;

        return view('user-center.aRate', ['paginator' => $paginator]);

    }
}
