/**
 * Created by mj on 17-5-29.
 * 各种加载更多
 */

/**
 * 加载更多
 * 规定容器(列表)标签的id为  title+'List'
 * 向服务器请求的地址为  'load' + title (遵循驼峰命名, title首字母为大写)
 * @param title
 * @param page
 */
function loadMore(title, page) {
    var container = title + 'List';
    title = title.charAt(0).toUpperCase() + title.slice(1); // 将title的首字母转化成大写
    var url = "load" + title+"?page=" + page;
    console.log(container+"   " + url);
    $("#" + container + " .loadMore").remove();
    $("#" + container).append('<center class="loading">\
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
        url: url,
        type: "get",
        success: function (msg) {
            $("#" + container + " .loading").remove();
            $("#" + container).append(msg);
        },
        error: function (xhr) {
            Materialize.toast('服务器错误:'+xhr.status, 3000, 'theme-bg-sec');
        }
    })
}