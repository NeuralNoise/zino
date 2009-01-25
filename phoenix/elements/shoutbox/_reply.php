<?php
    
    class ElementShoutboxReply extends Element {
        protected $mPersistent = array( 'userid' , 'useravatarid' );
        public function Render( $userid , $useravatarid , $user ) {
            global $user;
            
            ?><div class="comment newcomment">
                <div class="who"><a href=""><?php
                    Element( 'user/avatar', $user->Avatar->Id, $user->Id,
                             $user->Avatar->Width, $user->Avatar->Height,
                             $user->Name, 100, 'avatar', '', true, 50, 50 );
                    ?></a>
                </div>
                <div class="text" style="margin-top:-10px">
                    <textarea id="shoutbox_text" rows="2" cols="50" onkeyup="$( '#shoutbox_submit' )[ 0 ].disabled = ( $.trim( this.value ).length == 0 )">Πρόσθεσε ένα σχόλιο στη συζήτηση...</textarea>
                </div>
                <div class="bottom">
                    <input id="shoutbox_submit" type="submit" value="Σχολίασε!" disabled="disabled" />
                </div>
            </div><?php
        }
    }
?>
