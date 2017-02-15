@extends('layouts.nav')

@section('title', '确认')

@section('content')
    <div class="container">
        <div class="row">
            <form class="col s12 m12 l8 offset-l2 theme-sec" id="login_fm">
                <center><h2>输入密码</h2></center>
                <div class="row">
                    <div class="input-field col s12">
                        <i class="material-icons prefix">&#xE0DA;</i><!--vpn_key-->
                        <input id="icon_pass" name="password" type="password" required>
                        <label for="icon_pass">密码</label>
                    </div>
                </div>
                <div class="row">
                    <center>
                        <button onclick="login()" type="button"
                                class="waves-effect waves-light btn btn-large theme-dark">确定
                        </button>
                    </center>
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
                url: "/auth/confirm",
                data: str_data,
                success: function (msg) {
                    var dataObj = eval("(" + msg + ")");
                    if (dataObj.result == "true") {
                        @if(isset($continue))
                            Materialize.toast('密码正确！2秒后跳转回原页面……', 3000, 'theme-bg-sec');
                        @else
                            Materialize.toast('密码正确！2秒后跳转到主页……', 3000, 'theme-bg-sec');
                        @endif
                        window.setTimeout(redirect, 2000);

                    }
                    else {
                        Materialize.toast("密码错误！2秒后跳转到登录页……", 3000, 'theme-bg-sec');
                        window.setTimeout(redirectLogin, 2000);
                    }
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
            @if(isset($continue))
                window.location.href = "{{ $continue }}";
            @else
                window.location.href = "/";
            @endif
        }
        function redirectLogin() {
            @if(isset($continue))
                window.location.href = "/auth/login?continue={{ urlencode($continue) }}";
            @else
                window.location.href = "/auth/login";
            @endif
        }
    </script>
@endsection