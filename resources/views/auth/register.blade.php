@extends('layouts.nav')

@section('title', '加入')

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
            <form class="col m12 l8 theme-sec">
                <h2>注册</h2>
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
                    <div class="col s12">
                        {{--Gee-test--}}
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <i class="material-icons prefix">&#xE625;</i><!--sms-->
                        <input id="icon_captcha" type="text" class="validate">
                        <label for="icon_captcha">短信验证码</label>
                    </div>
                    <div class="input-field col s6">
                        <a class="waves-effect waves-light btn theme-dark">发送验证码</a>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <a class="waves-effect waves-light btn btn-large theme-dark">提交</a>
                    </div>
                </div>
            </form>
            <div class="col l4 hide-on-med-and-down theme-sec">
                <h4>欢迎加入 {{ env('APP_NAME', "Minawiki") }}</h4>
            </div>
        </div>
    </div>
@endsection