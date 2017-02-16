/**
 * Created by lijiahao on 2/16/17.
 */
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
function updatePageContent(title) {
    var str_data = $("#pageContent_fm input,#pageContent_fm textarea").map(function () {
        return ($(this).attr("name") + '=' + $(this).val());
    }).get().join("&");
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
            }
            else if(dataObj.msg == "invalid title")
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
function dropPageContent(title) {
    $('#page_content').removeAttr('style');
    $('#pageContent_fm').attr('style', 'display: none');
}