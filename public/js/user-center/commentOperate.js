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