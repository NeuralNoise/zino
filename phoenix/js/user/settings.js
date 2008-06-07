var Settings = {
	saver : 0,
	queue : {},
	showsaved : $( 'div.settings div.sidebar div span.saved' ),
	showsaving : $( 'div.settings div.sidebar div span.saving' ),
	invaliddob : false,
	slogan : $( '#slogan input' )[ 0 ] ? $( '#slogan input' )[ 0 ].value : false,
	favquote : $( '#favquote input' )[ 0 ] ? $( '#favquote input' )[ 0 ].value : false,
	aboutmetext : $( '#aboutme textarea' )[ 0 ] ? $( '#aboutme textarea' )[ 0 ].value : false,
	email : $( '#email input' )[ 0 ] ? $( '#email input' )[ 0 ].value : false,
	msn : $( '#msn input' )[ 0 ] ? $( '#msn input' )[ 0 ].value : false,
	gtalk : $( '#gtalk input' )[ 0 ] ? $( '#gtalk input' )[ 0 ].value : false,
	skype : $( '#skype input' )[ 0 ] ? $( '#skype input' )[ 0 ].value : false,
	yahoo : $( '#yahoo input' )[ 0 ] ? $( '#yahoo input' )[ 0 ].value : false,
	web : $( '#web input' )[ 0 ] ? $( '#web input' )[ 0 ].value : false,
	invalidemail : false,
	invalidmsn : false,
	SwitchSettings : function( divtoshow ) {
		//hack so that it is executed only when it is loaded
		var validtabs = [ 'personal', 'characteristics', 'interests', 'contact', 'settings' ];
		var found = false;
		var settingslis = $( 'div.settings div.sidebar ol li' );
		for ( i = 0; i < validtabs.length; ++i ) {
			if ( divtoshow == validtabs[ i ] ) {
				$( '#' + divtoshow + 'info' ).show();
				Settings.FocusSettingLink( settingslis[ i ], true );
				window.location.hash = window.location.hash.substr( 0, 1 ) + validtabs[ i ];
				found = true;
			}
			else {
				$( '#' + validtabs[ i ] + 'info' ).hide();
				Settings.FocusSettingLink( settingslis[ i ], false );
				
			}
		}
		if ( !found ) {
			$( '#' + validtabs[ 0 ] + 'info' ).show();
			window.location.hash = window.location.hash.substr( 0, 1 ) + 'personal';
			Settings.FocusSettingLink( settingslis[ 0 ] , true );
		}
	},
	FocusSettingLink : function( li, focus ) {
		if ( li ) {
			if ( focus ) {
				$( li ).addClass( 'selected' );
				li.getElementsByTagName( 'a' )[ 0 ].style.color = 'white';
			}
			else {
				$( li ).removeClass( 'selected' );
				li.getElementsByTagName( 'a' )[ 0 ].style.color = '#105cb6';
			}
		}
	},
	DoSwitchSettings : function() {
		setTimeout( Settings.SwitchSettings, 20 );
	},
	Enqueue : function( key , value , timerinterval ) {
		if ( Settings.saver !== 0 ) {
			clearTimeout( Settings.saver );
		}
		Settings.saver = setTimeout( Settings.Save , timerinterval );
		Settings.queue[ key ] = value;
	},
	Dequeue : function() {
		Settings.queue = {};
	},
	Save : function() {
		$( Settings.showsaving )
			.css( "display" , "inline" )
			.animate( { opacity : "1" } , 200 );
		Coala.Warm( 'user/settings/save' , Settings.queue );
		Settings.Dequeue();
	},
	AddInterest : function( type , typeid ) {
		//type can be either: hobbies, movies, books, songs, artists, games, quotes, shows
		var intervalue = $( 'div.settings div.tabs form#interestsinfo div.option div.setting div.' + type + ' input' )[ 0 ].value;
		if ( intervalue !== '' ) {
			if ( intervalue.length <= 32 ) {
				var newli = document.createElement( 'li' );
				var newspan = $( 'div.settings div.tabs form#interestsinfo div.creation' )[ 0 ].cloneNode( true );
				$( newspan ).removeClass( 'creation' ).find( 'span' ).append( document.createTextNode( showvalue ) );
				var link = newspan.getElementsByTagName( 'a' )[ 0 ];
				$( newli ).append( newspan );
				$( 'div.settings div.tabs form#interestsinfo div.option div.setting ul.' + type ).prepend( newli );
				Coala.Warm( 'user/settings/tags/new' , { text : intervalue , typeid : typeid , node : link } );
			}
			else {
				alert( '�� ������� ��� ������ �� ���� 32 ���������� �� ����' );
			}
			$( 'div.settings div.tabs form#interestsinfo div.option div.setting div.' + type + ' input' )[ 0 ].value = '';
			$( 'div.settings div.tabs form#interestsinfo div.option div.setting div.' + type + ' input' )[ 0 ].focus();
		}
	},
	RemoveInterest : function( tagid , node ) {
		var parent = node.parentNode.parentNode;
		$( node ).remove();
		$( parent ).hide( 'slow' );
		Coala.Warm( 'user/settings/tags/delete' , { tagid : tagid } );
	},
	ShowAvatarChange : function() {   
		var avies = $( '#avatarlist' )[ 0 ].cloneNode( true );
		$( avies ).show();
		Modals.Create( avies, 500, 500 );
		
	},
	SelectAvatar : function( imageid ) {
		Modals.Destroy();
		Coala.Warm( 'user/settings/avatar' , { imageid : imageid } );
	},
	AddAvatar : function( imageid ) {
		var li = document.createElement( 'li' );
		$( li ).hide();
		$( 'div.modal div.avatarlist ul' ).prepend( li );
		Coala.Warm( 'user/settings/upload' , { imageid : imageid } );
		var li2 = document.createElement( 'li' );
		$( 'div.settings div.tabs form#personalinfo div.option div.setting div.avatarlist ul' ).prepend( li2 );
	}
};
$( document ).ready( function() {
	if ( $( 'div.settings' )[ 0 ] ) {
		Settings.SwitchSettings( window.location.hash.substr( 1 ) );
		$( '#gender select' ).change( function() {
			var sexselected = $( '#sex select' )[ 0 ].value;
			var relselected = $( '#religion select' )[ 0 ].value;
			var polselected = $( '#politics select' )[ 0 ].value;
			Coala.Cold( 'user/settings/genderupdate' , { 
				gender : this.value,
				sex : sexselected,
				religion : relselected,
				politics : polselected
			} );
			Settings.Enqueue( 'gender' , this.value , 3000 );
		});
		$( '#dateofbirth select' ).change( function() {
			var day = $( '#dateofbirth select' )[ 0 ].value;
			var month = $( '#dateofbirth select' )[ 1 ].value;
			var year = $( '#dateofbirth select' )[ 2 ].value;
			//check for validdate
			if ( day != -1 && month != -1 && year != -1 ) {
				if ( Dates.ValidDate( day , month , year ) ) {
					if ( Settings.invaliddob ) {
						$( 'div.settings div.tabs form#personalinfo div span.invaliddob' )
							.animate( { opacity: "0" } , 1000 , function() {
								$( this ).css( "display" , "none" );
							});
						Settings.invaliddob = false;
					}
					Settings.Enqueue( 'dobd' , day , 4000 );
					Settings.Enqueue( 'dobm' , month , 4000 );
					Settings.Enqueue( 'doby' , year , 3000 );
				}
				else {
					if ( !Settings.invaliddob ) {
						$( 'div.settings div.tabs form#personalinfo div span.invaliddob' )
							.css( "display" , "inline" )
							.animate( { opacity: "1" } , 200 );	
						Settings.invaliddob = true;
					}
				}
			}
		});
		$( '#place select' ).change( function() {
			Settings.Enqueue( 'place' , this.value , 1000 );
		});
		$( '#education select' ).change( function() {
			Settings.Enqueue( 'education' , this.value , 1000 );
		});
		$( '#university select' ).change( function() {
			Settings.Enqueue( 'university' , this.value , 1000 );
		});
		$( '#sex select' ).change( function() {
			Settings.Enqueue( 'sex' , this.value , 3000 );
		});
		$( '#religion select' ).change( function() {
			Settings.Enqueue( 'religion' , this.value , 3000 );
		});
		$( '#politics select' ).change( function() {
			Settings.Enqueue( 'politics' , this.value , 3000 );
		});
		$( '#haircolor select' ).change( function() {
			Settings.Enqueue( 'haircolor' , this.value , 3000 );
		});
		$( '#eyecolor select' ).change( function() {
			Settings.Enqueue( 'eyecolor' , this.value , 3000 );
		});
		$( '#height select' ).change( function() {
			Settings.Enqueue( 'height' , this.value , 3000 );
		});
		$( '#weight select' ).change( function() {
			Settings.Enqueue( 'weight' , this.value , 3000 );
		});
		$( '#smoker select' ).change( function() {
			Settings.Enqueue( 'smoker' , this.value , 3000 );
		});
		$( '#drinker select' ).change( function() {
			Settings.Enqueue( 'drinker' , this.value , 3000 );
		});
		
		$( '#slogan input' ).change( function() {
			var text = this.value;
			if ( this.value === '' ) {
				text = '-1';
			}
			Settings.Enqueue( 'slogan' , text , 500 );
		}).keyup( function() {
			var text = this.value;
			if ( this.value === '' ) {
				text = '-1';
			}
			Settings.Enqueue( 'slogan' , text , 3000 );
			if ( Settings.slogan ) {
				Settings.slogan = this.value;
			}
		});
		
		$( '#aboutme textarea' ).change( function() {
			var text = this.value;
			if ( this.value === '' ) {
				text = '-1';
			}
			Settings.Enqueue( 'aboutme' , text , 3000 );
		}).keyup( function() {
			if ( Settings.aboutmetext != this.value ) {
				var text = this.value;
				if ( this.value === '' ) {
					text = '-1';
				}
				Settings.Enqueue( 'aboutme' , text , 3000 );
				if ( Settings.aboutmetext ) {
					Settings.aboutmetext = this.value;
				}
			}
		} );
		
		$( '#favquote input' ).change( function() {
			var text = this.value;
			if ( this.value === '' ) {
				text = '-1';
			}
			Settings.Enqueue( 'favquote' , text , 500 );
		}).keyup( function() {
			var text = this.value;
			if ( this.value === '' ) {
				text = '-1';
			}
			Settings.Enqueue( 'favquote' , text , 3000 );
			if ( Settings.favquote ) {
				Settings.favquote = this.value;
			}
		});
		
		$( '#email input' ).change( function() {
			var text = this.value;
			if ( this.value === '' ) {
				text = '-1';
			}
			Settings.Enqueue( 'email' , text , 500 );
		}).keyup( function() {
			var text = this.value;
			if ( Settings.invalidemail ) {
				if ( /^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+$/.test( text ) ) {
					$( 'div#email span' ).animate( { opacity: "0" } , 1000 , function() {
						$( 'div#email span' ).css( "display" , "none" );
					});
					Settings.invalidemail = false;
					Settings.Enqueue( 'email' , text , 3000 );
				}
			}
			else {
				if ( this.value === '' ) {
					text = '-1';
				}
				Settings.Enqueue( 'email' , text , 3000 );
			}
			if ( Settings.email ) {
				Settings.email = this.value;
			}
		});
		
		$( '#msn input' ).change( function() {
			var text = this.value;
			if ( this.value === '' ) {
				text = '-1';
			}
			Settings.Enqueue( 'msn' , text , 500 );
		}).keyup( function() {
			var text = this.value;
			if ( Settings.invalidmsn ) {
				if ( /^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+$/.test( text ) ) {
					$( 'div#msn span' ).animate( { opacity: "0" } , 1000 , function() {
						$( 'div#msn span' ).css( "display" , "none" );
					});
					Settings.invalidmsn = false;
					Settings.Enqueue( 'msn' , text , 3000 );
				}
			}
			else {
				if ( this.value === '' ) {
					text = '-1';
				}
				Settings.Enqueue( 'msn' , text , 3000 );
			}
			if ( Settings.msn ) {
				Settings.msn = this.value;
			}
		});
		
		$( '#gtalk input' ).change( function() {
			var text = this.value;
			if ( this.value === '' ) {
				text = '-1';
			}
			Settings.Enqueue( 'gtalk' , text , 500 );
		}).keyup( function() {
			var text = this.value;
			if ( this.value === '' ) {
				text = '-1';
			}
			Settings.Enqueue( 'gtalk' , text , 3000 );
			if ( Settings.gtalk ) {
				Settings.gtalk = this.value;
			}
		});
		
		$( '#skype input' ).change( function() {
			var text = this.value;
			if ( this.value === '' ) {
				text = '-1';
			}
			Settings.Enqueue( 'skype' , text , 500 );
		}).keyup( function() {
			var text = this.value;
			if ( this.value === '' ) {
				text = '-1';
			}
			Settings.Enqueue( 'skype' , text , 3000 );
			if ( Settings.skype ) {
				Settings.skype = this.value;
			}
		});
		
		$( '#yahoo input' ).change( function() {
			var text = this.value;
			if ( this.value === '' ) {
				text = '-1';
			}
			Settings.Enqueue( 'yahoo' , text , 500 );
		}).keyup( function() {
			var text = this.value;
			if ( this.value === '' ) {
				text = '-1';
			}
			Settings.Enqueue( 'yahoo' , text , 3000 );
			if ( Settings.yahoo ) {
				Settings.yahoo = this.value;
			}
		});
		
		$( '#web input' ).change( function() {
			var text = this.value;
			if ( this.value === '' ) {
				text = '-1';
			}
			Settings.Enqueue( 'web' , text , 500 );
		}).keyup( function() {
			var text = this.value;
			if ( this.value === '' ) {
				text = '-1';
			}
			Settings.Enqueue( 'web' , text , 3000 );
			if ( Settings.skype ) {
				Settings.skype = this.value;
			}
		});
		
		//interesttags
		$( 'form#interestsinfo div.option div.setting div.hobbies input' ).keydown( function( event ) {
			if ( event.keyCode == 13 ) {
				Settings.AddInterest( 'hobbies' , 1 );
			}
		} );
		$( 'form#interestsinfo div.option div.setting div.hobbies a' ).click( function() {
			Settings.AddInterest( 'hobbies' , 1 );
			return false;
		} );
		
		$( 'form#interestsinfo div.option div.setting div.movies input' ).keydown( function( event ) {
			if ( event.keyCode == 13 ) {
				Settings.AddInterest( 'movies' , 2 );
			}
		} );
		$( 'form#interestsinfo div.option div.setting div.movies a' ).click( function() {
			Settings.AddInterest( 'movies' , 2 );
			return false;
		} );
		
		$( 'form#interestsinfo div.option div.setting div.books input' ).keydown( function( event ) {
			if ( event.keyCode == 13 ) {
				Settings.AddInterest( 'books' , 3 );
			}
		} );
		$( 'form#interestsinfo div.option div.setting div.books a' ).click( function() {
			Settings.AddInterest( 'books' , 3 );
			return false;
		} );

		$( 'form#interestsinfo div.option div.setting div.songs input' ).keydown( function( event ) {
			if ( event.keyCode == 13 ) {
				Settings.AddInterest( 'songs' , 4 );
			}
		} );
		$( 'form#interestsinfo div.option div.setting div.songs a' ).click( function() {
			Settings.AddInterest( 'songs' , 4 );
			return false;
		} );
		
		$( 'form#interestsinfo div.option div.setting div.artists input' ).keydown( function( event ) {
			if ( event.keyCode == 13 ) {
				Settings.AddInterest( 'artists' , 5 );
			}
		} );
		$( 'form#interestsinfo div.option div.setting div.artists a' ).click( function() {
			Settings.AddInterest( 'artists' , 5 );
			return false;
		} );
		
		$( 'form#interestsinfo div.option div.setting div.games input' ).keydown( function( event ) {
			if ( event.keyCode == 13 ) {
				Settings.AddInterest( 'games' , 6 );
			}
		} );
		$( 'form#interestsinfo div.option div.setting div.games a' ).click( function() {
			Settings.AddInterest( 'games' , 6 );
			return false;
		} );
		$( 'form#interestsinfo div.option div.setting div.shows input' ).keydown( function( event ) {
			if ( event.keyCode == 13 ) {
				Settings.AddInterest( 'shows' , 7 );
			}
		} );
		$( 'form#interestsinfo div.option div.setting div.shows a' ).click( function() {
			Settings.AddInterest( 'shows' , 7);
			return false;
		} );
	}
});