<?php
    function UnitBackendNotificationCommentCreated( Comment $comment ) {
        global $libs;
        
        $libs->Load( 'notify/notify' );
        
        $notification = New Notification();
        $notification->Typeid = EVENT_COMMENT_CREATED;
        $notification->Itemid = $comment->Id;
        $notification->Created = $comment->Created;
        $notification->Fromuserid = $comment->Userid;
        $notification->Save();
        
        $finder = New NotificationFinder();
        $finder->DeleteByCommentAndUser( $comment->Parent, $comment->User );

        return false;
    }
?>
