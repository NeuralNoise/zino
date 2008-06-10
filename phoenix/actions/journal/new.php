<?php
	function ActionJournalNew( tInteger $id , tString $title , tString $text ) {
		global $user;
		global $libs;

        header( 'Content-type: text/plain' );

		$id = $id->Get();
		$title = $title->Get();
		$text = $text->Get();
		
		if ( $id > 0 ) {
			$journal = New Journal( $id );
			if ( $journal->User->Id != $user->Id ) {
                die( 'You can\'t edit this journal' );
				return;
			}
		}
		else {
            if ( !$user->Exists() ) {
                die( 'You must login first' );
                return;
            }
			$journal = New Journal();
		}
		$journal->Title = $title;

        $libs->Load( 'wysiwyg' );
        $result = WYSIWYG_PostProcess( $text );

        $journal->Text = $result;
		$journal->Save();
		
		return Redirect( '?p=journal&id=' . $journal->Id );
	}
?>
