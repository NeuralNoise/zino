<?php
    function UnitUserSettingsUpload( tInteger $imageid ) {
        global $libs;
        
        $libs->Load( 'album' );
        
        $image = New Image( $imageid->Get() );
        
        ?>var inner = <?php
        ob_start();
        Element( 'user/settings/personal/photosmall' , $image );
        echo w_json_encode( ob_get_clean() );
        ?>;
        $( $( 'div#avatarlist ul li' )[ 0 ] ).html( inner ).show();
        $( $( 'div#avatarlist ul li' )[ 0 ] ).html( inner );<?php
        if ( $image->Album->Numphotos == 1 ) {
            ?>Coala.Warm( 'user/settings/avatar' , { imageid : <?php
            echo $image->Id;
            ?> } );<?php
        }
    }
?>
