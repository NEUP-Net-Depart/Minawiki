@extends('layouts.nav')

@section('breadcrumb')

    <div class="nav-wrapper theme">
        <div class="container">
            @foreach($path as $page)
            <a href="/{{ $page->title }}" class="breadcrumb">{{ $page->title }}</a>
            @endforeach
        </div>
    </div>

@endsection