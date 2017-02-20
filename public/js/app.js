$(document).ready(function () {
    $(".button-collapse").sideNav();
    $('.modal').modal();
    $('select').material_select();
});

function setCookie(c_name, value, expiredays) {
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + expiredays);
    document.cookie = c_name + "=" + escape(value) +
        ((expiredays == null) ? "" : ";expires=" + exdate.toGMTString())
}

function getCookie(c_name) {
    if (document.cookie.length > 0) {
        c_start = document.cookie.indexOf(c_name + "=");
        if (c_start != -1) {
            c_start = c_start + c_name.length + 1;
            c_end = document.cookie.indexOf(";", c_start);
            if (c_end == -1) c_end = document.cookie.length;
            return unescape(document.cookie.substring(c_start, c_end));
        }
    }
    return ""
}

/*
 var clipclient = new ZeroClipboard();

 clipclient.on( "ready", function( readyEvent ) {
 // alert( "ZeroClipboard SWF is ready!" );

 clipclient.on( "aftercopy", function( event ) {
 // `this` === `client`
 // `event.target` === the element that was clicked
 //event.target.style.display = "none";
 alert("Copied text to clipboard: " + event.data["text/plain"] );
 } );
 } );*/
