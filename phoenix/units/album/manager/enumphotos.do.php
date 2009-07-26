<?php
    function UnitAlbumManagerEnumphotos( tInteger $albumid ) {
        global $libs;
        global $user;
        
        $libs->Load( 'image/image' );
        $libs->Load( 'album' );
        $albumid->Get();
        $album = New Album( $albumid );
        if ( $album->Ownerid == $user->Id ) {?>
            PhotoManager.preEnumphotos();<?php
            $finder = New ImageFinder();
            $images = $finder->FindByAlbum( $album, 0, 400 );
            foreach( $images as $image ) {
                ?>var newli = document.createElement( 'li' );
                newli.attr( "id", <?php echo $image->Id; ?> );
                var newdiv = document.createElement( 'div' );
                $( newdiv ).html( <?php
                ob_start();
                Element( 'image/view' , $image->Id , $image->Userid , $image->Width , $image->Height , IMAGE_CROPPED_100x100 , '' , '' , '' , false , 0 , 0 , 0 );
                echo w_json_encode( ob_get_clean() );
                ?> );
                var dragdiv = document.createElement( 'div' );
                $( dragdiv ).addClass( 'draginfo' );
                $( newdiv ).append( dragdiv ).addClass( "photo" );
                $( newli ).append( newdiv ).css( "display", "list-item" );
                $( "ul.photolist" ).append( newli );
                <?php
            }?>
            PhotoManager.postEnumphotos();<?php
        }
    }
?>