<?php

	function ElementNotificationView( $notif ) {
		global $rabbit_settings;
		
		?><div class="event" id="<?php
		echo $notif->Event->Id;
		?>">
			<div class="toolbox">
				<span class="time">πριν <?php
				echo $notif->Since;
				?></span>
				<a href="" onclick="Notification.Delete( '<?php
				echo $notif->Event->Id;
				?>' );return false;" title="Διαγραφή"><img src="<?php
				echo $rabbit_settings[ 'imagesurl' ];
				?>delete.png" /></a>
			</div>
			<div class="who"<?php
			if ( $notif->Event->Typeid != EVENT_FRIENDRELATION_CREATED ) {
				?> onclick="Notification.Visit( '<?php
				ob_start();
				Element( 'url' , $notif->Item );
				echo htmlspecialchars( ob_get_clean() );
				?>' , '<?php
				echo $notif->Event->Item->Typeid;
				?>' , '<?php
				echo $notif->Event->Id;
				?>' , '<?php
				echo $notif->Event->Item->Id;
				?>' );"<?php
			}
			?>><?php
				Element( 'user/avatar' , $notif->FromUser , 100 , 'avatar' , '' , true , 50 , 50 );
				Element( 'user/name' , $notif->FromUser , false );
				if ( $notif->Event->Typeid != EVENT_FRIENDRELATION_CREATED ) {
					if ( $notif->Item->Parentid == 0 ) {
						?> έγραψε:<?php
					}
					else {
						?> απάντησε στο σχόλιό σου:<?php
					}
				}
				else {
					?> σε πρόσθεσε στους φίλους:<?php
				}
			?></div>
			<div class="subject"<?php
			if ( $notif->Event->Typeid != EVENT_FRIENDRELATION_CREATED ) {
				?> onclick="Notification.Visit( '<?php
				ob_start();
				Element( 'url' , $notif->Item );
				echo htmlspecialchars( ob_get_clean() );
				?>' , '<?php
				echo $notif->Event->Item->Typeid;
				?>' , '<?php
				echo $notif->Event->Id;
				?>' , '<?php
				echo $notif->Event->Item->Id;
				?>' );"<?php
			}
			?>><?php
				if ( $notif->Event->Typeid != EVENT_FRIENDRELATION_CREATED ) {
					?><p><span class="text">"<?php
					$comment = $notif->Item;
					$text = $comment->GetText( 35 );
					echo utf8_substr( $text , 0 , 30 );
					if ( strlen( $text ) > 30 ) {
						?>...<?php
					}
					?>"</span>
					, <?php
					switch ( $comment->Typeid ) {
						case TYPE_USERPROFILE:
							?>στο προφίλ σου<?php
							break;
						case TYPE_POLL:
							?>στη δημοσκόπηση "<?php
							echo htmlspecialchars( $comment->Item->Title );
							?>"<?php
							break;
						case TYPE_IMAGE:
							?>στη φωτογραφία <?php
							Element( 'image/view' , $comment->Item , IMAGE_CROPPED_100x100 , '' , $comment->Item->Name , $comment->Item->Name , '' , true , 75 , 75 );
							break;
						case TYPE_JOURNAL:
							?>στο ημερολόγιο "<?php
							echo htmlspecialchars( $comment->Item->Title );
							?>"<?php
							break;
					}
					?></p>
					<div class="eof"></div><?php
				}
				else {
					?><div class="addfriend"><a href="" onclick="Notification.AddFriend( '<?php
					echo $notif->FromUser->Id;
					?>' );return false;">Προσθήκη στους φίλους</a></div>
					<div class="viewprofile"><a href="" onclick="Notification.Visit( '<?php
					Element( 'user/url' , $notif->FromUser );
					?>' , '0' , '<?php
					echo $notif->Event->Id;
					?>' , '0' );return false;">Προβολή προφίλ&raquo;</a></div><?php
				}
				?><div class="eof"></div>
			</div>
		</div><?php
	}
?>
