<?php

	function ElementPollNew( $theuser ) {
		global $user;
		global $xc_settings;

		if ( $theuser != $user || $user->Rights() < $xc_settings[ 'readonly' ] ) {
			return false;
		}

		?><div class="pollnew pollbox" style="opacity:0.5;filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);" id="newpoll">
			<h4 style="height:18px"><a href="" onclick="Poll.Create();return false;" style="background-image:url('<?php
			echo $xc_settings[ 'staticimagesurl' ];
			?>icons/add.png');background-repeat:no-repeat;padding-left:20px;height:18px;display:block">δημιουργία δημοσκόπησης</a></h4>
			<ul class="new">
			</ul>
		</div><?php
	}

?>
