<?php

	function UnitUserSettingsSave( tInteger $dobd , tInteger $dobm , tInteger $doby , tString $gender , tInteger $place , tString $education , tInteger $university , tInteger $mood , tString $sex , tString $religion , tString $politics , tString $slogan , tString $aboutme , tString $favquote , tString $haircolor , tString $eyecolor , tInteger $height , tInteger $weight , tString $smoker , tString $drinker , tString $email , tString $msn , tString $gtalk , tString $skype , tString $yahoo , tString $web , tString $oldpassword , tString $newpassword , tString $emailprofilecomment , tString $notifyprofilecomment , tString $emailphotocomment , tString $notifyphotocomment , tString $emailpollcomment , tString $notifypollcomment , tString $emailjournalcomment , tString $notifyjournalcomment , tString $emailreply , tString $notifyreply , tString $emailfriendaddition , tString $notifyfriendaddition ) {
		global $user;

		if ( $user->Exists() ) {
			$dobd = $dobd->Get();
			$dobm = $dobm->Get();
			$doby = $doby->Get();
			$gender = $gender->Get();
			$place = $place->Get();
			$education = $education->Get();
			$university = $university->Get();
			$mood = $mood->Get();
			$sex = $sex->Get();
			$religion = $religion->Get();
			$politics = $politics->Get();
			$slogan = $slogan->Get();
			$aboutme = $aboutme->Get();
			$favquote = $favquote->Get();
			$haircolor = $haircolor->Get();
			$eyecolor = $eyecolor->Get();
			$height = $height->Get();
			$weight = $weight->Get();
			$smoker = $smoker->Get();
			$drinker = $drinker->Get();
			$email = $email->Get();
			$msn = $msn->Get();
			$gtalk = $gtalk->Get();
			$skype = $skype->Get();
			$yahoo = $yahoo->Get();
			$web = $web->Get();
			$oldpassword = $oldpassword->Get();
			$newpassword = $newpassword->Get();
			$emailprofilecomment = $emailprofilecomment->Get();
			$notifyprofilecomment = $notifyprofilecomment->Get();
			$emailphotocomment = $emailphotocomment->Get();
			$notifyphotocomment = $notifyphotocomment->Get();
			$emailpollcomment = $emailpollcomment->Get();
			$notifypollcomment = $notifypollcomment->Get();
			$emailjournalcomment = $emailjournalcomment->Get();
			$notifyjournalcomment = $notifyjournalcomment->Get();
			$emailreply = $emailreply->Get();
			$notifyreply = $notifyreply->Get();
			$emailfriendaddition = $emailfriendaddition->Get();
			$notifyfriendaddition = $notifyfriendaddition->Get();
			
			if ( $dobd >=1 && $dobd <=31  && $dobm >= 1&& $dobm <= 12 && $doby ) {
				if ( strtotime( $doby . '-' . $dobm . '-' . $dobd ) ) {
					$user->Profile->BirthDay = $dobd;
					$user->Profile->BirthMonth = $dobm;
					$user->Profile->BirthYear = $doby;
				}
			}
			if ( $gender ) {
				$user->Gender = $gender;
			}
			if ( $place ) {
				if ( $place == -1 ) {
					$placeid = 0;
				}
				else {
					$newplace = New Place( $place );
					if ( $newplace->Exists() ) {
						$placeid = $newplace->Id;
					}
				}				
				$user->Profile->Placeid = $placeid;
			}
			if ( $education ) {
				$user->Profile->Education = $education;
			}
			if ( $university ) {
				if ( $university == -1 ) {
					$uniid = 0;
				}
				else {
					$newuni = New Uni( $university );
					if ( $newuni->Exists() ) {
						$uniid = $newuni->Id;
					}
				}
				$user->Profile->Uniid = $uniid;
			}
			if ( $mood ) {
				$user->Profile->Moodid = $mood;
			}
			if ( $sex ) {	
				$user->Profile->Sexualorientation = $sex;
			}
			if ( $religion ) {
				$user->Profile->Religion =  $religion;
			}
			if ( $politics ) {
				$user->Profile->Politics = $politics;
			}
			if ( $slogan ) {
				if ( $slogan == '-1' ) {
					$slogan = '';
				}
				$user->Profile->Slogan = $slogan;
			}
			if ( $aboutme ) {
				if ( $aboutme == '-1' ) {
					$aboutme = '';
				}
				$user->Profile->Aboutme = $aboutme;
			}
			if ( $favquote ) {
				if ( $favquote == '-1' ) {
					$favquote = '';
				}
				$user->Profile->Favquote = $favquote;
			}
			if ( $haircolor ) {
				$user->Profile->Haircolor = $haircolor;
			}
			if ( $eyecolor ) {
				$user->Profile->Eyecolor = $eyecolor;
			}
			if ( $height ) {
				$user->Profile->Height = $height;
			}
			if ( $weight ) {
				$user->Profile->Weight = $weight;
			}
			if ( $smoker ) {
				$user->Profile->Smoker = $smoker;
			}
			if ( $drinker ) {
				$user->Profile->Drinker = $drinker;
			}
			$emailerror = false;
			if ( $email ) {
				if ( $email == '-1' ) {
					$user->Profile->Email = '';
				}
				else {
					if ( preg_match( '#^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+$#', $email ) ) {
						$user->Profile->Email = $email;
					}
					else {
						$emailerror = true;
						?>$( 'div#email span' ).css( "display" , "inline" )
						.animate( { opacity: "1"} , 200 , function() {
							Settings.invalidemail = true;
						} );<?php
					}
				}
				//if email == -1 save empty
			}
			$msnerror = false;
			if ( $msn ) {
				if ( $msn == '-1' ) {
					$user->Profile->Msn = '';
				}
				else {
					if ( preg_match( '#^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+$#', $msn ) ) {
						$user->Profile->Msn = $msn;
					}
					else {
						$msnerror = true;
						?>$( 'div#msn span' ).css( "display" , "inline" )
						.animate( { opacity: "1"} , 200 , function() {
							Settings.invalidmsn = true;
						} );<?php
					}
				}
			}
			if ( $gtalk ) {
				$user->Profile->Gtalk = $gtalk;
			}
			if ( $skype ) {
				$user->Profile->Skype = $skype;
			}
			if ( $yahoo ) {
				$user->Profile->Yim = $yahoo;
			}
			if ( $web ) {
				$user->Profile->Homepage = $web;
			}
			if ( !$emailerror && !$msnerror ) {
				?>$( Settings.showsaving )
					.animate( { opacity : "0" } , 200 , function() {
					$( Settings.showsaving ).css( "display" , "none" );
					$( Settings.showsaved )
						.css( "display" , "block" )
						.css( "opacity" , "1" )
						.animate( { opacity : "0" } , 1500 , function() {
							$( Settings.showsaved ).css( "display" , "none" ).css( "opacity" , "0" );
						});
				});
				<?php
			}
			else {
				?>$( Settings.showsaving ).animate( { opacity : "0" } , 200 , function() {
						$( Settings.showsaving ).css( "display" , "none" );
					});<?php		
			}
			if ( $oldpassword && $newpassword ) {
				if ( $user->IsCorrectPassword( $oldpassword ) ) {
					if ( strlen( $newpassword ) >= 4 ) {
						$user->Password = $newpassword;
						?>Modals.Destroy();<?php
					}
				}
				else {
					?>Settings.oldpassworderror = true;
					$( Settings.oldpassworddiv ).find( 'div div span' ).fadeIn( 400 );
					Settings.oldpassword.focus();<?php
				}
			}
			?>alert( '<?php
			echo $emailprofilecomment;
			?> , <?php 
			echo $notifyprofilecomment;
			?>' );<?php
			if ( $emailprofilecomment ) {
				$user->Preferences->Emailprofilecomment = $emailprofilecomment;
			}
			if ( $notifyprofilecomment ) {
				$user->Preferences->Notifyprofilecomment = $notifyprofilecomment;
			}
			if ( $emailphotocomment ) {
				$user->Preferences->Emailphotocomment = $emailphotocomment;
			}
			if ( $notifyphotocomment ) {
				$user->Preferences->Notifyphotocomment = $notifyphotocomment;
			}
			if ( $emailpollcomment ) {
				$user->Preferences->Emailpollcomment = $emailpollcomment;
			}
			if ( $notifypollcomment ) {
				$user->Preferences->Notifypollcomment = $notifypollcomment;
			}
			if ( $emailjournalcomment ) {
				$user->Preferences->Emailjournalcomment = $emailjournalcomment;
			}
			if ( $notifyjournalcomment ) {
				$user->Preferences->Notifyjournalcomment = $notifyjournalcomment;
			}
			if ( $emailreply ) {
				$user->Preferences->Emailreply = $emailreply;
			}
			if ( $notifyreply ) {
				$user->Preferences->Notifyreply = $notifyreply;
			}
			if ( $emailfriendaddition ) {
				$user->Preferences->Emailfriendaddition = $emailfriendaddition;
			}
			if ( $notifyfriendaddition ) {
				$user->Preferences->Notifyfriendaddition = $notifyfriendaddition;
			}
			
			$user->Save();
			
			//$user->Profile->Save();
			if ( $user->Profile->Education == 'university' ) {
				$typeid = 0;
			}
			else if( $user->Profile->Education == 'TEI' ) {
				$typeid  = 1;
			}
			$showuni = isset( $typeid ) && $user->Profile->Placeid > 0;
			if ( $showuni ) {
				if ( $place || $education ) {
					?>$( '#university' ).html( <?php
						ob_start();
						Element( 'user/settings/personal/university' , $user->Profile->Placeid , $typeid );
						echo w_json_encode( ob_get_clean() );
					?> );
					$( '#university select' ).change( function() {
						Settings.Enqueue( 'university' , this.value , 1000 );
					});
					if ( $( $( '#university' )[ 0 ].parentNode ).hasClass( 'invisible' ) ) {
						$( $( '#university' )[ 0 ].parentNode ).css( "opacity" , "0" ).removeClass( "invisible" ).animate( { opacity : "1" } , 200 );
						$( '#unibarfade' ).css( "opacity" , "0" ).removeClass( "invisible" ).animate( { opacity : "1" } , 200 );
					}<?php
				}
			}
			else {
				if ( $place || $education ) {
					?>if ( !$( $( '#university' )[ 0 ].parentNode ).hasClass( 'invisible' ) ) {
						$( $( '#university' )[ 0 ].parentNode ).animate( { opacity : "0" } , 200 , function() {
							$( this ).addClass( "invisible" );
						} );
						$( '#unibarfade' ).animate( { opacity : "0" } , 200 , function() {
							$( this ).addClass( "invisible" );
						} );
					}<?php
				}
			}
		}
	}
?>
