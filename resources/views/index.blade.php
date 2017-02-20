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
                        <input id="this_page_id" value="{{ $current_page->id }}" type="hidden"
                               style="display: none">
                        <input id="this_page_title" value="{{ $current_page->title }}" type="hidden"
                               style="display: none">
                        <input id="this_left_data_page_title" value="{{ $left_data_page->title }}" type="hidden"
                               style="display: none">
                        <a id="editPageContentSubmitButton" href="javascript: updatePageContent()"
                           style="display: none;"
                           class="btn-floating waves-effect waves-light theme-bg-sec btn right"><i
                                    class="material-icons">&#xE163;<!--send--></i></a>
                        <a id="editPageContentReturnButton" href="javascript: dropPageContent()"
                           style="display: none;"
                           class="btn-floating waves-effect waves-light theme-bg-sec btn right float-margin"><i
                                    class="material-icons">&#xE5C4;<!--arrow_back--></i></a>
                        <a id="showPageHistoryReturnButton" href="javascript: showPageHistoryReturn()"
                           style="display: none;"
                           class="btn-floating waves-effect waves-light theme-bg-sec btn right"><i
                                    class="material-icons">&#xE5C4;<!--arrow_back--></i></a>
                        <a id="showPageHistoryButton" href="javascript: showPageHistory()"
                           class="btn-floating waves-effect waves-light theme-bg-sec btn right"><i
                                    class="material-icons">&#xE889;<!--history--></i></a>
                        @if(isset($uid) && $power >= $current_page->power)
                            <a id="editPageContentButton" href="javascript: editPageContent()"
                               class="btn-floating waves-effect waves-light theme-bg-sec btn right float-margin"><i
                                        class="material-icons left">&#xE3C9;<!--edit--></i></a>
                        @elseif(!isset($uid) && $current_page->power == 0)
                            <a id="editPageContentButton" href="/auth/login?continue={{ urlencode($continue) }}"
                               class="btn-floating waves-effect waves-light theme-bg-sec btn right float-margin"><i
                                        class="material-icons left">&#xE3C9;<!--edit--></i></a>
                        @endif
                    </h2>
                </center>
                <div id="page_content_container" class="row">
                    <div class="col s12 markdown-body" id="page_content">
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
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="message_input" name="message" placeholder="为这条编辑添加一条注释（可选）" type="text"
                                       class="validate">
                                <label for="message_input">编辑注释</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <input type="checkbox" name="is_little" class="filled-in" id="is_little_checkbox"/>
                                <label for="is_little_checkbox">这是一条小编辑</label>
                                <a href="javascript: updatePageContent()"
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
                <div id="comment_pool">
                    <div class="row" id="comment_fm">
                        <input id="comment_reply_id_input" type="hidden" style="display: none;" name="reply_id">
                        <div class="input-field col s12">
                            <textarea id="comment_input" name="text" class="materialize-textarea" required></textarea>
                            <label class="active" for="comment_input">发表评论</label>
                        </div>
                        <div class="input-field col s12" style="margin-top: 0">
                            <div class="col"><p style="margin-top: 0">您所发表的评论都将是匿名的。</p></div>
                            <div class="right">
                                <a href="javascript: comment()" class="waves-effect waves-light btn right theme-dark">发表<i
                                            class="material-icons right">&#xE163;
                                        <!--send--></i></a>
                            </div>
                        </div>
                        {!! csrf_field() !!}
                    </div>
                    <ul class="collection theme-dark-a" style="border-top: 0">
                        <div id="mostpopular_comment_container">

                        </div>
                        <div id="latest_comment_container">

                        </div>
                    </ul>
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
                        <input style="display: none;">
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
                        <input style="display: none">
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
                        <input style="display: none">
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
        function replying(id) {
            $('#comment_reply_id_input').val(id);
            $('#comment_input').attr('placeholder', "回复 " + $('#' + id.toString() + '_comment_signature').text()
                + "： " + $('#' + id.toString() + '_comment_content').text());
            $('#comment_input').focus();
            $("html, body").animate({
                scrollTop: $('#comment_fm').offset().top
            }, 0);
        }
        function loadComments(order, page) {
                $('#' + order + '_comment_container .loadmore').remove();
                $('#' + order + '_comment_container').append('<center class="loading">\
<div class="preloader-wrapper small active center" style="margin-top: 10px; margin-bottom: 10px">\
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
                    url: "/{{ $current_page->title }}/comment?order=" + order + "&page=" + page,
                    success: function (msg) {
                        $('#' + order + '_comment_container .loading').remove();
                        $('#' + order + '_comment_container').append(msg);
                        MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
                        $('.dropdown-button').dropdown({
                                inDuration: 300,
                                outDuration: 225,
                                constrainWidth: false, // Does not change width of dropdown to that of the activator
                                hover: false, // Activate on hover
                                gutter: 0, // Spacing from edge
                                belowOrigin: false, // Displays dropdown below the button
                                alignment: 'right', // Displays dropdown with edge aligned to the left of button
                                stopPropagation: false // Stops event propagation
                            }
                        );
                        //clipclient.clip(document.getElementsByClassName('copyTriggerButton'));
                    }
                });
        }
        function editPageContent() {
            $('#message_input').val('');
            $('#is_little_checkbox').removeAttr('checked');
            $('#page_content').attr('style', 'display: none');
            $('#pageContent_fm').removeAttr('style');
            $('#page_content_textarea').trigger('autoresize');
            $('#editPageContentButton').attr('style', 'display: none');
            $('#showPageHistoryButton').attr('style', 'display: none');
            $('#editPageContentReturnButton').removeAttr('style');
            $('#editPageContentSubmitButton').removeAttr('style');
        }
        function showPageHistory(page) {
            $('#page_content_container').attr('style', 'display: none');
            $('#page_history').removeAttr('style');
            $('#showPageHistoryButton').attr('style', 'display: none');
            $('#editPageContentButton').attr('style', 'display: none');
            $('#showPageHistoryReturnButton').removeAttr('style');
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
                                        + '</div>');
                                    $(el).find('.ct_div').append(
                                            @if(isset($uid) && $power >= $current_page->power)
                                                '<div class="row">' +
                                            '<a class="waves-effect waves-light theme-bg-sec btn right" href="javascript: restore(' +
                                            $(el).find('input[name="id"]').val() +
                                            ')"><i class="material-icons left">&#xE8B3;<!--restore--></i>恢复此版本</a>' +
                                            '</div>' +
                                            @elseif(!isset($uid) && $current_page->power == 0)
                                                '<div class="row">' +
                                            '<a class="waves-effect waves-light theme-bg-sec btn right" href="/auth/login?continue={{ urlencode($continue) }}">' +
                                            '<i class="material-icons left">&#xE8B3;<!--restore--></i>恢复此版本</a>' +
                                            '</div>' +
                                            @endif
                                                '');
                                    MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
                                }
                            });
                        },
                        onClose: function (el) {
                            $(el).find('.ct_div').html('<span class="markdown-body"></span>');
                        }
                    });
                }
            });
        }
        function showPageHistoryReturn() {
            $('#page_content_container').removeAttr('style');
            $('#page_history').attr('style', 'display: none');
            $('#showPageHistoryButton').removeAttr('style');
            $('#editPageContentButton').removeAttr('style');
            $('#showPageHistoryReturnButton').attr('style', 'display: none');
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
            $('select').material_select();
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
        function dropPageContent() {
            $('#page_content').removeAttr('style');
            $('#pageContent_fm').attr('style', 'display: none');
            $('#editPageContentButton').removeAttr('style');
            $('#showPageHistoryButton').removeAttr('style');
            $('#editPageContentReturnButton').attr('style', 'display: none');
            $('#editPageContentSubmitButton').attr('style', 'display: none');
        }
        $(document).ready(function () {
            loadLeftNav();
            loadComments('mostpopular', 1);
            loadComments('latest', 1);
        });
    </script>
@endsection