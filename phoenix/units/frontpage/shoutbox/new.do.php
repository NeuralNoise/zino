<?php

	function UnitFrontpageShoutboxNew( tString $text , tCoalaPointer $node ) {
		global $user;
		global $libs;
		
		$libs->Load( 'shoutbox' );
		
		$text = $text->Get();
		if ( $user->Exists() ) {
			if ( $text != '' ) {
				$shout = New Shout();
				$shout->Text = $text;
				$shout->Save();
				?>$( <?php
				echo $node;
				?> ).find( 'div.toolbox a' ).click( function( shoutid ) {
					Frontpage.DeleteShout( '<?php
					echo $shout->Id;
					?>' , <?php
					echo $node;
					?> );
					return false;
				} );<?php
			}
		}
	}
?>
