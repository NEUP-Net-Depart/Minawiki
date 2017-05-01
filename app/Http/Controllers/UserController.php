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
    public function ShowUserCenter(Request $request) {
        // 验证是否登录
        if (!$request -> session() -> has('user.id')) {
            $request['continue'] = '/user/';
            return (new AuthController()) -> showLoginView($request);
        }
        // 面包屑导航
        $page = Page::all();
        $currentPage = $page -> where('id', 3) -> first();
        $path = collect([]);
        while ($currentPage['id'] != 1) {
            $path -> prepend($currentPage);
            $currentPage = $currentPage -> where('id', $currentPage['father_id']) -> first();
        }
        $path -> prepend($currentPage);

        // 获得登录的用户并跳转
        $userid = $request -> session() -> get('user.id');
        return view('user-center', ['uid' => $userid, 'path' => $path]);
    }
}
