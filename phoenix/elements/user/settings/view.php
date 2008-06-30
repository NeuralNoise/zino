<?php
	function ElementUserSettingsView() {
		global $user;
		global $rabbit_settings;
		global $page;
		
		//$page->AttachStyleSheet( 'css/user/settings.css' );
		//$page->AttachScript( 'js/user/settings.js' );
		$page->SetTitle( 'Ρυθμίσεις' );
		if ( !$user->Exists() ) {
			return Redirect( $rabbit_settings[ 'webaddress' ] );
		}
		?><div class="settings">
		    <div class="sidebar"><?php
				//Element( 'user/settings/sidebar' );
		    ?></div>
		    <div class="tabs">
		        <form id="personalinfo" style="display:none"><?php
					//Element( 'user/settings/personal/view' );
		        ?></form>
		        <form id="characteristicsinfo" style="display:none"><?php
					//Element( 'user/settings/characteristics/view' );
		        ?></form>
		        <form id="interestsinfo" style="display:none"><?php
					//Element( 'user/settings/interests' );
		        ?></form>
		        <form id="contactinfo" style="display:none"><?php
					//Element( 'user/settings/contact' );
		        ?></form>
		        <form id="settingsinfo" style="display:none"><?php
					//Element( 'user/settings/settings' );
		        ?></form>
		    </div>
		</div>
		<div class="eof"></div><?php
	}
?>
