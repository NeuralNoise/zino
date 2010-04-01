<?php

    class ElementShoutboxView extends Element {
        public function Render( $shout , $empty ) {
            global $user;
            
            if ( !$empty ) {
                ?><div class="comment" id="s_<?php
                echo $shout->Id;
                ?>">
                    <div class="who"><?php
                        Element( 'user/display' , $shout->Userid , $shout->User->Avatarid , $shout->User, true );
                        ?> eip:
                    </div>
                    <div class="text"><?php
                        echo nl2br( $shout->Text ); // no htmlspecialchars(); the text is already sanitized
                    ?></div>
                </div><?php
            }
            else {
                ?><div class="comment empty" style="border-color:#dee;display:none">
                    <div class="who"><?php
                        Element( 'user/display' , $user->Id , $user->Avatarid , $user, true );
                        ?> eip:
                    </div>
                    <div class="text"></div>
                </div><?php
            }
        }
    }
?>
