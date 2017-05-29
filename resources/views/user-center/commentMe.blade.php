@extends('user-center.layout')
@section('title', '收到的评论')
@section('user-center-content')

    <center>
        <h2>收到的评论</h2>
        <ul class="collection theme-dark-a" id="myCommentList">

        </ul>
    </center>
    <script src="/js/loadMore.js"></script>
    <script>$(document).ready(loadMore("myComment", 1));</script>

@stop