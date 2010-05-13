<?php
    clude( 'models/mc.php' );
    clude( 'models/types.php' );
    global $settings;
    define( 'COMMENT_PAGE_LIMIT', 50 );

    class Comment {
        public static function FindByPage( $typeid, $itemid, $page ) {
            if ( $page <= 0 ) {
                $page = 1;
            }

            --$page; // start from 0

            $paged = Comment::GetMemcached( $typeid, $itemid );

            $commentids = $paged[ $page ];
            
            $comments = Comment::Populate( $commentids );

            return array( count( $paged ), $comments );
        }
        public static function Populate( $commentids ) {
            clude( 'models/bulk.php' );

            if ( empty( $commentids ) ) {
                return array();
            }

            $res = db(
                "SELECT
                    `comment_id` AS id, `comment_created` AS created,
                    `user_name` AS name, `user_gender` AS gender,
                    `user_id` AS userid,
                    `comment_bulkid` AS bulkid, `image_id` AS avatarid,
                    `comment_parentid` AS parentid
                FROM
                    `comments`
                    LEFT JOIN `users` ON `comment_userid` = `user_id`
                    LEFT JOIN `images` ON `user_avatarid` = `image_id`
                WHERE
                    `comment_id` IN :commentids;",
                array( 'commentids' => $commentids )
            );

            $comments = array();
            $bulkids = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                 $comments[ $row[ 'id' ] ] = $row;
                 $bulkids[] = $row[ 'bulkid' ];
            }

            $bulks = Bulk::FindById( $bulkids );

            $ret = array();
            foreach ( $commentids as $commentid ) {
                if ( isset( $comments[ $commentid ] ) ) {
                    $comments[ $commentid ][ 'text' ] = $bulks[ $comments[ $commentid ][ 'bulkid' ] ];
                    $ret[] = $comments[ $commentid ];
                }
            }

            return $ret;
        }
        function RegenerateMemcache( $typeid, $itemid ) {
            global $mc;
            
            $children = Comment::GetFromDb( $typeid, $itemid );

            $paged = array();
            $paged[ 0 ] = array();
            $cur_page = 0;
            $stack = array( 0 );
            while ( !empty( $stack ) ) {
                $parent = array_pop( $stack );
                if ( !is_array( $parent ) ) {
                    $parentid = 0;
                }
                else {
                    $parentid = $parent[ 'comment_id' ];

                    if ( $parent[ 'comment_parentid' ] == 0 ) { // top parent found!
                        if ( count( $paged[ $cur_page ] ) >= COMMENT_PAGE_LIMIT ) {
                            ++$cur_page;
                            $paged[ $cur_page ] = array();
                        }
                    }

                    $paged[ $cur_page ][] = (int)$parent[ 'comment_id' ];
                }

                if ( !isset( $children[ $parentid ] ) ) {
                    continue;
                }
                foreach ( $children[ $parentid ] as $comment ) {
                        $stack[] = $comment;
                }
            }

            $mc->set( 'comtree_' . $itemid . '_' . $typeid, $paged );

            return $paged;
        }
        public static function GetMemcached( $typeid, $itemid ) {
            global $mc;
            $paged = $mc->get( 'comtree_' . $itemid . '_' . $typeid );
            if ( $paged === false ) {
                return Comment::RegenerateMemcache( $typeid, $itemid );
            }
            return $paged;
        }
        public static function GetFromDb( $typeid, $itemid, $offset = 0, $limit = 100000 ) {
            $res = db(
                "SELECT
                    `comment_id`, `comment_parentid`
                FROM
                    `comments`
                WHERE
                    `comment_typeid` = :typeid AND
                    `comment_itemid` = :itemid AND
                    `comment_delid` = :delid
                ORDER BY
                    `comment_id` ASC
                LIMIT
                    :offset, :limit;",
                array(
                    'typeid' => $typeid,
                    'itemid' => $itemid,
                    'delid' => 0,
                    'offset' => $offset,
                    'limit' => $limit
                )
            );
            
            $children = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $children[ $row[ 'comment_parentid' ] ][] = $row;
            }

            return $children;
        }
        public static function Create( $userid, $text, $typeid, $itemid, $parentid ) {
            clude( 'models/bulk.php' );
            clude( 'models/wysiwyg.php' );
            clude( 'models/notification.php' );

            switch ( $typeid ) {
                case TYPE_POLL:
                    //include( 'models/poll.php' );
                    $item = Poll::Item( $itemid );
                    break;
                case TYPE_IMAGE:
                    //include( 'models/photo.php' );
                    $item = Photo::Item( $itemid );
                    break;
                case TYPE_USERPROFILE:
                    //include( 'models/user.php' );
                    $item = User::Item( $itemid );
                    if ( $item !== false ) {
                        $item[ 'userid' ] = $itemid;
                    }
                    break;
                case TYPE_JOURNAL:
                    //include( 'model/journal.php' );
                    $item = Journal::Item( $itemid );
                    break;
                default:
                    // no such comment type
                    return false;
            }
            if ( $item === false ) {
                // no such target item
                return false;
            }
            if ( $parentid ) {      
                // TODO: Check the parent of the parent and remove relevant notifications if owner of comment-to-be-created match
                $comment = Comment::Item( $parentid ); // TODO: Optimize; do not need bulk
                if ( $comment === false ) {
                    // no such parent comment
                    return false;
                }
                if ( $comment[ 'parentid' ] ) { //has grandparent
                    $grandparentComment = Comment::Item( $comment[ 'parentid' ] );
                    if ( $grandparentComment[ 'userid' ] == $userid ) {
                        Notification::DeleteByInfo( 4, $comment[ 'id' ], $userid );
                    }
                }
                else { //hasn't grandparent                     
                    if ( $item[ 'userid' ] == $userid ) {
                        Notification::DeleteByInfo( 4, $comment[ 'id' ], $userid );
                    }
                }
                $owner = $comment[ 'user' ][ 'id' ];
            }
            else {
                $owner = $item[ 'userid' ];
            }
            $text = nl2br( htmlspecialchars( $text ) );
            $text = WYSIWYG_PostProcess( $text );

            $bulkid = Bulk::Store( $text );
            db( "INSERT INTO `comments`
                    (`comment_userid`, `comment_bulkid`, `comment_typeid`, `comment_itemid`, `comment_parentid`, `comment_created`)
                VALUES
                    (:userid, :bulkid, :typeid, :itemid, :parentid, NOW())", compact( 'userid', 'bulkid', 'typeid', 'itemid', 'parentid' )
            );
            $id = mysql_insert_id();
            
            Comment::RegenerateMemcache( $typeid, $itemid );
            if ( $owner != $userid ) {
                Notification::Create( $userid, $owner, 'EVENT_COMMENT_CREATED', $id );
            }
            // TODO: comet
            return array(
                'id' => $id,
                'text' => $text
            );
        }
        public function Item( $commentid ){
            $ret = self::ItemMulti( array( $commentid ) );
            if ( !empty( $ret ) ) {
                return array_shift( $ret );
            }
            return false;
        }
        public static function ItemMulti( $ids ) {
            clude( 'models/bulk.php' );
            $res = db( 'SELECT
                            `comment_id` AS id, `comment_typeid` AS typeid, `comment_itemid` AS itemid, `comment_bulkid` AS bulkid,
                            `comment_created` AS created, `comment_parentid` AS parentid,
                            `user_id` AS userid, `user_name` AS username, `user_gender` AS gender,
                            `user_subdomain` AS subdomain, `user_avatarid` AS avatarid
                        FROM
                            `comments` CROSS JOIN `users`
                                ON `comment_userid` = `user_id`
                        WHERE
                            `comment_id` IN :ids', compact( 'ids' ) );
            $commentinfo = array();
            $bulkids = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $commentinfo[ $row[ 'id' ] ] = $row;
                $bulkids[] = $row[ 'bulkid' ];
            }
            $bulk = Bulk::FindById( $bulkids );
            foreach ( $commentinfo as $id => $comment ) {
                $commentinfo[ $id ][ 'text' ] = $bulk[ $comment[ 'bulkid' ] ];
                $commentinfo[ $id ][ 'user' ] = array(
                    'id' => $comment[ 'userid' ],
                    'name' => $comment[ 'username' ],
                    'avatarid' => $comment[ 'avatarid' ],
                    'gender' => $comment[ 'gender' ],
                    'subdomain' => $comment[ 'subdomain' ],
                );
            }
            return $commentinfo;
        }
    }
?>
