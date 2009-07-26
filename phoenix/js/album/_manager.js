/*
  DEVELOPER: Chorvus
*/

function upperOffset( jqelement ) {
    //This function returns the very first visible pixel's offset relative to the parent element (accepts a jquery element object)
    if (!jqelement) {
        return false;
    }
    return parseInt(jqelement.position().top) + parseInt(jqelement.css("marginTop")) + 1;
}
function lowerOffset( jqelement ) {
    //This function returns the very last visible pixel's offset relative to the parent element (accepts a jquery element object)
    if (!jqelement) {
        return false;
    }
    return parseInt(jqelement.position().top) + parseInt(jqelement.css("marginTop")) + jqelement.outerHeight() - 1;
}
function isScrollingElementVisible( eltVisible, eltScrolling ) {
    //Returns true only if the WHOLE element is visible (accepts a DOM element and the scrolling container
    //WARNING: Usable only for vertical scrolling
    if ( !eltVisible || !eltScrolling ) {
        return false;
    }
    var scrollPos = eltScrolling.scrollTop;
    var scrollHeight = $( eltScrolling ).height();
    return ( upperOffset( $(eltVisible) ) > scrollPos ) && ( lowerOffset( $(eltVisible) ) < scrollPos + scrollHeight );
}

var PhotoManager = {
    fade: false,
    draginfo: false,
    perpage: 20,
    
    checkEnabledAlbumbs: function() {
        jQuery.each( $("div.albumlist li"), function() {
            if (isScrollingElementVisible(this,$("div.albumlist").get()[0])) {
                if (  $(this).hasClass( "ui-droppable-disabled" ) ) { $(this).droppable( 'enable' ); }
            } else {
                if ( !$(this).hasClass( "ui-droppable-disabled" ) ) { $(this).droppable( 'disable' ); }
            }
        });
        if ( !$( "div.albumlist li.selected" ).hasClass( "ui-droppable-disabled" ) ) { $( "div.albumlist li.selected" ).droppable( 'disable' ); }
    }
    ,
    preEnumphotos: function() {
    }
    ,
    postEnumphotos: function() {
        $( 'div.photo img' ).draggable( { 
            //start: function ( event, ui ) { $("img", this).addClass( "dragging" ); },
            stop: function ( event, ui ) { $("body").css( "cursor", "normal" ); }, //avoid  a common jquery glitch
            handle : 'div.photo > img',
            helper : 'original',
            revert :  'invalid',
            cursor : 'move',
            zIndex : 500,
            scroll: false,
            cursorAt: { cursor: 'move', bottom: 50, left: 50 }
        } );
        //Fade out the helper (or cancel the hover timer) when the drag starts
        $( "div.photo" ).bind( "dragstart", function(event, ui) {
            if (PhotoManager.fade) {
                clearTimeout( PhotoManager.fade ); 
            } else {
                PhotoManager.draginfo.stop();
                PhotoManager.draginfo.fadeOut(100);
            }
        } );
        //Helper Bubble
        $("div.photo img").hover(
            //MouseEnter Event
            function () {
                PhotoManager.draginfo = $("div", $(this).parent().parent());
                if ( !$("img", PhotoManager.draginfo.parent()).hasClass( "ui-draggable-dragging" )) {
                    PhotoManager.fade = setTimeout( function() {
                        if (!PhotoManager.draginfo.parent().hasClass( "ui-draggable-dragging" )) {
                            PhotoManager.draginfo.fadeIn( "fast" );
                        }
                        PhotoManager.fade=false;
                    }, 600 );
                }
            }
            ,
            //MouseOut Event
            function () {
                if (PhotoManager.fade) {
                    clearTimeout( PhotoManager.fade ); 
                } else {
                    PhotoManager.draginfo.fadeOut( "fast" );
                }
            }
        );
    }
    ,
    OnLoad: function () {
        //Album selection
        $( "div.albumlist ul li" ).click( function () {
            $( this ).addClass( "selected" ).siblings().removeClass( "selected" ).droppable( "enable" );
            Coala.Warm( "album/manager/enumphotos", { albumid: $( this ).attr( "id" ) } );
            PhotoManager.checkEnabledAlbumbs();
        } );
        
        //Albums Droppable
        $( "div.albumlist li" ).droppable({
            //hoverClass: 'ui-state-active',	
            accept: "ul#photolist li div span.imageview img",
            tolerance: "pointer",
            areabound: $("div.albumlist"),
            drop: function(event, ui) {
                $(this).removeClass( "dropover");
                ui.draggable.parent().parent().fadeOut( "fast" ).animate( { width: "0" },
                    function() {
                        $(this).closest( "li" ).remove(); 
                        Coala.Warm( "album/manager/move", { 'photoid': $( this ).closest( "li" ).attr( "id" ), 'albumid': ui.droppable.closest( "li" ).attr( "id" ) } );
                        //alert(  + '->' + ui.draggable.closest( "li" ).attr( "id" ) );
                        //TODO:
                        //updatePaginationPage();
                        //animate photo shrink and remove
                    }) 
            },
            over: function(event, ui) {
                if ( !$(this).hasClass( "selected" ) ) {
                    $(this).addClass("dropover");
                }
            },
            out: function(event, ui) {
                if ( $(this).hasClass( "dropover" ) ) {
                    $(this).removeClass( "dropover");
                }
            }
        });
    }
}