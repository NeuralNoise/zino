<?php
    class ElementApiMain extends Element {
        public function Render() {
            header( 'Content-type: application/json' );
            
            ob_start();
            $res = Element::MasterElement();
            $master = ob_get_clean();
            
            echo $master;
            
            return $res;
        }
    }
?>