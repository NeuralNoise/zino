<?php
    class ElementAboutInfoContact extends Element {
        public function Render() {
            global $page;
            
            $page->Title( '�����������' );
            
            Element( 'about/contact/view' ); // TODO!
        }
    }
?>
