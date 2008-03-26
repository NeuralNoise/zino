<?php
    function ActionQuestionNew( tString $question ) {
    	global $user;
    	global $libs;

    	$libs->Load( 'question' );

    	if ( !( $user->CanModifyCategories() ) ) {
            return Redirect();
    	}
		$question = $question->Get();
        if ( trim( $question ) != '' ) {
			$eid = AddQuestion( $question );
        }
        return Redirect( '?p=questions' );
    }
?>
