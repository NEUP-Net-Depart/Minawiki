@extends('user-center.layout')
@section('title', '修改密码')
@section('user-center-content')

    <h2 style="text-align: center">修改密码</h2>
    <div class="container" id="changePsd_fm">

        {!!Form::Open(['url' => '#'])!!}

        <div class="input-field col s12" >
            <div>
                {!! Form::label('oldPsd', '旧密码') !!}
                {!! Form::password('oldPsd') !!}

            </div>
        </div>

        <div class="input-field col s12">
            <div>
                {!! Form::label('newPsd', '新密码') !!}
                {!! Form::password('newPsd') !!}

            </div>
        </div>
        <div class="input-field col s12">
            <div>
                {!! Form::label('repeat', '重复密码') !!}
                {!! Form::password('repeat') !!}
            </div>
        </div>

        <div class="row" >
            <div class="col s6" style="margin-top: 18px;">
                {!! Geetest::render() !!}
            </div>

            <div class="col s6" style="text-align: center;">
                <button onclick="changePsd()" type="button" class='btn btn-large theme-dark'>
                    提交
                </button>
            </div>
        </div>
        {!! Form::Close() !!}
    </div>

    <script>
        function changePsd() {
            var newPsd = $('#newPsd').val();
            var repeat = $('#repeat').val();

            var str_data = $("#changePsd_fm input").map(function () {
                return ($(this).attr("name") + '=' + $(this).val());
            }).get().join("&");

            console.log(str_data);

            if (newPsd !== repeat) {
                Materialize.toast("两次密码不一致", 3000, 'theme-bg-sec');
            } else {

                $.ajax({
                        type: "POST",
                    // TODO: user/changePassword
                        url: "/auth/changePassword",
                        data: str_data,
                        success: function (msg) {
                            var dataObject = eval('(' + msg + ')');
                            if (dataObject.result == 'true') {
                                Materialize.toast("修改密码成功", 3000, 'theme-bg-sec');
                            } else {
                                Materialize.toast("修改密码失败", 3000, 'theme-bg-sec');
                            }
                        },
                        error: function (xhr) {
                            if (xhr.status == 422) {
                                Materialize.toast('请正确填写相关字段！', 3000, 'theme-bg-sec')
                            } else {
                                Materialize.toast('服务器出错了，请刷新重试', 3000, 'theme-bg-sec')
                            }
                        }
                    }
                );
            }
        }
    </script>
@stop