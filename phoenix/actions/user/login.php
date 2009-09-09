<?php
    function ActionUserLogin( tText $username, tText $password ) {
        global $user;
        global $rabbit_settings;
        global $water;
        global $libs;
        
        $username = $username->Get();
        $password = $password->Get();
        $finder = New UserFinder();
        $user = $finder->FindByNameAndPassword( $username, $password );
        
        $libs->Load( 'loginattempt' );
        $libs->Load( 'adminpanel/ban' );
        $libs->Load( 'user/profile' );
        
        $loginattempt = New LoginAttempt();
        $loginattempt->Username = $username;
        if ( $user === false ) {
            $loginattempt->Password = $password;
            $loginattempt->Save();
            
            /*if ( LoginAttempt_checkBot( UserIp() ) ) {
                $ban = New Ban();
                $ban->BanIp( UserIp(), 15*60 );
            }*///TODO<--reconsider this

            return Redirect( '?p=a' );
        }
        $validate = $user->Profile->Emailvalidated;
		$timecreated = strtotime( $user->Created );
		$datecheck = strtotime( '2009-03-21 04:30:00');
        if ( !$validate && $timecreated > $datecheck ) {
            return Redirect( '?p=notvalidated&userid=' . $user->Id  );
        }
        // don't store the password for security reasons
        $loginattempt->Success = 'yes';
        $loginattempt->Save();
        // else...
        $user->UpdateLastLogin();
        $user->RenewAuthtokenIfNeeded();
        $user->Save();
        
        $_SESSION[ 's_userid' ] = $user->Id;
        $_SESSION[ 's_authtoken' ] = $user->Authtoken;
        User_SetCookie( $user->Id, $user->Authtoken );

        return Redirect( $_SERVER[ 'HTTP_REFERER' ]  );
    }
?>
