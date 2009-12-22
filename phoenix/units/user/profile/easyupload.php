<?php
    function UnitUserProfileEasyupload() {
        ?>$( 'div#easyphotoupload div.modalcontent' ).html( <?php
        ob_start();
        Element( 'user/profile/easyupload' );
        echo w_json_encode( ob_get_clean() );
        ?> ).css( 'padding' , '0' );
        // flash uploader
        var Flash = document.getElementById( 'flashuploader' );
        if ( typeof Flash.AppendPostVar == 'function' ) {
            Flash.AppendPostVar( 'albumid', '0' ); // ego album
            $( '#uploadframe' ).hide();
        }
        $( 'div#easyphotoupload div.modalcontent div ul li' ).click( function() {
            if ( !previousSelection ) {
                var previousSelection = $( 'div#easyphotoupload div.modalcontent div ul li' );
            }
            $( previousSelection ).removeClass( 'selected' );
            $( this ).addClass( 'selected' );
            previousSelection = $( this )[ 0 ];
            var albumname = $( this ).find( 'span img' ).attr( 'alt' );
            var username = GetUsername();
            $( 'div#easyphotoupload div.modalcontent div b' ).empty().append( document.createTextNode( albumname ) );

            // flash uploader
            var Flash = document.getElementById( 'flashuploader' );
            if ( typeof Flash.AppendPostVar == 'function' ) {
                var albumid = $( this ).attr( 'id' ).substr( 6 );
                alert( 'Set album id to ' + albumid );
                Flash.AppendPostVar( 'albumid', albumid );
            }
            else {
                var arguments = $( 'div#easyphotoupload div.modalcontent div.uploaddiv' ).children().attr( "<?php
                if ( UserBrowser() == 'MSIE' ) {
                    $attr = 'src';
                }
                else {
                    $attr = 'data';
                }
                echo $attr;
                ?>" ).split( "&" );
                arguments[ 0 ] = "?p=upload";
                arguments[ 1 ] = "albumid=" + $( this ).attr( 'id' ).substr( 6 );
                $( 'div#easyphotoupload div.modalcontent div.uploaddiv' ).children().attr( "<?php
                echo $attr;
                ?>", arguments.join( "&" ) );
            }
        } );<?php
    }
?>
