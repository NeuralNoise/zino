<?php
    function ActionNotificationEmailReply( tText $rawdata ) {
        global $libs;
        
        $libs->Load( 'notify/emailreplyhandler' );
        
        echo "Calling Notify_EmailReplyParse()...\n";
        
        $rawdata = $rawdata->Get();
        Notify_EmailReplyParse( $rawdata );

        echo "Called Notify_EmailReplyParse()...\n";
    }
?>
