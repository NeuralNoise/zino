<?php
	
	function ElementUserTrivialReligion( $religion , $gender ) {
		if ( $gender == 'm' || $gender == '-' ) {
			$religions = array( '-'   => '-',
						'christian'   => 'Χριστιανός',
						'muslim'      => 'Ισλαμιστής',
						'atheist'	  => 'Άθεος',
						'agnostic'	  => 'Αγνωστικιστής',
						'nothing'	  => 'Τίποτα'
			);
		}
		else {
			$religions = array( '-'	  => '-',
						'christian'   => 'Χριστιανή',
						'muslim' 	  => 'Ισλαμίστρια',
						'atheist' 	  => 'Άθεη',
						'agnostic'	  => 'Αγνωστικιστής',
						'nothing' 	  => 'Καμία'
			);
		}
		echo htmlspecialchars( $religions[ $religion ] );
	}
?>
