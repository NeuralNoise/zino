<?php
	function UnitUserJoin( tString $username , tString $password , tString $email ) {
		global $libs;
		
		$libs->Load( 'user/user' );
		$username = $username->Get();
		$password = $password->Get();
		$email = $email->Get();
		$finder = New UserFinder(); 
		if ( $finder->FindByName( $username ) ) {
			?>alert( Join.usernameexists );
			if ( !Join.usernameexists ) {
				Join.usernameexists = true;
				$( $( 'form.joinform div > span' )[ 1 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity : "1" } , 700 );
			}
			Join.username.focus();
			Join.username.select();
			alert( Join.usernameexists );<?php
		}
		/*
		if ( Valid_User( $username ) ) {
			?>alert( 'OK' );<?php
		}
		*/
	}
?>
