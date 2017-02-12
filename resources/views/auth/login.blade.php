@extends('layouts.nav')

@section('title', '登录')

@section('content')
    <style>
        p {
            margin-top: 2px;
            margin-bottom: 0;
            font-size: 21px;
        }
    </style>
    <div class="container">
        <div class="row">
            <form class="col m12 l8 offset-l2 theme-sec">
                <center><h2>登录</h2></center>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">&#xE0CD;</i><!--phone-->
                        <input id="icon_telephone" type="tel" class="validate">
                        <label for="icon_telephone">手机号</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">&#xE0DA;</i><!--vpn_key-->
                        <input id="icon_pass" type="password">
                        <label for="icon_pass">密码</label>
                    </div>
                </div>
                <div class="row">
                    <center><a class="waves-effect waves-light btn btn-large theme-dark">登录</a></center>
                </div>
            </form>
        </div>
    </div>
@endsection