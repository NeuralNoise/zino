<?php
	function ElementUserHaircolor( $color ) {
		$hairs = array( '-'		=> '-',
						'black' => 'Μαύρo', 
						'brown' => 'Καστανό',
						'red' 	=> 'Κόκκινο',
						'blond' => 'Ξανθό',
						'highlights' => 'Ανταύγιες',
						'dark' => 'Σκούρο καφέ',
						'grey' => 'Γκρι', 
						'skinhead' => 'Skinhead'
		);
		echo htmlspecialchars( $hairs[ $color ] );
	}
?>
