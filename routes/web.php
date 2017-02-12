<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

//Just for view test
Route::get('/register', function () {
    return view('auth.register');
});

//Just for view test
Route::get('/login', function () {
    return view('auth.login');
});