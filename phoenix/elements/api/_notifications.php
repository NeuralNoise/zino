<?php
    class ElementApiNotifications extends Element {
        public function Render( tInteger $id, tText $authtoken ) {
            global $libs;
            global $rabbit_settings;
            
            $libs->Load( 'user/user' );
            $libs->Load( 'notify/notify' );
            
            $userfinder = New UserFinder();
            $theuser = $userfinder->FindByIdAndAuthtoken( $id->Get(), $authtoken->Get() );
            
            if ( $theuser !== false ) {
                $notifinder = New NotificationFinder();
                $notifs = $notifinder->FindByUser( $theuser );
                if ( !empty( $notifs ) ) {
                    foreach ( $notifs as $notif ) {
                        unset( $notifarray );
                        
                        if ( $notif->Typeid == EVENT_COMMENT_CREATED ) {
                            $notifarray[ 'type' ] = 'comment';
                        }
                        else {
                            $notifarray[ 'type' ] = Notification_GetField( $notif );
                        }
                        $notifarray[ 'id' ] = $notif->Id;
                        
                        //FromUser
                        $notifarray[ 'fromuser' ][ 'subdomain' ] = $notif->FromUser->Subdomain;
                        $notifarray[ 'fromuser' ][ 'name' ] = $notif->FromUser->Name;
                        if ( $notif->FromUser->Avatarid == false ) {
                            $notifarray[ 'fromuser' ][ 'avatar' ][ 'anonymous' ] = true;
                            $notifarray[ 'fromuser' ][ 'avatar' ][ 'thumb150' ] = $rabbit_settings[ 'imagesurl' ] . 'anonymous150.jpg';
                        }
                        else {
                            $notifarray[ 'fromuser' ][ 'avatar' ][ 'id' ] = $notif->FromUser->Avatarid;
                            ob_start();
                            Element( 'image/url', $notif->FromUser->Avatarid , $notif->Fromuserid , IMAGE_CROPPED_150x150 );
                            $notifarray[ 'fromuser' ][ 'avatar' ][ 'thumb150' ] = ob_get_clean();
                        }
                        //Type
                        if ( $notif->Typeid == EVENT_COMMENT_CREATED ) {
                            ob_start();
                            Element( 'url' , $notif->Item );
                            $notifarray[ 'url' ] = ob_get_clean();                         
                            $comment = $notif->Item;
                            $text = $comment->GetText( 30 );
                            $notifarray[ 'comment' ][ 'text' ] = $text;
                            $notifarray[ 'comment' ][ 'id' ] = $comment->Id;
                            if ( mb_strlen( $comment->Text ) > 30 ) {
                                $notifarray[ 'comment' ][ 'text' ] .= "...";
                            }
                            switch ( $comment->Typeid ) {
                                case TYPE_USERPROFILE:
                                    $notifarray[ 'comment' ][ 'type' ] = 'profile';
                                    if ( $comment->Itemid == $notif->Touserid ) {
                                        $notifarray[ 'comment' ][ 'you' ] = true;
                                    }
                                    else {
                                        $notifarray[ 'comment' ][ 'owner' ][ 'subdomain' ] = $notif->Item->Item->Subdomain;
                                        $notifarray[ 'comment' ][ 'owner' ][ 'name' ] = $notif->Item->Item->Name;
                                    }
                                    break;
                                case TYPE_IMAGE:
                                    $notifarray[ 'comment' ][ 'type' ] = 'photo';
                                    $notifarray[ 'comment' ][ 'photo' ][ 'id' ] = $notif->Item->Itemid;
                                    $notifarray[ 'comment' ][ 'photo' ][ 'name' ] = $notif->Item->Item->Name;
                                    ob_start();
                                    Element( 'image/url', $comment->Itemid , $comment->Item->Userid , IMAGE_CROPPED_150x150 );
                                    $notifarray[ 'comment' ][ 'photo' ][ 'thumb150' ] = ob_get_clean();
                                    break;
                                case TYPE_JOURNAL:
                                    $notifarray[ 'comment' ][ 'type' ] = 'journal';
                                case TYPE_POLL:
                                    $notifarray[ 'comment' ][ 'type' ] = 'poll';
                                    $notifarray[ 'comment' ][ 'photo' ][ 'id' ] = $notif->Item->Item->Id;
                                    $notifarray[ 'comment' ][ 'photo' ][ 'title' ] = $notif->Item->Item->Title;
                                    break;
                            }
                            $notifarray[ 'comment' ][ 'answer' ] = !( $notif->Item->Parentid == 0 );
                        }
                        else {
                            switch ( $notif->Typeid ) {
                                case EVENT_FRIENDRELATION_CREATED;
                                    ob_start();
                                    Element( 'user/url' , $notif->Fromuserid , $notif->FromUser->Subdomain );
                                    $notifarray[ 'url' ] = ob_get_clean();
                                    $notifarray[ 'type' ] = 'friendship';
                                    $finder = New FriendRelationFinder();
                                    $hasyou = $finder->FindFriendship( $theuser, $notif->FromUser );
                                    $notifarray[ 'friendship' ][ 'yourfriend' ] = (bool) $hasyou;
                                    break;
                                case EVENT_IMAGETAG_CREATED:
                                    ob_start();
                                    Element( 'url', $notif->Item );
                                    $notifarray[ 'url' ] = ob_get_clean();
                                    $notifarray[ 'type' ] = 'tag';
                                    $image = New Image( $notif->Item->Imageid );
                                    $notifarray[ 'photo' ][ 'id' ] = $notif->Item->Imageid;
                                    ob_start();
                                    Element( 'image/url', $image->Id , $image->Userid , IMAGE_CROPPED_150x150 );
                                    $notifarray[ 'photo' ][ 'thumb150' ] = ob_get_clean();
                                    break;
                                case EVENT_FAVOURITE_CREATED:
                                    ob_start();
                                    Element( 'url', $notif->Item );
                                    $notifarray[ 'url' ] = ob_get_clean();
                                    $notifarray[ 'type' ] = 'favourite';
                                    switch ( $notif->Item->Typeid ) {
                                        case TYPE_IMAGE:
                                            $image = $notif->Item->Item;
                                            $notifarray[ 'favourite' ][ 'type' ] = 'photo';
                                            $notifarray[ 'favourite' ][ 'photo' ][ 'id' ] = $image->Id;
                                            ob_start();
                                            Element( 'image/url', $image->Id , $image->Userid , IMAGE_CROPPED_150x150 );
                                            $notifarray[ 'favourite' ][ 'photo' ][ 'thumb150' ] = ob_get_clean();
                                            $notifarray[ 'favourite' ][ 'photo' ][ 'name' ] = $image->Name;
                                            break;
                                        case TYPE_JOURNAL:
                                            $journal = $notif->Item->Item;
                                            $notifarray[ 'favourite' ][ 'type' ] = 'journal';
                                            $notifarray[ 'journal' ][ 'id' ] = $journal->Id;
                                            $notifarray[ 'journal' ][ 'title' ] = $journal->Title;
                                            break;
                                    }
                            }
                        }
                        $apiarray[] = $notifarray;
                    }
                } else
                {
                    $apiarray = false;
                }
            } else
            {
                $apiarray[ 'error' ][ 'description' ] = "Wrong username or authtoken";
            }
            if ( !$xml ) {
                echo w_json_encode( $apiarray );
            }
            else {
                echo 'XML Zino API not yet supported';
            }
        }
    }
?>
