@extends('layouts.nav-nav')

@section('title', 'Welcome')

@section('content')
    <div class="container">
        <div class="row">
            <div id="left-nav" class="col m4 s12">

            </div>
            <div class="col m8 s12">
                <center>
                    <h2>
                        {{ $current_page->title }}
                        <a href="javascript: showPageHistory()"
                           class="btn-floating waves-effect waves-light theme-bg-sec btn right"><i
                                    class="material-icons">&#xE889;<!--history--></i></a>
                        <a href="javascript: editPageContent()"
                           class="btn-floating waves-effect waves-light theme-bg-sec btn right"
                           style="margin-right: 3px"><i
                                    class="material-icons left">&#xE3C9;<!--edit--></i></a>
                    </h2>
                </center>
                <div id="page_content_container" class="row">
                    <div class="col s12" id="page_content">
                        @if(empty($content))
                            <p>还没有任何内容哦～</p>
                        @else
                            {!! $content->content !!}
                        @endif
                    </div>
                    <form id="pageContent_fm" style="display: none" class="col s12">
                        <div class="row">
                            <div class="input-field col s12">
                                @if(!empty($content))
                                    <textarea name="text" id="page_content_textarea"
                                              class="materialize-textarea">{{ $content->original }}</textarea>
                                @else
                                    <textarea name="text" id="page_content_textarea"
                                              class="materialize-textarea"></textarea>
                                @endif
                                <label for="page_content_textarea">这里是萌萌哒的内容</label>
                            </div>
                            <div class="col s12">
                                <a href="javascript: updatePageContent('{{ $current_page->title }}')"
                                   class="waves-effect waves-light btn-large theme-bg-sec right">提交</a>
                                <a href="javascript: dropPageContent()"
                                   class="waves-effect waves-light btn-large theme-bg-sec right"
                                   style="margin-right: 10px">放弃</a>
                            </div>
                        </div>
                        {!! csrf_field() !!}
                    </form>
                </div>
                <div id="page_history" style="display: none;" class="row">
                </div>
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
                                            <input id="add_page_is_folder_switch" name="is_folder" type="checkbox"
                                                   onchange="folderOnChange()">
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
                                            <input id="add_page_protect_children_switch" name="protect_children"
                                                   type="checkbox">
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
                            <a id="del_page_prompt" href="#!" class="red white-text btn-flat"
                               style="text-transform: none">我明白这样做的后果并且要删除</a>
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
                {!! csrf_field() !!}
            </form>
        </div>
        <div class="modal-footer">
            <a id="del_page_submit" href="#!"
               class="modal-action waves-effect red white-text btn-flat">我明白这样做的后果并且要删除</a>
            <a href="#!" class="modal-action modal-close waves-effect btn-flat ">取消</a>
        </div>
    </div>
    <script src="/js/index.app.js"></script>
    <script>
        function editPageContent() {
            $('#page_content').attr('style', 'display: none');
            $('#pageContent_fm').removeAttr('style');
        }
        function showPageHistory(page) {
            $('#page_content_container').attr('style', 'display: none');
            $('#page_history').removeAttr('style');
            $('#page_history').html('<center>\
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
                url: "/{{ $current_page->title }}/history?page=" + page,
                success: function (msg) {
                    $('#page_history').html(msg);
                    $('.collapsible').collapsible({
                        onOpen: function (el) {
                            $(el).find('span').html('<center>\
<div class="preloader-wrapper big active center">\
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
                                url: "/{{ $current_page->title }}/history/" + $(el).find('input[name="id"]').val(),
                                success: function (msg) {
                                    var dataObj = eval("(" + msg + ")");
                                    $(el).find('span').html('<div class="row">' + dataObj.content
                                        + '</div><div class="row">' +
                                        '<a class="waves-effect waves-light theme-bg-sec btn" href="javascript: restore()"><i class="material-icons left">&#xE8B3;<!--restore--></i>button</a>' +
                                        '</div>');
                                }
                            });
                        }
                    });
                }
            });
        }
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
                url: "/page/left-nav/{{ $current_page->title }}?continue={{ urlencode($continue) }}",
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
            $('#add_page_protect_children_switch').attr('disabled', 'disabled');
            @endif
            $('#add_page_submit').attr('href', 'javascript: addPage()');
            $('#add_page_modal').modal('open');
        }
        function showEditPageModal(title) {
            $('#add_page_title').html('修改页面');
            $('#add_page_title_input').val($('#' + title + "_title").val());
            $('#add_page_fatherid_input').val($('#' + title + "_father_id").val());
            @if(isset($power) && $power > 1)
            if ($('#' + title + "_is_folder").val() == "1") {
                $('#add_page_is_folder_switch').prop('checked', 'checked');
                $('#add_page_protect_children_switch').removeAttr('disabled');
            }
            else {
                $('#add_page_is_folder_switch').removeAttr('checked');
                $('#add_page_protect_children_switch').attr('disabled', 'disabled');
            }
            if ($('#' + title + "_is_notice").val() == "1")
                $('#add_page_is_notice_switch').prop('checked', 'checked');
            else
                $('#add_page_is_notice_switch').removeAttr('checked');
            if ($('#' + title + "_protect_children").val() == "1")
                $('#add_page_protect_children_switch').prop('checked', 'checked');
            else
                $('#add_page_protect_children_switch').removeAttr('checked');
            $('#add_page_power_select').val($('#' + title + "_power").val());
            @endif
            $('#add_page_submit').attr('href', 'javascript: editPage(' + $('#' + title + "_id").val() + ')');
            $('#add_page_modal').modal('open');
            $('#add_page_title_input').focus();
        }
        function showDelPageModal(title) {
            $('#del_page_prompt').html($('#' + title + "_title").val());
            $('#del_page_input').val("");
            $('#del_page_submit').attr('href', 'javascript: delPage(' + $('#' + title + "_id").val() + ')');
            $('#del_page_modal').modal('open');
        }
        function showMovePageModal(title) {
            $('#move_page_submit').attr('href', 'javascript: movePage(' + $('#' + title + "_id").val() + ')');
            $('#move_page_modal').modal('open');
        }
        function folderOnChange() {
            if ($('#add_page_is_folder_switch').is(":checked"))
                $('#add_page_protect_children_switch').removeAttr('disabled');
            else
                $('#add_page_protect_children_switch').attr('disabled', 'disabled');
        }
        $(document).ready(function () {
            loadLeftNav();
        });
    </script>
@endsection