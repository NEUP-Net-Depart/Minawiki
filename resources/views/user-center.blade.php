@extends('layouts.nav-nav')
@section('title', '个人中心')
@section('content')

<div class="container">
    <div id="left-nav" class="col m4 s12">
        
    </div>
    <div class="col m8 s12">
        <center>
            @if(isset($uid))
                <h2> {{ $uid }}</h2>
            @else
                <h2> 你还没登录呦</h2>
            @endif
        </center>
    </div>
</div>

@stop
