<?php

    class ElementDeveloperUserSettingsCharacteristicsHaircolor extends Element {
        public function Render() {
            global $user;
            
            ?><select><?php
                $hairs = array( '-' , 'black' , 'brown' , 'red' , 'blond' , 'highlights' , 'grey' , 'skinhead' );
                foreach ( $hairs as $hair ) {
                    ?><option value="<?php
                    echo $hair;
                    ?>"<?php
                    if ( $user->Profile->Haircolor == $hair ) {
                        ?> selected="selected"<?php
                    }
                    ?>><?php
                    Element( 'developer/user/trivial/haircolor' , $hair );
                    ?></option><?php
                }
            ?></select><?php
        }
    }
?>
