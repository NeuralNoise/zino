<?php
    class ElementUserProfileSidebarSocialSex extends Element {
        public function Render( $theuser ) {
            if ( $theuser->Profile->Sexualorientation != '-' ) {
                ?><li><strong>Σεξουαλικές προτιμήσεις</strong>
                <?php
                Element( 'user/trivial/sex' , $theuser->Profile->Sexualorientation , $theuser->Gender );
                ?></li><?php
            }
        }
    }
?>
