<?php
    
    function UnitAlbumDelete( tInteger $albumid ) {
        global $user;
        global $rabbit_settings;
        global $libs;
        
        $libs->Load( 'album' );
        $albumid = $albumid->Get();
        $album = New Album( $albumid );
        if ( $album->Ownertype == TYPE_USERPROFILE && $album->Owner->Id == $user->Id ) {
            if ( $album->Id != $user->Egoalbumid ) {
                $useralbum = $album->Owner->Name;
                $album->Delete();
                ?>window.location.href = '<?php
                echo $rabbit_settings[ 'webaddress' ];
                ?>?p=albums&username=<?php
                echo $album->Owner->Name;
                ?>';<?php
            }
        }
    }
?>
