<?php
    function UnitShoutboxNew( tText $text, tInteger $channel, tCoalaPointer $node, tCoalaPointer $f ) {
        global $user;
        global $libs;
        
        $libs->Load( 'wysiwyg' );
        $libs->Load( 'chat/message' );
        $libs->Load( 'comet' );
        
        $text = $text->Get();
		$channel = $channel->Get();
        if ( !$user->Exists() ) {
            ?>alert( "Πρέπει να είσαι συνδεδεμένος για να συμμετέχεις στην συζήτηση" );
            window.location.reload();<?php
            return;
        }
        
        if ( trim ( $text ) == '' ) {
            ?>alert( "Δεν μπορείς να δημοσιεύσεις κενό μήνυμα" );
            window.location.reload();<?php
            return;
        }
        
        $shout = New Shout();
        $shout->Text = WYSIWYG_PostProcess( htmlspecialchars( $text ) ); // TODO: WYSIWYG
		$shout->Channelid = $channel; // TODO: ensure only participants can send messages
        $shout->Save();
        
        ?>var node = <?php
        echo $node;
        ?>;
        $( node )
        .attr( {
            id : "s_<?php
            echo $shout->Id;
            ?>" } );
        var text = <?php
            echo w_json_encode( $shout->Text );
        ?>;

        if ( $.browser.msie ) {
            $( node ).find( 'div.text' ).html( text );
        }
        else {
            $( node ).find( 'div.text' ).html( text.replace( /&nbsp;/g, ' ' ) );
        }
        <?php
        if ( $f->Exists() ) {
            echo $f;
            ?>();<?php
        }
    }
?>
