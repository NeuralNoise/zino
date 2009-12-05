<?php
    
    class ElementPollList extends Element {
        public function Render( tText $username , tText $subdomain , tInteger $pageno ) {
            global $libs;
            global $page;
            global $rabbit_settings;
            global $xc_settings;
            global $user;
            
            Element( 'user/subdomainmatch' );
            
            $libs->Load( 'poll/poll' );
            $username = $username->Get();
            $subdomain = $subdomain->Get();
            $finder = New UserFinder();
            if ( $username != '' ) {
                if ( strtolower( $username ) == strtolower( $user->Name ) ) {
                    $theuser = $user;
                }
                else {
                    $theuser = $finder->FindByName( $username );
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

            if ( $theuser->Deleted ) {
                $libs->Load( 'rabbit/helpers/http' );
                return Redirect( 'http://static.zino.gr/phoenix/deleted' );
            }
            if ( Ban::isBannedUser( $theuser->Id ) ) {
                $libs->Load( 'rabbit/helpers/http' );
                return Redirect( 'http://static.zino.gr/phoenix/banned' );
            }
            
            $pageno = $pageno->Get();
            if ( $pageno <= 0 ) {
                $pageno = 1;
            }
            if ( strtoupper( substr( $theuser->Name, 0, 1 ) ) == substr( $theuser->Name, 0, 1 ) ) {
                $page->SetTitle( $theuser->Name . " Δημοσκοπήσεις" );
            }
            else {
                $page->SetTitle( $theuser->Name . " δημοσκοπήσεις" );
            }
			
			//Rhapsody testing svn commit with this comment
            $finder = New PollFinder();
            $polls = $finder->FindByUser( $theuser  , ( $pageno - 1 )*5 , 5 );

            Element( 'user/sections', 'poll' , $theuser );
            ?><div id="polist">
                <div class="ads"></div>
                <ul><?php
                    if ( $theuser->Id == $user->Id && $user->HasPermission( PERMISSION_POLL_CREATE ) ) {
                        ?><li class="create">
                            <a href=""><span class="s1_0048">&nbsp;</span>Δημιουργία δημοσκόπησης</a>
                        </li><?php
                    }
                    if ( !empty( $polls ) ) {
                        foreach ( $polls as $poll ) {
                            ?><li><?php
                            Element( 'poll/small' , $poll , true );
                            ?></li><?php
                        }
                    }
                    else {
                        if ( $theuser->Id != $user->Id ) {
                            ?>Δεν υπάρχουν δημοσκοπήσεις<?php
                        }
                    }
                ?></ul><?php
                if ( $theuser->Id == $user->Id && $user->HasPermission( PERMISSION_POLL_CREATE ) ) {
                    ?><div class="creationmockup">
                        <div>
                            <input type="text" /><a href="" title="Δημιουργία" class="s1_0065 createpoll">&nbsp;</a>
                        </div>
                        <div class="tip">
                            <span class="s1_0033">&nbsp;</span>Γράψε μια ερώτηση για τη δημοσκόπησή σου
                        </div>
                    </div>
                    <div class="tip2">
						<span class="s1_0033">&nbsp;</span>Γράψε μια επιλογή για τη δημοσκόπησή σου
                    </div>
                    <div class="creatingpoll">
                        <img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>ajax-loader.gif" alt="Δημιουργία" title="Δημιουργία" /> Δημιουργία...
                    </div><?php
                }
                ?><div class="pagifypolls"><?php

                $link = str_replace( '*', urlencode( $theuser->Subdomain ), $xc_settings[ 'usersubdomains' ] ) . 'polls?pageno=';
                $total_pages = ceil( $theuser->Count->Polls / 5 );
                $text = '( ' . $theuser->Count->Polls;
                if ( $theuser->Count->Polls == 1) {
                    $text .= ' Δημοσκόπηση )';
                }
                else {
                    $text .= ' Δημοσκοπήσεις )';
                }
                Element( 'pagify', $pageno, $link, $total_pages, $text );
                ?></div>
                <div class="eof"></div>
            </div><?php
            $page->AttachInlineScript( 'PollList.OnLoad();' );
        }
    }
?>
