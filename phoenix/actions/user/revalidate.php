<?php
	function ActionUserRevalidate( tInteger $userid ) {
		global $user;
		global $libs;
        
        $libs->Load( 'user/profile' );
        $libs->Load( 'rabbit/helpers/email' );
        
		$userid = $userid->Get();
		$user = New User( $userid );
		
		ob_start();
		$link = $user->Profile->ChangedEmail( '', $user->Name );
		$subject = Element( 'email/validate', $user->Name, $link );
		$text = ob_get_clean();
		Email( $user->Name, $user->Profile->Email, $subject, $text, "Zino", "noreply@zino.gr" );
		
        return Redirect( '?p=revalidate' );
    }
?>