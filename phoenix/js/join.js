var Join = {
	timervar : 0,
	hadcorrect : false,
	nousernamecounter : 0,
	nopasswordcounter : 0,
	repasswordcounter : 0,
	Focusinput : function ( node ) {
		$( node ).css( "border" , "1px solid #bdbdff" );
	},
	Unfocusinput : function ( node ) {
		$( node ).css( "border" , "1px solid #999" );
	},
	Checkpwd : function() {
		var node = $( 'form.joinform div div input' )[ 0 ];
		var pwd = $( 'form.joinform div input' )[ 1 ];
		var div = $( 'form.joinform div div' )[ 0 ];
		if ( Join.timervar !== 0 ) {
			clearTimeout( Join.timervar );
		}
		
		Join.timervar = setTimeout( function() {
			if ( node.value == pwd.value && node.value !== '' && !Join.hadcorrect ) {
				Join.hadcorrect = true;
				$( node ).css( "display" , "inline" );
				var okpwd = document.createElement( 'img' );
				okpwd.src = 'images/button_ok_16.png';
				okpwd.alt = 'Σωστή επαλήθευση';
				okpwd.title = 'Σωστή επαλήθευση';
				$( okpwd ).css( "padding-left" , "5px" );
				if ( typeof okpwd.style.opacity != 'undefined' ) {
					$( okpwd ).css( "opacity" , "0" );
					$( div ).append( okpwd );
					$( okpwd ).animate( { opacity: "1" } , 2000 ); 
				}
				else {
					$( div ).append( okpwd );
				}
			}
			else {
				var okpwd = $( 'form.joinform div div img' )[ 0 ];
				if ( node.value != pwd.value && okpwd ) {
					alert( okpwd );
					alert( div );
					div.removeChild( okpwd );
					Join.hadcorrect = false;
				}
			}
		}, 200 );
	},
	ShowTos : function () {
		var area = $( 'div#join_tos' )[ 0 ].cloneNode( true );
		$( area ).css( "display" , "block" );
		Modals.Create( area, 620, 520 );
	}
};
$( document ).ready( function(){
	$( 'form.joinform div input' ).focus( function() {
		Join.Focusinput( this );
	});
	$( 'form.joinform div input' ).blur( function() {
		Join.Unfocusinput( this );
	});
	$( 'form.joinform div input:first' )[ 0 ].focus();
	$( 'form.joinform div div input' ).keyup( function() {
		Join.Checkpwd();
	});
	$( 'form.joinform p a' ).click( function () {
		Join.ShowTos();
		return false;
	});
	$( 'div a.button' ).click( function() {
		//alert the username, password and email
		var username = $( 'form.joinform div input' )[ 0 ];
		var password = $( 'form.joinform div input' )[ 1 ];
		var repassword = $( 'form.joinform div input' )[ 2 ];
		var email = $( 'form.joinform div input' ) [ 3 ];
		if ( username.value == '' ) {
			if ( Join.nousernamecounter == 0 ) {
				++Join.nousernamecounter;
				$( $( 'form.joinform div > span' )[ 0 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity: "1" } , 3000 , function() {
					$( $( 'form.joinform div > span' )[ 0 ] ).animate( { opacity: "0" } , 3000 , function() {
						Join.nousernamecounter = 0;
					});
				});
			}
			username.focus();
		}
		if ( password.value == '' && username.value != '' ) {
			if ( Join.nopasswordcounter == 0 ) {
				++Join.nopasswordcounter;
				$( $( 'form.joinform div > span' )[ 1 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity: "1" } , 3000 , function() {
					$( $( 'form.joinform div > span' )[ 1 ] ).animate( { opacity: "0" } , 3000 , function() {
						Join.nopasswordcounter = 0;
					});
				});
			}
			password.focus();
		}
		if ( password.value != repassword.value && password.value != '' && username.value != '' ) {
			if ( Join.repasswordcounter == 0 ) {
				++Join.repasswordcounter;
				$( $( 'form.joinform div div > span' )[ 0 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity: "1" } , 3000 , function() {
					$( $( 'form.joinform div div > span' )[ 0 ] ).animate( { opacity: "0" } , 3000 , function() {
						Join.repasswordcounter = 0;
					});
				});
			}
			repassword.focus();
		}
		return false;
	});
});