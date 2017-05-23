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

        function setRead(id) {
            $('#' + id + '_comment').removeClass('unread').addClass('read');
            $('#' + id + '_comment' + ' .setRead').remove();
            $.ajax({
                type: 'post',
                url: '/user/read',
                data: '&id=' + id
            });

            var numberLabel = $('#newMessagesNum');
            if (numberLabel !== []) {
                var number = Number(numberLabel.text());
                number -= 1;
                if (number > 0) {
                    numberLabel.text(number);
                } else {
                    numberLabel.remove();
                }
            }
        }

        function setAllRead() {
            $('.aMessage').filter('.unread').each(function (index, element) {
                var id = element.getAttribute('id').split('_')[0];
                setRead(id);
            });
        }
    </script>

    <div>
        <link rel="stylesheet" href="/css/user-center/messageBox.css">
        <h3 style="text-align: center;">消息盒子</h3>
        <a href='javascript:setAllRead()' class="setRead">全部标记为已读</a>
        <ul class="collection" id="msgBox">

        </ul>
    </div>

    <script>
        $.ready(loadMessage(0));
        function loadMessage(i) {
            $('#msgBox .loadmore').remove();
            $('#msgBox').append('<center class="loading">\
<div class="preloader-wrapper small active center" style="margin-top: 10px; margin-bottom: 10px">\
<div class="spinner-layer theme-border-dark">\
<div class="circle-clipper left">\
<div class="circle"></div>\
</div><div class="gap-patch">\
<div class="circle"></div>\
</div><div class="circle-clipper right">\
<div class="circle"></div>\
</div>\
</div>\
</div>\
</center>\
');
            $.ajax({
                'url' : '/user/loadMessages?startIndex=' + i,
                'type' : 'get',
                success: function (msg) {
                    $('#msgBox .loading').remove();
                    $('#msgBox').append(msg);

                },
                error: function(xhr) {
                    Meterialize.toast('服务器错误: ' + xhr.status, 3000, 'theme-bg-sec');
                }
            })
        }
    </script>

@endsection