<?php
	function ElementEventList() {
		global $libs;
		global $water;
		
		$libs->Load( 'event' );
		
        $profiletypes = array( EVENT_USERPROFILE_EDUCATION_UPDATED, EVENT_USERPROFILE_SEXUALORIENTATION_UPDATED, EVENT_USERPROFILE_RELIGION_UPDATED, 
                               EVENT_USERPROFILE_POLITICS_UPDATED, EVENT_USERPROFILE_SMOKER_UPDATED, EVENT_USERPROFILE_DRINKER_UPDATED,
                               EVENT_USERPROFILE_ABOUTME_UPDATED, EVENT_USERPROFILE_MOOD_UPDATED, EVENT_USERPROFILE_LOCATION_UPDATED,
                               EVENT_USERPROFILE_HEIGHT_UPDATED, EVENT_USERPROFILE_WEIGHT_UPDATED, EVENT_USERPROFILE_HAIRCOLOR_UPDATED,
                               EVENT_USERPROFILE_EYECOLOR_UPDATED );

		$finder = New EventFinder();
        $events = $finder->FindLatest( 0, 20 );
		?><div class="latestevents">
			<h2>Συνέβησαν πρόσφατα</h2>
			<div class="list"><?php
				foreach ( $events as $event ) {
					if ( in_array( $event->Typeid, $profiletypes ) ) { 
						$type = 'profile_update';
					}	
					else {
						$type = $event->Typeid;
					}
					$info[ $event->User->Id ][ $type ][] = $event;
					$visited[ $event->User->Id ][ $type ] = false;	
				}
				foreach ( $events as $event ) {
					if ( in_array( $event->Typeid, $profiletypes ) ) {
						$type = 'profile_update';
					}
					else {
						$type = $event->Typeid;
					}
					if ( !$visited[ $event->User->Id ][ $type ] ) {
						$eventlist = $info[ $event->User->Id ][ $type ];
						$visited[ $event->User->Id ][ $type ] = true;
						?><div class="event">
							<div class="toolbox">
							</div>
							<div class="who"><?php
								Element( 'user/display' , $event->User );
								switch ( $type ) {
									case EVENT_IMAGE_CREATED:
										?> ανέβασε <?php
										if ( count( $eventlist ) > 1 ) {
											?>τις εικόνες<?php
										}
										else {
											?>την εικόνα<?php
										}
										break;
									case EVENT_JOURNAL_CREATED:
										?> καταχώρησε στο ημερολόγιο<?php
										break;
									case EVENT_POLL_CREATED:
										?> δημιούργησε <?php
										if ( count( $eventlist ) > 1 ) {
											?>τις δημοσκοπήσεις<?php
										}
										else {
											?>τη δημοσκόπηση<?php
										}
										break;
									case EVENT_USERSPACE_UPDATED:
										?> ανανέωσε τον χώρο<?php
										break;
									case 'profile_update':
										?> ανανέωσε το προφίλ<?php
										break;
									case EVENT_USER_CREATED:
										?> δημιούργησε λογαριασμό<?php
										break;
                                    case EVENT_FRIENDRELATION_CREATED:
                                        ?> πρόσθεσε στους φίλους<?php
                                        break;
								}
							?></div>
							<div class="subject"><?php
								$water->Trace( 'eventlist has ' . count( $eventlist ) );
								switch ( $type ) {
									case EVENT_IMAGE_CREATED:
										foreach ( $eventlist as $one ) {
											?><a href="?p=photo&amp;id=<?php
											echo $one->Item->Id;
											?>"><?php
											Element( 'image' , $one->Item , IMAGE_CROPPED_100x100 , '' , $one->User->Name , $one->User->Name , 'margin-right:3px;' , false , 0 , 0 );
											?></a><?php
										}
										break;
									case EVENT_JOURNAL_CREATED:
										foreach ( $eventlist as $one ) {
											?><a href="?p=journal&amp;id=<?php
											echo $one->Item->Id;
											?>"><?php
											echo htmlspecialchars( $one->Item->Title );
											?></a><?php
										}
										break;
									case EVENT_POLL_CREATED:
										foreach ( $eventlist as $one ) {
											?><a href="?p=poll&amp;id=<?php
											echo $one->Item->Id;
											?>"><?php
											echo htmlspecialchars( $one->Item->Question );
											?></a><?php
										}
										break;
									case EVENT_USERSPACE_UPDATED:
										?><a href="?p=space&amp;subdomain=<?php
										echo $event->User->Name;
										?>">Προβολή χώρου</a><?php
										break;
									case EVENT_FRIENDRELATION_CREATED:
										foreach ( $eventlist as $one ) {
											?><a href="<?php
											Element( 'user/url' , $one->Item->Friend );
											?>"><?php
											Element( 'user/avatar' , $one->Item->Friend, 100 , '' , 'margin-right:3px;' , false , 0 , 0 );
                                            ?></a><?php
										}
										break;
									case 'profile_update':
                                        Element( 'event/profileupdate', $eventlist );
										break;		
									case EVENT_USER_CREATED:
										?> δημιούργησε λογαριασμό<?php
										break;
								}
								?>
							</div>					
						</div><?php
					}
				}
			?></div>
		</div><?php
	}
?>
