<?php
    class Photo {
        public static function ListRecent( $offset = 0, $limit = 100 ) {
            $res = db(
                'SELECT
                    `image_id` AS id, `image_userid` AS userid, `image_created` AS created, `image_numcomments` AS numcomments,
                    `user_name` AS username, `user_subdomain` AS subdomain, `user_gender` AS gender, `user_avatarid` AS avatarid
                FROM
                    `images` CROSS JOIN `users`
                        ON image_userid = user_id
                WHERE
                    `image_delid`= 0 AND
                    `user_deleted` = 0 AND
                    `image_albumid` != 0
                ORDER BY
                    id DESC
                LIMIT :offset, :limit', compact( 'offset', 'limit' )
            );
            $photos = array();
            while ( $photo = mysql_fetch_array( $res ) ) {
                $photo[ 'user' ] = array();
                $photo[ 'user' ][ 'id' ] = $photo[ 'userid' ];
                $photo[ 'user' ][ 'name' ] = $photo[ 'username' ];
                $photo[ 'user' ][ 'subdomain' ] = $photo[ 'subdomain' ];
                $photo[ 'user' ][ 'gender' ] = $photo[ 'gender' ];
                $photo[ 'user' ][ 'avatarid' ] = $photo[ 'avatarid' ];
                $photos[] = $photo;
            }
            return $photos;
        }
        public static function ListByUser( $userid, $offset = 0, $limit = 100 ) {
            return db_array(
                'SELECT
                    `image_id` AS id, `image_userid` AS userid, `image_created` AS created, `image_numcomments` AS numcomments,
                    `user_name` AS username, `user_subdomain` AS subdomain, `user_gender` AS gender, `user_avatarid` AS avatarid
                FROM
                    `images` CROSS JOIN `users`
                        ON image_userid = user_id
                WHERE
                    `image_delid`=0 AND
                    `user_deleted` = 0 AND
                    `image_userid` = :userid AND
                    `image_albumid` != 0
                ORDER BY
                    id DESC
                LIMIT :offset, :limit', compact( 'userid', 'offset', 'limit' )
            );
        }
        public static function ListByAlbum( $albumid, $offset = 0, $limit = 100 ) {
            return db_array(
                'SELECT
                    `image_id` AS id, `image_userid` AS userid, `image_created` AS created, `image_numcomments` AS numcomments,
                    `user_name` AS username, `user_subdomain` AS subdomain, `user_gender` AS gender, `user_avatarid` AS avatarid
                FROM
                    `images` CROSS JOIN `users`
                        ON image_userid = user_id
                WHERE
                    `image_delid`=0 AND
                    `user_deleted` = 0 AND
                    `image_albumid` = :albumid 
                ORDER BY
                    id DESC
                LIMIT :offset, :limit', compact( 'albumid', 'offset', 'limit' )
            );
        }
        public static function Item( $id ) {
            /* TODO: 2 selects, stored procedure */
            $res = db(
                'SELECT
                    i.`image_id` AS id, i.`image_userid` AS userid, i.`image_created` AS created, i.`image_name` AS title,
                    `user_deleted` as userdeleted, `user_name` AS username, `user_gender` AS gender, `user_subdomain` AS subdomain, `user_avatarid` AS avatarid,
                    i.`image_width` AS w, i.`image_height` AS h, i.`image_numcomments` AS numcomments,
                    MAX( next.`image_id` ) AS nextid,
                    MIN( previous.`image_id` ) AS previd,
                    i.`image_delid` AS deleted,
                    `album_name` AS albumname, `album_id` AS albumid,
                    (`album_id` = `user_egoalbumid`) AS isegoalbum
                FROM
                    `images` AS i
                    CROSS JOIN `users`
                        ON i.`image_userid` = `user_id`
                    CROSS JOIN `albums`
                        ON i.`image_albumid` = `album_id`
                    LEFT JOIN `images` AS next
                        ON i.`image_userid` = next.`image_userid` AND
                        next.`image_id` < i.`image_id` AND
						next.`image_delid` = 0
                    LEFT JOIN `images` AS previous
                        ON i.`image_userid` = previous.`image_userid` AND
                        previous.`image_id` > i.`image_id` AND
						previous.`image_delid` = 0
                WHERE
                    i.`image_id` = :id 
                GROUP BY
                    i.`image_id`
                LIMIT 1;', array( 'id' => $id )
            );
            
            if (  mysql_num_rows( $res ) == 0 ) {
                return false;
            }

			$item = mysql_fetch_array( $res );
            if ( $item === false ) {
                return false;
            }
            $item[ 'w' ] = $item[ 'w' ] ;
            $item[ 'h' ] = $item[ 'h' ] ;
            $item[ 'album' ] = array(
                'id' => $item[ 'albumid' ],
                'egoalbum' => ( bool )$item[ 'isegoalbum' ],
                'name' => $item[ 'albumname' ]
            );
            $item[ 'deleted' ] = $item[ 'deleted' ];
            $item[ 'user' ] = array(
                'id' => (int)$item[ 'userid' ],
                'name' => $item[ 'username' ],
                'gender' => $item[ 'gender' ],
                'subdomain' => $item[ 'subdomain' ],
                'avatarid' => $item[ 'avatarid' ],
                'deleted' => $item[ 'userdeleted' ]
            );
			return $item;
        }
        public static function ListByIds( $ids ) {
			clude( 'models/db.php' );
			if ( empty( $ids ) ) {
				return array();
			}
            $res = db(
                'SELECT
                    `image_id` AS id, `image_userid` AS userid, `image_created` AS created, `image_name` AS title,
                    `user_name` AS username, `user_gender` AS gender, `user_subdomain` AS subdomain, `user_avatarid` as avatarid,
                    `image_width` AS w, `image_height` AS h, `image_numcomments` AS numcomments
                FROM
                    `images` CROSS JOIN `users`
                        ON `image_userid` = `user_id`
                WHERE
                    `image_id` IN :ids;', array( 'ids' => $ids )
            );

            $photos = array();
            while ( $photo = mysql_fetch_array( $res ) ) {
                $photo[ 'user' ] = array();
                $photo[ 'user' ][ 'id' ] = $photo[ 'userid' ];
                $photo[ 'user' ][ 'name' ] = $photo[ 'username' ];
                $photo[ 'user' ][ 'subdomain' ] = $photo[ 'subdomain' ];
                $photo[ 'user' ][ 'gender' ] = $photo[ 'gender' ];
                $photo[ 'user' ][ 'avatarid' ] = $photo[ 'avatarid' ];
                $photos[ $photo[ 'id' ] ] = $photo;
            }
            return $photos;

            /*
            $keys = array();
            $i = 1;
            foreach ( $ids as $id ) {
                $keys[ $id ] = $i;
                $i = $i + 1;
            }

            $images = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $images[ $keys[ $row[ 'id' ] ] ] = $row;
            }
            ksort( $images );
            
            return $images;
            */
        }
        public static function Create( $userid, $albumid, $tempname ) {
            clude( 'models/agent.php' );

            is_int( $userid ) or die( 'userid is not an integer' );
            is_int( $albumid ) or die( 'albumid is not an integer' );

            $ip = ( string )UserIp();

			//set delid = 1 in case of problem in upload
            db( 'INSERT INTO `images`
                ( `image_userid`, `image_albumid`, `image_userip`, `image_created`, `image_delid` )
                VALUES ( :userid, :albumid, :ip, NOW(), 1 )',
                compact( 'userid', 'albumid', 'ip' ) );

            $id = mysql_insert_id();

            $data = Photo::Upload( $userid, $id, $tempname );

            if ( !is_array( $data ) ) {
                Photo::Delete( $id );
                return $data;
            }

            $state = Photo::UpdateFileInformation( $id, $data[ 'width' ], $data[ 'height' ], $data[ 'filesize' ], $data[ 'mime' ] );
            $data[ 'id' ] = $id;

			//set delid = 0 , succesfull upload !
			Photo::UnDelete( $id );

            return $data;
        }
		public static function UnDelete( $id ) {
			if ( !is_numeric( $id ) ) {
				throw new Exception( "Id should be numeric" );
			}
			return db( "UPDATE
                        `images` 
                    SET 
                        `image_delid` = 0
                    WHERE 
                        `image_id` = :id
                    LIMIT 1;", compact( 'id' )
			);
		}
        public static function UpdateDetails( $id, $title, $albumid ) {
            $id = ( int )$id;
            $albumid = ( int )$albumid;
            
            $sql = "UPDATE
                        `images` 
                    SET 
                        `image_name` = :title,
                        `image_albumid` = :albumid
                    WHERE 
                        `image_id` = :id
                    LIMIT 1;";
            return db( $sql, compact( 'id', 'title', 'albumid' ) );
        }
        public static function UpdateFileInformation( $id, $width, $height, $size, $mime ) {
            $id = ( int )$id;
            $width = ( int )$width;
            $height = ( int )$height;
            $size = ( int )$size;

            return db( 'UPDATE 
                    `images`
                SET
                    `image_width` = :width,
                    `image_height` = :height,
                    `image_size` = :size,
                    `image_mime` = :mime
                WHERE
                    `image_id` = :id
                LIMIT
                    1', compact( 'id', 'width', 'height', 'size', 'mime' ) );
        }
        public static function Delete( $id ) {
            db(
                'UPDATE
                    `images`
                SET
                    `image_delid`=1
                WHERE
                    `image_id` = :id
                LIMIT 1', compact( 'id' )
            );
        }
        public static function Upload( $userid, $imageid, $tempfile, $resizeto = false /* 700x600 */ ) {
            /*
             * Ported from phoenix, commented out ImageException throwing
             */

            global $settings;

            if ( filesize( $tempfile ) > 7 * 1024 * 1024 ) { // 7 MB
                return -1;
            }

            if ( !file_exists( $tempfile ) ) {
                throw New Exception( 'Image_Upload() failed: Target temporary file does not exist: ' . $tempfile );
            }

            $data = array(
                'userid' => $userid,
                'imageid' => $imageid,
                'mime' => 'image/jpeg',
                'uploadimage' => "@$tempfile"
            );

            if ( $resizeto !== false ) {
                w_assert( preg_match( '#^[0-9]{1,4}x[0-9]{1,4}$#', $resizeto ) );
                $data[ 'size' ] = $resizeto;
            }

            if ( $settings[ 'beta' ] ) {
                $data[ 'sandbox' ] = 'yes';
                $data[ 'a' ] = 'mepwurjcEJerjWJjeIwpoqXkrotjXFPKL125XCV123501V'; // key for bypassing IP check, for photo testing from local working copies
            }

            $header[ 0 ] = "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
            $header[] = "Accept-Language: en-us,en;q=0.5";
            $header[] = "Accept-Encoding: gzip,deflate";
            $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
            $header[] = "Keep-Alive: 300";
            $header[] = "Connection: keep-alive";
            $header[] = "Expect:";
        
            $curl = curl_init();
            curl_setopt( $curl, CURLOPT_URL, $settings[ 'imagesuploadurl' ] );
            curl_setopt( $curl, CURLOPT_USERAGENT, "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.8) Gecko/20071030 Firefox/2.0.0.8" );
            curl_setopt( $curl, CURLOPT_HTTPHEADER, $header );
            curl_setopt( $curl, CURLOPT_ENCODING, 'gzip,deflate' );
            curl_setopt( $curl, CURLOPT_AUTOREFERER, true );
            curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $curl, CURLOPT_POST, 1 );
            curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );

            // curl_setopt( $curl, CURLOPT_VERBOSE, 1 );

            $data = curl_exec( $curl );

            if ( $data === false ) {
                throw New Exception( 'Image_Upload curl error: ' . curl_error( $curl ) . ' (' . curl_errno( $curl ) . ')' );
            }

            curl_close( $curl );

            if ( strpos( $data, "error" ) !== false ) {
                throw New Exception( 'Image_Upload could not upload the image: Server returned an error: ' . $data );
            }
            else if ( strpos( $data, "success" ) !== false ) {
                $start = strpos( $data, "[" ) + 1;

                $data = substr( $data, $start, strlen( $data ) - $start - 1 );
                $split = explode( "," , $data );
                $width = $split[ 0 ];
                $height = $split[ 1 ];
                $filesize = $split[ 2 ];
                $mime = $split[ 3 ];

                $upload = array();
                $upload[ 'width' ] = ( integer )$width;
                $upload[ 'height' ] = ( integer )$height;
                $upload[ 'filesize' ] = ( integer )$filesize;
                $upload[ 'mime' ] = $mime;

                return $upload;
            }
            // err'd
            // throw New ImageException( 'Image_Upload could not upload the image: Server returned an unknown state: ' . $data );
            throw New Exception( 'Image_Upload could not upload the image: Server returned an unknown state: ' . $data );
        }
    }
?>
