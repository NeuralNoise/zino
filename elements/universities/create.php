<?php
	function ElementUniversitiesCreate() {
		global $user;
		global $water;
		global $libs;
		
		$libs->Load( 'universities' );
		
		if ( !$user->CanModifyCategories() ) {
			die( '��� ����� �������� �� ���� ���� �� ������' );
		}
		$allunis = Uni_Retrieve( 0 , false );
		foreach( $allunis as $uni ) {
			echo $uni->Name;
			?><br /><?php
		}
		
		
	}
?>