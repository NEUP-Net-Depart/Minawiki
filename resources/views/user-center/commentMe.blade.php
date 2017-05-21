@extends('user-center.layout')
@section('title', '收到的评论')
@section('user-center-content')

    <center>
        <h2>收到的评论</h2>
        <ul class="collection theme-dark-a" id="myCommentList">

        </ul>
    </center>
    <script>

        $.onload(loadMyComments(1));

        function loadMyComments(i) {
            $('#myCommentList .loadmore').remove();
            $('#myCommentList').append('<center class="loading">\
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

                type: 'GET',
                url: '/user/loadCommentMe?page=' + i ,
                success: function (msg) {
                    $("#myCommentList .loading").remove();
                    $("#myCommentList").append(msg);
                },
                error: function (xhr) {
                    Materialize.toast('服务器出错'+ xhr.status, 3000, 'theme-bg-sec');
                }
            })
        }
    </script>

@stop