<?php
    class ElementUserProfileMainView extends Element {
        public function Render( $theuser, $commentid, $pageno ) {
            global $libs;
            global $user;
            global $water;
            global $xc_settings;
            global $page;
            global $rabbit_settings;
            
            $libs->Load( 'poll/poll' );
            $libs->Load( 'comment' );
            $libs->Load( 'relation/relation' );
            $libs->Load( 'user/statusbox' );
            $libs->Load( 'journal/journal' );
            $libs->Load( 'user/count' );
            $libs->Load( 'album' );
            $libs->Load( 'rabbit/helpers/http' );
            
            if ( $user->HasPermission( PERMISSION_COMMENT_VIEW ) ) {
            // if ( $theuser->Profile->Numcomments > 0 ) { // duh, problem here!
                $finder = New CommentFinder();
                if ( $commentid == 0 ) {
                    $comments = $finder->FindByPage( $theuser, $pageno, 0, 100000, true );
                    $total_pages = $comments[ 0 ];
                    $comments = $comments[ 1 ];
                }
                else {
                    $speccomment = New Comment( $commentid );
                    $comments = $finder->FindNear( $theuser, $speccomment, 0, 100000, true );
                    if ( $comments === false ) {
                        ob_start();
                        Element( 'user/url', $theuser->Id, $theuser->Subdomain );
                        return Redirect( ob_get_clean() );
                    }
                    $total_pages = $comments[ 0 ];
                    $pageno = $comments[ 1 ];
                    $comments = $comments[ 2 ];
                    
                    $libs->Load( 'notify/notify' );
                    
                    $finder = New NotificationFinder();
                    $finder->DeleteByCommentAndUser( $speccomment, $user );
                    $water->Trace( 'speccoment is ' . $speccomment->Id );
                }
            // }
            }

            $finder = New PollFinder();
            $polls = $finder->FindByUser( $theuser , 0 , 1 );
            $finder = New JournalFinder();
            $journals = $finder->FindByUser( $theuser , 0 , 1 );
            $egoalbum = New Album( $theuser->Egoalbumid );
            if ( $egoalbum->Numphotos > 0 ) {
                $finder = New ImageFinder();
                $images = $finder->FindByAlbum( $egoalbum , 0 , 10 );
            }
            
            $showuploadavatar = $theuser->Id == $user->Id && $egoalbum->Numphotos == 0;

            $finder = New FriendRelationFinder();
            if ( $finder->IsFriend( $user, $theuser ) == FRIENDS_B_HAS_A ) {
                Element( 'user/profile/main/antisocial', $theuser );
            }

            $finder = New StatusBoxFinder();
            $tweet = $finder->FindLastByUserId( $theuser->Id );
            if ( $tweet !== false || $theuser->Id == $user->Id ) {
                ?>
                <div class="tweetbox<?php
                    if ( $theuser->Id == $user->Id ) {
                        ?> tweetactive<?php
                        if ( $tweet === false ) {
                            ?> tweetblind<?php
                        }
                    }
                    ?>"<?php
                    if ( $theuser->Id == $user->Id ) {
                        ?> title="Άλλαξε το μήνυμα του &quot;τι κάνεις τώρα;&quot;"<?php
                    }
                    ?>>
					<i class="s1_0042 corner">&nbsp;</i>
                    <div class="sx_0005 tweet">
                        <div><?php
                        if ( $theuser->Id == $user->Id ) {
                            ?><a href=""><?php
                        }
                        if ( $theuser->Gender == 'f' ) {
                            ?>Η <?php
                        }
						elseif ( $theuser->Id == 872 ) {
							?>Το <?php
						}
                        else {
                            ?>Ο <?php
                        }
                        echo htmlspecialchars( $theuser->Name );
                        ?> <span><?php
                        if ( $tweet !== false ) {
                            echo htmlspecialchars( $tweet->Message );
                        }
                        else {
                            ?><i>τι κάνεις τώρα;</i><?php
                        }
                        ?></span><?php
                        if ( $theuser->Id == $user->Id ) {
                            ?></a><?php
                        }
                        ?></div>
                    </div>
                    <i class="s1_0041 corner">&nbsp;</i>
                </div><?php
                if ( $theuser->Id == $user->Id ) {
                    ?><div id="tweetedit">
                        <h3 class="modaltitle">Τι κάνεις τώρα;</h3>
                        <form>
                            <div class="input"><?php
                                if ( $theuser->Gender == 'f' ) {
                                    ?>Η <?php
                                }
                                else {
                                    ?>Ο <?php
                                }
                                echo htmlspecialchars( $theuser->Name );
                                ?> <input type="text" value="<?php
                                if ( $tweet !== false ) {
                                    echo htmlspecialchars( $tweet->Message );
                                }
                                ?>" />
                                <input type="submit" style="display:none" />
                            </div>
                            <div>
                                <ul>
                                    <li><a href="" class="button">Αποθήκευση</a></li>
                                    <li><a href="" class="button">Διαγραφή</a></li>
                                </ul>
                            </div>
                        </form>
                    </div>
                    <div id="easyphotoupload">
                        <h3 class="modaltitle">Ανέβασε μια φωτογραφία...</h3> 
                        <div class="modalcontent"> 
                            <img src="<?php
                            echo $rabbit_settings[ 'imagesurl' ];
                            ?>ajax-loader.gif" /><span class="plswait">Παρακαλώ περιμένετε...</span>
                        </div>
                    </div><?php
                }
            }
            ?>
            <div class="main"><?php
                if ( $showuploadavatar ) {
                    ?><div class="ybubble">    
                        <div class="body">
                            <h3>Ανέβασε μια φωτογραφία σου</h3>
                            <div class="uploaddiv">
                                <?php
                                if ( UserBrowser() == "MSIE" ) {
                                    ?><iframe src="?p=upload&amp;albumid=<?php
                                        echo $user->Egoalbumid;
                                        ?>&amp;typeid=2&amp;color=ffda74" class="uploadframe" id="uploadframe" scrolling="no" frameborder="0">
                                      </iframe><?php
                                }
                                else {
                                    ?><object data="?p=upload&amp;albumid=<?php
                                    echo $user->Egoalbumid;
                                    ?>&amp;typeid=2&amp;color=ffda74" class="uploadframe" id="uploadframe" type="text/html">
                                    </object><?php
                                }
                         ?></div>
                        </div>
                        <i class="bl"></i>
                        <i class="br"></i>
                    </div><?php
                }
                ?><div><?php
                if ( $egoalbum->Numphotos > 0 ) {
                    ?><div class="photos"><?php
                        if ( $egoalbum->Numphotos > 5 ) {
                            ?><div class="more"><a href="?p=album&amp;id=<?php
                            echo $egoalbum->Id;
                            ?>" class="button" title="Περισσότερες φωτογραφίες μου">&raquo;</a></div><?php
                        }
                        Element( 'user/profile/main/photos' , $images , $egoalbum , $theuser->Id );
                        ?></div><?php
                }
                ?>
                <div class="morealbums"><?php
                    if ( $theuser->Count->Albums > 1 ) {
                        ?><div class="viewalbums"><a href="<?php
                        Element( 'user/url' , $theuser->Id , $theuser->Subdomain );
                        ?>albums" class="button">Προβολή albums&raquo;</a></div><?php
                    }
                ?></div>
                </div><?php
                $finder = New FriendRelationFinder();
                $friends = $finder->FindByUser( $theuser , 0 , 12 );  
                if ( !empty( $friends ) || ( $user->Id == $theuser->Id && $user->Count->Relations == 0 ) ) { 
                        if ( $user->Id == $theuser->Id && $user->Count->Relations == 0 ) {
                            $usernorel = true;
                        }
                        else {
                            $usernorel = false;
                        }
                        Element( 'user/profile/main/friends' , $friends , $theuser->Count->Relations , $theuser->Id , $theuser->Subdomain , $usernorel );
                    ?><div class="barfade">
                        <div class="s1_0070 leftbar"></div>
                        <div class="s1_0071 rightbar"></div>
                    </div><?php
                }
                if ( !empty( $polls ) || ( $user->Id == $theuser->Id && $user->Count->Polls == 0 ) ) {
                    ?><div class="lastpoll">
                        <h2 class="pheading">Δημοσκοπήσεις<?php
                        if ( $theuser->Count->Polls > 0 ) {
                            ?> <span class="small1">(<a href="<?php
                            Element( 'user/url' , $theuser->Id , $theuser->Subdomain );
                            ?>polls">προβολή όλων</a>)</span><?php
                        }
                        ?></h2><?php
                        if ( $user->Id == $theuser->Id && $user->Count->Polls == 0 ) {
                            ?><div class="nopolls">
                            Δεν έχεις καμία δημοσκόπηση. Κάνε click στο παρακάτω link για να μεταβείς στη σελίδα
                            με τις δημοσκοπήσεις και να δημιουργήσεις μια.
                            <div><a href="<?php
                            Element( 'user/url' , $theuser->Id , $theuser->Subdomain );
                            ?>polls">Δημοσκοπήσεις</a>
                            </div>
                            </div><?php
                        } 
                        else {
                            ?><div class="container"><?php
                            Element( 'poll/small' , $polls[ 0 ] , true );
                            ?></div><?php
                        }
                    ?></div><?php
                }
                Element( 'user/profile/main/questions' , $theuser );
                if ( !empty( $polls ) /*or not empty questions*/ ) {
                    ?><div class="barfade" style="margin-top:20px;clear:right">
                        <div class="s1_0070 leftbar"></div>
                        <div class="s1_0071 rightbar"></div>
                    </div><?php
                }
                ?><div style="clear:right"></div><?php
                if ( !empty( $journals ) || ( $user->Id == $theuser->Id && $user->Count->Journals == 0 ) ) {
                    if ( $user->Id == $theuser->Id && $user->Count->Journals == 0 ) {
                        Element( 'user/profile/main/lastjournal', false, $theuser, 0, 0, 0, true );
                    }
                    else {
                        Element( 'user/profile/main/lastjournal' , $journals[ 0 ] , $theuser , $journals[ 0 ]->Id , $journals[ 0 ]->Numcomments , $theuser->Count->Journals , false );
                    }
                }
                if ( $user->HasPermission( PERMISSION_COMMENT_VIEW ) ) {
                    ?><div class="comments">
                        <h3>Σχόλια στο προφίλ <?php
                        if ( $theuser->Gender == 'f' ) {
                            ?>της <?php
                        }
                        else {
                            ?>του <?php
                        }
                        Element( 'user/name' , $theuser->Id , $theuser->Name , $theuser->Subdomain , false );
                        ?></h3><?php
                        if ( $pageno <= 0 ) {
                            $pageno = 1;
                        }
                        
                            if ( $user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
                                Element( 'comment/reply', $theuser->Id, TYPE_USERPROFILE , $user->Id , $user->Avatarid );
                            }
                        // if ( $theuser->Profile->Numcomments > 0 ) {
                        die( count( $comments[ 'comment' ] ) );
                            $indentation = Element( 'comment/arraylist' , $comments , TYPE_USERPROFILE , $theuser->Id );
                            $page->AttachInlineScript( 'Comments.nowdate = "' . NowDate() . '";' );
                            $page->AttachInlineScript( "Comments.OnLoad();" );
                            if ( $commentid > 0 && isset( $indentation[ $commentid ] ) ) {
                                Element( 'comment/focus', $commentid, $indentation[ $commentid ] );
                            }
                            ?><div class="pagifycomments"><?php
                                $link = str_replace( '*', urlencode( $theuser->Subdomain ), $xc_settings[ 'usersubdomains' ] ) . '?pageno=';
                                Element( 'pagify' , $pageno , $link, $total_pages );
                            ?></div><?php
                        // }
                    ?></div><?php
                }
            ?></div><?php    
        }
    }
?>
