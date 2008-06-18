<?php
	
	function ElementJournalList( tString $username , tString $subdomain , tInteger $pageno ) {
		global $page;
		global $rabbit_settings;
		global $user;
		
		$username = $username->Get();
		$subdomain = $subdomain->Get();
		$finder = New UserFinder();	
		if ( $username != '' ) {
			if ( strtolower( $username ) == strtolower( $user->Name ) ) {
				$theuser = $user;
			}
			else {
				$theuser = $finder->FindByName( $username );
			}
		}
		else if ( $subdomain != '' ) {
			if ( strtolower( $subdomain ) == strtolower( $user->Subdomain ) ) {
				$theuser = $user;
			}
			else {
				$theuser = $finder->FindBySubdomain( $subdomain );
			}
		}
		if ( !isset( $theuser ) || $theuser === false ) {
			$page->SetTitle( "Δε βρέθηκε ο χρήστης" );
			?>Ο χρήστης δεν υπάρχει<?php
			return;
		}
		
		if ( strtoupper( substr( $theuser->Name, 0, 1 ) ) == substr( $theuser->Name, 0, 1 ) ) {
			$page->SetTitle( $theuser->Name . " Ημερολόγιο" );
		}
		else {
			$page->SetTitle( $theuser->Name . " ημερολόγιο" );
		}
		
		$pageno = $pageno->Get();
		if ( $pageno <= 0 ) {
			$pageno = 1;
		}
		
		$finder = New JournalFinder();
		$journals = $finder->FindByUser( $theuser , ( $pageno - 1 )*5 , 5 );
		
		Element( 'user/sections' , 'journal' , $theuser );
		?><div id="journallist">
			<ul><?php
				if ( $theuser->Id == $user->Id ) {
					?><li class="create">
						<a href="?p=addjournal" class="new"><img src="<?php
						echo $rabbit_settings[ 'imagesurl' ];
						?>add3.png" alt="Δημιουργία καταχώρησης" title="Δημιουργία καταχώρησης" />Δημιουργία καταχώρησης</a>
					</li><?php
				}
				if ( !empty( $journals ) ) {
					foreach ( $journals as $journal ) {
						?><li><?php
							Element( 'journal/small' , $journal );
							?><div class="barfade">
								<div class="leftbar"></div>
								<div class="rightbar"></div>
							</div>
						</li><?php
					}
				}
				else {
					if ( $theuser->Id != $user->Id ) {
						?>Δεν υπάρχουν καταχωρήσεις<?php
					}
				}
			?></ul>
			<div class="pagifyjournals"><?php
            $link = '?p=journals&subdomain=' . $theuser->Subdomain . '&pageno=';
            $total_pages = ceil( $theuser->Count->Journals / 5 );
			Element( 'pagify', $pageno, $link, $total_pages );
			?></div>
		</div>
		<div class="eof"></div><img src="<?php
		echo $rabbit_settings[ 'imagesurl' ];
		?>heart.png" style="display:none;" /><?php
	}
?>
