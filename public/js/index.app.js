/**
 * Created by lijiahao on 2/16/17.
 */
function addPage() {
    var str_data1 = $("#addPage_fm input[type!=checkbox],#addPage_fm select").map(function () {
        return ($(this).attr("name") + '=' + encodeURIComponent($(this).val()));
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
        return ($(this).attr("name") + '=' + encodeURIComponent($(this).val()));
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
                if(id == $('#this_page_id').val())
                    history.go(0);
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
    if ($('#del_page_prompt').html() == $('#del_page_input').val())
        $.ajax({
            type: "POST",
            url: "/page/" + id,
            data: "_method=DELETE&_token=" + $(':input[name=_token]:last').val(),
            success: function (msg) {
                var dataObj = eval("(" + msg + ")");
                if (dataObj.result == "true") {
                    if(id == $('#this_page_id').val())
                        window.location.href = '/' + $('#this_left_data_page_title').val();
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
                if(id == $('#this_page_id').val())
                    history.go(0);
                $('#move_page_modal').modal('close');
                loadLeftNav();
            }
            else if (dataObj.msg == "father not exist")
                Materialize.toast("指定的父页面不存在", 3000, 'theme-bg-sec');
            else if (dataObj.msg == "improper father")
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
function updatePageContent() {
    var title = $('#this_page_title').val();
    var str_data1 = $("#pageContent_fm input[type!=checkbox],#pageContent_fm textarea").map(function () {
        return ($(this).attr("name") + '=' + encodeURIComponent($(this).val()));
    }).get().join("&");
    var str_data2 = $("#pageContent_fm input[type=checkbox]").map(function () {
        var is_checked = $(this).is(":checked") ? 1 : 0;
        return ($(this).attr("name") + '=' + is_checked);
    }).get().join("&");
    var str_data = str_data1 + "&" + str_data2;
    $.ajax({
        type: "POST",
        url: "/" + title + "/update",
        data: str_data,
        success: function (msg) {
            var dataObj = eval("(" + msg + ")");
            if (dataObj.result == "true") {
                $('#page_content').html(dataObj.version['content']);
                $('#page_content').removeAttr('style');
                $('#pageContent_fm').attr('style', 'display: none');
                $('#editPageContentButton').removeAttr('style');
                $('#showPageHistoryButton').removeAttr('style');
                $('#editPageContentReturnButton').attr('style', 'display: none');
                $('#editPageContentSubmitButton').attr('style', 'display: none');
                MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
            }
            else if (dataObj.msg == "invalid title")
                Materialize.toast("页面异常，请刷新重试", 3000, 'theme-bg-sec');
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

function restore(id) {
    var str_data = $("#restore_fm input").map(function () {
        return ($(this).attr("name") + '=' + encodeURIComponent($(this).val()));
    }).get().join("&");
    $.ajax({
        type: "POST",
        url: "/" + $("#this_page_title").val() +"/restore/" + id,
        data: str_data + "&_method=PUT",
        success: function (msg) {
            var dataObj = eval("(" + msg + ")");
            if (dataObj.result == "true") {
                $('#page_content').html(dataObj.version['content']);
                $('#page_content_textarea').html(dataObj.version['original']);
                $('#page_content_textarea').val(dataObj.version['original']);
                $('#page_history').attr('style', 'display: none');
                $('#page_content_container').removeAttr('style');
                $('#showPageHistoryButton').removeAttr('style');
                $('#editPageContentButton').removeAttr('style');
                $('#showPageHistoryReturnButton').attr('style', 'display: none');
                MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
                dropPageContent();
            }
            else if (dataObj.result == "invalid version id")
                Materialize.toast("无效的版本，请刷新页面后重试！", 3000, 'theme-bg-sec');
            else if (dataObj.result == "invalid title")
                Materialize.toast("页面错误，请刷新页面！", 3000, 'theme-bg-sec');
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

function comment() {
    if($('#comment_input').val() == "") {
        Materialize.toast("你什么都没写呀", 3000, 'theme-bg-sec');
        return;
    }
    var str_data1 = $("#comment_fm input").map(function () {
        return ($(this).attr("name") + '=' + encodeURIComponent($(this).val()));
    }).get().join("&");
    var str_data2 = $("#comment_fm textarea").map(function () {
        return ($(this).attr("name") + '=' + encodeURIComponent($(this).val()));
    }).get().join("&");
    var str_data = str_data1 + '&' + str_data2;
    $.ajax({
        type: "POST",
        url: "/" + $("#this_page_title").val() +"/comment",
        data: str_data,
        success: function (msg) {
            var dataObj = eval("(" + msg + ")");
            if (dataObj.result == "true") {
                $("#comment_input").html('');
                $("#comment_input").val('');
                $('#comment_input').trigger('autoresize');
                Materialize.toast("发表成功！", 3000, 'theme-bg-sec');
                $('#latest_comment_container').html('');
                loadComments('latest', 1);
            }
            else if (dataObj.result == "invalid title")
                Materialize.toast("页面错误，请刷新页面！", 3000, 'theme-bg-sec');
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