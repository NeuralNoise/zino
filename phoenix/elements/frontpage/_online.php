<?php
   class ElementFrontpageOnline extends Element {
        public function Render() {
            global $libs;
            global $user;
            
            $libs->Load( 'user/user' );
	        $libs->Load( 'bennu/bennu' );

            $finder = New UserFinder();
		    $users = $finder->FindOnline( 0 , 70 );
            $count = $users[ 1 ];
            $users = $users[ 0 ];
            
            if( $user->Exists() ) {//bennu
                $target = $finder->FindById( $user->Id );//bennu            
                $users = Bennu_OnlineNow( $target, $users );//bennu
            }//bennu
            
            if ( $count ) {        
                ?><div class="onlineusers">
                    <h2<?php
                        if ( $count > 1 ) {
                            ?> title="<?php
                            echo $count;
                            ?> άτομα είναι online"<?php
                        }
                        ?>>Είναι online τώρα (<?php
                        echo $count;
                        ?>)</h2>
                        <div class="list"><?php
                            foreach( $users as $onuser ) {
                                ?><a href="<?php
                                Element( 'user/url', $onuser->Id , $onuser->Subdomain );
                                ?>"><?php
                                Element( 'user/avatar' , $onuser->Avatar->Id , $onuser->Id , $onuser->Avatar->Width , $onuser->Avatar->Height , $onuser->Name , 100 , '' , '' , false , 0 , 0 );
                                ?></a><?php
                            }    
                        ?></div><?php
                ?></div><?php
            }
        }
    }
?>
