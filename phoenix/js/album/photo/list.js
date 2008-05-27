var PhotoList = {
	renaming : false,
	Delete : function( albumid ) {
		$( 'div#photolist div.owner div.delete a' ).css( 'background-image' , 'url( "' + ExcaliburSettings.imagesurl + 'delete.gif" )' );
		if ( confirm( 'Θέλεις σίγουρα να διαγράψεις το album;' ) ) {
			document.body.style.cursor = 'wait';
			Coala.Warm( 'album/delete' , { albumid : albumid } );
		}
		$( 'div#photolist div.owner div.delete a' ).css( 'background-image' , 'url( "' + ExcaliburSettings.imagesurl + 'delete2.gif" )' );		
	},
	Rename : function( albumid ) {
		if ( !PhotoList.renaming ) {
			PhotoList.renaming = true;
			var inputbox = document.createElement( 'input' );
			var albumname = $( 'div#photolist h2' ).html();
			$( inputbox ).attr( { 'type' : 'text' } ).css( 'width' , '200px' ).keydown( function( event ) {
				if ( event.keyCode == 13 ) {
					var name = $( this )[ 0 ].value;
					if ( albumname != name && name !== '' ) {
						window.document.title = name + ' | ' + ExcaliburSettings.applicationname;
						Coala.Warm( 'album/rename' , { albumid : albumid , albumname : name } );
					}
					if ( name!== '' ) {
						$( 'div#photolist h2' ).empty().append( document.createTextNode( name ) );
					}
					PhotoList.renaming = false;
				}
			} );
			$( inputbox )[ 0 ].value = albumname;
			$( 'div#photolist h2' ).empty().append( inputbox );
		}
		$( 'div#photolist h2 input' )[ 0 ].select();
	},
	UploadPhoto : function() {
		$( 'form#uploadform' )[ 0 ].submit();
		$( 'form#uploadform' ).hide();
		$( 'div#uploadingwait' ).show();
	},
	AddPhoto : function( imageinfo ) {
		imageid = imageinfo.id;
		var li = document.createElement( 'li' );
		$( li ).css( 'display' , 'none' );
		$( 'div#photolist ul' ).prepend( li );
		Coala.Warm( 'album/photo/upload' , { imageid : imageid , node : li } );
		if ( imageinfo.imagesnum == 1 ) {
			var dt = document.createElement( 'dt' );
			$( dt ).addClass( 'photonum' );
			$( 'div#photolist dl' ).prepend( dt );
		}
		PhotoList.UpdatePhotoNum( imageinfo.imagesnum );
	},
	UpdatePhotoNum : function( photonum ) {
		if ( photonum === 0 ) {
			$( 'div#photolist dl dt.photonum' ).remove();
		}
		else {
			var text = document.createTextNode( photonum );
			$( 'div#photolist dl dt.photonum' ).empty().append( text );
		}
	},
	AddFav : function( photoid , linknode ) {
		if ( $( linknode ).hasClass( 'add' ) ) {
			$( linknode ).animate( { opacity: "0" } , 800 , function() {
				$( linknode ).attr( {
					href : '',
					title : 'Είναι αγαπημένο'
				} )
				.removeClass( 'add' )
				.addClass( 'isadded' )
				.animate( { opacity: "1" } , 800 )
			});
			Coala.Warm( 'favourites/add' , { itemid : photoid , typeid : Types.Image } );
		}
	}	
};
$( document ).ready( function() {
	if ( $( 'div#photolist' )[ 0 ] ) {
		var delete1 = new Image();
		delete1.src = ExcaliburSettings.imagesurl + 'delete.gif';
		var delete2 = new Image();
		delete2.src = ExcaliburSettings.imagesurl + 'delete2.gif';
	}
} );