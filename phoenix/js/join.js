var Join = {
	timervar : 0,
	hadcorrect : false,
	usernameerror : false, //used to check if a username has been given
	invalidusername : false,
	pwderror : false, //used to check if a password has been given
	repwderror : false, //used to check if password is equal with the retyped password
	usernameexists : false,
	username : $( 'form.joinform div input' )[ 0 ],
	password : $( 'form.joinform div input' )[ 1 ],
	repassword : $( 'form.joinform div input' )[ 2 ],
	email : $( 'form.joinform div input' ) [ 3 ],
	ShowTos : function () {
		var area = $( 'div#join_tos' )[ 0 ].cloneNode( true );
		$( area ).css( "display" , "block" );
		Modals.Create( area, 620, 520 );
	}
};
$( document ).ready( function(){
	$( 'form.joinform div input' ).focus( function() {
		$( this ).css( "border" , "1px solid #bdbdff" );
	}).blur( function() {
		$( this ).css( "border" , "1px solid #999" );
	});
	
	$( $( 'form.joinform div input' )[ 0 ] ).keyup( function() {
		if ( Join.usernameerror ) {
			if ( Join.username.value.length >= 4 ) {
				Join.usernameerror = false;
				$( $( 'form.joinform div > span' )[ 0 ] ).animate( { opacity: "0" } , 700 , function() {
					$( this ).css ( "display" , "none");
				});
			}
		}
		if ( Join.usernameexists ) {
			Join.usernameexists = false;
			$( $( 'form.joinform div > span' )[ 1 ] ).animate( { opacity: "0" } , 700 , function() {
				$( this ).css( "display" , "none" );
			});
		}
		if ( Join.invalidusername ) {
			Join.invalidusername = false;
			$( $( 'form.joinform div > span' )[ 2 ] ).animate( { opacity: "0" } , 700 , function() {
				$( this ).css( "display" , "none" );
			});
		}
	});	
	
	$( $( 'form.joinform div input' )[ 1 ] ).keyup( function() {
		if ( Join.pwderror ) {
			if ( Join.password.value.length >= 4 ) {
				Join.pwderror = false;
				$( $( 'form.joinform div > span' )[ 3 ] ).animate( { opacity: "0" } , 700 , function() {
					$( this ).css( "display" , "none" )
				});
			}
		}
	});
	
	$( $( 'form.joinform div input' )[ 2 ] ).keyup( function() {
		if ( Join.repwderror ) {
			if ( Join.repassword.value == Join.password.value ) {
				Join.repwderror = false;
				$( $( 'form.joinform div > span' )[ 4 ] ).animate( { opacity: "0" } , 700 , function() {
					$( this ).css( "display" , "none" );
				});
			}
		}
	});
	
	Join.username.focus();
	
	$( 'form.joinform p a' ).click( function () {
		Join.ShowTos();
		return false;
	});
	
	$( 'div a.button' ).click( function() {
		var create = true;
		if ( Join.username.value.length < 4 ) {
			if ( !Join.usernameerror ) {
				Join.usernameerror = true;
				$( $( 'form.joinform div > span' )[ 0 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity : "1" } , 400 );
			}
			Join.username.focus();
			create = false;
		}
		if ( Join.username.value.length >= 4 && !Join.username.value.match( /^[a-zA-Z][a-zA-Z\-_0-9]{3,49}$/ ) ) {
			if ( !Join.invalidusername ) {
				Join.invalidusername = true;
				$( $( 'form.joinform div > span' )[ 2 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity : "1" } , 400 );
			}
			Join.username.focus();
			create = false;
		}
		if ( Join.password.value.length < 4 ) {
			if ( !Join.pwderror ) {
				Join.pwderror = true;
				$( $( 'form.joinform div > span' )[ 3 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity: "1" } , 400 );
			}
			if ( Join.username.value.length >= 4 && Join.username.value.match( /^[a-zA-Z][a-zA-Z\-_0-9]{3,49}$/ ) ) {
				//if the username and password are empty then focus the username inputbox
				Join.password.focus();
			}
			create = false;
		}
		if ( Join.password.value != Join.repassword.value && Join.password.value.length >= 4 ) {
			if ( !Join.repwderror ) {
				Join.repwderror = true;
				$( $( 'form.joinform div div > span' )[ 0 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity: "1" } , 400 );
			}
			Join.repassword.focus();
			create = false;
		}
		if ( create ) {
			//Coala call
			document.body.cursor = 'wait';
			Coala.Warm( 'user/join' , { username : Join.username.value , password : Join.password.value , email : Join.email.value } );
		}
		return false;
	});
});