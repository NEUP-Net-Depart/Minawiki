@extends('user-center.layout')
@section('title', '收到的评论')
@section('user-center-content')

        <h2 class="center">收到的评论</h2>
    <a class="setRead" href="javascript: setAllRead('comment')">全部标记为已读</a>
        <ul class="collection theme-dark-a messageList" id="commentMeList">

        </ul>

        <script src="/js/user-center/loadMore.js"></script>
        <script src="/js/user-center/message.js"></script>
    <script>$(document).ready(loadMore("commentMe", 1));</script>


@stop