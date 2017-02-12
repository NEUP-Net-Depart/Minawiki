@extends('layouts.master')

@section('nav')

    <header>
        <nav class="nav-extended">

            <div class="nav-wrapper theme-dark">
                <div class="container">
                    <a href="#" class="brand-logo">{{ env('APP_NAME', "Minawiki") }}</a>
                    <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">&#xE5D2;
                            <!--menu--></i></a>
                    <ul id="nav-mobile" class="right hide-on-med-and-down nav-height">
                        <li>
                            <form>
                                <div class="input-field">
                                    <input id="search" type="search" placeholder="Search" required class="auto-height">
                                    <label class="label-icon" for="search"><i class="material-icons">&#xE8B6;
                                            <!--search--></i></label>
                                    <i class="material-icons">&#xE5CD;<!--close--></i>
                                </div>
                            </form>
                        </li>
                        <li><a href="#">登录</a></li>
                        <li><a href="#">注册</a></li>
                    </ul>
                    <ul class="side-nav" id="mobile-demo">
                        <li><a href="#">登录</a></li>
                        <li><a href="#">注册</a></li>
                    </ul>
                </div>
            </div>
            @yield('breadcrumb')
        </nav>
    </header>

@endsection