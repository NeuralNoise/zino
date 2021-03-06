<?php
    
    class Album {
        public static function Item( $id ) {
            $res = db_array( 
                'SELECT
                    `album_id` AS id, `album_name` AS name, `album_delid` AS delid, `album_ownerid` AS ownerid, `album_numphotos` AS numphotos,
                    `album_ownertype` AS ownertype, `album_mainimageid` AS mainimageid, `album_description` AS description
                FROM
                    `albums`
                WHERE
                    `album_id` = :id AND
                    `album_delid` = 0
                LIMIT 1', compact( 'id' )
            );
            if ( empty( $res ) ) {
                return false;
            }
            return array_shift( $res );
        }
        public static function ListByUser( $userid, $offset = 0, $limit = 50 ) {
            is_numeric( $userid ) or die( 'userid not an integer' );

            $res = db(
                'SELECT
                    `album_id` AS id, `album_name` AS name, `album_delid` AS delid, `album_ownerid` AS ownerid, `album_numphotos` AS numphotos,
                    `album_ownertype` AS ownertype, `album_mainimageid` AS mainimageid
                FROM
                    `albums` 
                WHERE
                    `album_ownerid` = :userid AND
                    `album_delid` = 0
                ORDER BY
                    `album_id` DESC
                LIMIT :offset, :limit;',
                compact( 'userid', 'offset', 'limit' )
            );

            $albums = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $row[ 'id' ] = (int)$row[ 'id' ];
                $row[ 'ownerid' ] = (int)$row[ 'ownerid' ];
                $row[ 'mainimageid' ] = (int)$row[ 'mainimageid' ];
                $row[ 'numphotos' ] = (int)$row[ 'numphotos' ];
                $row[ 'delid' ] = (int)$row[ 'delid' ];
                $albums[ $row[ 'id' ] ] = $row;
            }

            return $albums;
        }
        public static function Create( $userid, $name, $description ) {
			clude( "models/types.php" );
	
            is_int( $userid ) or die( 'userid not an integer' );
			if ( !is_string( $name ) ) {
				throw new Exception( "No name given" );
			}
			if ( !is_string( $description ) ) {
				$description = "";
			}

            clude( 'models/agent.php' );
            clude( 'models/url.php' );

            $album = array(
                'ownertype' => TYPE_USERPROFILE,
                'ownerid' => $userid,
                'userip' => UserIp(),
                'name' => $name,
                'url' => URL_FormatUnique( $name, $userid, 'Album::ItemByUrlAndOwner' ),
                'description' => $description
            );

            $res = db( 
                "INSERT INTO `albums` ( `album_id`, `album_ownertype`, `album_ownerid`, `album_created`, `album_userip`, `album_name`, `album_url`, 
                                        `album_mainimageid`, `album_description`, `album_delid`, `album_numcomments`, `album_numphotos` )
                VALUES ( 0, :ownertype, :ownerid, NOW(), 0, :name, :url, 0, :description, 0, 0, 0 );",
                $album
            );

            $album[ 'id' ] = mysql_insert_id();
            $album[ 'created' ] = date( 'Y-m-d H:i:s', time() );
            $album[ 'mainimageid' ] = 0;
            $album[ 'delid' ] = 0;
            $album[ 'numcomments' ] = 0;
            $album[ 'numphotos' ] = 0;

            return $album;
        }
        public static function Update( $albumid, $name = false, $mainimageid = false ) {
            assert( is_string( $name ) );
            assert( is_numeric( $mainimageid ) );

            return db( 
                "UPDATE 
                    `albums` 
                SET 
                    `album_name` = :name,
                    `album_mainimageid` = :mainimageid
                WHERE
                    `album_id` = :albumid
                LIMIT 1;", compact( 'albumid', 'name', 'mainimageid' )
            );
        }
        public static function Delete( $id ) {
            is_numeric( $id ) or die( 'id not numeric' );
            $success = db(
                "UPDATE `albums` SET `album_delid` = 1 WHERE `album_id` = :id LIMIT 1;",
                compact( 'id' )
            );
            if ( !$success ) {
                return false;
            }
            return mysql_affected_rows() == 1;
        }
        public static function ItemByUrlAndOwner( $url, $ownerid ) {
            $res = db(
                "SELECT
                    *
                FROM
                    `albums`
                WHERE
                    `album_url` = :url AND
                    `album_ownerid` = :ownerid
                LIMIT 1;",
                compact( 'url', 'ownerid' )
            );

            return mysql_fetch_array( $res );
        }
    }

?>
