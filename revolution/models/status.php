<?php

    class Status {
        public static function Create( $userid, $text ) {
            is_int( $userid ) or die;

            $status = array(
                'userid' => $userid,
                'text' => $text
            );

            $res = db( "INSERT INTO `statusbox` ( `statusbox_id`, `statusbox_userid`, `statusbox_message`, `statusbox_created` )
                        VALUES ( 0, :userid, :text, NOW() );", $status );

            $status[ 'id' ] = mysql_insert_id();
            return $status;
        }
    }

?>
