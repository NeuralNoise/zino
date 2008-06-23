<?php

    function ElementPmView( $pm, $folder ) {
        global $user;
        global $water;
        global $rabbit_settings;

        w_assert( is_object( $pm->Sender ) );

        ?><div class="message" style="width:620px;" id="pm_<?php
            echo $pm->Pmid;
            ?>">
            <div class="infobar<?php
            
            if ( !$pm->IsSender( $user ) ) {
                ?> received"<?php
            }
            else {
                ?>" style="cursor:default;"<?php
            }
            ?>><?php
                if ( !$pm->IsSender( $user ) ) {
                    ?><a href="" style="float:right;" onclick="pms.DeletePm( this.parentNode.parentNode, '<?php
                    echo $pm->Pmid;
                    ?>', <?php
                    echo $folder->Id;
                    ?>, '<?php
                    if ( $pm->IsRead() ) {
                        ?>true<?php
                    }
                    else {
                        ?>false<?php
                    }
                    ?>' );return false;"><img src="<?php
                    echo $rabbit_settings[ 'imagesurl' ];
                    ?>delete.png" /></a><?php
                }
                if ( !$pm->IsRead() && !$pm->IsSender( $user ) ) {
                    ?><img style="float:left;padding: 0px 4px 3px 2px;" src="<?php
                    echo $rabbit_settings[ 'imagesurl' ];
                    ?>email_open.png" alt="Νέο μήνημα" title="Νέο μήνυμα" /><?php
                }
                ?><div class="infobar_info" onclick="pms.ExpandPm( this, <?php
                if ( !$pm->IsSender( $user ) ) {
                    if ( !$pm->IsRead() ) {
                        ?> true<?php
                    }
                    else {
                        ?> false<?php
                    }
                }
                else {
                    ?> false<?php
                }
                ?>, <?php
                echo $pm->Pmid;
                ?>, <?php
                echo $folder->Id;
                ?> );return false;"><?php
                if ( $pm->IsSender( $user ) ) {
                    ?> προς τ<?php
                    $pmuser = $pm->Receivers;
                }
                else {
                    ?> από τ<?php
                    $pmuser = $pm->Sender;
                }

                if ( is_array( $pmuser ) && count( $pmuser ) > 1 ) { /* many receivers */
                    ?>ους<?php
                }
                else if ( is_array( $pmuser ) ) { /* one receiver, no need for array */
                    // w_assert( isset( $pmuser[ 0 ] ) );

                    w_assert( isset( $pmuser[ 0 ] ) );
                    $pmuser = $pmuser[ 0 ];
                    w_assert( is_object( $pmuser ) );
                }
                if ( !is_array( $pmuser ) ) { /* sender or one receiver */
                    w_assert( is_object( $pmuser ) );
                    if ( $pmuser->Gender == 'female' ) {
                        ?>ην<?php
                    }
                    else {
                        ?>ον<?php
                    }
                }

                ?> </div><div style="display:inline" class="infobar_info"><?php
                if ( $pm->IsSender( $user ) ) {
                    $receivers = $pm->Receivers;
                    while ( $receiver = array_shift( $receivers ) ) {
                        Element( 'user/name', $receiver );
                        if ( count( $receivers ) ) {
                            ?>, <?php
                        }
                    }
                }
                else {
                    Element( 'user/name', $pm->Sender );
                }
                ?></div><div onclick="pms.ExpandPm( this, <?php
                if ( !$pm->IsRead() ) {
                    ?> true<?php
                }
                else {
                    ?> false<?php
                }
                ?>, <?php
                echo $pm->Pmid;
                ?>, <?php
                echo $folder->Id; 
                ?>);return false;" style="display:inline;" class="infobar_info">, πριν <?php
                echo $pm->Since;
                ?></div>
            </div>

            <div class="text" style="background-color: #f8f8f6;display:none;">
                <div><?php
                    echo $pm->Text;
                ?><br /><br /><br /><br />
                </div>
            </div>
            <div class="lowerline" style="background-color: #f8f8f6;display:none;">
                <div class="leftcorner"> </div>
                <div class="rightcorner"> </div>
                <div class="middle"> </div>
                <div class="toolbar"><?php
                    if ( !$pm->IsSender( $user ) ) {
                        ?><ul>
                            <li><a href="" onclick="<?php
                            ob_start();
                            ?>pms.NewMessage( <?php
                            echo w_json_encode( $pm->Sender->Name );
                            ?>, <?php
                            echo w_json_encode( $pm->Text );
                            ?> );return false;<?php
                            echo htmlspecialchars( ob_get_clean() );
                            ?>">Απάντηση</a></li>
                        </ul><?php
                    }
                ?></div>
            </div>
        </div><?php
    }

?>
