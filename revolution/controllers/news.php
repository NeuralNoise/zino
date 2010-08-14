<?php
    class ControllerNews {
        public static function Listing() {    
            clude( 'models/db.php' );
            clude( 'models/poll.php' );
            clude( 'models/journal.php' );
            clude( 'models/photo.php' );
            clude( 'models/spot.php' );

	        $pollids = Spot::GetPolls( 4005, 25 );//polls from spot
            if ( is_array( $pollids ) ) {
	            $foundpolls = Poll::ListByIds( $pollids );
				$keys = array();
				$i = 1;
				foreach ( $pollids as $id ) {
				    $keys[ $id ] = $i;
				    $i = $i + 1;
				}
				$polls = array();
				foreach ( $foundpolls as $poll ) {
					$polls[ $keys[ $poll[ 'id' ] ] ] = $poll;
				}
				ksort( $polls );
            }
            else {
            	$polls = Poll::ListRecent( 25 );
            }

			$journalids = Spot::GetJournals( 4005, 25 );//journals from spot
            if ( is_array( $journalids ) ) {
	            $foundjournals = Journal::ListByIds( $journalids );
				$keys = array();
				$i = 1;
				foreach ( $journalids as $id ) {
				    $keys[ $id ] = $i;
				    $i = $i + 1;
				}
				$journals = array();
				foreach ( $foundjournals as $journal ) {
					$journals[ $keys[ $journal[ 'id' ] ] ] = $journal;
				}
				ksort( $journals );
            }
            else {
            	$journals = Journal::ListRecent( 25 );
            }

            $photos = Photo::ListRecent( 0, 25 );
            $content = array();
			$i = 0;
			reset( $polls );//mix results randomly while keeping the initial order
			reset( $journals );
			$pflag = false;
			$jflag = false;
			while ( $pflag == false || $jflag == false ) {
				if ( $pflag == true || current( $polls ) == false ) {
					while ( current ( $journals ) != false ) {
						$content[ $i ] = current ( $journals );
						$content[ $i ][ 'type' ] = 'journal';
						++$i;
						next( $journals );
					}
					$jflag = true;
				}
				else if ( $jflag == true || current( $journals ) == false ) {
					while ( current ( $polls ) != false ) {
						$content[ $i ] = current ( $polls );
						$content[ $i ][ 'type' ] = 'poll';
						++$i;
						next( $polls );
					}
					$pflag = true;
				}
				else {
					if ( rand( 1, 10 ) < 6 ) {
						$content[ $i ] = current ( $journals );
						$content[ $i ][ 'type' ] = 'journal';
						++$i;
						next( $journals );
					}
					else {
						$content[ $i ] = current ( $polls );
						$content[ $i ][ 'type' ] = 'poll';
						++$i;
						next( $polls );
					}
				}
			}

/*
           
            foreach ( $polls as $poll ) {
                $content[ $i ] = $poll;
                $content[ $i ][ 'type' ] = 'poll';
                ++$i;
            }
            foreach ( $journals as $journal ) {
                $content[ $i ] = $journal;
                $content[ $i ][ 'type' ] = 'journal';
                ++$i;
            }
*/
            //foreach ( $photos as $photo ) {
            //    $content[ $i ] = $photo;
            //    $content[ $i ][ 'type' ] = 'photo';
            //    ++$i;
            //}
            // shuffle( $content );
            // shuffle( $content );
            global $settings;
            //usort( $content, array( __CLASS__, 'Compare' ) );// dont sort the news
            include 'views/news/listing.php';
        }
        private static function Compare( $a, $b ) {
            return $a[ 'id' ] < $b[ 'id' ];
        }
    }
?>
