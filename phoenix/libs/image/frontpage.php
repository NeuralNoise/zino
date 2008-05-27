<?php
    class FrontpageImageFinder extends Finder {
        public function FindLatest( $offset = 0, $limit = 15 ) {
            $prototype = New FrontpageImage();

            return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Imageid', 'DESC' ) );
        }
    }
    
    class FrontpageImage extends Satori {
        protected $mDbTableAlias = 'imagesfrontpage';
        
        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Image = $this->HasOne( 'Image', 'Imageid' );
        }
    }
?>
