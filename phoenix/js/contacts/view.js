/**     available tabs: 
* search in zino.
*   width: max, min-height: 560px
* search in other networks: login
*   max-width: 600px, min-height: 250px
* search in other networks: loading
*   max-width: 700px, min-height: 466px
* search in other networks: contacts In zino
*   max-width: 700px, min-height: 466px
* search in other networks: contacts Not in zino
*   max-width: 570px, min-height: 420px
* invite by mail
*   max-width: 540px, min-height: 400px;
*/
var contacts = {
    frontpage: "",
    tab: 1,
    step: 0,
	provider: "",
	username: "",
	password: "",
	borderhide: "",
    contactsNotInZino: 0,
    redirectToFrontpage: function(){
		clearTimeout( contacts.borderhide );
        window.location = contacts.frontpage;
    },
    changeToSearchInZino: function(){
        if ( contacts.tab == 1 && contacts.step == 0 || $( '.invite_contacts *:animated' ).length != 0 ){
            return false;
        }
        $( '#top_tabs li' ).removeClass().filter( '#searchInZino' ).addClass( 'selected' );
        var maxwidth = $( '#content' ).innerWidth();
        contacts.tab = 1;
        contacts.step = 0;
        $( "#top_tabs" ).css( 'zIndex', '10' );
        $( '.tab:visible, #foot' ).fadeOut( 'normal', function(){
            $( '#body' ).css({
                        borderWidth: '1px 0 0 0'
                    }).animate({
                maxWidth: maxwidth,
                minHeight: 560
                }, function(){
                    $( '#searchtab' ).fadeIn( 'normal' );
            });
        });
        return true;
    },
    changeToFindInOtherNetworks: function(){
        if ( contacts.tab == 2 && contacts.step == 0 || $( '.invite_contacts *:animated' ).length != 0 ){
            return false;
        }
        $( '#top_tabs li' ).removeClass().filter( '#otherNetworks' ).addClass( 'selected' );
        var maxwidth = $( '#content' ).innerWidth();
        document.title = "Αναζήτηση φίλων | Zino";
        contacts.tab = 2;
        contacts.step = 0;
        $( '.tab:visible' ).fadeOut( 'normal', function(){
            $( '#body' ).animate({
                maxWidth: 600,
                minHeight: 250
                }, function(){
                    $( this ).css({
                        borderWidth: '1px'
                    });
					
                    $( '#login' ).fadeIn( 'normal', function(){
						$( '#mail input' ).focus();
					});
                    $( "#top_tabs" ).css( 'zIndex', '10' );
                    $( "#foot input" ).removeClass() //.addClass( 'continue' )
                        .unbind().bind( 'click', contacts.retrieve )
                        .parent().filter( "div:hidden" ).fadeIn( 'normal' );
            });
        });
        return true;
    },
    changeToAddByEmail: function(){
        if ( contacts.tab == 3 || $( '.invite_contacts *:animated' ).length != 0 ){
            return false;
        }
        $( '#top_tabs li' ).removeClass().filter( '#ByEmail' ).addClass( 'selected' );
        var maxwidth = $( '#content' ).innerWidth();
        document.title = "Πρόσκληση φίλων | Zino";
        contacts.tab = 3;
        $( '.tab:visible' ).fadeOut( 'normal', function(){
            $( '#body' ).animate({
                maxWidth: 540,
                minHeight: 320
                }, function(){
                    $( this ).css({
                        borderWidth: '1px'
                    });
					
                    $( '#inviteByEmail' ).fadeIn( 'normal', function(){
						$( '#contactMail textarea' ).focus();
                    });
                    $( "#foot input" ).removeClass().addClass( "invite" )
                        .unbind().bind( 'click', contacts.sendInvitations )
                        .parent().filter( "div:hidden" ).fadeIn( 'normal' );
                    $( "#top_tabs" ).css( 'zIndex', '10' );
            });
        });
        return true;
    },
    search: function(){
        if( !Search.check() ){
            return false;
        }
        var gender = $( '#gender input:checked' ).val();
        var minage = $( '#age select:first-child option:selected' ).val();
        var maxage = $( '#age select:last-child option:selected' ).val();
        var placeid = $( '#place select option:selected' ).val();
        var orientation = $( '#orientation select option:selected ').val();
        
        Coala.Cold(
            'contacts/search', {
                gender: gender,
                minage: minage,
                maxage: maxage,
                placeid: placeid,
                orientation: orientation
            }
        );
        return false;
    },
    sendInvitations: function(){
        var text = $( '#contactMail textarea' ).val();
        var mails = text.split( /[\s,;]+/ );
        var mail;
        var corMails = new Array();
        for ( var i in mails ){
            var mail = mails[ i ];
            corMails.push( mail );
        }
        var mailString = corMails.join( ';' );
        Coala.Warm( 'contacts/invitebymail', {
            mails: mailString
        });
        $( '#foot input' ).unbind();
		document.body.style.cursor = 'wait';
    },
    retrieve: function(){
        contacts.provider = $( "#left_tabs li.selected span" ).attr( 'id' );
        var email = $( "#mail input" ).val().split( '@' );
        contacts.username = email[ 0 ];
        if ( contacts.provider == "hotmail" ){
            if ( email[ 1 ] == 'windowslive.com' || email[ 1 ] == 'live.com' || email[ 1 ] == 'msn.com' ){
                contacts.username += '@' + email[ 1 ];
            }
            else{
                contacts.username += "@hotmail.com";
            }
            contacts.provider == 'hotmail';
        }
        contacts.password = $( "#password input" ).val();
        if ( contacts.username == "" || contacts.password == "" ){
            $( "#security" ).css({
                'background': '#FEF4B7 url(http://static.zino.gr/phoenix/error.png) no-repeat 6px center',
                'font-weight': 'bold',
                'padding': '10px 10px 10px 30px'
            }).html( 'Συμπλήρωσε το e-mail σου και τον κωδικό σου.' );
            return false;
        }
        Coala.Warm( 'contacts/retrieve', {
            provider: contacts.provider,
            username: contacts.username,
            password: contacts.password
        });
        contacts.loading();
    },
	loading: function(){
        document.title = "Φόρτωση επαφών...";
        contacts.step = 1;
        $( "#top_tabs" ).css( 'zIndex', '-10' );
        $( "#foot, .tab:visible" ).fadeOut( 'normal', function(){
            $( "#body" ).animate({
                maxWidth: 700,
                minHeight: 466
            }, 'normal', function(){
                $( "#loading" ).fadeIn();
            });
        });
	},
    message: function( text, callback ){
		if ( typeof( callback ) != 'undefined' ){
			$( '#body' ).css( 'borderWidth', 0 );
		}
        $( '#message h1' ).html( text );
		$( '#top_tabs:visible' ).fadeOut( 'normal' );
		$( '.tab:visible' ).fadeOut( 'normal', function(){
            $( '#body' ).animate({
                minHeight: 300
            }, 'normal', function(){
                $( '#message' ).fadeIn( 'normal' );
			});
        });
		$( '#foot:visible' ).fadeOut( 'normal' );
		document.body.style.cursor = 'default';
		contacts.borderhide = setTimeout( function(){
			$( '#body' ).css( 'borderWidth', '1px' );
			}, 3100 );
		setTimeout( callback, 3000 );
    },
    addContactInZino: function( display, mail, location, id ){
        div = document.createElement( "div" );
        var text = "<div class='contactName'>";
        text += "<input type='checkbox' checked='checked' /><div class='who'>";
        text += display;
        text += "</div><div class='contactMail text'>" + mail + "</div>";
        text += "</div>";
        text += "<div class='location'>";
        text += location;
        text += "</div>";
        
        $( div ).addClass( "contact comment" ).attr( 'id', id ).html( text ).appendTo( '#contactsInZino .contacts' );
    },
    previewContactsInZino: function( num ){
        document.title = "Προσθήκη φίλων | Zino";
		document.body.style.cursor = 'default';
        contacts.step = 2;
        contacts.contactsNotInZino = num;
        $( '.tab:visible' ).fadeOut( 'normal', function(){
            $( '#contactsInZino' ).fadeIn( 'normal' );
            $( "#foot input" ).removeClass().addClass( 'add' )
                .unbind().bind( 'click', contacts.addFriends )
                .parent().filter( "div:hidden" ).fadeIn( 'normal' );
        });
	},
    addContactNotZino: function( mail, nickname, contact_id ){
        div = document.createElement( "div" );
        var text = "<input type='checkbox' checked='checked' />";
        if ( mail != nickname && mail.split( "@" )[ 0 ] != nickname ){
            text += "<div class='contactNickname'>" + nickname + "</div>";
            text += "<div class='contactMail'>" + mail + "</div>";
        }
        else{
            text += "<div style='margin-top: 8px' class='contactMail'>" + mail + "</div>";
        }
        $( div ).attr( 'id', 'contact_' + contact_id ).addClass( "contact" ).html( text ).appendTo( '#contactsNotZino .contacts' );
    },
    previewContactsNotInZino: function( num ){
        if ( $( '#contactsNotZino .contacts .contact' ).length == 0 ){
            contacts.redirectToFrontpage();
            return;
        }
        document.title = "Πρόσκληση φίλων | Zino";
		document.body.style.cursor = 'default';
        contacts.step = 3;
        $( '.tab:visible' ).fadeOut( 'normal', function(){
            $( '#body' ).animate({
                maxWidth: 570,
                minHeight: 480
                }, function(){
                    $( '#contactsNotZino' ).fadeIn( 'normal' );
                    $( "#foot input" ).removeClass().addClass( 'invite' )
                        .unbind().bind( 'click', contacts.invite )
                        .parent().filter( "div:hidden" ).fadeIn( 'normal' );
            });
        });
	},
    addFriends: function(){
    var ids = new Array;
        $( "#contactsInZino .contact input:checked" ).parent().parent().each( function( i ){
            ids.push( $( this ).attr( "id" ) );
        });
        idsString = ids.join( " " );
        Coala.Warm( "contacts/addfriends", {
            "ids": idsString
        });
		document.body.style.cursor = 'wait';
    },
    invite: function(){
    var ids = new Array;
        $( "#contactsNotZino .contact input:checked" ).parent().each( function( i ){
            var id = $( this ).attr( 'id' ).split( "_" )[ 1 ];
            ids.push( id );
        });
        idsString = ids.join( "," );
        Coala.Warm( "contacts/invite", {
            "ids": idsString
        });
		document.body.style.cursor = 'wait';
    },
    calcCheckboxes: function(){
        if ( contacts.step == 2 ){
            if ( $( "#contactsInZino input:checked" ).size() ){
                $( "#foot input" ).removeClass().addClass( "add" );
            }
            else{
                if ( contacts.contactsNotInZino == 0 ){
                    $( "#foot input" ).removeClass().addClass( "finish" );
                }
                else{
                    $( "#foot input" ).removeClass();
                }
            }
        }
        else{ //if step == 3
            if ( $( "#contactsNotZino input:checked" ).size() ){
                $( "#foot input" ).removeClass().addClass( "invite" );
            }
            else{
                $( "#foot input" ).removeClass().addClass( "finish" );
            }
        }
    },
	init: function(){
		$( "#foot input" ).bind( 'click', contacts.retrieve );
		//left tabs clickable
		$('#left_tabs li').click( function(){
			$('#left_tabs li').removeClass();
			$( this ).addClass( 'selected' );
            $( '#mail input' ).focus();
		});
        //top tabs clickable
        $( '#top_tabs li' )
            .filter( '#otherNetworks' ).click( contacts.changeToFindInOtherNetworks ).end()
            .filter( '#ByEmail' ).click( contacts.changeToAddByEmail ).end()
            .filter( '#searchInZino' ).click( contacts.changeToSearchInZino );
        
        
        //next step with enter
        $( '#password input' ).keydown( function( event ){
            if ( event.keyCode == 13 ){
                $( '#foot input' ).click();
            }
        });
		//checkboxes
		$( ".networks .contact input" ).attr( "checked", "checked" );
		
		$( ".networks .selectAll .all" ).click( function(){
			$( this ).parent().siblings( '.contacts' ).find( 'input' ).attr( "checked", "checked" ).each(function(){
                this.checked=true;
            });
            contacts.calcCheckboxes();
		});
		$( ".networks .selectAll .none" ).click( function(){
			$( this ).parent().siblings( '.contacts' ).find( 'input' ).attr( "checked", "" ).each(function(){
                this.checked=false;
            });
            contacts.calcCheckboxes();
		});
        //search maxwidth calculate
        var maxwidth = $( '#content' ).innerWidth();
        $( '#body' ).css( 'maxWidth', maxwidth );
	}
};
