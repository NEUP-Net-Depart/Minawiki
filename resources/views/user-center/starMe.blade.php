@extends('user-center.layout')
@section('title', '收到的赞')
@section('user-center-content')

    <h3 style="text-align: center">收到的赞</h3>
<ul class="collection" id="starMeList">

</ul>

    <script src="/js/loadMore.js"></script>
    <script>$(document).ready(loadMore("starMe", 1));</script>
    @endsection