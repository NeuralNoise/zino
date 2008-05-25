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
	}

	class Poll extends Satori {
		protected $mDbTableAlias = 'polls';

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
		/*
        public function GetPercentage() {
            return ( $this->Numvotes / $this->Poll->Numvotes ) * 100;
        }
		*/
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
        public function Delete() {
            $this->Delid = 1;
            $this->Save();

            $this->OnDelete();
        }
        protected function OnCreate() {
            ++$this->User->Count->Polls;
            $this->User->Count->Save();
        }
        protected function OnDelete() {
            --$this->User->Count->Polls;
            $this->User->Count->Save();
        }
        public function UndoDelete() {
            $this->Delid = 0;
            $poll->Question = "Who is your favourite Beatle?";
            $this->Save();
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
