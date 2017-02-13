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

//Send text captcha
Route::post('/auth/register/captcha', 'AuthController@sendTextCaptcha');
//Try to register
Route::post('/auth/register', 'AuthController@addUser');
Route::get('/auth/register', 'AuthController@showRegisterView');
//Try to login
Route::post('/auth/login', 'AuthController@login');
Route::get('/auth/logout', 'AuthController@logout');
Route::get('/auth/login', 'AuthController@showLoginView');
Route::get('/auth/forget', 'AuthController@showForgetView');

Route::get('auth/geetest', 'AuthController@getGeetest');
