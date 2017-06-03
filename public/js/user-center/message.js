/**
 * 和消息盒子相关的功能
 * Created by mj on 17-5-29.
 */

/***
 * 将特定id的消息设置为已读
 * 规定每条消息标签的id为  type_id
 * 显示未读消息数的标签id为  newMessageNum_type
 * 设置全部为已读的按钮的标签id为  setAllReadButton_type
 * @param id
 */
function setRead(id) {
    var type = id.split('_')[0];
    var lable = $('#' + id);

    if (! lable.hasClass('unread'))
        return false;

    var token = $("#Comment_fm input").val();

    console.log('id=' + id + "&_token=" + token);

    lable.removeClass('unread').addClass('read');
    $('#' + id  + ' .setRead').remove();
    $.ajax({
        type: 'post',
        url: '/user/read',
        data: 'id=' + id + "&_token=" + token,
        success: function(msg) {
            console.log(msg);
        },
        error: function (xhr) {
            Materialize.toast(xhr.status, 3000, 'theme-bg-sec')
        }
    });

    // console.log('#newMessageNum_' + type);
    var numberLabel = $('#newMessageNum_' + type);
    if (numberLabel !== []) {
        var number = Number(numberLabel.text());
        number -= 1;
        if (number > 0) {
            numberLabel.text(number);
        } else {
            numberLabel.remove();
            // 不显示, 但是占据原来的空间
            $("#setAllReadButton_" + type).css('visibility', 'hidden');
        }
    }
    return true;
}

function setAllRead(type) {
    $('.' + type + '_message').filter('.unread').each(function (index, element) {
        var id = element.getAttribute('id');
        setRead(id);
    });
}