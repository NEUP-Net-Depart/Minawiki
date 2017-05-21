@extends('layouts.master')

@section('nav')

    <header>
        <nav class="nav-extended">

            <div class="nav-wrapper theme-dark">
                <div class="container">
                    <a href="/" class="brand-logo">{{ env('APP_NAME', "Minawiki") }}</a>
                    <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">&#xE5D2;
                            <!--menu--></i></a>
                    <ul id="nav-mobile" class="right hide-on-med-and-down nav-height">
                        <li>
                            <form action="http://www.baidu.com" method="get">
                                <div class="input-field">
                                    <input type="hidden" name="page" value="@yield('title')">
                                    <input name="wd" id="search" type="search" placeholder="Search" required class="auto-height">
                                    <label class="label-icon" for="search"><i class="material-icons">&#xE8B6;
                                            <!--search--></i></label>
                                    <i class="material-icons">&#xE5CD;<!--close--></i>
                                </div>
                            </form>
                        </li>
                        @if(isset($uid))
                            <li><a href="/user/">#{{ $uid }}</a></li>
                            <li><a href="/auth/logout">退出</a> </li>
                        @else
                            <li><a href="/auth/login">登录</a></li>
                            <li><a href="/auth/register">注册</a></li>
                        @endif
                    </ul>
                    <ul class="side-nav" id="mobile-demo">
                        @if(isset($uid))
                            <li><a href="/user/">#{{ $uid }}</a></li>
                            <li><a href="/auth/logout">退出</a> </li>
                        @else
                            <li><a href="/auth/login">登录</a></li>
                            <li><a href="/auth/register">注册</a></li>
                        @endif
                    </ul>
                </div>
            </div>
            @yield('breadcrumb')
        </nav>
    </header>

@endsection

@section('footer')
    @include('layouts.footer')
@endsection