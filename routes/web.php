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

//Send text captcha
Route::post('/auth/register/captcha', 'AuthController@sendTextCaptcha');
//Try to register
Route::post('/auth/register', 'AuthController@addUser');
Route::get('/auth/register', 'AuthController@addUser');
//Try to login
Route::post('/auth/login', 'AuthController@login');
Route::get('/auth/login', 'AuthController@login');
//Logout
Route::get('/auth/logout', 'AuthController@logout');