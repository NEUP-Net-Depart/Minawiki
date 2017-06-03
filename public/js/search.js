/**
 * Created by mj on 17-5-24.
 * 用于搜索
 */

/**
 *
 * @param text String
 * @param content 被查找的标签实例
 */
function highLight(text, content) {
    console.log(text);
    clearSelection();

    if ($.trim(content) === '')
        return 1;

    var regular = new RegExp(text, 'g'); // 创建正则表达式对象, g为全局搜索
    var context = content.text();

    if (!regular.test(context)) {
        return 2; // 没找到, 返回2
    }

    // console.log(content.children("p").context.all);

    content.find('p').each(function () {
        // 替换文本
        var html = $(this).text();
        var replaced = html.replace(regular, '<span class="selected">' + text + '</span>');
        $(this).html(replaced);
    })

}

function clearSelection(){
    console.log('clean');
    $('p').each(function(){
        //找到所有highlight属性的元素；
        $(this).find('.selected').each(function(){
            $(this).replaceWith($(this).html());//将他们的属性去掉；
        });
    });
}