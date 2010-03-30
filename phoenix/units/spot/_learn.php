<?php
    function UnitSpotLearn( tInteger $type, tInteger $id, tArray $info ) {
        global $user;
        global $xc_settings;
        global $libs;
        
        $libs->Load( 'research/spot' );
        
        switch( $type->Get() ) {
            case TYPE_JOURNAL:
                $libs->Load( 'journal/journal' );
                
                $journal = New Journal( $id->Get() );
                if ( !$journal->Exists() ) {
                    ?>alert( 'Item does not exist' );<?php
                    return;
                }
                
                Element( 'url', $journal );
                ?>window.location.href = '<?php
                echo $url;
                ?>';<?php
                break;
            default:
                ?>alert( 'Wrong item type' );<?php
                return;
        }
    }
?>
