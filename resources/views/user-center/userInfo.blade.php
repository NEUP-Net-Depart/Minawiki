@extends('user-center.layout')
@section('title', '个人信息')
@section('user-center-content')
    <link href="/css/user-center/user-info.css" rel="stylesheet">
    <div id="user-center">
    <h3 style="text-align: center">个人中心</h3>
    <hr class="line">

    <div id="myPoint" class="contentArea">
        <h4>我的积分</h4>

        <hr class="line">

        <p id = 'PointText'>积分规则为，每日签到+5，活跃（评、赞）+5，收到赞+2</p>

        <div class="pointArea">
            <p id="point" class="theme-word-dark"> {{ $point }} </p>
        </div>
        <div style="clear: both;"></div>
        <div id="pointDetail">
            <a id = "detailButton">详细信息</a>
            <ul id="myPointDetailList" style="display: none;">
        </ul>
        </div>
    </div>
        <hr class="line">
    </div>
    <script>
        $("#pointDetail").hover( function () {
            if ($("#pointDetail").hasClass('hover')) {
                var list = $('#myPointDetailList');
                list.slideDown(200);
                if (! $(list).hasClass('loaded')) {
                    load();
                    $(list).addClass('loaded');
                }

            }
        }, function () {
            $("#myPointDetailList").slideUp(20);
            $('#pointDetail').removeClass('hover');
        });
        $("#detailButton").hover( function () {
            $('#pointDetail').addClass('hover');
            var list = $('#myPointDetailList');
            list.slideDown(200);
            if (! $(list).hasClass('loaded')) {
                load();
                $(list).addClass('loaded');
            }
        });
        function load() {
            $.ajax({
                type: 'GET',
                url: '/user/userInfo/loadMyPointDetails',
                success: function (msg) {
                    $("#myPointDetailList").append(msg);
                },
                error: function (xhr) {
                    $("#myPointDetailList").append("<center>服务器出错</center>");
                }
            })
        }

    </script>

@stop
