<!-- 在天国的消息盒子 -->
@extends('user-center.layout')
@section('title', '消息盒子')
@section('user-center-content')
    <script>
        function loadAComment(id) {
            var t = null;
            $.ajax({
                url: '/user/loadAComment?comment_id=' + id,
                type: 'get',
                async: false, // 等待ajax执行完再执行下面的代码
                success: function (msg) {
                    t =  msg;
                },
                errot: function (xhr) {
                    Meterialize.toast('服务器错误:' + xhr.status, 3000, 'theme-bg-sec');
                    return null;
                }
            });
            return t;
        }


    </script>

    <div>
        <link rel="stylesheet" href="/css/user-center/messageBox.css">
        <h3 style="text-align: center;">消息盒子</h3>
        <a href='javascript:setAllRead()' class="setRead" id = "setAllReadButton">全部标记为已读</a>
        <ul class="collection" id="messageList">

        </ul>
    </div>

    <script src="/js/user-center/loadMore.js"></script>
    <script src="/js/user-center/message.js"></script>
    <script>$.ready(loadMessage("message", 1));</script>

@endsection