@extends('user-center.layout')

@section('user-center-content')

    <center>
    <h2>我的评论</h2>
        <div id="delete_comment_modal" class="modal">
            <div class="modal-content">
                <h4>删除评论</h4>
            </div>
            <div class="modal-footer">
                <a id="del_comment_submit" href="#!"
                   class="modal-action waves-effect red white-text btn-flat">我明白这样做的后果并且要删除</a>
                <a href="#!" class="modal-action modal-close waves-effect btn-flat ">取消</a>
            </div>
        </div>


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
                url: '/user/loadMyComments?startIndex=' + i ,
                success: function (msg) {
                    $("#myCommentList .loading").remove();
                    $("#myCommentList").append(msg);
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