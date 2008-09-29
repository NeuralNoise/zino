<?php
    class ElementNotificationView extends Element {
        public function Render( $notif ) {
            global $rabbit_settings;
            global $libs;
            global $user;
            
            $libs->Load( 'relation/relation' );
            $libs->Load( 'image/tag' );

            if ( !$notif->Event->Exists() ) {
                return;
            }

            ?><div class="event" id="<?php
            echo $notif->Event->Id;
            ?>">
                <div class="toolbox">
                    <span class="time"><?php
                    Element( 'date/diff', $notif->Event->Created );
                    ?></span>
                    <a href="" onclick="return Notification.Delete( '<?php
                    echo $notif->Event->Id;
                    ?>' )" title="Διαγραφή"><img src="<?php
                    echo $rabbit_settings[ 'imagesurl' ];
                    ?>delete.png" /></a>
                </div>
                <div class="who"<?php
                if ( $notif->Event->Typeid != EVENT_FRIENDRELATION_CREATED
                      && $notif->Event->Typeid != EVENT_IMAGETAG_CREATED
                      && $notif->Event->Typeid != EVENT_FAVOURITE_CREATED ) {
                    ?> onclick="Notification.Visit( '<?php
                    ob_start();
                    Element( 'url' , $notif->Item );
                    echo htmlspecialchars( ob_get_clean() );
                    ?>' , '<?php
                    echo $notif->Event->Item->Typeid;
                    ?>' , '<?php
                    echo $notif->Event->Id;
                    ?>' , '<?php
                    echo $notif->Event->Item->Id;
                    ?>' );"<?php
                }
                ?>><?php
                    Element( 'user/avatar' , $notif->FromUser->Avatar->Id , $notif->FromUser->Id , $notif->FromUser->Avatar->Width , $notif->FromUser->Avatar->Height , $notif->FromUser->Name , 100 , 'avatar' , '' , true , 50 , 50 );
                    Element( 'user/name' , $notif->FromUser->Id , $notif->FromUser->Name , $notif->FromUser->Subdomain , false );
                    switch ( $notif->Event->Typeid ) {
                        case EVENT_FRIENDRELATION_CREATED:
                            ?> σε πρόσθεσε στους φίλους:<?php
                            break;
                        case EVENT_IMAGETAG_CREATED:
                            ?> σε αναγνώρισε:<?php
                            break;
                        case EVENT_FAVOURITE_CREATED:
                            ?> πρόσθεσε στα αγαπημένα:<?php
                            break;
                        default:
                            if ( $notif->Item->Parentid == 0 ) {
                                ?> έγραψε:<?php
                            }
                            else {
                                ?> απάντησε στο σχόλιό σου:<?php
                            }
                            break;
                    }
                ?></div>
                <div class="subject"<?php
                switch ( $notif->Event->Typeid ) {
                    case EVENT_IMAGETAG_CREATED:
                        ?> onclick="Notification.Visit( '<?php
                        ob_start();
                        Element( 'url', $notif->Item );
                        echo htmlspecialchars( ob_get_clean() );
                        ?>' , '0', '<?php
                        echo $notif->Event->Id;
                        ?>', '0' );"<?php
                    break;
                    case EVENT_FRIENDRELATION_CREATED:
                        break;
                    case EVENT_FAVOURITE_CREATED:
                        ?> onclick="Notification.Visit( '<?php
                        ob_start();
                        Element( 'url', $notif->Item );
                        echo htmlspecialchars( ob_get_clean() );
                        ?>' , '0', '<?php
                        echo $notif->Event->Id;
                        ?>', '0' );"<?php
                        break;
                    default: // Comment
                        ?> onclick="Notification.Visit( '<?php
                        ob_start();
                        Element( 'url' , $notif->Item );
                        echo htmlspecialchars( ob_get_clean() );
                        ?>' , '<?php
                        echo $notif->Event->Item->Typeid;
                        ?>' , '<?php
                        echo $notif->Event->Id;
                        ?>' , '<?php
                        echo $notif->Event->Item->Id;
                        ?>' );"<?php
                        break;
                }
                ?>><?php
                    switch ( $notif->Event->Typeid ) {
                        case EVENT_FRIENDRELATION_CREATED:
                            $finder = New FriendRelationFinder();
                            $res = $finder->FindFriendship( $user , $notif->FromUser );
                            if ( !$res ) {
                                ?><div class="addfriend" id="addfriend_<?php
                                echo $notif->Fromuserid;
                                ?>"><a href="" onclick="return Notification.AddFriend( '<?php
                                echo $notif->Event->Id;
                                ?>' , '<?php
                                echo $notif->FromUser->Id;
                                ?>' )">Πρόσθεσέ τ<?php
                                if ( $notif->FromUser->Gender == 'f' ) {
                                    ?>η<?php
                                }
                                else {
                                    ?>o<?php
                                }
                                ?>ν στους φίλους</a></div><?php
                            }
                            ?><div class="viewprofile"><a href="" onclick="Notification.Visit( '<?php
                            Element( 'user/url' , $notif->FromUser->Id , $notif->FromUser->Subdomain );
                            ?>' , '0' , '<?php
                            echo $notif->Event->Id;
                            ?>' , '0' );return false">Προβολή προφίλ&raquo;</a></div><?php
                            break;
                        case EVENT_IMAGETAG_CREATED:
                            ?><p><?php
                            $image = New Image( $notif->Item->Imageid );
                            if ( $image->Name != '' ) {
                                ?>στην εικόνα "<?php
                                echo htmlspecialchars( $image->Name );
                                ?>"<?php
                            }
                            else if ( $image->Album->Id == $image->User->Egoalbumid ) {
                                ?>στις φωτογραφίες <?php
                                if ( $image->User->Id == $user->Id ) {
                                    ?>σου<?php
                                }
                                else if ( $image->User->Gender == 'f' ) {
                                    ?>της <?php
                                }
                                else {
                                    ?>του <?php
                                }
                                if ( $image->User->Id != $user->Id ) {
                                    echo htmlspecialchars( $image->User->Name );
                                }
                            }
                            else {
                                ?>σε μια εικόνα του Album "<?php
                                echo htmlspecialchars( $image->Album->Name );
                                ?>"<?php
                            }
                            Element( 'image/view' , $image->Id , $image->User->Id , $image->Width , $image->Height , IMAGE_CROPPED_100x100 , '' , $image->Name , $image->Name , '' , true , 75 , 75 );
                            ?></p><?php
                            break;
                        case EVENT_FAVOURITE_CREATED:
                            ?><p><?php
                            switch ( $notif->Item->Typeid ) {
                                case TYPE_IMAGE:
                                    $image = $notif->Item->Item;
                                    if ( $image->Name != '' ) {
                                        ?>την εικόνα "<?php
                                        echo htmlspecialchars( $image->Name );
                                        ?>"<?php
                                    }
                                    else if ( $image->Album->Id == $image->User->Egoalbumid ) {
                                        ?>μια φωτογραφία σου<?php
                                    }
                                    else {
                                        ?>μια εικόνα του Album "<?php
                                        echo htmlspecialchars( $image->Album->Name );
                                    }
                                    Element( 'image/view' , $image->Id , $image->User->Id , $image->Width , $image->Height , IMAGE_CROPPED_100x100 , '' , $image->Name , $image->Name , '' , true , 75 , 75 );
                                    
                                    break;
                                case TYPE_JOURNAL:
                                    $journal = $notif->Item->Item;
                                    ?>Το ημερολόγιό σου <a href="<?php
                                    ob_start();
                                    Element( 'url', $journal );
                                    echo htmlspecialchars( ob_get_clean() );
                                    ?>"><?php
                                    echo htmlspecialchars( $journal->Title );
                                    ?></a><?php
                                    break;
                            }
                            ?></p><?php
                            break;
                        default: // Comment
                            ?><p><span class="text">"<?php
                            $comment = $notif->Item;
                            $text = $comment->GetText( 30 );
                            echo $text;
                            if ( mb_strlen( $comment->Text ) > 30 ) {
                                ?>...<?php
                            }
                            ?>"</span>
                            , <?php
                            switch ( $comment->Typeid ) {
                                case TYPE_USERPROFILE:
                                    ?>στο προφίλ <?php
                                    if ( $comment->Item->Id == $notif->Touserid ) {
                                        ?>σου<?php
                                    }
                                    else {
                                        if ( $notif->FromUser->Gender == 'f' ) {
                                            ?>της <?php
                                        }
                                        else {
                                            ?>του <?php
                                        }
                                        if ( $notif->Fromuserid != $comment->Item->Userid ) {
                                            ?><a href="<?php
                                            ob_start();
                                            Element( 'url', $comment );
                                            echo htmlspecialchars( ob_get_clean() );
                                            ?>" class="itempic"><?php
                                            Element( 'user/avatar' , $user->Avatar->Id , $user->Id , $user->Avatar->Width , $user->Avatar->Height , $user->Name , IMAGE_CROPPED_100x100 , '' , '' , false , 0 , 0 );
                                            ?></a><?php
                                        }
                                    }
                                    break;
                                case TYPE_POLL:
                                    ?>στη δημοσκόπηση "<?php
                                    echo htmlspecialchars( $comment->Item->Title );
                                    ?>"<?php
                                    break;
                                case TYPE_IMAGE:
                                    ?>στη φωτογραφία <?php
                                    Element( 'image/view' , $comment->Item->Id , $comment->Item->User->Id , $comment->Item->Width , $comment->Item->Height , IMAGE_CROPPED_100x100 , '' , $comment->Item->Name , $comment->Item->Name , '' , true , 75 , 75 );
                                    break;
                                case TYPE_JOURNAL:
                                    ?>στο ημερολόγιο "<?php
                                    echo htmlspecialchars( $comment->Item->Title );
                                    ?>"<?php
                                    break;
                            }
                            ?></p>
                            <div class="eof"></div><?php
                            break;
                    }
                    ?><div class="eof"></div>
                </div>
            </div><?php
        }
    }
?>
