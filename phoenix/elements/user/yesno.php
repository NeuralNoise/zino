<?php
	function ElementUserYesno( $answer ) {
		$yesno = array( '-'		=> '-',
						'yes' => '���', 
						'no' => '���'
		);
		echo htmlspecialchars( $yesno[ $answer ] );
	}
?>
