<?php

	function ElementUserTrivialEducation( $education ) {
		$educations = array( '-' => '-',
							 'elementary' => '��������',
							 'gymnasioum' => '��������',
							 'TEE' 		  => '���',
							 'lyceum' 	  => '������',
							 'TEI'		  => '���',
							 'university' => '������������'
		);
		echo htmlspecialchars( $educations[ $education ] );
	}
?>
