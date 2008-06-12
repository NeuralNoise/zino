<?php
	
	function ElementCommentView( $comment , $indent , $numchildren ) {
		global $user;
		global $libs;
		
		$libs->Load( 'comment' );

		?><div id="comment_<?php
		echo $comment->Id;
		?>" class="comment" style="border-color:#dee;<?php
		if ( $indent > 0 ) {
			?>margin-left:<?php
			echo $indent*20;
			?>px;<?php
		}
		?>">
			<div class="toolbox">
				<span class="time">πριν <?php
				echo $comment->Since;
				?></span><?php
				if ( ( $user->Id == $comment->User->Id || $user->HasPermission( PERMISSION_COMMENT_DELETE_ALL ) ) && $numchildren == 0 ) {
					?><a href="" onclick="Comments.Delete( <?php
					echo $comment->Id;
					?> );return false;" title="Διαγραφή"></a><?php
				}
			?></div>
			<div class="who"><?php
				Element( 'user/display' , $comment->User );
				?> είπε:
			</div>
			<div class="text"<?php
			if ( ( $user->Id == $comment->User->Id && ( time()-strtotime( $comment->Created ) < 900 ) ) || $user->HasPermission( PERMISSION_COMMENT_EDIT_ALL )  ) {
				?> ondblclick="Comments.Edit( <?php
				echo $comment->Id;
				?> );return false;"<?php
			}
			?>><?php
				echo htmlspecialchars( $comment->Text );
			?></div><?php
			if ( $indent <= 50 && $user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
				?><div class="bottom">
					<a href="">Απάντα</a> σε αυτό το σχόλιο
				</div><?php
			}
			/*?><div id="children_<?php
			echo $comment->Id;
			?>" style="display:none"><?php
			echo $numchildren;
			?></div>*/?>
		</div><?php
	}
?>
