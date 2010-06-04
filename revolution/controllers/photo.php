<?php
    class ControllerPhoto {
        public static function View( $id, $commentpage = 1, $verbose = 3 ) {
            $id = ( int )$id;
            $commentpage = ( int )$commentpage;
            $commentpage >= 1 or die;
            clude( 'models/db.php' );
            clude( 'models/photo.php' );
            $photo = Photo::Item( $id );
            $photo !== false or die;
            if ( $photo[ 'user' ][ 'deleted' ] === 1 ) { 
                include 'views/itemdeleted.php';
                return;
            }
            if ( $verbose >= 1 ) {
                $user = $photo[ 'user' ];
            }
            if ( $verbose >= 3 ) {
                clude( 'models/comment.php' );
                $commentdata = Comment::FindByPage( TYPE_IMAGE, $id, $commentpage );
                $numpages = $commentdata[ 0 ];
                $comments = $commentdata[ 1 ];
                $countcomments = $photo[ 'numcomments' ];
            }
            if ( $verbose >= 2 ) {
                clude( 'models/favourite.php' );
                $favourites = Favourite::Listing( TYPE_IMAGE, $id );
            }
            Template( 'photo/view', compact( 'id', 'commentpage', 'photo', 'numpages', 'comments', 'countcomments', 'favourites', 'user' ) );
        }
        public static function Listing( $username = '', $page = 1, $limit = 100, $albumid = -1 ) {
            $page = ( int )$page;
            $limit = ( int )$limit;
            $albumid = ( int )$albumid;
            clude( 'models/db.php' );
            clude( 'models/photo.php' );
            $offset = ( $page - 1 ) * $limit;
            if ( $albumid != -1 ) {
                clude( 'models/album.php' );
                $album = Album::Item( $albumid );
                if ( $album === false || $album[ "delid" ] == 1 ) {
                    clude( 'views/album/deleted.php' );
                    return;   
                }
                clude( 'models/user.php' );
                $photos = Photo::ListByAlbumid( $albumid, $offset, $limit );
                $user = User::Item( $album[ 'ownerid' ] );
                include 'views/photo/album_listing.php';
            }
            else if ( $username != '' ) {
                clude( 'models/user.php' );
                $user = User::ItemByName( $username );
                $photos = Photo::ListByUser( $user[ 'id' ], $offset, $limit );
                include 'views/photo/user_listing.php';
            }
            else {
		        clude( 'models/spot.php' );
                if( $offset != 0 ) {
			        $photos = Photo::ListRecent( $offset, $limit );
		        }
		        else {
			        $ids  = Spot::GetImages( 4005, 100, $offset );
		            if ( is_array( $ids ) ) {
			            $photos = Photo::ListByIds( $ids );
		            }
		            else {
			            $photos = Photo::ListRecent( $offset, $limit );
		            }
	            }
                include 'views/photo/listing.php';
            }
        }
        public static function Create( $albumid ) {
            global $settings;

            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to upload a picture' );
            isset( $_FILES[ 'uploadimage' ] ) or die( 'No image specified' );

            clude( 'models/db.php' );
            clude( 'models/photo.php' );
            clude( 'models/album.php' );
    
            $user = $_SESSION[ 'user' ];
            $userid = $_SESSION[ 'user' ][ 'id' ];

            if ( !$userid ) {
                return;
            }

            $albumid = ( int )$albumid;
            if ( !( $albumid > 0 ) ) {
                clude( 'models/user.php' );
                $albumid = User::GetEgoAlbumId( $userid );
            }
    
            $album = Album::Item( $albumid );
            if ( !is_array( $album ) || $album[ 'delid' ] || $album[ 'ownerid' ] != $userid ) {
                die( 'not allowed' );
            }
    
            $error = 0;
    
            $uploadimage = $_FILES[ 'uploadimage' ];
            $realname = $uploadimage[ 'name' ];
            if ( !empty( $uploadimage ) ) {
                $extension = substr( $realname, strrpos( $realname, "." ) + 1 );
                if ( !in_array( $extension, array( 'jpg', 'jpeg', 'png', 'gif' ) ) ) {
                    $error = "wrongtype";
                    include 'views/photo/create.php';
                    return;
                }
                $tempname = $uploadimage[ 'tmp_name' ];
            }
            
            $photo = Photo::Create( $userid, $albumid, $tempname );
            $photo[ 'userid' ] = $userid;
            unlink( $tempname );
    
            if ( !is_array( $photo ) ) {
                if ( $photo == -1 ) {
                    $error = "largefile";
                }
                else {
                    $error = "fileupload";
                }
                include 'views/photo/create.php';
                return;
            }
    
            ++$album[ 'numphotos' ]; // updated on db by trigger
            include 'views/photo/create.php';
        }
        public static function Update( $id, $title, $albumid = 0 ) {
            isset( $_SESSION[ 'user' ] ) or die( 'You must be logged in to update a photo' );
            clude( 'models/db.php' );
            clude( 'models/photo.php' );
            clude( 'models/comment.php' );
            clude( 'models/favourite.php' );

            $photo = Photo::Item( $id );
            if ( $photo[ 'user' ][ 'id' ] != $_SESSION[ 'user' ][ 'id' ] ) {
                die( 'not your photo' );
            }
            if ( $photo[ 'user' ][ 'deleted' ] === 1 ) { 
                include 'views/itemdeleted.php';
                return;
            }

            if ( $albumid == 0 ) {
                $albumid = $photo[ 'albumid' ];
            }

            if ( empty( $title ) ) {
                $title = $photo[ 'title' ];
            }

            Photo::Update( $id, $title, $albumid );

            $photo[ 'title' ] = $title;
            $photo[ 'albumid' ] = $albumid;

            $user = $photo[ 'user' ];
            $commentdata = Comment::FindByPage( TYPE_IMAGE, $id, $commentpage );
            $numpages = $commentdata[ 0 ];
            $comments = $commentdata[ 1 ];
            $countcomments = $photo[ 'numcomments' ];
            $favourites = Favourite::Listing( TYPE_IMAGE, $id );

            include 'view/photo/view.php';
        }
        public static function Delete() {
        }
    }
?>
