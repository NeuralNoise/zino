<?php
    function ElementUserURL( $theuser, $base = false ) {
        global $xc_settings;
		
        if ( !is_object( $theuser ) ) {
            return;
        }
        if ( !( $theuser instanceof User ) ) {
            return;
        }
        
        if ( $theuser->Subdomain != '' ) {
            echo str_replace( '*', urlencode( $theuser->Subdomain ), $xc_settings[ 'usersubdomains' ] );
        }
        else {
            if ( $base ) {
                return;
            }
            echo 'user/' . urlencode( $theuser->Name );
        }
    }
?>
