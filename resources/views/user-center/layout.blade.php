@extends('layouts.nav')
@section('content')

<div class="container">
    <link rel="stylesheet" href="/css/user-center/layout.css">
    <div class="row">
    <div id="left-nav" class="col m4 s12">
        <ul class="collection theme-word-dark theme-sec-i">
            <li class="collection-item" >
                <a href="/user/userInfo">个人信息</a>
            </li>
            <li class="collection-item">
                <a href="/user/myComment">我的评论</a>
            </li>
            <li class="collection-item" >
                <a href="/user/commentMe">评论我的</a>
            </li>
            <li class="collection-item">
                <a href="/user/myRate">我的评分</a>
            </li>

            {{--<li class="collection-item">--}}
                {{--<a href="/user/message">消息盒子</a>--}}
                {{--@if(isset($newMessageNumber) and $newMessageNumber > 0)--}}
                {{--<span id="newMessagesNum" class="theme-dark">{{ $newMessageNumber }}</span>--}}
                    {{--@endif--}}
            {{--</li>--}}

            <li class="collection-item">
                <a href="/user/starMe">收到的赞</a>
            </li>
            <li class="collection-item" >
                <a href="/user/changePsd">修改密码</a>
            </li>
        </ul>

    </div>
    <div class="col m8 s12">
            @if(isset($uid))

            @else
                <h3> 你还没登录呦</h3>
            @endif
            <div id="content" style="margin: 30px;">
                @yield('user-center-content')
                <!-- 内容区 -->
            </div>
        </div>
    </div>
</div>

@stop
