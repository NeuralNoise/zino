<?php
    class ControllerSettings {
        public static function View() {
            $usersettings = array();
            if ( isset( $_SESSION[ 'user' ] ) ) {
                $userid = $_SESSION[ 'user' ][ 'id' ];
                clude( "models/usersettings.php" );
                $usersettings = Usersettings::Get( $userid );
            }
            else {
                //you are not logged in
            }            
            clude( "views/settings/view.php" );
        }
        public static function Listing() {
            
        }
       
    }
?>