<?php
    class User {
        public static function Login( $username, $password ) {
            if ( !$username || !$password ) {
                return false;
            }
            $res = db(
                'SELECT
                    `user_id` AS id, `user_name` AS name,
                    `user_authtoken` AS authtoken, `user_gender` AS gender
                FROM
                    `users`
                WHERE
                    `user_name` = :username
                    AND `user_password` = MD5( :password ) LIMIT 1',
                compact( 'username', 'password' )
            );
            if ( mysql_num_rows( $res ) ) {
                $row = mysql_fetch_array( $res );
                $row[ 'id' ] = ( int )$row[ 'id' ];
                return $row;
            }
            return false;
        }
        public static function GetEgoAlbumId( $userid ) {
            return ( int )array_shift( array_shift( db_array(
                'SELECT
                    `user_egoalbumid` AS egoalbumid
                FROM
                    `users`
                WHERE
                    `user_id` = :userid
                LIMIT 1;', compact( 'userid' )
            ) ) );
        }
        public static function Item( $id ) {
            $res = db(
                'SELECT
                    `user_id` AS id,
                    `user_deleted` as userdeleted, `user_name` AS name, `user_gender` AS gender, `user_subdomain` AS subdomain, `user_avatarid` AS avatarid
                FROM
                    `users`
                WHERE
                    `user_id` = :id
                LIMIT 1;', compact( 'id' )
            );
			return mysql_fetch_array( $res );
        }
        public static function ItemByName( $name ) {
            $res = db(
                'SELECT
                    `user_id` AS id,
                    `user_deleted` as userdeleted, `user_name` AS name, `user_gender` AS gender, `user_subdomain` AS subdomain, `user_avatarid` AS avatarid
                FROM
                    `users`
                WHERE
                    `user_name` = :name
                LIMIT 1;', compact( 'name' )
            );
			return mysql_fetch_array( $res );
        }
        public static function ItemBySubdomain( $subdomain ) {
            $res = db(
                'SELECT
                    `user_id` AS id,
                    `user_deleted` as userdeleted, `user_name` AS name, `user_gender` AS gender, `user_subdomain` AS subdomain, `user_avatarid` AS avatarid
                FROM
                    `users`
                WHERE
                    `user_subdomain` = :subdomain
                LIMIT 1;', compact( 'subdomain' )
            );
			return mysql_fetch_array( $res );
        }
        public static function ItemDetailsBySubdomain( $subdomain ) {
            return User::ItemDetailsByWhereClause( 'user_subdomain', $subdomain );
        }
        public static function ItemDetailsByName( $name ) {
            return User::ItemDetailsByWhereClause( 'user_name', $name );
        }
        public static function ItemDetails( $id ) {
            return User::ItemDetailsByWhereClause( 'user_id', $id );
        }
        private static function ItemDetailsByWhereClause( $field, $value ) {
            $query = 
                'SELECT
                    `user_id` AS id,
                    `user_deleted` as userdeleted, `user_name` AS name, `user_gender` AS gender, `user_subdomain` AS subdomain, `user_avatarid` AS avatarid,
                    `place_name` AS location,
                    `profile_numcomments` AS numcomments,
                    `profile_height`,
                    `profile_weight`,
                    `profile_smoker`,
                    `profile_drinker`,
                    `profile_skype`,
                    `profile_msn`,
                    `profile_gtalk`,
                    `profile_yim`,
                    `profile_eyecolor`,
                    `profile_haircolor`,
                    `profile_sexualorientation`,
                    `profile_relationship`,
                    `profile_religion`,
                    `profile_politics`,
                    `profile_slogan`,
                    `profile_aboutme`,
                    `profile_dob`,
                    `mood_labelmale`, `mood_labelfemale`,
                    `mood_url`,
                    (
                        ( DATE_FORMAT( NOW(), "%Y" ) - DATE_FORMAT( `profile_dob`, "%Y" ))
                        - ( DATE_FORMAT( NOW(), "00-%m-%d" ) < DATE_FORMAT( `profile_dob`,"00-%m-%d" ) )
                    ) AS profile_age,
                    `latest`.`statusbox_message` AS status
                FROM
                    `users`
                    LEFT JOIN `userprofiles`
                        ON `user_id`=`profile_userid`
                    LEFT JOIN `places`
                        ON `profile_placeid`=`place_id`
                    LEFT JOIN `moods`
                        ON `profile_moodid`=`mood_id`
                    LEFT JOIN `statusbox` AS latest
                        ON  `user_id` = `latest`.`statusbox_userid`
                    LEFT JOIN `statusbox` AS newer
                        ON `user_id` = `newer`.`statusbox_userid` AND `newer`.`statusbox_id` > `latest`.`statusbox_id`
                WHERE 
                    `' . $field . '` = :' . $field .' AND
                    `newer`.`statusbox_id` IS NULL
                LIMIT 1;';
            $res = db( $query, array( $field => $value ) );
			$row = mysql_fetch_array( $res );
            if ( $row === false ) {
                return false;
            }
            static $mooddetails = array( 'labelmale', 'labelfemale', 'url' );
            $row[ 'mood' ] = array();
            foreach ( $mooddetails as $detail ) {
                $row[ 'mood' ][ $detail ] = $row[ 'mood_' . $detail ];
                unset( $row[ 'mood_' . $detail ] );
            }
            static $profiledetails = array(
                'height', 'weight', 'smoker', 'drinker',
                'skype', 'msn', 'gtalk', 'yim',
                'eyecolor', 'haircolor',
                'sexualorientation', 'relationship',
                'religion', 'politics',
                'slogan', 'aboutme', 'dob', 'age'
            );
            foreach ( $profiledetails as $detail ) {
                $row[ 'profile' ][ $detail ] = $row[ 'profile_' . $detail ];
                unset( $row[ 'profile_' . $detail ] );
            }
            static $positivefields = array( 'height', 'weight' );
            foreach ( $positivefields as $field ) {
                if ( !( $row[ 'profile' ][ $field ] > 0 ) ) {
                    unset( $row[ 'profile' ][ $field ] );
                }
            }
            static $enumfields = array( 'sexualorientation', 'politics', 'religion', 'eyecolor', 'haircolor', 'relationship' );
            foreach ( $enumfields as $field ) {
                if ( $row[ 'profile' ][ $field ] == '-' ) {
                    unset( $row[ 'profile' ][ $field ] );
                }
            }
            static $textfields = array( 'msn', 'skype', 'yim', 'aboutme', 'slogan' );
            foreach ( $textfields as $field ) {
                if ( $row[ 'profile' ][ $field ] == '' ) {
                    unset( $row[ 'profile' ][ $field ] );
                }
            }
            if ( empty( $row[ 'status' ] ) ) {
                unset( $row[ 'status' ] );
            }

            return $row;
        }
        public static function UpdateItemDetails( $details, $userid ) {
            $whitelist = array_flip( array( 'profile_updated', 'profile_email', 'profile_emailvalidated', 'profile_emailvalidationhash', 'profile_placeid' , 'profile_dob', 'profile_slogan', 'profile_schoolid', 'profile_sexualorientation', 'profile_relationship', 'profile_religion', 'profile_politics', 'profile_aboutme', 'profile_moodid', 'profile_eyecolor', 'profile_haircolor', 'profile_height', 'profile_weight', 'profile_smoker', 'profile_drinker', 'profile_favquote', 'profile_mobile', 'profile_skype', 'profile_msn', 'profile_gtalk', 'profile_yim', 'profile_homepage', 'profile_firstname', 'profile_lastname', 'profile_address', 'profile_addressnum', 'profile_postcode', 'profile_area', 'profile_numcomments', 'profile_education', 'profile_educationyear', 'profile_songid', 'profile_songwidgetid' ) );

            if ( !is_array( $details ) ) return false;
            foreach ( $details as $key => $val ) {
                if ( !isset( $whitelist[ $key ] ) ) {
                    return false;
                }
            }
            
            $query = 
                'UPDATE `userprofiles`
                SET';
            foreach ( $details as $key => $val ) {
                $query = $query . " ". $key . " = :" . $key . ",";
            }       
            $query = $query . 'WHERE 
                    `profile_userid` = :userid
                LIMIT 1;';

            $details[ 'userid' ] = $userid;
            $res = db( $query, $details );
			
            return true;
        }
        public static function ListOnline() {
            $res = db(
                'SELECT
                    `user_id` AS id, `user_name` AS name
                FROM
                    `users`
                    CROSS JOIN `lastactive` ON
                        `user_id` = `lastactive_userid`
                WHERE
                    `lastactive_updated` > NOW() - INTERVAL 5 MINUTE
                ORDER BY
                    `lastactive_updated` DESC'
            );
            $ret = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $ret[ $row[ 'name' ] ] = $row;
            }
            ksort( $ret );
            $ret = array_values( $ret );
            return $ret;
        }
        private static function ValidateUsername( $username ) {
            static $reserved = array(
                'anonymous',
                'www',
                'beta',
                'store',
                'radio',
                'iphone',
                'universe',
                'images',
                'images2',
                'static',
                'api',
                'developers'
            );
            return ( bool )preg_match( '#^[a-zA-Z][a-zA-Z\-_0-9]{3,19}$#', $username ) && !in_array( $username , $reserved );
        }
        private static function DeriveSubdomain( $username ) {
            /* RFC 1034 - They must start with a letter, 
            end with a letter or digit,
            and have as interior characters only letters, digits, and hyphen.
            Labels must be 63 characters or less. */
            $username = strtolower( $username );
            $username = preg_replace( '/([^a-z0-9-])/i', '-', $username ); //convert invalid chars to hyphens
            $pattern = '/([a-z]+)([a-z0-9-]*)([a-z0-9]+)/i';
            if ( !preg_match( $pattern, $username, $matches ) ) {
                return false;
            }
            return $matches[ 0 ];
        }
        public static function Create( $name, $email, $password ) {
            $password = md5( $password );
            $subdomain = self::DeriveSubdomain( $name );
            if ( !self::ValidateUsername( $name ) ) {
                return false;
            }
            if ( $subdomain === false ) {
                // could not derive a subdomain
                return false;
            }
            db( 'INSERT INTO `users`
                 ( `user_name`, `user_email`, `user_password`, `user_subdomain` )
                 VALUES ( :name, :email, :password, :subdomain )',
                 compact( 'name', 'email', 'password', 'subdomain' ) );
            if ( !mysql_affected_rows() ) {
                return false; // username taken, or subdomain taken
            }
            $userid = mysql_insert_id();
            
            // TODO: Send welcome e-mail
            return $userid;
        }
    } 
    class UserCount { 
        public function Item( $userid ) {
            return array_pop( db_array(
                'SELECT
                    `count_images` AS images, `count_polls` AS polls, `count_journals` AS journals,
                    `count_comments` AS comments, `count_shouts` AS shouts, `count_relations` AS friends,
                    `count_answers` AS answers, `count_favourites` AS favourites
                FROM
                    `usercounts`
                WHERE
                    `count_userid` = :userid', compact( 'userid' ) )
            );
        }
    }
    class Settings {
        public static function Update( $userid, $emailnotif ) {
            is_int( $userid ) or die;
            $emailnotif = $emailnotif ? "yes" : "no";
            $res = db( 
                "UPDATE 
                    `usersettings` 
                SET 
                    `setting_emailprofilecomment` = '$emailnotif', 
                    `setting_emailphotocomment` = '$emailnotif',
                    `setting_emailphototag` = '$emailnotif',
                    `setting_emailjournalcomment` = '$emailnotif',
                    `setting_emailpollcomment` = '$emailnotif',
                    `setting_emailreply` = '$emailnotif',
                    `setting_emailfriendaddition` = '$emailnotif',
                    `setting_emailfriendjournal` = '$emailnotif',
                    `setting_emailfriendpoll` = '$emailnotif',
                    `setting_emailfriendphoto` = '$emailnotif',
                    `setting_emailfavourite` = '$emailnotif',
                    `setting_emailbirthday` = '$emailnotif'
                WHERE
                    `setting_userid` = :userid
                LIMIT 1;", compact( 'userid' ) 
            );
            return $res;
        }
        public static function Get( $userid ) {
            is_int( $userid ) or die;
            $res = db( 
                "SELECT 
                    `setting_emailprofilecomment`, `setting_notifyprofilecomment` 
                FROM 
                    `usersettings` 
                WHERE 
                    `setting_userid` = :userid
                LIMIT 1;", compact( 'userid' )
            );
            $row = mysql_fetch_array( $res );
            return array( $row[ 'setting_notifyprofilecomment' ] == 'yes', $row[ 'setting_emailprofilecomment' ] == 'yes' );
        }
    }
?>
