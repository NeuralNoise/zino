<?php
    class ControllerNews {
        public static function Listing() {    
            include_fast( 'models/db.php' );
            include_fast( 'models/poll.php' );
            include_fast( 'models/journal.php' );
            include_fast( 'models/photo.php' );
            $polls = Poll::ListRecent( 25 );
            $journals = Journal::ListRecent( 25 );
            $photos = Photo::ListRecent( 0, 25 );
            $content = array();
            $i = 0;
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
            //foreach ( $photos as $photo ) {
            //    $content[ $i ] = $photo;
            //    $content[ $i ][ 'type' ] = 'photo';
            //    ++$i;
            //}
            shuffle( $content );
            shuffle( $content );
            global $settings;
            include 'views/news/listing.php';
        }
    }
?>
