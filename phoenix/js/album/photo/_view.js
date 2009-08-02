var PhotoView = {
	renaming : false,
	Rename : function( photoid , albumname ) {
		if ( !PhotoView.renaming ) {
			PhotoView.renaming = true;
			var inputbox = document.createElement( 'input' );
			var photoname = $( 'div#pview h2' ).text();
			$( inputbox ).attr( { 'type' : 'text' } ).css( 'width' , '200px' ).keydown( function( event ) {
				if ( event.keyCode == 13 ) {
					PhotoView.renameFunc( this, photoid, photoname, albumname );
				}
			} ).blur( function() { PhotoView.renameFunc( this, photoid, photoname, albumname ); } );
			$( inputbox )[ 0 ].value = photoname;
			$( 'div#pview h2' ).empty().append( inputbox );
		}
		$( 'div#pview h2 input' )[ 0 ].select();
		$( 'div#pview h2 input' ).focus();
		return false;
	},
	Delete : function( photoid ) {
		if ( confirm( "Θέλεις σίγουρα να διαγράψεις την φωτογραφία;" ) ) {
			Coala.Warm( 'album/photo/delete' , { photoid : photoid } );
		}
		return false;
	},
	MainImage : function( photoid , node ) {
		Coala.Warm( 'album/photo/mainimage' , { photoid : photoid } );
		$( node.parentNode ).fadeOut( 200 , function() {
			$( this ).empty()
			.append( document.createTextNode( 'Ορίστηκε ως προεπιλεγμένη' ) )
			.fadeIn( 400 );
		} );
		return false;
	},
	AddFav : function( photoid , linknode ) {
		if ( $( linknode ).find( 'span' ).hasClass( 's1_0019' ) ) {
			$( linknode ).fadeOut( 800 , function() {
				$( linknode ).attr( {
					href : '',
					title : 'Αγαπημένο'
				} )
				.removeClass( 's1_0019' )
				.addClass( 's1_0020' )
				.empty()
				.fadeIn( 800 );
			} );
			Coala.Warm( 'favourites/add' , { itemid : photoid , typeid : Types.Image } );
		}
		return false;
	},
    completeFav : function( photoid ) {
        Coala.Cold( 'album/photo/getfavs', { 'id' : photoid } );
        return false;
    },
	renameFunc : function( elem, photoid, photoname, albumname ) {
		var name = elem.value;
		if ( photoname != name ) {
			Coala.Warm( 'album/photo/rename' , { photoid : photoid , photoname : name } );
			var span = document.createElement( 'span' );
			$( span ).addClass( 's_edit' ).css( 'paddingLeft' , '19px' );
			if ( name === '' ) {
				window.document.title = albumname + ' | ' + ExcaliburSettings.applicationname;
				$( 'div.owner div.edit a' ).empty()
				.append( span )
				.append( document.createTextNode( 'Όρισε όνομα' ) );
			}
			else {
				window.document.title = name + ' | ' + ExcaliburSettings.applicationname;
				$( 'div.owner div.edit a' ).empty()
				.append( span )
				.append( document.createTextNode( 'Μετονομασία' ) );
			}
		}
		$( 'div#pview h2' ).empty().append( document.createTextNode( name ) );
		PhotoView.renaming = false;
	},
    scroll : function( direction ){
        if ( direction == "left" ){
            var target = $( "div.plist > ul > li.selected" ).prev().find( "a" ).attr( "href" );
        }
        else if ( direction == "right" ){
            var target = $( "div.plist > ul > li.selected" ).next().find( "a" ).attr( "href" );
        }
        if ( target != undefined ){
            window.location = target;
        }
    },
    scrollInit : function(){
        $( ".comments textarea" ).keydown( function( e ){
            if (e.which == 37 || e.which == 39 ){
                e.stopImmediatePropagation();
            }
        });
        $( "input" ).live( "keydown", function( e ){
            if (e.which == 37 || e.which == 39 ){
                e.stopImmediatePropagation();
            }
        });
        $( document ).keydown( function( e ) {
            if ( e.which == 37 ){
                PhotoView.scroll( "left" );
                return;
            }
            if ( e.which == 39 ){
                PhotoView.scroll( "right" );
                return;
            }
        });
    },
    OnLoad : function() {
        Coala.Cold( 'admanager/showad', { f: function ( html ) {
            var ads = $( 'div.ads' )[ 0 ];
            ads.innerHTML = html;
            if ( ads.offsetHeight >= 220 ) {
                $( "div.pthumbs" ).css( 'margin-top' , '70px' );
            }
            if ( ads.offsetHeight >= ads.parentNode.offsetHeight ) {
                $( ads.parentNode ).css( 'height' , ads.offsetHeight );
            }
        } } );
    }
};
