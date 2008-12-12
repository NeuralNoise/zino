<?php
    
    class ElementUserProfileView extends Element {
        public function Render( tText $name , tText $subdomain, tInteger $commentid , tInteger $pageno ) {
            global $page;
            global $user;
            global $water;
            global $libs;

            $libs->Load( 'relation/relation' );
            $commentid = $commentid->Get();
            $pageno = $pageno->Get();
            $name = $name->Get();
            $subdomain = $subdomain->Get();
            $finder = New UserFinder();

            Element( 'user/subdomainmatch' );

            if ( $name != '' ) {
                if ( strtolower( $name ) == strtolower( $user->Name ) ) {
                    $theuser = $user;
                }
                else {
                    $theuser = $finder->FindByName( $name );
                }
            }
            else if ( $subdomain != '' ) {
                if ( strtolower( $subdomain ) == strtolower( $user->Subdomain ) ) {
                    $theuser = $user;
                }
                else {
                    $theuser = $finder->FindBySubdomain( $subdomain );
                }
            }
            if ( !isset( $theuser ) || $theuser === false ) {
                ?>Ο χρήστης δεν υπάρχει<?php
                return;
            }
            $page->SetTitle( $theuser->Name );

            if ( $user->Id != $theuser->Id && $user->Exists() ) {
                $finder = New FriendRelationFinder();
                $res = $finder->FindFriendship( $user , $theuser );
                if ( !$res ) {
                    $page->AttachInlineScript( 'Profile.ShowFriendLinks( true , " ' .$theuser->Id . ' " );' );
                }
                else {
                    $page->AttachInlineScript( 'Profile.ShowFriendLinks( false , " ' . $res->Id . ' " );' );
                }                    
            } 
            if ( $theuser->LastActivity->Updated != '0000-00-00 00:00:00' ) {
                if ( $theuser->LastActivity->IsOnline() ) {
                    $text = "αυτή τη στιγμή!";
                }
                else {
                    ob_start();
                    Element( 'date/diff' , $theuser->LastActivity->Updated );
                    $text = ob_get_clean();
                }
                $page->AttachInlineScript( 'Profile.ShowOnlineSince( " ' . $text . ' " );' );
            }
            else {
                $page->AttachInlineScript( 'Profile.ShowOnlineSince( false );' );
            }
            $dob = explode( '-', $theuser->Profile->Dob );
            if ( count( $dob ) == 3 && $dob[ 0 ] != '0000' ) {
                $page->AttachInlineScript( 'Profile.CheckBirthday( ' . $dob[ 0 ] . ', ' . $dob[ 1 ] . ', ' . $dob[ 2 ] . ' );' );
            }
            ?><div id="profile"><?php
                $schoolexists = $theuser->Profile->School->Numstudents > 2;
                Element( 'user/profile/sidebar/view' , $theuser , $theuser->Id , $theuser->Profile->Updated, $schoolexists );
                $e = Element( 'user/profile/main/view' , $theuser, $commentid, $pageno );
                if ( $e instanceof HTTPRedirection ) {
                    return $e;
                }
                ?><div class="eof"></div><?php
                Element( 'ad/view', AD_USERPROFILE, $page->XMLStrict() );
            ?></div><?php
        }
    }
?>
