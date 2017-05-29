@extends('layouts.nav')

@section('title', '加入')

@section('content')
    <div class="container">
        <div class="row">
            <form class="col m12 l8 theme-sec" id="reg_fm">
                <h2>注册</h2>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">&#xE0CD;</i><!--phone-->
                        <input name="tel" id="icon_telephone" type="tel" class="validate" required>
                        <label for="icon_telephone">手机号</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">&#xE0DA;</i><!--vpn_key-->
                        <input name="password" id="icon_pass" type="password" required>
                        <label for="icon_pass">密码</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        {!! Geetest::render() !!}
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <i class="material-icons prefix">&#xE625;</i><!--sms-->
                        <input name="captcha" id="icon_captcha" type="text" class="validate" required>
                        <label for="icon_captcha">短信验证码</label>
                    </div>
                    <div class="input-field col s6">
                        <a id="btn_send" class="waves-effect waves-light btn theme-dark" href="javascript: sendMsg()">发送验证码</a>
                    </div>
                </div>
                <p>点击提交即表示您同意<a class="theme-word-dark" href="#">隐私政策</a>和<a class="theme-word-dark" href="#">服务条款</a>。</p>
                <div class="row">
                    <div class="input-field col s6">
                        <button onclick="register()" type="button" class="waves-effect waves-light btn btn-large theme-dark">提交</button>
                    </div>
                </div>
                {!! csrf_field() !!}
            </form>
            <div class="col l4 hide-on-med-and-down theme-sec">
                <h4>欢迎加入 {{ env('APP_NAME', "Minawiki") }}</h4>
            </div>
        </div>
    </div>
    <script>
        function sendMsg() {
            var str_data = $("#reg_fm input").map(function () {
                return ($(this).attr("name") + '=' + $(this).val());
            }).get().join("&");
            $.ajax({
                type: "POST",
                url: "/auth/register/captcha",
                data: str_data,
                success: function (msg) {
                    var dataObj = eval("(" + msg + ")");
                    if(dataObj.code == 0) {
                        Materialize.toast('验证码已发送！撒花', 3000, 'theme-bg-sec');
                        runCount(30);
                    }
                    else if(dataObj.code > 0 && dataObj.msg == "send interval too short")
                        Materialize.toast('你发送的太快了，请稍后再试吧', 3000, 'theme-bg-sec');
                    else if(dataObj.result == "false")
                        Materialize.toast('您已注册，请直接登录', 3000, 'theme-bg-sec');
                    else
                        Materialize.toast('短信服务器出错了，请稍后再试吧', 3000, 'theme-bg-sec');
                },
                error: function (xhr) {
                    if (xhr.status == 422) {
                        Materialize.toast('验证失败或验证码已过期，请刷新滑动验证码重试', 3000, 'theme-bg-sec')
                    } else {
                        Materialize.toast('验证码服务器出错了，请稍后再试吧', 3000, 'theme-bg-sec')
                    }
                }
            });
        }
        function register() {
            var str_data = $("#reg_fm input").map(function () {
                return ($(this).attr("name") + '=' + $(this).val());
            }).get().join("&");
            $.ajax({
                type: "POST",
                url: "/auth/register",
                data: str_data,
                success: function (msg) {
                    var dataObj = eval("(" + msg + ")");
                    if(dataObj.result == "true") {
                        @if(isset($continue))
                            Materialize.toast('注册成功！撒花～～～2秒后跳转回原页面……', 3000, 'theme-bg-sec');
                        @else
                            Materialize.toast('注册成功！撒花～～～2秒后跳转到主页……', 3000, 'theme-bg-sec');
                        @endif
                        window.setTimeout(redirect, 2000);

                    }
                    else if(dataObj.msg == "telephone already exists")
                        Materialize.toast("您不能重复注册！", 3000, 'theme-bg-sec');
                    else if(dataObj.msg == "expired captcha")
                        Materialize.toast("短信验证码已过期，请重新发送", 3000, 'theme-bg-sec');
                    else
                        Materialize.toast("短信验证码错误，请重试", 3000, 'theme-bg-sec');
                },
                error: function (xhr) {
                    if (xhr.status == 422) {
                        Materialize.toast('验证失败，请刷新重试', 3000, 'theme-bg-sec')
                    } else {
                        Materialize.toast('服务器出错了，请刷新重试', 3000, 'theme-bg-sec')
                    }
                }
            });
        }
        function redirect() {
            @if(isset($continue))
                window.location.href = "{{ $continue }}";
            @else
                window.location.href = "/";
            @endif
        }
        function runCount(t){
            if(t > 0){
                $("#btn_send").attr('disabled','disabled');
                $("#btn_send").html(t.toString() + " 秒后重新发送");
                t--;
                setTimeout(function(){runCount(t);},1000);
            }else{
                $("#btn_send").removeAttr('disabled');
                $("#btn_send").html("重新发送验证码");
            }
        }
    </script>
@endsection