<?php
    class ElementUserSettingsEmailValidate extends Element {
        public function Render( tInteger $userid, tString $hash ) {
            global $libs;
            global $user;
            
            $libs->Load( 'user/profile' );
            
            $userid = $userid->Get();
            $hash = $hash->Get();
            
            if ( !ValidateEmail( $userid, $hash ) ) {
                ?><p>Η επιβεβαίωση του e-mail σου δεν ήταν δυνατό να πραγματοποιηθεί.<br />
                Παρακαλούμε ξαναδοκίμασε.</p><?php
                return;
            }
            
            $myuser = New User( $userid );
            $myuser->UpdateLastLogin();
            $myuser->Save();
            $_SESSION[ 's_userid' ] = $myuser->Id;
            $_SESSION[ 's_authtoken' ] = $myuser->Authtoken;
            User_SetCookie( $myuser->Id, $myuser->Authtoken );
            return Redirect( '?p=joined' );
        }
    }
?>
