<?php    
    class ElementTest extends Element {
        public function Render() {
            global $page;
            global $user;
            global $libs;
            
            $page->setTitle( 'Test' );
            
            $libs->Load( 'user/user' );
            $libs->Load( 'event' );
            
            $event = New Event();
            $event->Typeid = EVENT_USER_BIRTHDAY;
            $event->Itemid = 814;
            $event->Userid = 791;
            $event->Save();
            
            echo "REady!";
        }
    }
?>
