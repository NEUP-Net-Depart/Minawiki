@extends('user-center.layout')
@section('title', '我的评分')
@section('user-center-content')

    <div class="container">
        <link rel="stylesheet" href="/css/user-center/rating.css">
        <h3 align="center">我的评分</h3>
        <ul class="collection theme-dark-a" id="myRatingList">

        </ul>
    </div>
    <script src="/js/loadMore.js"></script>
    <script>
        $(document).ready(loadMore("myRating", 1));

        function showDeleteCommentModal(id) {
            $("#del_comment_submit").attr('href', 'javascript: deleteComment(' + id +')');
            $("#delete_comment_modal").modal('open');
        }

        function register() {

            $(".rate_detailButton").hover(function () {
                var text = $(this).next(".rate_detailText");
                $(text).show();
                $(text).addClass('hover');
            });

            $(".rate_detailArea").hover(function () {
                var text = $(this).find(".rate_detailText");
                if ($(text).hasClass('hover')) {
                    $(text).show();
                }
            }, function () {
                var text = $(this).find(".rate_detailText");
                $(text).removeClass('hover');
                $(text).hide();
            });

        }
    </script>

@stop