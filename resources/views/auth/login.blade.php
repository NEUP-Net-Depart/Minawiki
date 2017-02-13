@extends('layouts.nav')

@section('title', '登录')

@section('content')
    <div class="container">
        <div class="row">
            <form class="col s12 m12 l8 offset-l2 theme-sec" id="login_fm">
                <center><h2>登录</h2></center>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">&#xE0CD;</i><!--phone-->
                        <input id="icon_telephone" type="tel" name="tel" class="validate" required>
                        <label for="icon_telephone">手机号</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">&#xE0DA;</i><!--vpn_key-->
                        <input id="icon_pass" name="password" type="password" required>
                        <label for="icon_pass">密码</label>
                    </div>
                </div>
                <a class="theme-word-dark" href="/auth/forget">忘记密码了？</a>
                <div class="row">
                    <center><button onclick="login()" type="button" class="waves-effect waves-light btn btn-large theme-dark">登录</button></center>
                </div>
                {!! csrf_field() !!}
            </form>
        </div>
    </div>
    <script>
        function login() {
            var str_data = $("#login_fm input").map(function () {
                return ($(this).attr("name") + '=' + $(this).val());
            }).get().join("&");
            $.ajax({
                type: "POST",
                url: "/auth/login",
                data: str_data,
                success: function (msg) {
                    var dataObj = eval("(" + msg + ")");
                    if(dataObj.result == "true") {
                        Materialize.toast('登录成功！欢迎回来～2秒后跳转到主页……', 3000, 'theme-bg-sec');
                        window.setTimeout(redirect, 2000);

                    }
                    else
                        Materialize.toast("用户名或密码错误！", 3000, 'theme-bg-sec');
                },
                error: function (xhr) {
                    if (xhr.status == 422) {
                        Materialize.toast('请正确填写相关字段！', 3000, 'theme-bg-sec')
                    } else {
                        Materialize.toast('服务器出错了，请刷新重试', 3000, 'theme-bg-sec')
                    }
                }
            });
        }
        function redirect() {
            window.location.href = "/";
        }
    </script>
@endsection