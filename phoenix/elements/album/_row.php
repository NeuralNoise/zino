<?php
    
    class ElementAlbumRow extends Element {
        public function Render( $album ) {
            global $rabbit_settings;

            if ( $album->Id == $album->Owner->Egoalbumid ) {
                $albumname = '���';
            }
            else {
                $albumname = $album->Name;
            }
        }
    }
?>
