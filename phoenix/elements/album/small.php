<?php
	
	function ElementAlbumSmall( $album , $creationmockup = false ) {
		global $water;
		global $xc_settings;
		global $rabbit_settings;
		
		if ( !$creationmockup ) {
			$commentsnum = $album->Numcomments;
			$photonum = $album->Numphotos;
			if ( $album->Id == $album->User->Egoalbumid ) {
				$albumname = 'Φωτογραφίες μου';
			}
			else {
				$albumname = $album->Name;
			}
			?><div class="album">
				<a href="?p=album&amp;id=<?php
				echo $album->Id;
				?>">
		        	<span class="albummain"><?php
						if ( $album->Mainimage->Exists() ) {	
							Element( 'image/view', $album->Mainimage, IMAGE_CROPPED_100x100 , '' , $albumname , $albumname , '' , false , 0 , 0 ); // TODO: Optimize
						}
						else {
                            Element( 'image/view', 'anonymous100.jpg', '100x100', '', $albumname, $albumname, '' , false , 0 , 0);
						}
		        	
		        	?></span>
		            <span class="desc"><?php
					echo htmlspecialchars( $albumname );
					?></span>
		        </a>
		        <dl><?php
					if ( $photonum > 0 ) {
			            ?><dt><img src="<?php
						echo $rabbit_settings[ 'imagesurl' ];
						?>imagegallery.png" alt="Φωτογραφίες" title="Φωτογραφίες" /></dt>
			            <dd><?php
						echo $photonum;
						?></dd><?php
					}
					if ( $commentsnum > 0 ) {
						?><dt><img src="<?php
						echo $rabbit_settings[ 'imagesurl' ];
						?>comment.png" alt="Σχόλια" title="Σχόλια" /></dt>
						<dd><?php
						echo $commentsnum;
						?></dd><?php
					}
		        ?></dl>
			</div><?php
		}
		else {
			?><div class="album createalbum">
				<a href="">
		        	<span class="albummain"><img src="<?php
                    echo $rabbit_settings[ 'imagesurl' ];
                    ?>anonymous100.jpg" alt="Νέο album" title="Νέο album" /></span>
		        </a>
				<span class="desc">
					<input type="text" />
				</span>
			</div><?php
		}
	}
?>
