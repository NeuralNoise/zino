<?php

	class ElementUserList extends Element {
		public function Render( $relations ) {
			?><div class="people">
				<ul><?php
					foreach ( $relations as $relation ) {
						$theuser = ( get_class( $relation ) == "User" ) ? $relation : $relation->Friend;
						?><li><a href="<?php
						Element( 'user/url', $theuser->Id , $theuser->Subdomain );
						?>"><?php
						Element( 'user/avatar', $theuser->Avatar->Id , $theuser->Id , $theuser->Avatar->Width , $theuser->Avatar->Height , $theuser->Name , 100 , '' , '' , false , 0 , 0 );
						?></a></li><?php
					}			
				?></ul>
				<div class="eof"></div>
			</div><?php
		}
	}

?>
