<?php
    class ElementFavouriteView extends Element {
        public function Render( tText $subdomain, tText $type ) {
            global $rabbit_settings;
            global $libs;
            global $user;

            $libs->Load( 'favourite' );
            $libs->Load( 'journal' );
            
            $subdomain = $subdomain->Get();
            $type = $type->Get();

            switch ( $type ) {
                case 'journals':
                    $type = TYPE_JOURNAL;
                    break;
                case 'photos':
                    $type = TYPE_IMAGE;
                    break;
                default:
                    $type = false; // all
            }
            
            // Find all user's favourite journals
            $userfinder = New UserFinder();
            $theuser = $userfinder->FindBySubdomain( $subdomain );
            $favfinder = New FavouriteFinder();
            $favourites = $favfinder->FindByUserAndType( $theuser, $type );

            Element( 'user/sections', 'favourites', $theuser );

            ?><div id="favourites">
                <div class="list">
                    <ul class="favcategories">
                        <?php
                        /*
                           <li><a href=""><img src="<?php
                            echo $rabbit_settings[ 'imagesurl' ];
                            ?>chart_bar-trans.png" alt="Δημοσκοπήσεις" title="Δημοσκοπήσεις" /></a></li>
                        */
                        ?>
                        <li<?php
                        if ( $type === TYPE_JOURNAL ) {
                            ?> class="selected"<?php
                        }
                        ?>><a href="<?php
                        Element( 'user/url', $theuser->Id, $theuser->Subdomain );
                        ?>favourites/journals"><img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>book.png" alt="Ημερολόγια" title="Ημερολόγια" /></a></li>
                        <li<?php
                        if ( $type === TYPE_IMAGE ) {
                            ?> class="selected"<?php
                        }
                        ?>><a href="<?php
                        Element( 'user/url', $theuser->Id, $theuser->Subdomain );
                        ?>favourites/photos"><img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>photo.png" alt="Εικόνες" title="Εικόνες" /></a></li>
                        <li<?php
                        if ( $type === false ) {
                            ?> class="selected"<?php
                        }
                        ?> style="width:40px;">
                        <a href="<?php
                        Element( 'user/url', $theuser->Id, $theuser->Subdomain );
                        ?>favourites">Όλα</a></li>
                    </ul>
                    <div style="clear:right"></div>
                    <ul class="events"><?php
                        $i = 0;
                        if ( !count( $favourites ) ) {
                            ?><li class="last"><div><?php
                            if ( $theuser->Id == $user->Id ) {
                                ?>Δεν έχεις κάποια αγαπημένα.<br />
                                Μπορείς να προσθέσεις μία φωτογραφία ή ένα ημερολόγιο που σου αρέσει στα αγαπημένα σου όταν το βλέπεις.<?php
                            }
                            else {
                                switch ( $theuser->Gender ) {
                                    case 'f':
                                        ?>Η <?php
                                        break;
                                    case 'm':
                                    default:
                                        ?>O <?php
                                }
                                Element( 'user/name', $theuser->Id, $theuser->Name, $theuser->Subdomain, true );
                                ?> δεν έχει αγαπημένα.<br />
                                Πρότεινέ <?php
                                switch ( $theuser->Gender ) {
                                    case 'f':
                                        ?>της<?php
                                        break;
                                    case 'm':
                                    default:
                                        ?>του<?php
                                }
                                ?> κάποιο ημερολόγιο ή φωτογραφία για να το προσθέσει.<?php
                            }
                            ?></div></li><?php
                        }
                        else {
                            foreach ( $favourites as $favourite ) {
                                ?><li class="<?php
                                if ( $i == count( $favourites ) - 1 ) {
                                    ?>last <?php
                                }
                                switch ( $favourite->Typeid ) {
                                    case TYPE_IMAGE:
                                        ?>photo<?php
                                        break;
                                    case TYPE_JOURNAL
                                        ?>journal<?php
                                        break;
                                }
                                ?>"><div><a href="<?php
                                ob_start();
                                Element( 'url', $favourite->Item );
                                echo htmlspecialchars( ob_get_clean() );
                                ?>"><?php
                                switch ( $favourite->Typeid ) {
                                    /*
                                    case TYPE_POLL:
                                        ?><a href="">Πόσες φορές τη βδομάδα βαράς μαλακία;</a> από <a href="">dionyziz</a><?php
                                        break;
                                    */
                                    case TYPE_JOURNAL:
                                        echo htmlspecialchars( $favourite->Item->Title );
                                        ?></a> από <?php
                                        Element( 'user/name', $favourite->Item->User->Id, $favourite->Item->User->Name, $favourite->Item->User->Subdomain, true );
                                        break;
                                    case TYPE_IMAGE:
                                        if ( $favourite->Item->Name != '' ) {
                                            ?>"<?php
                                            echo htmlspecialchars( $favourite->Item->Name );
                                            ?>"<?php
                                        }
                                        else if ( $favourite->Item->Album->Id == $favourite->Item->User->Egoalbumid ) {
                                            ?>Φωτογραφία <?php
                                            if ( $favourite->Item->User->Id == $user->Id ) {
                                                ?>σου<?php
                                            }
                                            else if ( $favourite->Item->User->Gender == 'f' ) {
                                                ?>της <?php
                                            }
                                            else {
                                                ?>του <?php
                                            }
                                            if ( $favourite->Item->User->Id != $user->Id ) {
                                                echo htmlspecialchars( $favourite->Item->User->Name );
                                            }
                                        }
                                        else {
                                            ?>Μια εικόνα του Album "<?php
                                            echo htmlspecialchars( $favourite->Item->Album->Name );
                                            ?>"<?php
                                        }
                                        ?><br /><?php
                                            Element( 'image/view' , $favourite->Item->Id , $favourite->Item->User->Id , $favourite->Item->Width , $favourite->Item->Height , IMAGE_PROPORTIONAL_210x210, '' , $favourite->Item->Name , $favourite->Item->Name , '' , false, 0, 0 );
                                            ?>
                                        </a><?php
                                        break;
                                }
                                ?></div></li><?php
                                ++$i;
                            }
                        }
                    ?></ul>
                </div>
            </div><?php
        }
    }
?>
