var Friends = {
    Add: function( listitem ) {
        var already;
        var img;
        img = document.createElement( 'img' );
        img.src = 'file:///E:/work/kamibu/excalibur/phoenix/etc/mockups/friends/accept.png';
        img.alt = '�����';
        img.className = 'done';
        
        already = document.createElement( "span" );
        already.appendChild( img );
        already.appendChild( document.createTextNode( "�����" ) );
        already.className = "already";
        
        var anchor = $( "a.add", listitem ).unbind( "click" ).blur().fadeOut( 100, function() {
            anchor.removeClass( "add" ).addClass( "remove" ).contents()[ 0 ].nodeValue = "-";
            anchor.find( "span" ).contents()[ 0 ].nodeValue = "�������� �����";
            listitem.appendChild( already );
            $( anchor ).css( { 'z-index': 2 } );
            $( already ).hide().fadeIn( 300 );
            $( img ).css( { opacity: 0 } ).animate( {
                top: -1
            }, { queue: false, duration: 1000, easing: 'easeOutBounce' } ).animate( {
                opacity: 1
            }, { queue: false, duration: 800, easing: 'swing' } );
            setTimeout( function() {
                already.lastChild.nodeValue = '�����';
                $( already ).css( { 'z-index': 0 } ).find( 'img' ).fadeOut( 200, function() { $( this ).remove() } );
                $( anchor ).show().click( function() {
                    return Friends.Remove( $( this ).closest( "li" )[0] );
                } );
            }, 3000 );
        } );
        return false;
    }
    ,
    Remove: function( listitem ) {
        var already = $( "span.already", listitem )[0];
        var img;
        img = document.createElement( 'img' );
        img.src = 'file:///E:/work/kamibu/excalibur/phoenix/etc/mockups/friends/accept.png';
        img.alt = '�����';
        img.className = 'done';
        
        already.lastChild.nodeValue = "";
        already.appendChild( img );
        already.appendChild( document.createTextNode( "����������" ) );
        $( already ).hide();
        
        var anchor = $( "a.remove", listitem ).unbind( "click" ).blur().fadeOut( 100, function() {
            anchor.removeClass( "remove" ).addClass( "add" ).contents()[ 0 ].nodeValue = "+";
            anchor.find( "span" ).contents()[ 0 ].nodeValue = "���� �����";
            listitem.appendChild( already );
            $( already ).hide().fadeIn( 300 );
            $( img ).css( { opacity: 0 } ).animate( {
                top: -1
            }, { queue: false, duration: 1000, easing: 'easeOutBounce' } ).animate( {
                opacity: 1
            }, { queue: false, duration: 800, easing: 'swing' } );
            setTimeout( function() {
                $( already ).find( 'img' ).fadeOut( 100, function() { $( this ).remove() } );
                $( already ).fadeOut( 100, function() { $( this ).remove(); } );
                $( anchor ).fadeIn( 300 ).click( function() {
                    return Friends.Add( $( this ).closest( "li" )[0] );
                } );
            }, 3000 );
        } );
        return false;
    }
    ,
    Load: function() {
        $( "a.add" ).click( function() { return Friends.Add( $( this ).closest( "li" )[0] ); } );
        $( "a.remove" ).click( function() { return Friends.Remove( $( this ).closest( "li" )[0] ); } );
        Kamibu.ClickableTextbox( $( '#friends input' )[ 0 ], true, 'black', '#aaa', function () {} );
    }
}