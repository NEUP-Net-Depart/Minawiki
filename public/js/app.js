$(document).ready(function () {
    $(".button-collapse").sideNav();
    $('.modal').modal();
    $('select').material_select();
});

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
