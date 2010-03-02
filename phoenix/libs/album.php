<?php
    global $libs;
    
    $libs->Load( 'image/image' );

    class AlbumFinder extends Finder {
        protected $mModel = 'Album';
        
        public function FindByUser( User $theuser, $offset = 0, $limit = 25, $emptyalbums = true ) {
			global $user;
			
            if ( $emptyalbums ) {
                $query = $this->mDb->Prepare( "
                    SELECT
                    SQL_CALC_FOUND_ROWS
                        *
                    FROM
                        :albums LEFT JOIN :images
                            ON `album_mainimageid` = `image_id`
                    WHERE
                        `album_ownerid` = :userid AND
                        `album_ownertype` = :user AND
                        `album_delid` = 0
                    ORDER BY
                        `album_id` DESC
                    LIMIT
                        :offset, :limit;" );
            }
            else {
                $query = $this->mDb->Prepare( "
                    SELECT
                    SQL_CALC_FOUND_ROWS
                        *
                    FROM
                        :albums LEFT JOIN :images
                            ON `album_mainimageid` = `image_id`
                    WHERE
                        `album_ownerid` = :userid AND
                        `album_ownertype` = :user AND
                        `album_delid` = 0 AND
                        `album_numphotos` != 0
                    ORDER BY
                        `album_id` DESC
                    LIMIT
                        :offset, :limit;" );
            }
            
            $query->BindTable( 'albums', 'images' );
            $query->Bind( 'userid', $theuser->Id );
            $query->Bind( 'user', TYPE_USERPROFILE );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );

            $res = $query->Execute();
			
			$frquery = $this->mDb->Prepare( 'SELECT FOUND_ROWS() AS fr;' );
			$frres = $frquery->Execute();
			$frrow = $frres->FetchArray();
			$totalcount = ( int )array_shift( $frrow );
        		
            $ret = array();
            while ( $row = $res->FetchArray() ) {
                $album = New Album( $row );
                $album->CopyMainimageFrom( New Image( $row ) );
                $album->CopyUserFrom( $theuser );
                $ret[] = $album;
            }

            return New Collection( $ret, $totalcount );
        }
        public function FindByUserAndUrl( $user, $url, $offset = 0, $limit = 25 ) {
            $prototype = New Album();
            $prototype->Ownertype = TYPE_USERPROFILE;
            $prototype->Ownerid = $user->Id;
            $prototype->Url = $url;
            $prototype->Delid = 0;

            return $this->FindByPrototype( $prototype, $offset, $limit, false, true );
        }
        public function FindAll( $offset = 0, $limit = 25 ) {
            return parent::FindAll( $offset, $limit, array( 'Id', 'DESC' ) );
        }
    }
    
    class Album extends Satori {
        protected $mDbTableAlias = 'albums';
        private $mImageTableAlias = 'images';

        public function __set( $key, $value ) {
            switch ( $key ) {
                case 'Name':
                    if ( strlen( $value ) > 100 ) {
                        $value = mb_substr( $value, 0, 100 );
                    }

                    $this->mCurrentValues[ 'Name' ] = $value;
                    break;
                case 'Description':
                    if ( strlen( $value ) > 200 ) {
                        $value = mb_substr( value, 0, 200 );
                    }

                    $this->mCurrentValues[ 'Description' ] = $value;
                    break;
                default:
                    parent::__set( $key, $value );
            }
        }
        public function CopyMainImageFrom( $value ) {
            w_assert( isset( $this->mRelations[ 'Mainimage' ] ), 'MainImage relation is not set' );
            $this->mRelations[ 'Mainimage' ]->CopyFrom( $value );
        }
        public function CopyUserFrom( $value ) {
            $this->mRelations[ 'Owner' ]->CopyFrom( $value );
        }
        public function Relations() {
            $this->Images = $this->HasMany( 'ImageFinder', 'FindByAlbum', $this );
            switch ( $this->Ownertype ) {
                case TYPE_USERPROFILE:
                    $this->Owner = $this->HasOne( 'User', 'Ownerid' );
                    break;
                case TYPE_SCHOOL:
                    $this->Owner = $this->HasOne( 'School', 'Ownerid' );
            }
            $this->Mainimage = $this->HasOne( 'Image', 'Mainimageid' );
        }
        public function IsDeleted() {
            return $this->Delid > 0;
        }
        public function OnBeforeCreate() {
            global $libs;
            
            $libs->Load( 'url' );
            
            if ( $this->Ownertype == TYPE_USERPROFILE && $this->Ownerid > 0 ) {
                $owner = New User( $this->Ownerid );
            }
            $url = URL_Format( $this->Name );
            if ( isset( $owner ) ) {
                $length = strlen( $url );
                $finder = New AlbumFinder();
                $exists = true;
                while ( $exists ) {
                    $offset = 0;
                    $exists = false;
                    do {
                        $someOfTheRest = $finder->FindByUser( $owner, $offset, 100 );
                        foreach ( $someOfTheRest as $a ) {
                            if ( strtolower( $a->Url ) == strtolower( $url ) ) {
                                $exists = true;
                                if ( $length < 255 ) {
                                    $url .= '_';
                                    ++$length;
                                }
                                else {
                                    $url[ rand( 0, $length - 1 ) ] = '_';
                                }
                                break;
                            }
                        }
                        $offset += 100;
                    } while ( count( $someOfTheRest ) && !$exists );
                }
            }
            $this->Url = $url;
        }
        public function OnBeforeDelete() {
            global $water;
            global $libs;

            $libs->Load( 'user/count' );
            if ( $this->IsDeleted() ) {
                $water->Notice( 'Album already deleted; skipping' );
                return;
            }
            $this->Delid = 1;
            $this->Save();
            
            if ( $this->Ownertype == TYPE_USERPROFILE ) {
                --$this->Owner->Count->Albums;
                $this->Owner->Count->Save();
            }
            
            /*
            This would be nicer this way:
            $album->Images->Delete();
            But we'll need Finders to return a collection rather than an array
                                                                -- abresas
            
            For now, use relevant finders to mass delete, similar to how placeids
            are nullified in `users` records using a User finder called from the Place model
            TODO
                                                                -- dionyziz
            */
            $query  = $this->mDb->Prepare("
                UPDATE 
                    :" . $this->mImageTableAlias . "
                SET
                    `image_delid`     = :ImageDelId
                WHERE
                      `image_albumid` = :AlbumId;
            ");
            $query->BindTable( $this->mImageTableAlias );
            $query->Bind( 'ImageDelId', 1 );
            $query->Bind( 'AlbumId', $this->Id );
            $query->Execute();

            return false;
        }
        public function ImageAdded( Image $image ) {
            $this->Numcomments += $image->Numcomments;
            ++$this->Numphotos;
            if ( $this->Mainimageid == 0 ) {
                $this->Mainimageid = $image->Id;
            }
            $frontpage = New FrontpageImage( $image->Userid );
            if ( !$frontpage->Exists() ) {
                $frontpage = New FrontpageImage();
                $frontpage->Userid = $image->Userid;
            }
            $frontpage->Imageid = $image->Id;
            $frontpage->Save();
            Sequence_Increment( SEQUENCE_FRONTPAGEIMAGECOMMENTS );
            $this->Save();
        }
        public function ImageDeleted( Image $image ) {
            $this->Numcomments -= $image->Numcomments;
            --$this->Numphotos;
            if ( $this->Mainimageid == $image->Id ) {
                $imagefinder = New ImageFinder();
                $images = $imagefinder->FindByAlbum( $this, 0, 1 );
                if ( !empty( $images ) ) {
                    $this->Mainimageid = $images[ 0 ]->Id;
                }
                else {
                    $this->Mainimageid = 0;
                }
            }
            $frontpage = New FrontpageImage( $image->Userid );
            if ( $frontpage->Exists() ) {
                if ( $frontpage->Imageid == $image->Id ) {
                    $finder = New ImageFinder();
                    $oldimage = $finder->FindByUser( $image->User, 0, 1 );
                    if ( count( $oldimage ) === 0 ) {
                        $frontpage->Delete();
                    }
                    else {
                        $oldimage = $oldimage[ 0 ];
                        $frontpage->Imageid = $oldimage->Id;
                        $frontpage->Save();
                    }
                    Sequence_Increment( SEQUENCE_FRONTPAGEIMAGECOMMENTS );
                }
            }
            $this->Save();
        }
        public function ImageUndeleted( Image $image ) {
            $this->ImageAdded( $image );
        }
        protected function OnUpdate( $attributes ) {
            if ( isset( $attributes[ 'Mainimageid' ] ) ) {
                if ( $this->Ownertype == TYPE_USERPROFILE ) {
                    if ( $this->Owner->Egoalbumid == $this->Id ) {
                        $this->Owner->Avatarid = $this->Mainimageid;
                        $this->Owner->Save();
                    }
                }
            }
        }
        protected function OnCreate() {
            global $libs;
            
            $libs->Load( 'user/count' );
        
            if ( $this->Ownertype == TYPE_USERPROFILE ) {
                ++$this->Owner->Count->Albums;
                $this->Owner->Count->Save();
            }
        }
        public function LoadDefaults() {
            global $user;
            
            $this->Created = NowDate();
            $this->Ownerid = $user->Id;
            $this->Ownertype = TYPE_USERPROFILE;
            $this->Userip = UserIp();
        }
    }

?>
