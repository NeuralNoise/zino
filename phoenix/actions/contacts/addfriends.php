<?php
    function ActionContactsAddfriends( tTextArray $approved, tText $email ) {
        global $user;
        global $libs;
        
        $libs->Load( 'relation/relation' );
        
        $email->Get();
              
        foreach ( $approved as $userid ) {
            $userid = $userid->Get();            
            $theuser = New User( $userid );
            if ( $theuser->Exists() ) {
                $relation = New FriendRelation();
                $relation->Userid = $user->Id;
                $relation->Friendid = $theuser->Id;
                $relation->Typeid = 3;
                $relation->Save();
            }
        }
        
        return Redirect( '?p=contactfinder&email=' . urlencode( $email ) . '&step=2' );
    }
?>
