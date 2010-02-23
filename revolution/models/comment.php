<?php
    include 'models/mc.php';
    include 'models/types.php';

    function Comment_FindByPage( $typeid, $itemid, $page ) {
        if ( $page <= 0 ) {
            $page = 1;
        }

        --$page; // start from 0

        $paged = Comment_GetMemcached( $typeid, $itemid );

        $commentids = $paged[ $page ];
        $comments = Comment_Populate( $commentids );

        return array( count( $paged ), $comments );
    }
    function Comment_Populate( $commentids ) {
        include 'models/bulk.php';

        if ( empty( $commentids ) ) {
            return array();
        }

        $res = db(
            "SELECT
                `comment_id` AS id, `comment_created` AS created,
                `user_name` AS username, `user_gender` AS gender,
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
    function Comment_RegenerateMemcache( $typeid, $itemid ) {
        global $mc;
        
        $children = Comment_GetFromDb( $typeid, $itemid );

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
    function Comment_GetMemcached( $typeid, $itemid ) {
        global $mc;

        $paged = $mc->get( 'comtree_' . $itemid . '_' . $typeid );
        if ( $paged === false ) {
            $paged = Comment_RegenerateMemcache( $typeid, $itemid );
        }

        return $paged;
    }
    function Comment_GetFromDb( $typeid, $itemid, $offset = 0, $limit = 100000 ) {
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
?>
