<?php

	class Poll {
		public static function ListRecent( $amount ) {
		    $res = db(
                'SELECT
					`poll_id` as id, `poll_question` as question, `poll_url` as url, `poll_userid` as userid, `poll_created` as created , `poll_numvotes` as numvotes, `poll_numcomments` as numcomments 
				FROM 
					`polls`
				CROSS JOIN `users` ON
					`poll_userid` = `user_id`
				WHERE `poll_delid` = 0
				ORDER BY `poll_id` DESC
				LIMIT :amount', array( 'amount' => $amount ) 
            );
            $polls = array();
            while ( $row = mysql_fetch_array( $res ) ) {
                $polls[] = $row;
            }
            return $polls;
        }
		
		public static function Item( $id ) {
			$res = db(
					'SELECT
						`user_name` as name, `user_subdomain` as subdomain, `user_avatarid` as avatarid, `user_gender` as gender, `poll_id` as id, `poll_question` as question, `poll_url` as url, `poll_userid` as userid, `poll_created` as created , `poll_numvotes` as numvotes, `poll_numcomments` as numcomments 
					FROM 
						`polls`
					CROSS JOIN `users` ON
						`poll_userid` = `user_id`
					WHERE `poll_id` = :id
					LIMIT 1', compact( 'id' ) 
			);
			
			$item = array();
			$item = mysql_fetch_array( $res );
			
			$res2 = db(
					'SELECT
						`polloption_id` as id, `polloption_text` as text, `polloption_numvotes` as numvotes
					FROM
						`polloptions`
					WHERE 
						`polloption_pollid` = :id 
					LIMIT 
						0,25',compact( 'id' )
			);
			
			$item[ 'options' ] = array();
			while ( $row = mysql_fetch_array( $res ) ) {
                $item[ 'options' ] = $row;
            }
			return $item;
		}
	}
?>