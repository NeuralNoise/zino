var Comet = {
    Channels: {},
    ChannelsLength: 0,
    HandshakeCompleted: false,
    Handshake: function () {
        channels = [];
        for ( channelid in Comet.Channels ) {
            channels.push( channelid );
        }
        $.post( 'tunnel/create', {
            channels: channels.join( "," )
        }, Comet.OnHandshakeCompleted, 'xml' );
    },
    OnHandshakeCompleted: function ( res ) {
        Comet.HandshakeCompleted = true;
        Comet.TunnelAuthtoken = $( res ).find( 'tunnel authtoken' ).text();
        Comet.TunnelId = $( res ).find( 'tunnel' ).attr( 'id' );
        Comet.Connect();
    },
    Init: function () {
        setTimeout( Comet.Handshake, 50 );
    },
    Connect: function () {
        $.get( '/subscribe?id=' + Comet.TunnelId, {}, Comet.OnFishArrival, 'text' );
    },
    OnFishArrival: function ( res ) {
        var xmlDoc;

        var a = res.split( "\n" );
        a.splice( 0, 3 );
        a.splice( a.length - 2, 2 );
        res = a.join( "\n" );

        if ( window.DOMParser ) {
            var parser = new DOMParser();
            xmlDoc = parser.parseFromString( res, "text/xml" );
        }
        else {
            xmlDoc = new ActiveXObject( "Microsoft.XMLDOM" );
            xmlDoc.async = 'false';
            xmlDoc.loadXML( res );
        }
        Comet.Connect(); // reconnect

        var channelid = $( xmlDoc ).find( 'channel' ).attr( 'id' );
        if ( typeof Comet.Channels[ channelid ] != 'undefined' ) {
            Comet.Channels[ channelid ]( $( xmlDoc ).find( 'channel' )[ 0 ] ); // fire callback
        }
    },
    Renew: function () {
        $.post( 'tunnel/update', {
            tunnelid: Comet.TunnelId,
            tunnelauthtoken: Comet.TunnelAuthtoken
        } );
    },
    Unsubscribe: function ( channelid ) {
        // TODO: remove channelid from Comet.Channels; call tunnel/update
    },
    Subscribe: function ( channelid, callback ) {
        Comet.Channels[ channelid ] = callback;
        // TODO: Call tunnel/update if we've already handshaked
    },
};
