@extends('layouts.nav-nav')

@section('title', 'Welcome')

@section('content')
    <style>
        p {
            margin-top: 2px;
            margin-bottom: 0;
            font-size: 21px;
        }
    </style>
    <div class="container">
        <div class="row">
            <div class="col m4 s12">
                <div class="collection">
                    <a href="#!" class="collection-item">Alvin</a>
                    <a href="#!" class="collection-item active">Alvin</a>
                    <a href="#!" class="collection-item">Alvin</a>
                    <a href="#!" class="collection-item">Alvin</a>
                </div>
            </div>
            <div class="col m8 s12">
                <center><h2>Welcome</h2></center>
            </div>
        </div>
    </div>
@endsection