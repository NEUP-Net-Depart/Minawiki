@extends('layouts.nav-nav')
@section('title', '个人中心')
@section('content')

<div class="container">
    <div class="row">
    <div id="left-nav" class="col m4 s12">
        <ul class="collection theme-word-dark theme-sec-i">
            <li class="collection-item" style="transform: translateX(0px); opacity: 1;">
                <a href="/user/userInfo">个人信息</a>
            </li>
            <li class="collection-item" style="transform: translateX(0px); opacity: 1;">
                <a href="/user/myComment" onclick="showMyCommits()">我的评论</a>
            </li>
            <li class="collection-item" style="transform: translateX(0px); opacity: 1;">
                <a href="/user/myStar">我的赞</a>
            </li>
            <li class="collection-item" style="transform: translateX(0px); opacity: 1;">
                <a href="/user/commentMe">评论我的</a>
            </li>
            <li class="collection-item" style="transform: translateX(0px); opacity: 1;">
                <a href="/user/starMe">赞过我的</a>
            </li>
            <li class="collection-item" style="transform: translateX(0px); opacity: 1;">
                <a href="/user/myRate">我的评分</a>
            </li>
            <li class="collection-item" style="transform: translateX(0px); opacity: 1;">
                <a href="/user/changePsd">修改密码</a>
            </li>
        </ul>

    </div>
    <div class="col m8 s12">
        <div style="text-align: center">
            @if(isset($uid))

            @else
                <h3> 你还没登录呦</h3>
            @endif
            <div id="content">
                @yield('user-center-content')
                <!-- 内容区 -->
            </div>
        </div>
    </div>
    </div>
</div>

@stop
