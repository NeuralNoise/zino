<?php

	function ElementShoutboxView( $shout , $empty ) {
		global $user;
		
		if ( !$empty ) {
			?><div class="comment" style="border-color: #dee;" id="<?php
			echo $shout->Id;
			?>">
				<div class="toolbox">
					<span class="time">πριν <?php
					echo $shout->Since;
					?></span><?php
					if ( ( $user->Id == $shout->User->Id && $user->HasPermission( PERMISSION_SHOUTBOX_DELETE ) ) || $user->HasPermission( PERMISSION_SHOUTBOX_DELETE_ALL ) ) {
						?><a href="" onclick="Frontpage.DeleteShout( '<?php
						echo $shout->Id;
						?>' );return false;" title="Διαγραφή"></a><?php
					}
				?></div>
				<div class="who"><?php
					Element( 'user/display' , $shout->User );
					?> είπε:
				</div>
				<div class="text"><?php
					echo $shout->Text; // no htmlspecialchars(); the text is already sanitized
				?></div>
			</div><?php
		}
		else {
			?><div class="comment empty" style="border-color:#dee;display:none">
	 			<div class="toolbox">
					<span class="time">πριν λίγο</span><?php
					if ( $user->HasPermission( PERMISSION_SHOUTBOX_DELETE )  ) {
						?><a href="" onclick="return false" title="Διαγραφή"></a><?php
					}
				?></div>
				<div class="who"><?php
					Element( 'user/display' , $user );
					?> είπε:
				</div>
				<div class="text"></div>
			</div><?php
		}
	}
?>
