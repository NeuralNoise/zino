<?php
	function ElementUserYesno( $answer ) {
		$yesno = array( '-'		=> '-',
						'yes' => '���', 
						'no' => '���',
						'socially' => '�� �����'
		);
		echo htmlspecialchars( $yesno[ $answer ] );
	}
?>
