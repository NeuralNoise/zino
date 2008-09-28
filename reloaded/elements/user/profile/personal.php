<?php
	function ElementUserProfilePersonal( $theuser ) {
		global $libs;
		global $user;
		global $page;
		global $xc_settings;
		
		$libs->Load( 'place' );
		$libs->Load( 'interesttag' );

		$page->AttachScript( 'js/interesttag.js' );
		$page->AttachScript( 'js/modal.js' );
		$page->AttachScript( 'js/universities.js' );
		$page->AttachScript( 'js/coala.js' );
		$page->AttachStyleSheet( 'css/modal.css' );
		$page->AttachStyleSheet( 'css/interesttags.css' );
		
		static $monthsfordob = array(
									1  => 'Ιανουαρίου',
									2  => 'Φεβρουαρίου',
									3  => 'Μαρτίου',
									4  => 'Απριλίου',
									5  => 'Μαίου',
									6  => 'Ιουνίου',
									7  => 'Ιουλίου',
									8  => 'Αυγούστου',
									9  => 'Σεπτεμβρίου',
									10 => 'Οκτωβρίου',
									11 => 'Νοεμβρίου',
									12 => 'Δεκεμβρίου'
								);

		if ( $theuser->Gender() != "-" ) {
			switch ( $theuser->Gender() ) {
				case "male":
					$gn = "άνδρας";
					$artcl ="Ο";
					$artclgen = "του";
					break;
				case "female":
					$gn = "γυναίκα";
					$artcl = "Η";
					$artclgen = "της";
					break;
			}
			if ( $theuser->Id() == 1 ) {
				$gn = "Αγόρι";
			}
		}
		$validdob = false;
		if ( $theuser->DateOfBirth() != "0000-00-00" ) {
			if ( $theuser->DateOfBirthYear() != "2005" ) {
				$validdob = true;
				$nowdate = getdate();
				$nowyear = $nowdate[ "year" ];
				$ageyear = $nowyear - $theuser->DateOfBirthYear();
				$nowmonth = $nowdate[ "mon" ];
				$nowday = $nowdate[ "mday" ];
				$hasbirthday = false;
				if ( $nowmonth < $theuser->DateOfBirthMonth() ) {
					--$ageyear;
				}
				else {
					if ( $nowmonth == $theuser->DateOfBirthMonth() ) {
						if ( $nowday < $theuser->DateOfBirthDay() ) {
							--$ageyear;
						}
						else {
							if ( $nowday == $theuser->DateOfBirthDay() ) {
								$hasbirthday = true;
							}
						}
					}
				}
			}
		}
		
		if ( !isset( $gn ) && !$validdob && !$theuser->Place() && !$theuser->Hobbies() ) { // if there's no info to display
			return;
		}
		$uni = $theuser->Uni();
			
		if ( $user->Id() == $theuser->Id() ) {
			ob_start();
			// ProfileOptions.Init( g( "user_profile_personal" ) );
			$page->AttachInlineScript( ob_get_clean() );
		}
		
		?><div class="personal">
			<h4>προσωπικές πληροφορίες</h4>
			<ul id="user_profile_personal"><?php
				$classl = false;
				$class = '';
				if ( $theuser->Gender() != "-" ) {
					?><li><dl>
						<dt>φύλο</dt>
						<dd id="user_options_gender"><?php
						echo $gn; 
						?></dd>
					</dl></li><?php
					if ( $classl ) {
						$classl = false;
					}
					else {
						$classl = true;
					}
				}
				if ( $validdob ) {
					if ( $classl ) {
						$class = ' class="l"';
						$classl = false;
					}
					else {	 
						$class = '';
						$classl = true;
					}
					?><li><dl<?php
					echo $class;
					?>><dt>ηλικία</dt>
						<dd><?php
							echo $ageyear;
						?></dd>
					</dl></li><?php
				}
				if ( $validdob ) { 
					if ( $classl ) {
						$class = ' class="l"';
						$classl = false;
					}
					else {
						$class= '';
						$classl = true;
					}
					?><li><dl<?php
					echo $class;
					?>><dt>γενέθλια</dt>
						<dd><?php
						if ( $hasbirthday ) { 
							?>Έχει σήμερα γενέθλια και γίνεται <?php
							echo $ageyear;
						}
						else { 
							echo $theuser->DateOfBirthDay() . " " . $monthsfordob[ intval( $theuser->DateOfBirthMonth() ) ];
						} 
						?></dd>
					</dl></li><?php
				}
				if ( $theuser->Place() ) {	
					if ( $classl ) {	
						$class = ' class="l"';
						$classl = false;
					}
					else {
						$class = '';
						$classl = true;
					}
					?><li><dl<?php
					echo $class;
					?>><dt>περιοχή</dt>
						<dd><?php
							echo $theuser->Location(); 
						if ( $user->CanModifyCategories() ) { 
						?> (<a href="?p=places">Διαχείριση Περιοχών</a>)<?php
						} ?></dd>
					</dl></li><?php
				}
				if ( $uni->Exists() && !$user->IsAnonymous() && $user->Id() != $theuser->Id() ) { //add condition for having set uni 
					if ( $classl ) {
						$class = ' class="l"';
						$classl = false;
					}
					else {
						$class = '';
						$classl = true;
					}
					?><li><dl<?php
					echo $class;
					?>><dt>πανεπιστήμιο</dt>
					<dd><?php
					if ( $uni->Exists() ) {
						echo htmlspecialchars( $uni->Name );
						?> - <?php
						echo htmlspecialchars( $uni->Place->Name );
					}
					?></dd>
					</dl></li><?php
					$unishowing = true;
				}
				else if ( $user->Id() == $theuser->Id() && isset( $ageyear ) && $ageyear >= 17 ) {
					if ( $classl ) {
						$class = ' class="l"';
						$classl = false;
					}
					else {
						$class = '';
						$classl = true;
					}
					?><li><dl<?php
					echo $class;
					?>><dt>πανεπιστήμιο</dt>
					<dd id="uniname"><?php
					if ( $uni->Exists() ) {
						echo htmlspecialchars( $uni->Name );
						?> - <?php
						echo htmlspecialchars( $uni->Place->Name );
						?> <a href="" onclick="Uni.SetUni();return false;"><img src="<?php
						echo $xc_settings[ 'staticimagesurl' ];
						?>icons/edit.png" alt="Επεξεργασία" title="Επεξεργασία" /></a>
						<a href="" onclick="Uni.UnsetUni();return false;" style="margin-left:2px;"><img src="<?php
						echo $xc_settings[ 'staticimagesurl' ];
						?>icons/delete.png" alt="Διαγραφή" title="Διαγραφή" /></a><?php
					}
					else {
						?><a href="" onclick="Uni.SetUni();return false;">Είσαι φοιτητής;</a><?php
					}
					?></dd>
					</dl></li><?php
					$unishowing = true;
				}
				$tags = InterestTag_List( $theuser );
				if ( !empty( $tags ) || $user->Id() == $theuser->Id() ) {
					if ( $classl ) {
						$class = ' class="l"';
						$classl = false;
					}
					else {
						$class = '';
						$classl = true;
					}
					?><li><dl<?php
					echo $class;
					?>><dt>ενδιαφέροντα</dt>
						<dd id="interests"><?php
							$tagslen = count( $tags );
							for ( $i = 0; $i < $tagslen;++$i ) {
								?><a href="tag/<?php
								echo htmlspecialchars( urlencode( $tags[ $i ]->Text ) );
								?>"><?php
								echo htmlspecialchars( $tags[ $i ]->Text );
								?></a><?php
								if ( $i != $tagslen-1 ) {
									?>, <?php
								}
							}
							if ( $theuser->Id() == $user->Id() ) {
							?> <a href="" onclick="InterestTag.Create();return false;"><img src="<?php
							echo $xc_settings[ 'staticimagesurl' ];
							?>icons/page_new.gif" /></a><?php
							}
						?></dd>
					</dl></li><?php
				} 
				?>
			</ul>
		</div>
		<div id="testmodaluni" style="width:450px;height:200px;display:none">
			<h4>Επέλεξε εκπαιδευτικό ίδρυμα</h4>
			<div>
				Πόλη<br />
				<select id="modaltownsel" onchange="Uni.CreateUniList();return false;">
				<option value="0" <?php
				if ( !$uni->Exists() ) {
					?>selected="selected"<?php
				}
				?>>(καμία)</option><?php
				$places = AllPlaces();
				foreach( $places as $place ) {
					?><option value="<?php
					echo $place->Id;
					?>"><?php
					echo htmlspecialchars( $place->Name );
					?></option><?php
				}
				?></select><br />
			</div>
			<div>
			
			</div><br />
			<a href="" onclick="Modals.Destroy();return false;">&#187;Ακύρωση</a>
		</div><?php
	}
?>
