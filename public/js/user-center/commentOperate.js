/**
 * 与评论相关的操作
 * Created by mj on 17-5-29.
 */

function star(page_title, id) {
    var casenum = $('#' + id.toString() + '_star_casenum').val();
    setStar(casenum, id);
    var str_data = $("#Comment_fm input").map(function () {
        return ($(this).attr("name") + '=' + encodeURIComponent($(this).val()));
    }).get().join("&");
    $.ajax({
        type: "POST",
        url: "/" + page_title + "/comment/" + id.toString() + "/star",
        data: str_data,
        success: function (msg) {
            try {
                var dataObj = eval("(" + msg + ")");
                if (dataObj.result == "true") ;
                else {
                    resetStar(casenum, id);
                    if (dataObj.result == "invalid title")
                        Materialize.toast("页面错误，请刷新页面！", 3000, 'theme-bg-sec');
                    else if (dataObj.result == "invalid comment id")
                        Materialize.toast("评论已不存在！", 3000, 'theme-bg-sec');
                    else
                        Materialize.toast("有点小问题", 3000, 'theme-bg-sec');
                }
            }
            catch (e) {
                resetStar(casenum, id);
            }
        },
        error: function (xhr) {
            resetStar(casenum, id);
            if (xhr.status == 422) {
                Materialize.toast('请正确填写相关字段！', 3000, 'theme-bg-sec')
            } else {
                Materialize.toast('服务器出错了，请刷新重试', 3000, 'theme-bg-sec')
            }
        }
    });
}
function setStar(casenum, id) {
    console.log(id);
    if (casenum == 0) {
        $('#' + id.toString() + '_star').html('&#xE838;');
        $('#' + id.toString() + '_star_badge').html((parseInt($('#' + id.toString() + '_star_badge').html()) + 1).toString());
        $('#' + id.toString() + '_star_badge').removeAttr('style');
        $('#' + id.toString() + '_star_casenum').val(1);
    }
    else if (casenum == 1) {
        $('#' + id.toString() + '_star').html('&#xE838;');
        $('#' + id.toString() + '_star_badge').html((parseInt($('#' + id.toString() + '_star_badge').html()) + 1).toString());
        $('#' + id.toString() + '_star_casenum').val(2);
    }
    else if (casenum == 2) {
        $('#' + id.toString() + '_star').html('&#xE83A;');
        $('#' + id.toString() + '_star_badge').html((parseInt($('#' + id.toString() + '_star_badge').html()) - 2).toString());
        if (parseInt($('#' + id.toString() + '_star_badge').html()) <= 0)
            $('#' + id.toString() + '_star_badge').attr('style', 'display: none');
        $('#' + id.toString() + '_star_casenum').val(0);
    } else {
        console.log(casenum)
    }
}
function resetStar(casenum, id) {
    if (casenum == 0) {
        $('#' + id.toString() + '_star').html('&#xE83A;');
        $('#' + id.toString() + '_star_badge').html((parseInt($('#' + id.toString() + '_star_badge').html()) - 1).toString());
        $('#' + id.toString() + '_star_casenum').val('0');
        if (parseInt($('#' + id.toString() + '_star_badge').html()) <= 0)
            $('#' + id.toString() + '_star_badge').attr('style', 'display: none');
    }
    else if (casenum == 1) {
        $('#' + id.toString() + '_star').html('&#xE838;');
        $('#' + id.toString() + '_star_badge').html((parseInt($('#' + id.toString() + '_star_badge').html()) - 1).toString());
        $('#' + id.toString() + '_star_casenum').val('1');
    }
    else if (casenum == 2) {
        $('#' + id.toString() + '_star').html('&#xE838;');
        $('#' + id.toString() + '_star_badge').html((parseInt($('#' + id.toString() + '_star_badge').html()) + 2).toString());
        $('#' + id.toString() + '_star_badge').removeAttr('style');
        $('#' + id.toString() + '_star_casenum').val('2');
    }
}

function replying(holderID, replyID) {
    $(".reply_Box").hide(); // 先关闭所有回复区域
    setRead('comment_' + holderID); // 设置为已读
    var holder = $("#comment_" + holderID); // 装这个评论的容器
    if (holder.hasClass('hasReplyBox')) {
        $("#reply_Box_" + holderID).show();
    } else {
        holder.append('<ul class="input-field reply_Box" id="reply_Box_'+ holderID +'">' +
            '<textarea id="comment_input_' + replyID + '" name="text" class="materialize-textarea" required></textarea>' +
            '<label class="active" for="comment_input_' + replyID + '" style="width: 20%">发表评论</label>' +
            '<div style="text-align: right;">' +
            '<a href="javascript: comment('+ replyID +')" class="waves-effect waves-light btn theme-dark">发表' +
            '<i class="material-icons right">&#xE163;</i></a>' +
            '</div>'+
            '</ul>');
        holder.addClass('hasReplyBox');
    }
}

function comment(replyID) {

    var comment_input = $('#comment_input_' + replyID);
    if (comment_input.val() === "") {
        Materialize.toast("你什么都没写呀", 3000, 'theme-bg-sec');
        return;
    }
    var str_data = comment_input.map(function () {
        return ($(this).attr("name") + '=' + encodeURIComponent($(this).val()));
    }).get().join("&");

    var page_title = $("#comment_page_" + replyID);

    var token = $("#Comment_fm input").val();

    console.log(token);

    console.log("reply_id=" + replyID + "&" + str_data + "&_token=" + token);
    $.ajax({
        type: "POST",
        url: "/" + page_title.html() + "/comment",
        data: "reply_id=" + replyID + "&" + str_data + "&_token=" + token,
        success: function (msg) {
            var dataObj = eval("(" + msg + ")");
            if (dataObj.result === "true") {
                $("#commentMeList li").remove();
                loadMore('commentMe', 1);
                Materialize.toast("发表成功！", 3000, 'theme-bg-sec');

            }
            else if (dataObj.result === "invalid title")
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

function deleteComment(page_title, id) {
    var str_data = $("#Comment_fm input").map(function () {
        return ($(this).attr("name") + '=' + encodeURIComponent($(this).val()));
    }).get().join("&");
    $.ajax({
        type: "POST",
        url: "/" + page_title + "/comment/" + id.toString(),
        data: str_data + '&_method=DELETE',
        success: function (msg) {
            var dataObj = eval("(" + msg + ")");
            if (dataObj.result == "true") {
                if (dataObj.msg == "delete success") {
                    Materialize.toast("删除成功！", 3000, 'theme-bg-sec');
                    $('#' + id.toString() + '_comment_box').remove();
                }
                else if (dataObj.msg == "ban success")
                    Materialize.toast("屏蔽成功！", 3000, 'theme-bg-sec');
            }
            else if (dataObj.result == "invalid title")
                Materialize.toast("页面错误，请刷新页面！", 3000, 'theme-bg-sec');
            else if (dataObj.result == "invalid comment id")
                Materialize.toast("评论已不存在！", 3000, 'theme-bg-sec');
            else if (dataObj.result == "unauthorized")
                Materialize.toast("权限不足！", 3000, 'theme-bg-sec');
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