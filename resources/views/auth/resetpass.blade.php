@extends('layouts.nav')

@section('title', '找回密码')

@section('content')
    <div class="container">
        <div class="row">
            <form class="col s12 m12 l8 offset-l2 theme-sec" id="reset_fm">
                <center><h2>重置密码</h2></center>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">&#xE0CD;</i><!--phone-->
                        <input name="tel" id="icon_telephone" type="tel" class="validate" required>
                        <label for="icon_telephone">手机号</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col s12">
                        <center>{!! Geetest::render() !!}</center>
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
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">&#xE0DA;</i><!--vpn_key-->
                        <input name="password" id="icon_pass" type="password" required>
                        <label for="icon_pass">新密码</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <center>
                            <button onclick="resetpass()" type="button"
                                    class="waves-effect waves-light btn btn-large theme-dark">重置
                            </button>
                        </center>
                    </div>
                </div>
                {!! csrf_field() !!}
            </form>
        </div>
    </div>
    <script>
        function sendMsg() {
            var str_data = $("#reset_fm input").map(function () {
                return ($(this).attr("name") + '=' + $(this).val());
            }).get().join("&");
            $.ajax({
                type: "POST",
                url: "/auth/forget/captcha",
                data: str_data,
                success: function (msg) {
                    var dataObj = eval("(" + msg + ")");
                    if (dataObj.code == 0) {
                        Materialize.toast('验证码已发送！撒花', 3000, 'theme-bg-sec');
                        runCount(30);
                    }
                    else if (dataObj.code > 0 || dataObj.msg == "send interval too short")
                        Materialize.toast('你发送的太快了，请稍后再试吧', 3000, 'theme-bg-sec');
                    else if (dataObj.result == "false")
                        Materialize.toast('用户不存在，请先注册', 3000, 'theme-bg-sec');
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
        function resetpass() {
            var str_data = $("#reset_fm input").map(function () {
                return ($(this).attr("name") + '=' + $(this).val());
            }).get().join("&");
            $.ajax({
                type: "POST",
                url: "/auth/forget",
                data: str_data,
                success: function (msg) {
                    var dataObj = eval("(" + msg + ")");
                    if (dataObj.result == "true") {
                        Materialize.toast('改密成功！请用新的密钥登录～2秒后跳转到登录页面……', 3000, 'theme-bg-sec');
                        window.setTimeout(redirect, 2000);

                    }
                    else if (dataObj.msg == "wrong telephone")
                        Materialize.toast("用户不存在，请先注册", 3000, 'theme-bg-sec');
                    else if (dataObj.msg == "expired captcha")
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
            window.location.href = "/auth/login";
        }
        function runCount(t) {
            if (t > 0) {
                $("#btn_send").attr('disabled', 'disabled');
                $("#btn_send").html(t.toString() + " 秒后重新发送");
                t--;
                setTimeout(function () {
                    runCount(t);
                }, 1000);
            } else {
                $("#btn_send").removeAttr('disabled');
                $("#btn_send").html("重新发送验证码");
            }
        }
    </script>
@endsection