var Join = {
	ShowTos : function () {
		var area = $( 'div#join_tos' )[ 0 ].cloneNode( true );
		$( area ).css( "display" , "block" );
		Modals.Create( area, 620, 520 );
	},
    UserExists : function() {
        if ( !Join.usernameexists ) {
            Join.usernameexists = true;
            $( $( 'form.joinform div > span' )[ 1 ] ).css( "opacity" , "0" ).css( "display" , "inline" ).animate( { opacity : "1" } , 700 );
            Join.username.focus();
            Join.username.select();
            document.body.style.cursor = 'default';
        }
    },
    ErrorHandler : function( varname , node ) {
        if ( !Join[ varname ] ) {
            Join[ varname ] = true;
            $( node ).css( 'opacity' , '0' ).css( 'display' , 'inline' ).animate( { opacity : "1" } , 400 );
        }
    },
    JoinOnLoad : function() {
        Join.timervar = 0;
        Join.hadcorrect = false;
        Join.usernameerror = false; //used to check if a username has been given
        Join.invalidusername = false;
        Join.pwderror = false; //used to check if a password has been given
        Join.repwderror = false; //used to check if password is equal with the retyped password
        Join.usernameexists = false;
        Join.emailerror = false;
        Join.emailexists = false;
        Join.username = $( 'form.joinform div input' )[ 0 ];
        Join.password = $( 'form.joinform div input' )[ 1 ];
        Join.repassword = $( 'form.joinform div input' )[ 2 ];
        Join.enabled = true;
        Join.email = $( 'form.joinform div input' )[ 3 ];
        $( 'form.joinform' ).submit( function() {
            return false;
        } );
        $( 'form.joinform div input' ).focus( function() {
            $( this ).css( "border" , "1px solid #bdbdff" );
        }).blur( function() {
            $( this ).css( "border" , "1px solid #999" );
        });
        $( Join.username ).keyup( function( event ) {
            if ( event.keyCode == 13 && !Join.usernameerror && !Join.usernameexists && !Join.invalidusername ) {
                Join.password.focus();
            }
        } );
        $( Join.password ).keyup( function( event ) {
            if ( event.keyCode == 13 && !Join.pwderror ) {
                Join.repassword.focus();
            }
        } );
        $( Join.repassword ).keyup( function( event ) {
            if ( event.keyCode == 13 && !Join.repwderror ) {
                Join.email.focus();
            }
        } );
        $( Join.email ).keyup( function( event ) {
            if ( event.keyCode == 13 && !Join.emailerror && !Join.emailexists ) {
                $( 'div a.button' )[ 0 ].focus();
            }
        } );
        $( Join.username ).keydown( function( event ) {
            if ( Join.usernameerror ) {
                if ( Join.username.value.length >= 4 && Join.username.value.length <= 20 ) {
                    Join.usernameerror = false;
                    $( $( 'form.joinform div > span' )[ 0 ] ).animate( { opacity: "0" } , 700 , function() {
                        $( this ).css ( "display" , "none");
                    });
                }
            }
            if ( Join.usernameexists ) {
                if ( event.keyCode != 13 ) {
                    Join.usernameexists = false;
                    $( $( 'form.joinform div > span' )[ 1 ] ).animate( { opacity: "0" } , 700 , function() {
                        $( this ).css( "display" , "none" );
                    });
                    $( 'div a.button' ).removeClass( 'button_disabled' );
                    Join.enabled = true;
                }
            }
            if ( Join.invalidusername ) {
                Join.invalidusername = false;
                $( $( 'form.joinform div > span' )[ 2 ] ).animate( { opacity: "0" } , 700 , function() {
                    $( this ).css( "display" , "none" );
                });
            }
        });	
        
        $( Join.password ).keyup( function() {
            if ( Join.pwderror ) {
                if ( Join.password.value.length >= 4 ) {
                    Join.pwderror = false;
                    $( $( 'form.joinform div > span' )[ 3 ] ).animate( { opacity: "0" } , 700 , function() {
                        $( this ).css( "display" , "none" );
                    });
                }
            }
        });
        
        $( Join.repassword ).keyup( function() {
            if ( Join.repwderror ) {
                if ( Join.repassword.value == Join.password.value ) {
                    Join.repwderror = false;
                    $( $( 'form.joinform div > span' )[ 4 ] ).animate( { opacity: "0" } , 700 , function() {
                        $( this ).css( "display" , "none" );
                    });
                }
            }
        });
        
        $( Join.email ).keyup( function() {
            if ( Join.emailerror ) {
                if ( Join.email.value === '' || Kamibu.ValidEmail( Join.email.value ) ) {
                    Join.emailerror = false;
                    $( $( 'form.joinform div > span' )[ 5 ] ).animate( { opacity: "0" } , 700 , function() {
                        $( this ).css( "display" , "none" );
                    });
                }
            }
			if ( Join.emailexists ){
				Join.emailexists = false;
				$( $( 'form.joinform div > span' )[ 6 ] ).animate( { opacity: "0" } , 700 , function() {
					$( this ).css( "display" , "none" );
				});
				$( 'div a.button' ).removeClass( 'button_disabled' );
			}
        });
        
        if ( Join.username ) {
            Join.username.focus();
        }
        
        $( 'form.joinform p a' ).click( function () {
            Join.ShowTos();
            return false;
        });
        
        $( 'div a.button' ).click( function() {
            var create = true;
            if ( Join.username.value.length < 4 || Join.username.value.length > 20 ) {
                Join.ErrorHandler( 'usernameerror' , $( 'form.joinform div > span' )[ 0 ] );
                Join.username.focus();
                create = false;
            }
            if ( Join.username.value.length >= 4 ) { 
                Join.ErrorHandler( 'invalidusername' , $( 'form.joinform div > span' )[ 2 ] );
                Join.username.focus();
                create = false;
            }
            if ( Join.password.value.length < 4 ) {
                Join.ErrorHandler( 'pwderror' , $( 'form.joinform div > span' )[ 3 ] );
                if ( !Join.usernamerror && !Join.invalidusername && !Join.usernameexists ) {
                    //if the username and password are empty then focus the username inputbox
                    Join.password.focus();
                }
                create = false;
            }
            if ( Join.password.value != Join.repassword.value && !Join.pwderror ) {
                Join.ErrorHandler( 'repwderror' , $( 'form.joinform div div > span' )[ 0 ] );
                if ( !Join.usernameerror && !Join.invalidusername && !Join.usernameexists ) {
                    Join.repassword.focus();
                }
                create = false;
            }
            if ( !Kamibu.ValidEmail( Join.email.value ) ) {
                Join.ErrorHandler( 'emailerror' , $( 'form.joinform div > span' )[ 5 ] );
                if ( !Join.usernameerror && !Join.invalidusername && !Join.usernameexists && !Join.pwderror && !Join.repwderror ) {
                    Join.email.focus();
                }
                create = false;
            }
            if ( create ) {
                if ( Join.enabled ) {
                    document.body.style.cursor = 'wait';
                    $( this ).addClass( 'button_disabled' );
                    Coala.Warm( 'user/join' , { username : Join.username.value , password : Join.password.value , email : Join.email.value } );
                }
            }
            return false;
        } );
    }
};
