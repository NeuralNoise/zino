<?php
	
	function ElementUserSettingsCharacteristicsSmoker() {
		global $user;
		
		?><select name="smoker"><?php
			$yesno = array( '-' , 'yes' , 'no' , 'socially' );
			foreach ( $yesno as $answer ) {
				?><option value="<?php
				echo $answer;
				?>"<?php
				if ( $user->Profile->Smoker == $answer ) {
					?> selected="selected"<?php
				}
				?>><?php
				Element( 'user/trivial/yesno' , $answer );
				?></option><?php
			}
		?></select><?php
	}
?>
