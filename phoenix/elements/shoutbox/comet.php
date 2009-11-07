<?php
    class ElementShoutboxComet extends Element {
        public function Render() {
            global $user, $page;
            
            ob_start();
            ?>Comet.Init(<?php
            echo w_json_encode( uniqid() );
            ?>);
            Comet.Subscribe( 'FrontpageShoutboxNew' );
            Comet.Subscribe( 'FrontpageShoutboxTyping' );
            <?php
            $page->AttachInlineScript( ob_get_clean() );
        }
    }
?>