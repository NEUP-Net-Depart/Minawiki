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
                <!--Admin-->
                <ul class="collection theme-word-dark theme-sec-i">
                    <li class="collection-item">
                        <div>
                            <a href="#!">Alvin</a><a href="#del_page_modal" class="secondary-content"><i
                                        class="material-icons">
                                    &#xE872;<!--delete--></i></a><a href="#move_page_modal" class="secondary-content"><i
                                        class="material-icons">&#xE89F;<!--open_with--></i></a><a
                                    href="#edit_page_modal"
                                    class="secondary-content"><i
                                        class="material-icons">&#xE150;<!--create--></i></a></div>
                    </li>
                    <li class="collection-item">
                        <div>
                            <a href="#!">Alvin</a><a href="#del_page_modal" class="secondary-content"><i
                                        class="material-icons">
                                    &#xE872;<!--delete--></i></a><a href="#move_page_modal" class="secondary-content"><i
                                        class="material-icons">&#xE89F;<!--open_with--></i></a><a
                                    href="#edit_page_modal"
                                    class="secondary-content"><i
                                        class="material-icons">&#xE150;<!--create--></i></a></div>
                    </li>
                    <li class="collection-item">
                        <div>
                            <a href="#!">Alvin</a><a href="#del_page_modal" class="secondary-content"><i
                                        class="material-icons">
                                    &#xE872;<!--delete--></i></a><a href="#move_page_modal" class="secondary-content"><i
                                        class="material-icons">&#xE89F;<!--open_with--></i></a><a
                                    href="#edit_page_modal"
                                    class="secondary-content"><i
                                        class="material-icons">&#xE150;<!--create--></i></a></div>
                    </li>
                    <li class="collection-item">
                        <div>
                            <a href="#!">Alvin</a><a href="#del_page_modal" class="secondary-content"><i
                                        class="material-icons">
                                    &#xE872;<!--delete--></i></a><a href="#move_page_modal" class="secondary-content"><i
                                        class="material-icons">&#xE89F;<!--open_with--></i></a><a
                                    href="#edit_page_modal"
                                    class="secondary-content"><i
                                        class="material-icons">&#xE150;<!--create--></i></a></div>
                    </li>
                    <a href="#add_page_modal" class="collection-item modal-trigger" style="text-align: center"><i
                                class="material-icons">
                            &#xE147;</i></a>
                </ul>
                <!--User-->
                <div class="collection theme-word-dark">
                    <a href="#!" class="collection-item"><span class="badge">1</span>Alvin</a>
                    <a href="#!" class="collection-item"><span class="badge">1</span>Alvin</a>
                    <a href="#!" class="collection-item active"><span class="badge">1</span>Alvin</a>
                    <a href="#!" class="collection-item"><span class="badge">1</span>Alvin</a>
                    <a href="#add_page_modal" class="collection-item modal-trigger" style="text-align: center"><i
                                class="material-icons">
                            &#xE147;</i></a>
                </div>
            </div>
            <div class="col m8 s12">
                <center><h2>Welcome</h2></center>
            </div>
        </div>
    </div>
    <!-- Add Page Modal -->
    <div id="add_page_modal" class="modal modal-fixed-footer">
        <div class="modal-content">
            <h4>添加页面</h4>
            <form class="col s12">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="title" type="text" class="validate">
                        <label for="title">页面标题</label>
                    </div>
                </div>
                <!-- Admin -->
                <div class="row">
                    <div class="col s12 m6">
                        <div class="row">
                            <div class="col s12">
                                <div class="switch">
                                    <label>
                                        普通页面
                                        <input type="checkbox">
                                        <span class="lever"></span>
                                        公告页面
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <div class="switch">
                                    <label>
                                        文件
                                        <input type="checkbox">
                                        <span class="lever"></span>
                                        文件夹
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <div class="switch">
                                    <label>
                                        <input type="checkbox">
                                        <span class="lever"></span>
                                        允许子目录
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <div class="switch">
                                    <label>
                                        <input type="checkbox">
                                        <span class="lever"></span>
                                        锁定目录
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 m6">
                        <div class="row">
                            <div class="input-field col s12">
                                <select>
                                    <option value="0" selected>登录的用户</option>
                                    <option value="1">管理员</option>
                                    <option value="2">超级管理员</option>
                                    <option value="4">root</option>
                                </select>
                                <label>编辑权限</label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">确定</a>
        </div>
    </div>
    <!-- Alter Page Modal -->
    <div id="edit_page_modal" class="modal">
        <div class="modal-content">
            <h4>修改页面</h4>
            <form class="col s12">
                <div class="row">
                    <div class="input-field col s12">
                        <input id="title" type="text" class="validate">
                        <label for="title">页面标题</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">确定</a>
        </div>
    </div>
    <!-- Move Page Modal -->
    <div id="move_page_modal" class="modal">
        <div class="modal-content">
            <h4>移动页面</h4>
            <form class="col s12">
                <div class="row">
                    <div class="input-field col s12 m6">
                        <input id="title" type="text" class="validate">
                        <label for="title">父页面 id</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <select>
                            <option value="" disabled selected>可以从这里选择</option>
                            <option value="1">Java</option>
                            <option value="2">C#</option>
                            <option value="3">PHP</option>
                        </select>
                        <label>选择页面</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">确定</a>
        </div>
    </div>
    <!-- Del Page Modal -->
    <div id="del_page_modal" class="modal">
        <div class="modal-content">
            <h4>删除页面</h4>
            <form class="col s12">
                <div class="row">
                    <div class="input-field col s12">
                        <p>你确定要删除吗？</p>
                        <p>输入URL。</p>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="title" type="text" class="validate">
                        <label for="title">删除确认字段</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">我明白这样做的后果并且要删除</a>
        </div>
    </div>
@endsection