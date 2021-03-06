<?php
    function UnitUserJoined( tInteger $doby , tInteger $dobm , tInteger $dobd , tText $gender , tInteger $location ) {
        global $user;
        global $rabbit_settings;
        global $libs;
        
        $libs->Load( 'user/profile' );
        $libs->Load( 'place' );
        
        $doby = $doby->Get();
        $dobm = $dobm->Get();
        $dobd = $dobd->Get();
        $gender = $gender->Get();
        $location = $location->Get();
		$validdob = true;
        if ( checkdate( $dobm , $dobd , $doby ) ) {
			$user->Profile->BirthMonth = $dobm;
			$user->Profile->BirthDay = $dobd;
			$user->Profile->BirthYear = $doby;
        }
        if( $gender == 'm' || $gender == 'f' ) {
            $user->Gender = $gender;
        }
        if ( $location != 0 ) {
            if ( $location == -1 ) {
                $user->Profile->Placeid = 0;
            }
            else {
                $place = New Place( $location );
                if ( $place->Exists() && !$place->IsDeleted() ) {
                    $user->Profile->Placeid = $place->Id;
                }
            }
        }
        $user->Save();
        $user->Profile->Save();
		?>$( 'div a.button' ).removeClass( 'button_disabled' );<?php
		if ( $validdob ) {
	        ?>location.href = '<?php
	        echo $rabbit_settings[ 'webaddress' ];
	        ?>?newuser=true';<?php
		}
    }
?>
