@extends('layouts.master')

@section('title', '安装')

@section('content')

    <div class="row">
        <form class="col s12 m6" action="/install" method="post">
            <div class="row">
                <div class="col s12">
                    <h3>Set Root Password</h3>
                </div>
                <div class="input-field col s12">
                    <i class="material-icons prefix">phone</i>
                    <input id="icon_telephone" type="tel" name="tel" class="validate">
                    <label for="icon_telephone">Telephone</label>
                </div>
                <div class="input-field col s12">
                    <i class="material-icons prefix">vpn_key</i>
                    <input id="icon_key" type="password" name="password" class="validate">
                    <label for="icon_key">Password</label>
                </div>
                <div class="col s12">
                    <h3>Set Root Page</h3>
                </div>
                <div class="input-field col s12">
                    <i class="material-icons prefix">title</i>
                    <input id="icon_telephone" type="tel" name="title" class="validate">
                    <label for="icon_telephone">Title</label>
                </div>
                {{ csrf_field() }}
                <div class="input-field col s12">
                    <button class="btn waves-effect waves-light black btn-large" type="submit" name="action">Submit
                        <i class="material-icons right">send</i>
                    </button>
                </div>
            </div>
        </form>
    </div>

@endsection