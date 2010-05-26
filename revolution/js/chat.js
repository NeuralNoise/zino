function innerxml( node ) {
    return (node.xml || (new XMLSerializer()).serializeToString(node) || "").replace(
        new RegExp("(^<\\w*" + node.tagName + "[^>]*>)|(<\\w*\\/\\w*" + node.tagName + "[^>]*>$)", "gi"), "");
}
var ExcaliburSettings = {
    Production: true
};

var Chat = {
     Visible: false,
     Inited: false,
     ChannelsLoaded: {},
     ChannelByUserId: {},
     CurrentChannel: 0,
     Loading: false,
     UserId: 0,
     Authtoken: '',
     GetOnline: function () {
        $( '#onlineusers' ).css( { opacity: 0.5 } );
        $.get( 'users/online', {}, function ( res ) {
            var users = $( res ).find( 'user' );
            var user;
            var online = $( '#onlineusers' );
            var name;
            var html = '<li class="selected world" id="u0">Zino</li>';
            online.css( { opacity: 1 } );
            online = online[ 0 ];
            for ( i = 0; i < users.length; ++i ) {
                user = users[ i ];
                name = $( user ).find( 'name' ).text();
                html += '<li id="u' + $( user ).attr( 'id' ) + '">' + name + '</li>';
            }
            online.innerHTML = html;
            $( '#onlineusers li' ).click( function () {
                $( '#onlineusers li' ).removeClass( 'selected' );
                $( this ).addClass( 'selected' );
                Chat.Unflash( this.id.substr( 1 ) );
                var userid = this.id.split( 'u' )[ 1 ];
                if ( userid == 0 ) {
                    Chat.Show( 0 );
                 }
                else {
                    Chat.ShowPrivate( userid );
                }
            } );
        }, 'xml' );
     },
     HistoryFromXML: function ( res ) {
        var channelid = $( res ).find( 'chatchannel' ).attr( 'id' );
        if ( $( '#chatmessages_' + channelid ).length == 0 ) {
            $( '#chatmessages' )[ 0 ].innerHTML += '<ol style="" class="chatchannel" id="chatmessages_' + channelid + '" style="display:none"></ol>';
        }
        var history = $( '#chatmessages_' + channelid )[ 0 ];
        var messages = $( res ).find( 'discussion comment' );
        var text;
        var html = '';
        var shoutid;
        
        for ( i = 0; i < messages.length; ++i ) {
            text = innerxml( $( messages[ i ] ).find( 'text' )[ 0 ] );
            author = $( messages[ i ] ).find( 'author name' ).text();
            shoutid = $( messages[ i ] ).attr( 'id' );

            html += '<li id="' + shoutid + '"><strong';
            if ( author == User ) {
                html += ' class="self"';
            }
            html += '>';
            html += author;
            html += '</strong> <span class="text">' + text + '</span></li>';
        }
        history.innerHTML = html;
     },
     GetMessages: function ( channelid, callback ) {
         $.get( 'chat/messages', { channelid: channelid }, function ( res ) {
             Chat.HistoryFromXML( res );
             callback( res );
         }, 'xml' );
     },
     LoadHistory: function ( channelid, callback ) {
         Chat.GetMessages( channelid, callback );
     },
     Init: function () {
         $( '.col2' )[ 0 ].innerHTML +=
             '<div style="" id="chat">'
                 + '<div class="userlist">'
                     + '<ol id="onlineusers"></ol>'
                 + '</div>'
                 + '<div class="textmessages">'
                     + '<div class="loading" style="display:none">Λίγα δευτερόλεπτα υπομονή...</div>'
                     + '<div id="chatmessages"></div>'
                     + '<div id="outgoing"><div><textarea style="color:#ccc">Στείλε ένα μήνυμα</textarea></div></div>'
                 + '</div>'
             + '</div>';
         Chat.Show( 0 );
         $( '#chat textarea' ).keydown( function ( e ) {
             switch ( e.keyCode ) {
                case 27: // ESC
                    this.value = '';
                    $( this ).blur();
                    break;
                case 13: // enter
                    Chat.SendMessage( Chat.CurrentChannel, this.value );
                    this.value = '';
                    $( this ).blur();
                    $( this ).focus();
             }
         } ).keyup( function ( e ) {
             switch ( e.keyCode ) {
                 case 13: // enter
                    this.value = '';
             }
         } );
         Kamibu.ClickableTextbox( $( '#chat textarea' )[ 0 ], 'Γράψε ένα μήνυμα', 'black', '#ccc' );
         document.domain = 'zino.gr';
         var bigNumber = 123456789;
         $.get( 'session', function ( res ) {
             Chat.UserId = $( res ).find( 'user' ).attr( 'id' );
             Chat.Authtoken = $( res ).find( 'authtoken' ).text();
             Comet.Init( Math.random() * bigNumber, 'universe.alpha.zino.gr' );
             Chat.Join( '0' );
             Chat.Join( Chat.UserId + ':' + Chat.Authtoken ); // TODO: Join( UserId + ':' + Authtoken )
             Chat.Inited = true;
         } );
     },
     SendMessage: function ( channelid, text ) {
         if ( text.replace( /^\s+/, '' ).replace( /\s+$/, '' ).length == 0 ) {
             // empty message
             return;
         }

         var li = document.createElement( 'li' );
         li.innerHTML = '<strong class="self">' + User + '</strong> <span class="text">' + text + '</span>';
         $( '#chatmessages_' + channelid )[ 0 ].appendChild( li );
         $( '#chatmessages_' + channelid )[ 0 ].lastChild.scrollIntoView();
         var lastChild = $( '#chatmessages_' + channelid )[ 0 ].lastChild;

         $.post( 'chat/message/create', {
            channelid: channelid,
            text: text
         }, function ( res ) {
             var shoutid = $( res ).find( 'comment' ).attr( 'id' );
                
            if ( document.getElementById( shoutid ) ) {
                // already received this message through comet
                $( lastChild ).remove(); // remove duplicate
            }
            // didn't receive it through comet yet; update the innerHTML and ids
            // when it's received through comet, it'll be ignored
            $( lastChild ).find( 'span' )[ 0 ].innerHTML = innerxml( $( res ).find( 'text' )[ 0 ] );
            $( lastChild )[ 0 ].id = shoutid;
         }, 'xml' );
     },
     OnMessageArrival: function ( res ) {
         var channelid = $( res ).find( 'chatchannel' ).attr( 'id' );
         if ( $( '#chatmessages_' + channelid ).length == 0 ) {
             $( '#chatmessages' )[ 0 ].innerHTML += '<ol style="display:none" class="chatchannel" id="chatmessages_' + channelid + '"></ol>';
         }
         var history = $( '#chatmessages_' + channelid )[ 0 ];
         var messages = $( res ).find( 'discussion comment' );
         var text;
         var html = '';
         var li;
         var shoutid;

         for ( i = 0; i < messages.length; ++i ) {
             shoutid = $( messages[ i ] ).attr( 'id' );
             author = $( messages[ i ] ).find( 'author name' ).text();
             // alert( shoutid );
             if ( document.getElementById( shoutid ) ) {
                 // message has already been received
                 continue;
             }
             if ( author == User ) {
                 continue; // don't display my own messages; they've already been added by the SendMessage function
             }
             text = innerxml( $( messages[ i ] ).find( 'text' )[ 0 ] );
             li = document.createElement( 'li' );
             li.id = shoutid;
             li.innerHTML = '<strong>' + author + '</strong> <span class="text">' + text + '</span></li>'; 
             history.appendChild( li );
         }
         if ( Chat.CurrentChannel == channelid ) {
             li.scrollIntoView();
         }
         else {
             var userid, cid, found;
             found = false;
             for ( userid in Chat.ChannelByUserId ) {
                 cid = Chat.ChannelByUserId[ userid ];
                 if ( cid == channelid ) {
                     found = true;
                     Chat.Flash( userid, text );
                     break;
                 }
             }
             if ( !found ) {
                 $.get( '', {}, function ( res ) {
                     $( res ).find( 'user' )
                 } );
             }
         }
     },
     Flash: function ( userid, message ) {
         // TODO: Multiple participants
         if ( $( '#u' + userid ).hasClass( 'flash' ) ) {
             return;
         }
         $( '#u' + userid ).addClass( 'flash' ).html(
            '<span class="username">' + $( '#u' + userid ).text() + '</span>'
            + '<span class="text">' + message + '</span>'
         );
     },
     Unflash: function ( userid ) {
         if ( !$( '#u' + userid ).hasClass( 'flash' ) ) {
             return;
         }
         $( '#u' + userid ).removeClass( 'flash' );
         var uname = $( '#u' + userid + ' .username' ).text();
         $( '#u' + userid ).text( uname );
     },
     Join: function ( channelid ) {
         // Listen to push messages here
         Comet.Subscribe( 'chat/messages/list/' + channelid, Chat.OnMessageArrival );
         Comet.Subscribe( 'chat/typing/list/' + channelid, Chat.OnMessageArrival );
     },
     NowLoading: function () {
         document.body.style.cursor = 'wait';
         $( '.chatchannel' ).hide();
         $( '.textmessages .loading' ).show();
     },
     DoneLoading: function () {
         document.body.style.cursor = 'default';
         $( '.textmessages .loading' ).hide();
     },
     // switch to a channel given a userid; if not loaded, it will load it
     ShowPrivate: function ( userid ) {
         var channelid;
         if ( typeof Chat.ChannelByUserId[ userid ] == 'undefined' ) {
             Chat.NowLoading();
             $.get(
                'chat/messages', {
                    channelid: 0,
                    userid: userid
                },
                function ( res ) {
                    channelid = $( res ).find( 'chatchannel' ).attr( 'id' );
                    Chat.ChannelByUserId[ userid ] = channelid;
                    Chat.HistoryFromXML( res );
                    Chat.ChannelsLoaded[ channelid ] = true;
                    Chat.DisplayChannel( channelid );
                    Chat.DoneLoading();
                }, 'xml'
             );
         }
         else {
             channelid = Chat.ChannelByUserId[ userid ];
             Chat.DisplayChannel( channelid );
         }
     },
     // switches to given channel; loads it if not yet lo
     Show: function ( channelid ) {
         if ( typeof Chat.ChannelsLoaded[ channelid ] == 'undefined' ) {
             Chat.NowLoading();
             Chat.LoadHistory( channelid, function () {
                 Chat.ChannelsLoaded[ channelid ] = true;
                 Chat.DisplayChannel( channelid );
                 Chat.DoneLoading();
             } );
         }
         else {
             Chat.DisplayChannel( channelid );
         }
     },
     // switch to an already loaded channel
     DisplayChannel: function ( channelid ) {
         $( '.chatchannel' ).hide();
         $( '#chatmessages_' + channelid ).show();
         if ( $(' #chatmessages_' + channelid + ' li' ).length ) {
             $( '#chatmessages_' + channelid )[ 0 ].lastChild.scrollIntoView();
         }
         Chat.CurrentChannel = channelid;
     },
     // hide/show the chat application
     Toggle: function () {
         if ( !Chat.Inited ) {
             Chat.Init();
         }
         if ( Chat.Visible ) {
             $( '.col2 > div' ).show(); 
             $( '#chat' ).hide();
         }
         else {
             $( '.col2 > div' ).hide();
             $( '#chat' ).show();
             Chat.GetOnline();
         }
         Chat.Visible = !Chat.Visible;
     }
};

