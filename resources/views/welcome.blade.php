@extends('layouts.master')

@section('title', 'Welcome')

@section('content')
    <center><h1>Welcome</h1></center>
    {!! Geetest::render() !!}
@endsection