<?php	
	function ActionSpaceEdit( tString $text ) {
		global $user;
		global $libs;

		if ( !$user->Exists() ) {
			die( "You must login first" );
			
		}
		$text = $text->Get();
	
        $libs->Load( 'wysiwyg' );
        $result = WYSIWYG_PostProcess( $text );

        $user->Space->Text = $result;
		$user->Space->Save();

        ob_start();
        Element( 'user/url', $user );

		return Redirect( ob_get_clean() . 'space' );
	}
?>
