<?php

    class JournalFinder extends Finder {
        protected $mModel = 'Journal';
        
        public function FindByUserId( $userid ) {
            $prototype = New Journal();
            $prototype->UserId = $userid;
            return $this->FindByPrototype( $prototype );
        }
    }
    
    class Journal extends Satori {
        protected $mDbTableAlias = 'journals';
        
        public function Relations() {
            $this->User = $this->HasOne( 'User', 'userid' );
        }
    }

    // this can't be so small..

?>
