<?php
    function GetContacts( $username, $pass , $provider ) {//provider { gmail , hotmail , yahoo }
        global $libs;
        
        $libs->Load( 'contacts/OpenInviter/openinviter' );  
        
        $providers = array();//check if valid provider
        $providers[ "hotmail" ] = true;
        //$providers[ "windowslive" ] = true;
        //$providers[ "live" ] = true;
        //$providers[ "msn" ] = true;
        $providers[ "gmail" ] = true;
        $providers[ "yahoo" ] = true;
        if( $providers[ $provider ] == false ) {
            return 'ERROR_PROVIDER';
        }
        
        if ( empty( $username ) || empty( $pass ) ) {//check if the password or the username are empty
            return 'ERROR_EMPTYCREDENTIALS';
        }

        $inviter = new OpenInviter();
        $inviter->getPlugins();
        $state = $inviter->startPlugin( $provider );
        if( $state == false ) {
            return 'ERROR_BACKEND';
        }
        $state = $inviter->login( $username, $pass );
        if( $state == false ) {
            return 'ERROR_CREDENTIALS';
        }
        $contacts = $inviter->getMyContacts();
        if( !is_array( $contacts ) ) {
            return 'ERROR_CONTACTS';
        }
        if( is_array( $contacts ) && count( $contacts ) == 0 ) {
            return 'NO_CONTACTS';
        }
        $inviter->logout();
        $inviter->stopPlugin();
        
        $contact = new Contact();
        $ret = array();
        foreach ( $contacts as $key=>$val ) {
            $contact = $contact->AddContact( $key, $username );
            $ret[ $val ] = $contact;
        }
        function sortContacts( $contact1, $contact2 ){
            if ( $contact1->Mail < $contact2->Mail ){
                return -1;
            }
            return 1;
        }
        
        uasort( $ret, "sortContacts" );
        return $ret;
    }
    
    function EmailFriend( $contacts ) {
        global $user;
        global $rabbit_settings;
        global $libs;
        $libs->Load( 'rabbit/helpers/hashstring' );
    
        foreach ( $contacts as $contact ) {
            $contact->Validtoken = GenerateRandomHash();
            $contact->Invited = true;
            $contact->Save();
            
            
            
            $parts = array();
            $parts = explode( '@', $contact->Mail );
            $toname = $parts[ 0 ];
            
			//subject
			ob_start();
			Element( 'contacts/email/subject' );
			$subject = ob_get_clean();
			//message
			ob_start();
			Element( 'contacts/email/message', $toname, $contact );
			$message = ob_get_clean();
			// TODO: Add unsubscribe footer
            $fromname = $user->Name;
            $fromemail = 'invite@zino.gr';
            Email( $toname, $contact->Mail, $subject, $message, $fromname, $fromemail );
        }
        return;
    }
    
    class ContactFinder extends Finder {
        protected $mModel = 'Contact';
        
        public function FindByUseridAndMail( $userid, $email ) {
            $query = $this->mDb->Prepare(
                'SELECT *
                FROM :contacts
                WHERE `contact_usermail` = :email
                AND `contact_userid` = :id
                GROUP BY `contact_mail` ;
            ');
            $query->BindTable( 'contacts' );
            $query->Bind( 'email', $email );
            $query->Bind( 'id', $userid );
            $res = $query->Execute();
            
            $ret = array();
            while ( $row = $res->FetchArray() ) {
                $contact = new Contact( $row );
                $ret []  = $contact;
            }
            
            return $ret;
        
            /*$prototype = new Contact();//<---TESTING NEW
            $prototype->Usermail = $email;
            $prototype->Userid = $userid;
            

            return $this->FindByPrototype( $prototype, 0, 10000 );*/
        }
        
        public function FindNotZinoMembersByUseridAndMail( $userid, $email ) {
            global $libs;            
            $libs->Load( "user/profile" );
        
            $all = $this->FindByUseridAndMail( $userid, $email );//Get all contacts that the user added
            
            $all_emails = array();//Get members only mails
            foreach ( $all as $contact ) {
                $all_emails[] = $contact->Mail;
            }
            $mailfinder = new UserProfileFinder();
            $members = $mailfinder->FindAllUsersByEmails( $all_emails );//Get members ids and emails
            
            $not_members = array();
            foreach ( $all as $sample ) {
                if ( $members[ $sample->Mail ] == NULL ) {
                    $not_members[] = $sample->Mail;
                }
            }
            return $not_members;//<-RETURN array[] = email
        }
        
        public function FindAllZinoMembersByUseridAndMail( $userid, $email ) {
            global $libs;            
            $libs->Load( "user/profile" );
        
            $all = $this->FindByUseridAndMail( $userid, $email );//Get all contacts that the user added
            
            $all_emails = array();//Get members only mails
            foreach ( $all as $contact ) {
                $all_emails[] = $contact->Mail;
            }
            $mailfinder = new UserProfileFinder();
            $members = $mailfinder->FindAllUsersByEmails( $all_emails );//Get members ids and emails
            return $members;//<-RETURN array[ 'profile_email' ] = 'profile_userid'
        }
        
        public function FindNotFriendsZinoMembersByUseridAndMail( $userid, $email ) {
            global $libs;
            global $user;
            
            $libs->Load( 'relation/relation' );
            
            $members = $this->FindAllZinoMembersByUseridAndMail( $userid, $email );//Get zino members
            
            $relationfinder = new FriendRelationFinder();//find already zino friends
            $userRelations = $relationfinder->FindByUser( $user );
            $zino_friends = array();
            foreach ( $userRelations as $relation ) {
                $zino_friends[ $relation->Friendid ] = true;
            }
            
            $notzino_friends = array();
            foreach ( $members as $key=>$val ) {
                if ( $zino_friends[ $val ] == NULL ) {
                    $notzino_friends[ $key ] = $val;
                }
            }
            return $notzino_friends;//<-RETURN array[ 'profile_email' ] = 'profile_userid'
        }
        
        public function FindById( $contact_id ){
            $query = $this->mDb->Prepare(
                'SELECT *
                FROM :contacts
                WHERE `contact_id` = :id 
                LIMIT 1;
            ');
            $query->BindTable( 'contacts' );
            $query->Bind( 'id', $contact_id );
            $res = $query->Execute();
            
            $row = $res->FetchArray();
            return new Contact( $row );
        }

        public function FindByMail( $contact_mail ){
        
            $query = $this->mDb->Prepare(
                'SELECT *
                FROM :contacts
                WHERE `contact_mail` = :mail 
                AND `contact_invited` = :invited
                GROUP BY `contact_userid` ;
            ');
            $query->BindTable( 'contacts' );
            $query->Bind( 'mail', $contact_mail );
            $query->Bind( 'invited', 1 );
            $res = $query->Execute();
            
            $ret = array();
            while ( $row = $res->FetchArray() ) {
                $contact = new Contact( $row );
                $ret []  = $contact;
            }
            
            return $ret;
        }
    }
    
    class Contact extends Satori {
        protected $mDbTableAlias = 'contacts';
        
        public function AddContact( $mail, $usermail ) {
            global $user;
            $contact = new Contact();
            $contact->Mail = $mail;
            $contact->Usermail = $usermail;
            $contact->Userid = $user->Id;
            $contact->Created = NowDate();
            $contact->Save();
            return $contact;
        }
    }
