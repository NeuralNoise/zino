<?php
    class ElementShoutboxReply extends Element {
        protected $mPersistent = array( 'userid' , 'useravatarid' );
        
        public function Render( $userid, $useravatarid, $user ) {
            global $user;
            
            ?><div class="comment newcomment">
                <div class="who"><?php
                    Element( 'user/avatar', $user->Avatarid, $user->Id,
                             $user->Avatar->Width, $user->Avatar->Height,
                             $user->Name, 100, 'avatar', '', true, 50, 50 );
                    ?> 
                </div>
                <div class="text">
                    <input id="shoutbox_text" disabled="disabled" value="" />
                </div>
                <div class="bottom">
                    <div class="typing"></div>
                    <input id="shoutbox_submit" type="submit" value="Σχολίασε!" disabled="disabled" />
                </div>
            </div><?php
        }
    }
?>
