<?php
	
	function ElementCommentReply( $itemid, $typeid ) {
		global $user;
		global $page;
		
		$page->AttachScript( 'js/comments.js' );
		$page->AttachScript( 'js/coala.js' );
		?><div class="comment newcomment">
			<div class="toolbox">
				<span class="time">τα σχόλια είναι επεξεργάσημα για ένα τέταρτο</span>
			</div>
			<div class="who"><?php
				Element( 'user/display' , $user );
				?> πρόσθεσε ένα σχόλιο
			</div>
			<div class="text">
				<textarea rows="" cols=""></textarea>
			</div>
			<div class="bottom">
				<form onsubmit="return false;"><input type="submit" value="Σχολίασε!" onclick="Comments.Create(0);" /></form>
			</div>
			<div style="display:none" id="item"><?php
			echo $itemid;
			?></div>
			<div style="display:none" id="type"><?php
			echo $typeid;
			?></div>
		</div><?php
	}
?>
