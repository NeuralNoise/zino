<?php
	
	function ElementSpaceEdit() {
		global $user;
		global $page;
		
		$page->SetTitle( 'Επεξεργασία χώρου' );
		Element( 'user/sections' , 'space' , $user );
		?><div id="editspace">
			<h2>Επεξεργασία χώρου</h2>
			<div class="edit">
				<form method="post" action="do/space/edit">
					<?php
                        Element( 'wysiwyg/view', 'wysiwyg', $user->Space->Text );
					?>
					<div class="submit">
						<input type="submit" value="Δημοσίευση" />
					</div>
				</form>
			</div>
			<div class="eof"></div>
		</div><?php
	
	}
?>
