<?php

    class ElementDeveloperUserTrivialUniversity extends Element {
        public function Render( $uni ) {
            if ( $uni->Exists() ) {
                echo htmlspecialchars( $uni->Name );
            }
        }
    }
?>
