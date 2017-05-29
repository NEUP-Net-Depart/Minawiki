@extends('user-center.layout')
@section('title', '我的评论')
@section('user-center-content')

    <center>
    <h2>我的评论</h2>
        <div id="delete_comment_modal" class="modal">
            <div class="modal-content">
                <h4>删除评论</h4>
            </div>
            <div class="modal-footer">
                <a id="del_comment_submit" href="#"
                   class="modal-action waves-effect modal-close red white-text btn-flat">我明白这样做的后果并且要删除</a>
                <a href="#" class="modal-action modal-close waves-effect btn-flat ">取消</a>
            </div>
        </div>


        <ul class="collection theme-dark-a" id="myCommentList">

        </ul>
    </center>

    <script src="/js/loadMore.js"></script>
    <script>
        $(document).ready(loadMore("myComment", 1));

        function showDeleteCommentModal(id) {
            $("#del_comment_submit").attr('href', 'javascript: deleteComment(' + id +')');
            $("#delete_comment_modal").modal('open');
        }

        function deleteComment(id) {
            $.ajax({
                type: 'delete',
                url: '/user/'
            })
        }
    </script>

@stop