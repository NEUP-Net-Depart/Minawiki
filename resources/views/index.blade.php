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
            <div id="left-nav" class="col m4 s12">

            </div>
            <div class="col m8 s12">
                <center><h2>Welcome</h2></center>
            </div>
        </div>
    </div>
    <!-- Add Page Modal -->
    <div id="add_page_modal" class="modal
    @if(isset($power) && $power > 1)
            modal-fixed-footer
            @endif
            ">
        <div class="modal-content">
            <h4 id="add_page_title">添加页面</h4>
            <form class="col s12" id="addPage_fm">
                <div class="row">
                    <div class="input-field col s12">
                        <input name="title" id="add_page_title_input" type="text" class="validate">
                        <label for="add_page_title_input">页面标题</label>
                    </div>
                </div>
                <!-- Admin -->
                @if(isset($power) && $power > 1)
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="row">
                                <div class="col s12">
                                    <div class="switch">
                                        <label>
                                            普通页面
                                            <input id="add_page_is_notice_switch" name="is_notice" type="checkbox">
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
                                            页面
                                            <input id="add_page_is_folder_switch" name="is_folder" type="checkbox">
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
                                            <input id="add_page_allow_folder_switch" name="allow_child_folder" type="checkbox">
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
                                            <input id="add_page_protect_children_switch" name="protect_children" type="checkbox">
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
                                    <select id="add_page_power_select" name="power">
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
                @endif
                <input name="father_id" id="add_page_fatherid_input" type="hidden">
                {!! csrf_field() !!}
            </form>
        </div>
        <div class="modal-footer">
            <a id="add_page_submit" href="#!" class="modal-action waves-effect btn-flat theme-dark theme-lk">确定</a>
            <a href="#!" class="modal-action modal-close waves-effect btn-flat ">关闭</a>
        </div>
    </div>
    <!-- Move Page Modal -->
    <div id="move_page_modal" class="modal">
        <div class="modal-content">
            <h4>移动页面</h4>
            <form id="movePage_fm" class="col s12">
                <div class="row">
                    <div class="input-field col s12">
                        <input placeholder="父页面标题" name="father_title" type="text" class="validate">
                        <label for="title">移动到</label>
                    </div>
                </div>
                {!! csrf_field() !!}
            </form>
        </div>
        <div class="modal-footer">
            <a id="move_page_submit" i href="#!" class="modal-action waves-effect btn-flat theme-dark theme-lk">确定</a>
            <a href="#!" class="modal-action modal-close waves-effect btn-flat ">关闭</a>
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
                        <p>
                            输入
                            <a id="del_page_prompt" href="#!" class="red white-text btn-flat" style="text-transform: none">我明白这样做的后果并且要删除</a>
                            来确认删除。
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s12">
                        <input id="del_page_input" type="text" class="validate">
                        <label for="del_page_input">删除确认字段</label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <a id="del_page_submit" href="#!" class="modal-action waves-effect red white-text btn-flat">我明白这样做的后果并且要删除</a>
            <a href="#!" class="modal-action modal-close waves-effect btn-flat ">取消</a>
        </div>
    </div>
    <script>
        function loadLeftNav() {
            $('#left-nav').html('<center>\
                <div class="preloader-wrapper big active center" style="margin-top: 30px">\
                <div class="spinner-layer theme-border-dark">\
                <div class="circle-clipper left">\
                <div class="circle"></div>\
                </div><div class="gap-patch">\
                <div class="circle"></div>\
                </div><div class="circle-clipper right">\
                <div class="circle"></div>\
                </div>\
                </div>\
                </div>\
                </center>\
                ');
            $.ajax({
                type: "GET",
                url: "/page/left-nav/{{ $current_page->title }}",
                success: function (msg) {
                    $('#left-nav').html(msg);
                }
            });
        }
        function showAddPageModal() {
            $('#add_page_title').html('添加页面');
            $('#add_page_title_input').val("");
            $('#add_page_fatherid_input').val({{ $left_data_page->id }});
            @if(isset($power) && $power > 1)
            $('#add_page_power_select').val(0);
            $('#add_page_is_folder_switch').removeAttr('checked');
            $('#add_page_is_notice_switch').removeAttr('checked');
            $('#add_page_protect_children_switch').removeAttr('checked');
            $('#add_page_allow_folder_switch').removeAttr('checked');
            @endif
            $('#add_page_submit').attr('href', 'javascript: addPage()');
            $('#add_page_modal').modal('open');
        }
        function showEditPageModal(title) {
            $('#add_page_title').html('修改页面');
            $('#add_page_title_input').val($('#' + title + "_title").val());
            $('#add_page_fatherid_input').val($('#' + title + "_father_id").val());
            @if(isset($power) && $power > 1)
            if ($('#' + title + "_is_folder").val() == "1")
                $('#add_page_is_folder_switch').prop('checked', 'checked');
            else
                $('#add_page_is_folder_switch').removeAttr('checked');
            if ($('#' + title + "_is_notice").val() == "1")
                $('#add_page_is_notice_switch').prop('checked', 'checked');
            else
                $('#add_page_is_notice_switch').removeAttr('checked');
            if ($('#' + title + "_protect_children").val() == "1")
                $('#add_page_protect_children_switch').prop('checked', 'checked');
            else
                $('#add_page_protect_children_switch').removeAttr('checked');
            if ($('#' + title + "_allow_folder").val() == "1")
                $('#add_page_allow_folder_switch').prop('checked', 'checked');
            else
                $('#add_page_allow_folder_switch').removeAttr('checked');
            $('#add_page_power_select').val($('#' + title + "_power").val());
            @endif
            $('#add_page_submit').attr('href', 'javascript: editPage('+ $('#' + title + "_id").val() + ')');
            $('#add_page_modal').modal('open');
            $('#add_page_title_input').focus();
        }
        function showDelPageModal(title) {
            $('#del_page_prompt').html($('#' + title + "_title").val());
            $('#del_page_input').val("");
            $('#del_page_submit').attr('href', 'javascript: delPage('+ $('#' + title + "_id").val() + ')');
            $('#del_page_modal').modal('open');
        }
        function showMovePageModal(title) {
            $('#move_page_submit').attr('href', 'javascript: movePage('+ $('#' + title + "_id").val() + ')');
            $('#move_page_modal').modal('open');
        }
        function addPage() {
            var str_data1 = $("#addPage_fm input[type!=checkbox],#addPage_fm select").map(function () {
                return ($(this).attr("name") + '=' + $(this).val());
            }).get().join("&");
            var str_data2 = $("#addPage_fm input[type=checkbox]").map(function () {
                var is_checked = $(this).is(":checked") ? 1 : 0;
                return ($(this).attr("name") + '=' + is_checked);
            }).get().join("&");
            var str_data = str_data1 + "&" + str_data2;
            $.ajax({
                type: "POST",
                url: "/page",
                data: str_data,
                success: function (msg) {
                    var dataObj = eval("(" + msg + ")");
                    if (dataObj.result == "true") {
                        $('#add_page_modal').modal('close');
                        loadLeftNav();
                    }
                    else if (dataObj.msg == "restricted")
                        Materialize.toast("标题中含有限制字符！", 3000, 'theme-bg-sec');
                    else if (dataObj.msg == "reserved")
                        Materialize.toast("标题名为保留关键字！", 3000, 'theme-bg-sec');
                    else if (dataObj.msg == "page already exists")
                        Materialize.toast("标题已经存在！", 3000, 'theme-bg-sec');
                    else
                        Materialize.toast("有点小问题", 3000, 'theme-bg-sec');
                },
                error: function (xhr) {
                    if (xhr.status == 422) {
                        Materialize.toast('请正确填写相关字段！', 3000, 'theme-bg-sec')
                    } else {
                        Materialize.toast('服务器出错了，请刷新重试', 3000, 'theme-bg-sec')
                    }
                }
            });
        }
        function editPage(id) {
            var str_data1 = $("#addPage_fm input[type!=checkbox],#addPage_fm select").map(function () {
                return ($(this).attr("name") + '=' + $(this).val());
            }).get().join("&");
            var str_data2 = $("#addPage_fm input[type=checkbox]").map(function () {
                var is_checked = $(this).is(":checked") ? 1 : 0;
                return ($(this).attr("name") + '=' + is_checked);
            }).get().join("&");
            var str_data = str_data1 + "&" + str_data2 + "&_method=PUT";
            $.ajax({
                type: "POST",
                url: "/page/" + id,
                data: str_data,
                success: function (msg) {
                    var dataObj = eval("(" + msg + ")");
                    if (dataObj.result == "true") {
                        $('#add_page_modal').modal('close');
                        loadLeftNav();
                    }
                    else if (dataObj.msg == "restricted")
                        Materialize.toast("标题中含有限制字符！", 3000, 'theme-bg-sec');
                    else if (dataObj.msg == "reserved")
                        Materialize.toast("标题名为保留关键字！", 3000, 'theme-bg-sec');
                    else if (dataObj.msg == "page already exists")
                        Materialize.toast("标题已经存在！", 3000, 'theme-bg-sec');
                    else
                        Materialize.toast("有点小问题", 3000, 'theme-bg-sec');
                },
                error: function (xhr) {
                    if (xhr.status == 422) {
                        Materialize.toast('请正确填写相关字段！', 3000, 'theme-bg-sec')
                    } else {
                        Materialize.toast('服务器出错了，请刷新重试', 3000, 'theme-bg-sec')
                    }
                }
            });
        }
        function delPage(id) {
            if($('#del_page_prompt').html() == $('#del_page_input').val())
                $.ajax({
                    type: "POST",
                    url: "/page/" + id,
                    data: "_method=DELETE&_token={!! csrf_token() !!}",
                    success: function (msg) {
                        var dataObj = eval("(" + msg + ")");
                        if (dataObj.result == "true") {
                            $('#del_page_modal').modal('close');
                            loadLeftNav();
                        }
                        else
                            Materialize.toast("有点小问题", 3000, 'theme-bg-sec');
                    },
                    error: function (xhr) {
                        if (xhr.status == 422) {
                            Materialize.toast('请正确填写相关字段！', 3000, 'theme-bg-sec')
                        } else {
                            Materialize.toast('服务器出错了，请刷新重试', 3000, 'theme-bg-sec')
                        }
                    }
                });
            else
                Materialize.toast("请正确地输入删除确认字段", 3000, 'theme-bg-sec');
        }
        function movePage(id) {
            var str_data = $("#movePage_fm input").map(function () {
                return ($(this).attr("name") + '=' + $(this).val());
            }).get().join("&");
            $.ajax({
                type: "POST",
                url: "/page/move/" + id,
                data: str_data,
                success: function (msg) {
                    var dataObj = eval("(" + msg + ")");
                    if (dataObj.result == "true") {
                        $('#move_page_modal').modal('close');
                        loadLeftNav();
                    }
                    else if(dataObj.msg == "father not exist")
                        Materialize.toast("指定的父页面不存在", 3000, 'theme-bg-sec');
                    else if(dataObj.msg == "improper father")
                        Materialize.toast("不恰当的父亲！", 3000, 'theme-bg-sec');
                    else
                        Materialize.toast("有点小问题", 3000, 'theme-bg-sec');
                },
                error: function (xhr) {
                    if (xhr.status == 422) {
                        Materialize.toast('请正确填写相关字段！', 3000, 'theme-bg-sec')
                    } else {
                        Materialize.toast('服务器出错了，请刷新重试', 3000, 'theme-bg-sec')
                    }
                }
            });
        }
        $(document).ready(function () {
            loadLeftNav();
        });
    </script>
@endsection