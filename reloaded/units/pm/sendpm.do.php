<?php
	function UnitPmSendpm( tString $usernames , tString $pmtext ) {
		global $user;
		global $libs;

		$libs->Load( 'pm' );
		$usernames = $usernames->Get();
		$pmtext = $pmtext->Get();
		
		$test = explode( ' ' , $usernames );
		$userreceivers = User_ByUsername( $test );

		foreach ( $userreceivers as $i => $receiver ) {
			if ( $receiver->Id() == $user->Id() ) {
				unset( $userreceivers[ $i ] );
			}
		}
		
		if ( empty( $userreceivers ) ) {
			?>alert('Δεν έχεις ορίσει κάποιον έγκυρο παραλήπτη');<?php
		}
		else {
			$pm = new PM();
			$pm->SenderId = $user->Id();
			$pm->Text = $pmtext;
			foreach ( $userreceivers as $receiver ) {	
				$pm->AddReceiver( $receiver );
			}
			$pm->Save();
		}
		?>pms.ShowFolderPm( document.getElementById( 'sentfolder' ) , -2 );<?php
	}
	
?>
