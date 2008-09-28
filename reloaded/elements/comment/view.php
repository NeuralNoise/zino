<?php
	function ElementCommentView( $comment, $indent, $numchildren = 0 ) {
		global $water;
		global $user;
		global $xc_settings;
		
		$theuser = $comment->User();
		$allowreply = $indent < 50;
		
		?><div id="comment_<?php
		echo $comment->Id();
		?>" class="comment" style="margin-left:<?php
		echo 10 * $indent;
		?>px">
			<div class="upperline">
				<div class="leftcorner">&nbsp;</div>
				<div class="title"><?php
				if ( $theuser->IsAnonymous() ) {
					?>ανώνυμος<?php
				}
				else {
					Element( 'user/static' , $theuser );
				}
				?>, πριν <?php
				echo $comment->Since();
				
				if ( $user->CanModifyCategories() ) {
					?>&nbsp;&nbsp;<span style="opacity: 0.7"><?php
					echo $comment->Ip();
					?></span>
					&nbsp;<span style="opacity: 0.5"><small><?php
					echo $comment->Id();
					?></small></span><?php
				}
				
				?></div>
				<div class="fade">&nbsp;</div>
				<div class="rightcorner">&nbsp;</div>
				<div class="filler">&nbsp;</div>
			</div>
			<div class="avatar"><?php
				Element( 'user/icon' , $theuser );
			?></div>
			<div class="text">
				<div id="comment_text_<?php 
					echo $comment->Id(); 
					?>"><?php
					echo $comment->Text();
					?><br /><br /><br /><div class="sig"><?php
					
					echo htmlspecialchars( $theuser->Signature() );
				
				?><br /><br /></div></div>
			</div>
			<div class="lowerline">
				<div class="leftcorner">&nbsp;</div>
				<div class="rightcorner">&nbsp;</div>
				<div class="middle">&nbsp;</div>
				<div class="toolbar"><?php
					ob_start();
					if ( $allowreply && !( $user->IsAnonymous() && !$xc_settings[ 'anonymouscomments' ] ) && $user->Rights() >= $xc_settings[ 'readonly' ] ) {
						?><li><a onclick="Comments.Reply( <?php 
						echo $comment->Id(); 
						?>, <?php 
						echo $indent; 
						?> ); return false;">Απάντηση</a></li><?php
					}
					if ( $user->CanModifyCategories() || ( $user->Exists() && $user->Id() == $theuser->Id() && daysDistance($comment->SQLDate() ) < 1 ) && $user->Rights() >= $xc_settings[ 'readonly' ] ) { 
						?><li><a style="cursor: pointer;" onclick="Comments.Edit( <?php 
						echo $comment->Id(); 
						?> ); return false;">Επεξεργασία</a></li><?php
						if ( !$numchildren ) {
							?><li><a style="cursor:pointer" onclick="Comments.Delete( <?php 
							echo $comment->Id(); 
							?> ); return false;">Διαγραφή</a></li><?php
						}
					}
					if ( $theuser->IsAnonymous() && !$numchildren && $user->CanModifyCategories() && $user->Rights() >= $xc_settings[ 'readonly' ] ) {
						?><li><a style="cursor:pointer" onclick="Comments.MarkAsSpam( <?php
						echo $comment->Id();
						?> ); return false;">Spam</a></li><?php
					}
					$lis = ob_get_clean();
					if ( !empty( $lis ) ) {
						?><ul id="comment_<?php
						echo $comment->Id(); 
						?>_toolbar"><?php
						echo $lis;
						?></ul><?php
					}
					?>
					<ul id="comment_edit_<?php 
						echo $comment->Id(); 
						?>_toolbar" style="display: none">
						<li><a style="cursor: pointer;" onclick="Comments.checkEmpty( <?php
						echo $comment->Id();
						?> );">Επεξεργασία!</a></li>
						<li><a style="cursor: pointer;" onclick="Comments.cancelEdit( <?php 
						echo $comment->Id(); 
						?> ); return false;">Ακύρωση</a></li><?php
					?></ul>
				</div>
				<div id="c_<?php
				echo $comment->Id();
				?>_children" style="display: none"><?php
				echo $numchildren;
				?></div>
			</div>
		</div><?php
	}
	
?>
