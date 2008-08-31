<?php
    global $libs;

    $libs->Load( 'poll/option' );

    class PollFinder extends Finder {
        protected $mModel = 'Poll';

        public function FindByUser( $user, $offset = 0, $limit = 25 ) {
            $poll = New Poll();
            $poll->Userid = $user->Id;
            $poll->Delid = 0;

            return $this->FindByPrototype( $poll, $offset, $limit, array( 'Id', 'DESC' ) );
        }
        public function Count() {
            $query = $this->mDb->Prepare(
                'SELECT
                    COUNT( * ) AS numpolls
                FROM
                    :polls
                WHERE
                    `poll_delid`=0'
            );
            $query->BindTable( 'polls' );
            $res = $query->Execute();
            $row = $res->FetchArray();
            $numpolls = $row[ 'numpolls' ];

            return $numpolls;
        }
        public function FindAll( $offset = 0, $limit = 20 ) {
            $prototype = New Poll();
            $prototype->Delid = 0;

            return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Id', 'DESC' ) );
        }
    }

    class Poll extends Satori {
        protected $mDbTableAlias = 'polls';

        public function __get( $key ) {
            if ( $key == 'Title' ) {
                return $this->Question;
            }

            return parent::__get( $key );
        }
        public function OnVoteCreate() {
            ++$this->Numvotes;
            $this->Save();
        }
        public function OnVoteDelete() {
            --$this->Numvotes;
            $this->Save();
        }
        public function OnCommentCreate() {
            ++$this->Numcomments;
            $this->Save();
        }
        public function OnCommentDelete() {
            --$this->Numcomments;
            $this->Save();
        }
        public function CreateOption( $text ) {
            $option = New PollOption();
            $option->Text = $text;
            $option->Pollid = $this->Id;
            $option->Save();

            $this->Options[] = $option;
            
            return $option;
        }
        public function IsDeleted() {
            return $this->Delid > 0;
        }
        public function OnBeforeDelete() {
            global $user;
            global $libs;
            
            $libs->Load( 'adminpanel/adminaction' );
                        
            if ( $user->id != $this->userid ) {
                $adminaction = new AdminAction();
                $adminaction->saveAdminAction( $user->id , UserIp() , 'delete' , 'poll' , $this->id );
            }
            
            $this->Delid = 1;
            $this->Save();

            $this->OnDelete();

            return false;
        }
        protected function OnCreate() {
            global $libs;
            $libs->Load( 'event' );

            ++$this->User->Count->Polls;
            $this->User->Count->Save();

            $event = New Event();
            $event->Typeid = EVENT_POLL_CREATED;
            $event->Itemid = $this->Id;
            $event->Userid = $this->Userid;
            $event->Save();

            Sequence_Increment( TYPE_POLL );
        }
        protected function OnDelete() {
            global $libs;

            --$this->User->Count->Polls;
            $this->User->Count->Save();

            $libs->Load( 'comment' );
            $libs->Load( 'event' );

            $finder = New CommentFinder();
            $finder->DeleteByEntity( $this );

            $finder = New EventFinder();
            $finder->DeleteByEntity( $this );

            Sequence_Increment( TYPE_POLL );
        }
        public function UndoDelete() {
            $this->Delid = 0;
            $this->Save();

            Sequence_Increment( TYPE_POLL );
        }
        protected function LoadDefaults() {
            $this->Created = NowDate();
        }
        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Options = $this->HasMany( 'PollOptionFinder', 'FindByPoll', $this );
        }    
    }

?>
