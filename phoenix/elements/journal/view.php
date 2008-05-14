<?php
	
	function ElementJournalView( tInteger $id ) {
	
		$journal = New Journal( $id->Get() );
		Element( 'user/sections' , 'journal' , $journal->User );
		
		?><div id="journalview">
			<h2><?php
			echo htmlspecialchars( $journal->Title );
			?></h2>
			<div class="journal" style="clear:none;">	
				<dl><?php
					if ( $journal->Numcomments > 0 ) {
						?><dd class="commentsnum"><?php
						echo $journal->Numcomments;
						?> σχόλι<?php
						if ( $journal->Numcomments == 1 ) {
							?>ο<?php
						}
						else {
							?>α<?php
						}
						?></dd><?php
					}
					?><dd class="addfav"><a href="">Προσθήκη στα αγαπημένα</a></dd>
					<dd class="lastentries"><a href="">Παλαιότερες καταχωρήσεις&raquo;</a></dd>
				</dl>
				<div class="eof"></div>
				<p><?php
				echo $journal->Text; //has to get through some editing for tags that are not allowed
				?></p>
			</div>
			<div class="comments"><?php
				Element( 'comment/list' );
			?></div>
			<div class="eof"></div>		
		</div><?php
	}
?>
