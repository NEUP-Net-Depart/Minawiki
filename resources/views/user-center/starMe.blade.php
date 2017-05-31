@extends('user-center.layout')
@section('title', '收到的赞')
@section('user-center-content')

    <h3 style="text-align: center">收到的赞</h3>
    <span class="star-badge"></span>
    <a class="setRead" id="setAllReadButton_star" href="javascript: setAllRead('star')">
        <i class="material-icons">done_all</i></a>
    <ul class="collection messageList" id="starMeList">
    </ul>

    <script src="/js/user-center/loadMore.js"></script>
    <script src="/js/user-center/message.js"></script>
    <script>$(document).ready(loadMore("starMe", 1));</script>
@endsection