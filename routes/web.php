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

Route::get('/', 'IndexController@index');

//Initialize geetest
Route::get('auth/geetest','AuthController@getGeetest');
//Register
Route::get('/auth/register', 'AuthController@showRegisterView');
Route::post('/auth/register/captcha', 'AuthController@sendTextCaptcha');
Route::post('/auth/register', 'AuthController@addUser');
//Login
Route::get('/auth/login', 'AuthController@showLoginView');
Route::post('/auth/login', 'AuthController@login');
//Logout
Route::get('/auth/logout', 'AuthController@logout');
//Change password
Route::get('/auth/forget', 'AuthController@showForgetView');
Route::post('/auth/forget/captcha', 'AuthController@sendForgetTextCaptcha');
Route::post('/auth/forget', 'AuthController@changePassword');