<?php
    function UnitContactsRetrieve( tText $provider , tText $username, tText $password ) {
        global $libs;
        global $user;
        $libs->Load( 'relation/relation' );
        $libs->Load( 'contacts/contacts' );
        
        $provider = $provider->Get();
        $username = $username->Get();
        $password = $password->Get();
        
        $ret = GetContacts( $username, $password, $provider );
        if( !is_array( $ret ) ){
            ?>
            setTimeout( function(){
            <?php
                if ( $ret == 'ERROR_PROVIDER' ){
                    ?>$( "#security" ).css({
                        'background': '#FF9090 url(http://static.zino.gr/phoenix/xerror.png) no-repeat 6px center',
                        'font-weight': 'bold',
                        'padding': '10px 10px 10px 30px'
                    }).html( 'Υπήρξε πρόβλημα στο σύστημα. Παρακαλώ δοκιμάστε αργότερα.' );<?php
                }
                if ( $ret == 'ERROR_CREDENTIALS' ){
                    ?>$( "#security" ).css({
                        'background': '#FEF4B7 url(http://static.zino.gr/phoenix/error.png) no-repeat 6px center',
                        'font-weight': 'bold',
                        'padding': '10px 10px 10px 30px'
                    }).html( 'Το e-mail ή ο κωδικός που έγραψες δεν είναι σωστά.' );<?php
                }
            ?>
                contacts.backToLogin();
            }, 3000 );<?php
            return;
        }
        $contactsInZino = 0;
        $contactsNotZino = 0;
        $mailfinder = new UserProfileFinder();
        $members = $mailfinder->FindAllUsersByEmails( $ret );
        foreach( $ret as $mail => $nickname ){
            if ( $members[ $mail ] != "" ){
                $theuser = new User( $members[ $mail ] );
                $finder = New FriendRelationFinder();
                if ( $finder->IsFriend( $theuser, $user ) ){
                    continue;
                }
                ?>contacts.addContactInZino( '<?php
                Element( 'user/display', $theuser->Id, $theuser->Avatar->Id, $theuser );
                ?>', '<?php
                echo addslashes( $mail );
                ?>', '<?php
                echo addslashes( $theuser->Profile->Location->Name );
                ?>', '<?php
                echo $theuser->Id;
                ?>' );<?php
                $contactsInZino++;
            }
            else {
                ?>contacts.addContactNotZino( '<?php
                echo addslashes( $mail );
                ?>', '<?php
                echo addslashes( $nickname );
                ?>' );<?php
                $contactsNotZino++;
            }
        }
        ?>$( "#contactsInZino > h3" ).html( "<?php
            echo $contactsInZino;
            if ( $contactsInZino == 1 ){
                ?> επαφή σου έχει Zino. Πρόσθεσέ την στους φίλους σου...<?php
            }
            else{
                ?> επαφές σου έχουν Zino. Πρόσθεσέ τις στους φίλους σου...<?
            }
        ?>" );
        $( "#contactsNotZino > h3" ).html( "<?php
            echo $contactsNotZino;
            if ( $contactsNotZino == 1 ){
                ?> επαφή σου δεν έχει Zino ακόμα! Προσκάλεσέ την τώρα!<?php
            }
            else{
                ?> επαφές σου δεν έχουν Zino ακόμα! Προσκάλεσέ τους τώρα!<?
            }
        ?>" );
        $( ".contacts input" ).click( function(){
            contacts.calcCheckboxes( contacts.step );
        });
        setTimeout( function(){
                contacts.previwContacts<?php
                if ( !$contactsInZino ){
                echo "Not";
                }
                ?>InZino();
            }, 3000 );<?php
    }
?>
