<?php

	function ElementUserSettingsPersonalDob() {
		global $user;
		global $water;
		?><select class="small">
			<option value="-1"<?php
			$water->Trace( 'dd-mm-yyyy :' . $user->Profile->BirthDay . ' ' . $user->Profile->BirthMonth . ' ' . $user->Profile->BirthYear );
			if ( !$user->Profile->BirthDay ) {
				?> selected="selected"<?php
			}
			?>>-</option><?php
			for ( $i = 1; $i <= 31; ++$i ) {
				?><option value="<?php
				if ( $i <= 9 ) {
					?>0<?php
				}
				echo $i;
				?>"<?php
				if ( $user->Profile->BirthDay == $i ) {
					?> selected="selected"<?php
				}
				?>><?php
				if ( $i <= 9 ) {
					?>0<?php
				}
				echo $i;
				?></option><?php
			}
		?></select>
		<select class="small">
			<option value="-1"<?php
			if ( !$user->Profile->BirthMonth ) {
				?> selected="selected"<?php
			}
			?>><?php
			Element( 'user/trivial/month' , '-' );
			?></option><?php
			for ( $i = 1; $i <= 12; ++$i ) {
				?><option value="<?php
				if ( $i <= 9 ) {
					$i = '0' . $i;
				}
				echo $i;
				?>"<?php
				if ( $user->Profile->BirthMonth == $i ) {
					?> selected="selected"<?php
				}
				?>><?php
				Element( 'user/trivial/month' , $i );
				?></option><?php
			}
		?></select>
		<select class="small">
			<option value="-"<?php
			if ( !$user->Profile->BirthYear ) {
				?> selected="selected"<?php
			}
			?>>-</option><?php
			for ( $i = 2001; $i >= 1950; --$i ) {
				?><option value="<?php
				echo $i;
				?>"<?php
				if ( $user->Profile->BirthYear == $i ) {
					?> selected="selected"<?php
				}
				?>><?php
				echo $i;
				?></option><?php
			}
		?></select><?php
	}
?>
