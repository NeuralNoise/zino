<?php
	
	function ElementJournalNew( tInteger $id ) {
		global $user;
		global $page;
		global $water;
		
		$id = $id->Get();
		
		if ( $id > 0 ) {
			$journal = New Journal( $id );
			$page->SetTitle( $journal->Title );
		}
		else {
			$page->SetTitle( "Δημιουργία καταχώρησης" );
		}
		Element( 'user/sections' , 'journal' , $user );
		?><div id="journalnew">
			<h2><?php
			if ( $id > 0 ) {
				?>Επεξεργασία <?php
			}
			else {
				?>Δημιουργία <?php
			}
			?> καταχώρησης</h2><?php
			if ( ( isset( $journal ) && $journal->User->Id == $user->Id ) || $id == 0 ) {
				?><div class="edit">
					<form method="post" action="do/journal/new" onsubmit="return JournalNew.Create( '<?php
							echo $id;
							?>' );">
						<input type="hidden" name="id" value="<?php
						echo $id;
						?>" />
						<div class="title">
							<span>Τίτλος:</span><input type="text" value="<?php
							if ( $id > 0 ) {
								echo htmlspecialchars( $journal->Title );
							}
							?>" name="title" tabindex="1" />
						</div>
                        <?php
                        Element( 'wysiwyg', 'wysiwyg', 'text', $journal->Text );
                        ?>
						<div class="submit">
							<input type="submit" value="Δημοσίευση" />
						</div>
					</form>
				</div><?php
			}
			else {
				?>Δεν έχεις δικαίωμα να επεξεργαστείς την καταχώρηση<?php
			}
		?></div>
		<div class="eof"></div><?php
	}
?>
