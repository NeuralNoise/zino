<?php
	function ElementInteresttagView( tString $text, tInteger $offset ) {
		global $page;
		global $libs;
		global $user;
		
		$text = $text->Get();
		$offset = $offset->Get();
		
		if ( !ValidId( $offset ) ) {
            $offset = 1;
        }
		
		$libs->Load( 'interesttag' );
		$page->SetTitle( 'Ενδιαφέροντα: ' . $text );
		$page->AttachStyleSheet( 'css/rounded.css' );
		
		if ( !InterestTag_Valid( $text ) ) {
			?><b>Παρακαλώ, το ενδιαφέρον να αποτελείται απο μία λέξη, χωρίς κενά ή κόμματα.</b><?php
			return;
		}
		
		$tags = InterestTag_List( $text, $offset, 20);
		$all = InterestTag_Count();
		if ( $all == 0 ) {
			?><b>Λυπάμαι, δεν υπάρχουν χρήστες με τέτοια ενδιαφέροντα</b><?php
			return;
		}
        
        $tag_users = array();
        foreach ( $tags as $tag ) {
            $tag_users[] = $tag->User;
        }

		Element( 'user/profile/friends' , $tag_users, $user->Id(), true, $text );
		
		if( $all > 20 ) {
			Element( 'pagify' , $offset , 'tag&amp;text='.$text , $all , 20 );
		}
	}
?>
