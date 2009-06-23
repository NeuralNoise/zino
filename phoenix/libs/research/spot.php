<?php

    /*
    SPOT - Social Prediction And Optimization Tool
    Class for connecting to the Spot deamon.

    Coming soon: unit tests!
    */
    
    define( 'SPOT_PORT', 21490 );

    class Spot {
        private static $mRequestHeader = "SPOT\n";
        private static $mServerIp = '88.198.246.217'; // europa.kamibu.com

        public function __construct() {
            // do nothing! static methods
        }
        private static function SendRequest( $requestBody ) {
            // TODO: Response Text
            // TODO: Error checking

            $request = self::$mRequestHeader . $requestBody;
            
            $sock = socket_create( AF_INET, SOCK_STREAM, SOL_TCP );
            w_assert( $sock !== false, "Socket creation failed. Reason: " . socket_strerror( socket_last_error( $sock ) ) );
            $result = socket_connect( $sock, self::$mServerIp, SPOT_PORT );
            w_assert( $result !== false, "Socket connection failed. Reason: ($result) " . socket_strerror( socket_last_error( $sock ) ) );
            socket_write( $sock, $request );

            $response = socket_read( $sock, 1024 );
            socket_close( $sock );

            $lines = explode( "\n", $response );
            w_assert( $lines[ 0 ] == "SUCCESS", "Spot failed! Response: $response" );
            array_shift( $lines );
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
        public static function GetContent( $user ) {
            $userid = $user->Id;
            $request = "GET CONTENT\n$userid\n";
            $content = self::SendRequest( $request );
            // TODO: process content somehow?

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

            $content = array();
            foreach ( $lines as $id ) {
                if ( empty( $id ) ) {
                    continue;
                }
                $content[] = New Journal( $id );
            }

            $water->ProfileEnd();

            return $content;
        }
        public static function GetImages( $user ) {
            global $libs;
            global $water;
            $libs->Load( 'image/image' );

            $water->Profile( 'Spot get images' );

            $userid = $user->Id;
            $request = "GET IMAGES\n$userid\n";
            $lines = self::SendRequest( $request );

            $content = array();
            foreach ( $lines as $id ) {
                $content[] = New Image( $id );
            }

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

            $content = array();
            foreach ( $lines as $id ) {
                if ( empty( $id ) ) {
                    continue;
                }
                $content[] = New Poll( $id );
            }

            $water->ProfileEnd();

            return $content;
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
