<?php
	function ElementUserProfileSidebarSocialDrinker( $theuser ) {
		$drinker = array( 
			'yes' => '���',
			'no' => '���',
			'socially' => '�� �����'
		);
		?><dt><strong>������;</strong></dt>
		<dd><?php
		echo $drinker[ $theuser->Profile->Drinker ];
		?></dd><?php
	}
?>