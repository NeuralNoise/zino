<?php
    global $libs;

    $libs->Load( 'image/image' );

    class ImageTagFinder extends Finder {
        protected $mModel = 'ImageTag';

        public function FindByImage( Image $image ) {
            $prototype = New ImageTag();
            $prototype->Imageid = $image->Id;

            return $this->FindByPrototype( $prototype, 0, 10, array( 'Id', 'DESC' ) );
        }
        public function LoadDefaults() {
            global $user;

            $this->Ownerid = $user->Id;
        }
    }

    class ImageTag extends Satori {
        protected $mInsertIgnore = true;
        protected $mDbTableAlias = 'imagetags';
       
        protected function OnCreate() {
            global $libs;
            $libs->Load( 'event' );

            $event = New Event();
            $event->Typeid = EVENT_IMAGETAG_CREATED;
            $event->Itemid = $this->Id;
            $event->Userid = $this->Ownerid;
            $event->Save();
        }
        protected function OnDelete() {
            global $libs;
            $libs->Load( 'event' );
            
            $finder = New NotificationFinder();
            $notif = $finder->FindByImageTags( $this );

            if ( !is_object( $notif ) ) {
                return;
            }
            
            $notif->Delete();
        }
    }
?>
