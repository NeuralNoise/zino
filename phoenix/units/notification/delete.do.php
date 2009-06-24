<?php
    
    function UnitNotificationDelete( tInteger $notificationid , tBoolean $relationnotif ) {
        global $user;
        global $libs;
        
        $notificationid = $notificationid->Get();
        $relationnotif = $relationnotif->Get();

        $libs->Load( 'notify' );
        
        $notif = New Notification( $notificationid );
        if ( $notif->Exists() ) {
            if ( $notif->Touserid == $user->Id ) {
                $theuser = $notif->FromUser;
                $notif->Delete();
            }
        }
        if ( $relationnotif ) {
            ?>document.location.href = <?php
            ob_start();
            Element( 'user/url' , $theuser->Id , $theuser->Subdomain );
            echo w_json_encode( ob_get_clean() );
            ?>;<?php
        }
    }
?>
