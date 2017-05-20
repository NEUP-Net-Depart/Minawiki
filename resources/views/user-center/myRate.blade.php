@extends('user-center.layout')

@section('user-center-content')

    <div class="container">
        <link rel="stylesheet" href="/css/user-center/rating.css">
        <h3 align="center">我的评分</h3>
        <ul class="collection theme-dark-a" id="myRateList">

        </ul>
    </div>
    <script>

        $.onload(loadMyRating(1));

        function loadMyRating(i) {
            $('#myRateList .loadmore').remove();
            $('#myRateList').append('<center class="loading">\
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
                url: '/user/loadMyRating?startIndex=' + i ,
                success: function (msg) {
                    $("#myRateList .loading").remove();
                    $("#myRateList").append(msg);
                },
                error: function (xhr) {
                    Materialize.toast('服务器出错'+ xhr.status, 3000, 'theme-bg-sec');
                }

            })
        }

        function showDeleteCommentModal(id) {
            $("#del_comment_submit").attr('href', 'javascript: deleteComment(' + id +')');
            $("#delete_comment_modal").modal('open');
        }
    </script>

@stop