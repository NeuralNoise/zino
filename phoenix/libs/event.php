<?php

	/*
	Developer: abresas
	*/

	/*
	new comment

	$event = New Event();
	$event->Typeid = EVENT_COMMENT_CREATED;
	$event->Itemid = $comment->Id;
	$event->Created = $comment->Created;
	$event->Userid = $comment->Userid;
	$event->Save();
	*/

	function Event_Types() {
        // New events here!
        // EVENT_MODEL(_ATTRIBUTE)_ACTION
        return array(
            1 => 'EVENT_ALBUM_CREATED',
            2 => 'EVENT_ALBUM_UPDATED', // not in use
            3 => 'EVENT_ALBUM_DELETED', // not in use
            4 => 'EVENT_COMMENT_CREATED',
            5 => 'EVENT_COMMENT_UPDATED', // not in use
            6 => 'EVENT_COMMENT_DELETED', // not in use
            7 => 'EVENT_IMAGE_CREATED',
            8 => 'EVENT_IMAGE_UPDATED', // not in use
            9 => 'EVENT_IMAGE_DELETED', // not in use
            10 => 'EVENT_JOURNAL_CREATED',
            11 => 'EVENT_JOURNAL_UPDATED',
            12 => 'EVENT_JOURNAL_DELETED', // not in use
            13 => 'EVENT_POLL_CREATED',
            14 => 'EVENT_POLL_UPDATED', // not in use
            15 => 'EVENT_POLL_DELETED', // not in use
            16 => 'EVENT_POLLVOTE_CREATED', // not in use
            17 => 'EVENT_POLLOPTION_CREATED', // not in use
            18 => 'EVENT_POLLOPTION_DELETED', // not in use
            19 => 'EVENT_FRIENDRELATION_CREATED',
            20 => 'EVENT_FRIENDRELATION_UPDATED',
            21 => 'EVENT_USERSPACE_UPDATED',
            22 => 'EVENT_USERPROFILE_UPDATED', // not in use
            23 => 'EVENT_USERPROFILE_VISITED', // not in use
            24 => 'EVENT_USERPROFILE_EDUCATION_UPDATED',
            25 => 'EVENT_USERPROFILE_SEXUALORIENTATION_UPDATED',
            26 => 'EVENT_USERPROFILE_RELIGION_UPDATED',
            27 => 'EVENT_USERPROFILE_POLITICS_UPDATED',
            28 => 'EVENT_USERPROFILE_SMOKER_UPDATED',
            29 => 'EVENT_USERPROFILE_DRINKER_UPDATED',
            30 => 'EVENT_USERPROFILE_ABOUTME_UPDATED',
            31 => 'EVENT_USERPROFILE_MOOD_UPDATED',
            32 => 'EVENT_USERPROFILE_LOCATION_UPDATED',
            33 => 'EVENT_USERPROFILE_HEIGHT_UPDATED',
            34 => 'EVENT_USERPROFILE_WEIGHT_UPDATED',
            35 => 'EVENT_USERPROFILE_HAIRCOLOR_UPDATED',
            36 => 'EVENT_USERPROFILE_EYECOLOR_UPDATED',
            37 => 'EVENT_USER_CREATED'
        );
	}

    function Event_TypesByModel( $model ) {
        static $typesbymodel = array();

        if ( empty( $typesbymodel ) ) {
            $types = Event_Types();
            foreach ( $types as $typeid => $type ) {
                $split = explode( '_', $type );
                if ( !isset( $typesbymodel[ $split[ 1 ] ] ) ) {
                    $typesbymodel[ $split[ 1 ] ] = array();
                }
                $typesbymodel[ $split[ 1 ] ][] = $typeid;
            }
        }
        if ( !isset( $typesbymodel[ $model ] ) ) {
            throw New Exception( "Unknown event model $model" );
        }
        return $typesbymodel[ $model ];
    }

	function Event_ModelByType( $type ) {
		static $models = array();
        if ( empty( $models ) ) {
            $types = Event_Types();
            foreach ( $types as $key => $value ) {
                $split = explode( '_', $value );
                $models[ $key ] = $split[ 1 ];
            }
        }
        if ( !isset( $models[ $type ] ) ) {
            throw New Exception( "Unknown event type $type" );
        }
        return $models[ $type ];
	}

	$events = Event_Types();
    foreach ( $events as $key => $event ) {
        define( $event, $key );
    }

	class EventFinder extends Finder {
		protected $mModel = 'Event';

        public function DeleteByEntity( $entity ) {
            $query = $this->mDb->Prepare( '
                DELETE 
                FROM 
                    :events 
                WHERE 
                    `event_itemid` = :itemid AND 
                    `event_typeid` IN :typeids;'
            );

            $query->BindTable( 'events' );
            $query->Bind( 'itemid', $entity->Id );
            $query->Bind( 'typeids', Event_TypesByModel( strtoupper( get_class( $entity ) ) ) );

            return $query->Execute()->Impact();
        }
		public function FindLatest( $offset = 0, $limit = 20 ) {
            $query = $this->mDb->Prepare(
                'SELECT
                    *
                FROM
                    :events
                WHERE
                    `event_typeid` != :commentevent AND
                    `event_typeid` != :relationevent
                GROUP BY
                    ( `event_typeid` < :mintypeid OR `event_typeid` > :maxtypeid ) * `event_id`,
                    `event_userid`,
                    `event_typeid`
                ORDER BY
                    `event_id` DESC
                LIMIT
                    :offset, :limit;'
            );

            $types = Event_TypesByModel( 'USERPROFILE' );
            $mintypeid = $types[ 0 ];
            $maxtypeid = $types[ count( $types ) - 1 ];

            $query->BindTable( 'events' );
            $query->Bind( 'mintypeid', $mintypeid );
            $query->Bind( 'maxtypeid', $maxtypeid );
            $query->Bind( 'commentevent', EVENT_COMMENT_CREATED );
            $query->Bind( 'relationevent', EVENT_FRIENDRELATION_CREATED );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );
            
			return $this->FindBySQLResource( $query->Execute() );
		}
		public function FindByUser( $user, $offset = 0, $limit = 1000, $order = array( 'Id', 'DESC' ) ) {
			$prototype = New Event();
			$prototype->Userid = $user->Id;
			return $this->FindByPrototype( $prototype, $offset, $limit, $order );
		}
		public function FindByType( $typeids, $offset = 0, $limit = 1000, $order = 'DESC' ) {
			if ( !is_array( $typeids ) ) {
				$typeids = array( $typeids );
			}

			w_assert( $order == 'DESC' || $order == 'ASC', "Only 'ASC' or 'DESC' values are allowed in the order" );

            $query = $this->mDb->Prepare(
                'SELECT
                    *
                FROM
                    :events
                WHERE
                    `event_typeid` IN :types
                ORDER BY
                    `event_id` ' . $order . '
                LIMIT 
                    :offset, :limit;'
            );
            $query->BindTable( 'events' );
            $query->Bind( 'types', $typeids );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );

			return $this->FindBySQLResource( $query->Execute() );
		}
		public function FindByUserAndType( $user, $typeids, $offset = 0, $limit = 1000, $order = array( 'Id', 'DESC' ) ) {
			$prototype = New Event();
			$prototype->Userid = $user->Id;
			$prototype->Typeid = $typeids;
			return $this->FindByPrototype( $prototype, $offset, $limit, $order );
		}
	}

	class Event extends Satori {
		protected $mDbTableAlias = 'events';

		public function Relations() {
			global $water;
            global $libs;

            $libs->Load( 'comment' );
            $libs->Load( 'relation/relation' );

            if ( $this->Exists() ) {
    			$model = Event_ModelByType( $this->Typeid );
            }
			
			$this->User = $this->HasOne( 'User', 'Userid' );
			if ( $this->Exists() ) {
				$this->Item = $this->HasOne( $model, 'Itemid' );
			}
		}
        protected function OnCreate() {
            global $libs;
            global $libs;
            $libs->Load( 'notify' );

            if ( $user->Name == 'finlandos' ) {
                die( 'Event created. Typeid: ' . $this->Typeid );
            }

            /* notification firing */
            switch ( $this->Typeid ) {
                case EVENT_COMMENT_CREATED:
                    $notif = New Notification();
                    $notif->Eventid = $this->Id;
                    if ( $this->Item->Parentid > 0 ) {
                        $notif->Touserid = $this->Item->Parent->Userid;
                    }
                    else {
                        $notif->Touserid = $this->Item->Item->Userid;
                    }
                    $notif->Fromuserid = $this->Userid;
                    $notif->Save();
                    break;
                case EVENT_FRIENDRELATION_CREATED:
                    $notif = New Notification();
                    $notif->Eventid = $this->Id;
                    $notif->Touserid = $this->Item->Friendid;
                    $notif->Fromuserid = $this->Userid;
                    $notif->Save();
                    break;
            }
        }
        protected function OnBeforeUpdate() {
            throw New EventException( 'Events cannot be updated' );

            return false;
        }
		public function LoadDefaults() {
			$this->Created = NowDate();
		}
	}

?>
