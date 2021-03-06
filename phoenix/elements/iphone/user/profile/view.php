<?php
    class ElementiPhoneUserProfileView extends Element {
        public function Render( tText $subdomain ) {
            global $user;
            global $xc_settings;
            global $user;
            global $libs;
            global $page;

            $libs->Load( 'user/statusbox' );
            $libs->Load( 'relation/relation' );

            $subdomain = $subdomain->Get();
            $finder = New UserFinder();
            $theuser = $finder->FindBySubdomain( $subdomain );

            if ( !$theuser->Exists() ) {
                return;
            }

            $page->SetTitle( $theuser->Name );

            $finder = New StatusBoxFinder();
            $tweet = $finder->FindLastByUserId( $theuser->Id );
 
            ?><div class="profile"><?php
            Element( 'user/avatar', $theuser->Avatarid, $theuser->Id,
                     $theuser->Avatar->Width, $theuser->Avatar->Height,
                     $theuser->Name, 100, 'avatar', '', true, 50, 50 );
            ?><h2><?php
            echo $theuser->Name;
            ?></h2>
            <span class="subtitle"><?php
            echo htmlspecialchars( $theuser->Profile->Slogan );
            ?></span><?php
            if ( $tweet !== false ) {
                ?><div class="tweet"><?php
                if ( $theuser->Gender == 'f' ) {
                    ?>Η <?php
                }
                else {
                    ?>Ο <?php
                }
                echo htmlspecialchars( $theuser->Name );
                ?> <?php
                echo htmlspecialchars( $tweet->Message );
                ?></div><?php
            }
            ?><div class="eof" style="padding-bottom:10px"></div>
            <div class="barfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div><?php
            $schoolexists = $theuser->Profile->School->Numstudents > 2;
            Element( 'user/profile/sidebar/info', $theuser, $schoolexists );
            ?><div class="details"><?php  
            $showgender = $theuser->Gender != '-';
            $showweight = $theuser->Profile->Weight > -3;
            $showheight = $theuser->Profile->Height > -3;
            if ( $showgender || $showweight || $showheight ) { 
                ?><div class="look">
                <img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>body-male-slim-short.jpg" alt="" /><?php
                Element( 'user/profile/sidebar/look', $theuser->Profile->Height, $theuser->Profile->Weight, $theuser->Gender );
                ?></div><?php
            }
            Element( 'user/profile/sidebar/social/view', $theuser );
            ?></div><?php
            $finder = New FriendRelationFinder();
            $friends = $finder->FindByUser( $theuser , 0 , 12 );  
            if ( count( $friends ) ) {
                ?><h3><?php
                if ( $user->Id == $theuser->Id ) {
                    ?>Οι φίλοι μου<?php
                }
                else {
                    ?>Οι φίλοι τ<?php
                    switch ( $theuser->Gender ) {
                        case 'f':
                            ?>ης<?php
                            break;
                        case 'm':
                        default:
                            ?>ου<?php
                            break;
                    }
                }
                ?></h3><?php
                $users = array();
                foreach ( $friends as $friend ) {
                    $users[] = $friend->Friend;
                }
                Element( 'iphone/user/list', $users );
            }
            ?></div><?php
        }
    }
?>
