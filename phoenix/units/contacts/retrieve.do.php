<?php
    function UnitContactsRetrieve( tText $provider , tText $username, tText $password ) {
        global $libs;
        global $user;
        $provider = $provider->Get();
        $username = $username->Get();
        $password = $password->Get();
        
        
        $libs->Load( 'contacts/contacts' );
        $ret = GetContacts( $username, $password, $provider );
        
        if( !is_array( $ret ) ){
            return;
        }
        $contactsInZino = 1;
        foreach( $ret as $contactMail ){
            ?>$( '#contactsInZino .contacts .contact:first' ).clone()
                .children( ".contactMail" ).html("<?php
            echo $contactMail;
                ?>").end().appendTo( " #contactsInZino .contacts" );<?php
            $contactsInZino++;
        }
        ?>$( "#contactsInZino > h3" ).html( "<?php
            echo $contactsInZino;
            if ( $contactsInZino == 1 ){
                echo " ����� ��� ���� Zino. �������� ��� ����� ������ ���...";
            }
            else{
                echo " ������ ��� ����� Zino. �������� ��� ����� ������ ���...";
            }
        ?>" );<?php
    }
?>
