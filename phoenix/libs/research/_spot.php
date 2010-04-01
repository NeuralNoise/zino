<?php

    /*
    SPOT - Social Prediction And Optimization Tool
    Class for connecting to the Spot deamon.

    Coming soon: unit tests!
    */
    
    class Spot {
        private static $mRequestHeader = "SPOT\n";

        public function __construct() {
            // do nothing! static methods
        }
        private static function SendRequest( $requestBody ) {
            global $xc_settings;

            // TODO: Response Text
            // TODO: Error checking

            $request = self::$mRequestHeader . $requestBody;
            $sock = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
            // w_assert( $sock !== false, "Socket creation failed. Reason: " . socket_strerror( socket_last_error( $sock ) ) );
            if ( $sock === false ) {
                return false;
            }

            $result = @socket_connect( $sock, $xc_settings[ 'spotdaemon' ][ 'address' ], $xc_settings[ 'spotdaemon' ][ 'port' ] );
            // w_assert( $result !== false, "Spot connection failed. Run spot daemon." );
            if ( $result === false ) {
                socket_close( $sock );
                return false;
            }

            socket_write( $sock, $request );

            $response = socket_read( $sock, 1024 );
            socket_close( $sock );

            $lines = explode( "\n", $response );
            // w_assert( $lines[ 0 ] == "SUCCESS", "Spot failed! Response: $response" );
            array_shift( $lines ); // success message
            array_pop( $lines ); // useless last line exploded
            return $lines;
        }
        public static function CommentCreated( $comment ) {
            $userid = $comment->Userid;
            $itemid = $comment->Itemid;
            $typeid = $comment->Typeid;
            $request = "NEW COMMENT\n$userid\n$itemid\n$typeid\n";
            self::SendRequest( $request );
        }
        public static function VoteCreated( $vote ) {
            $userid = $vote->Userid;
            $pollid = $vote->Pollid;
            $optionid = $vote->Optionid;
            $request = "NEW VOTE\n$userid\n$pollid\n$optionid\n";
            self::SendRequest( $request );
        }
        public static function FavouriteCreated( $favourite ) {
            $userid = $favourite->Userid;
            $itemid = $favourite->Itemid;
            $typeid = $favourite->Typeid;
            $request = "NEW FAVOURITE\n$userid\n$itemid\n$typeid\n";
            self::SendRequest( $request );
        }
		public static function JournalVisited( $info, $journalid ) {
			global $user;
            $userid = $user->Id;
            $itemid = $journalid;
            $trainvalues = $info;
            $request = "VISITED\n$userid\n$itemid\n$trainvalues\n";
            self::SendRequest( $request );
        }
        public static function GetContent( $user, $numImages = 30, $numJournals = 10, $numPolls = 10 ) {
            global $libs;
            $libs->Load( 'image/image' );
            $libs->Load( 'journal/journal' );
            $libs->Load( 'poll/poll' );

            $userid = $user->Id;
            $request = "GET CONTENT\n$userid\n$numImages\n$numJournals\n$numPolls\n";
            $content = self::SendRequest( $request );
            // TODO: process content somehow?

            $lines = self::SendRequest( $request );
            if ( $lines === false ) {
                return $lines;
            }

            $imageids = array();
            $journalids = array();
            $pollids = array();
            $idToOrder = array();
            foreach ( $lines as $order => $line ) {
                list( $type, $id ) = explode( ":", $line );
                $idToOrder[ $type ][ $id ] = $order;
                switch ( $type ) {
                    case 'image':
                        $imageids[] = $id;
                        break;
                    case 'journal':
                        $journalids[] = $id;
                        break;
                    case 'poll':
                        $pollids[] = $id;
                        break;
                }
            }

            $finder = New ImageFinder();
            $images = $finder->FindByIds( $imageids );

            $finder = New JournalFinder();
            $journals = $finder->FindByIds( $journalids );

            $finder = New PollFinder();
            $polls = $finder->FindByIds( $pollids );

            $content = array();
            foreach ( $images as $image ) {
                $content[ $idToOrder[ 'image' ][ $image->Id ] ] = $image;
            }
            foreach ( $journals as $journal ) {
                $content[ $idToOrder[ 'journal' ][ $journal->Id ] ] = $journal;
            }
            foreach ( $polls as $poll ) {
                $content[ $idToOrder[ 'poll' ][ $poll->Id ] ] = $poll;
            }

            return $content;
        }
        public static function GetJournals( $user, $num = 4 ) {
            global $libs;
            global $water;
            $libs->Load( 'journal/journal' );

            $water->Profile( 'Spot get journals' );

            $userid = $user->Id;
            $request = "GET JOURNALS\n$userid\n$num\n";
            $lines = self::SendRequest( $request );
            if ( $lines === false ) {
                return $lines;
            }

            foreach ( $lines as $index => $id ) {
                $lines[ $index ] = (int)$id;
            }

            $water->ProfileEnd();

            return $lines; // journal ids
        }
		public static function GetJournalsExtended( $user, $num = 4 ) {
            global $libs;
            global $water;
            $libs->Load( 'journal/journal' );

            $water->Profile( 'Spot get journals' );

            $userid = $user->Id;
            $request = "GET JOURNALS EXTENDED\n$userid\n$num\n";
            $lines = self::SendRequest( $request );
            if ( $lines === false ) {
                return $lines;
            }
			$res = array();
			$tempid;
            foreach ( $lines as $index => $id ) {
                $lines[ $index ] = explode( " ", $id );
				$tempid = (int)$lines[ $index ][ 0 ];
				array_shift( $lines[ $index ] );
				$res[ $index ] = array( "journalid" => $tempid, "ranks" => $lines[ $index ] );
            }

            $water->ProfileEnd();

            return $res; // journal ids and extended info
        }
        public static function GetImages( $user, $num = 30 ) {
            global $libs;
            global $water;
            $libs->Load( 'image/image' );

            $water->Profile( 'Spot get images' );

            $userid = $user->Id;
            $request = "GET IMAGES\n$userid\n$num\n";
            $lines = self::SendRequest( $request );
            if ( $lines === false ) {
                return $lines;
            }

            $content = array();

            foreach ( $lines as $index => $id ) {
                $lines[ $index ] = (int)$id;
            }

            $finder = New ImageFinder();
            $content = $finder->FindByIds( $lines );
            
            $water->ProfileEnd();

            return $content;
        }
        public static function GetPolls( $user, $num = 4 ) {
            global $libs;
            global $water;
            $libs->Load( 'poll/poll' );

            $water->Profile( 'Spot get polls' );

            $userid = $user->Id;
            $request = "GET POLLS\n$userid\n$num\n";
            $lines = self::SendRequest( $request );
            if ( $lines === false ) {
                return $lines;
            }
            foreach ( $lines as $index => $id ) {
                $lines[ $index ] = (int)$id;
            }

            $water->ProfileEnd();

            return $lines; // journal ids
        }
        public static function GetSamecom( $auser, $buser ) { // for testing only.
            $auserid = $auser->Id;
            $buserid = $buser->Id;
            $request = "GET SAMECOM\n$auserid\n$buserid\n";
            $response = self::SendRequest( $request );
            $samecom = (int)( $response[ 0 ] );
            return $samecom;
        }
        public static function GetUniquecoms( $user ) { // for testing only.
            $userid = $user->Id;
            $request = "GET UNIQUECOMS\n$userid\n";
            $response = self::SendRequest( $request );
            $uniquecoms = (int)( $response[ 0 ] );
            return $uniquecoms;
        }
    }

?>
