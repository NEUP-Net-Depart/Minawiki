<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Page;

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
        $currentPage -> title = "用户中心";
        $currentPage -> father_id = '1';
        $currentPage -> id = '9999';

        while ($currentPage['id'] != 1) {
            $path -> prepend($currentPage);
            $currentPage = $currentPage -> where('id', $currentPage['father_id']) -> first();
        }
        $path -> prepend($currentPage);


        // 获得登录的用户并跳转
        $userid = $request -> session() -> get('user.id');
        return view('user-center.'.$subPage, ['uid' => $userid, 'path' => $path]);
    }
}
