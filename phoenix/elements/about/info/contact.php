<?php
    class ElementAboutInfoContact extends Element {
        public function Render() {
            global $page;
            
            $page->SetTitle( '�����������' );
            
            Element( 'about/contact/view' ); // TODO!
        }
    }
?>
