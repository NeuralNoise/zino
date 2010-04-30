var News = {
    Prepare: function( collection ) {
        $( collection ).click( 
            function() { 
                var id = $( this ).attr( 'id' ).split( '_' );
                return News.Preview.call( this, id[1], id[0] );
            } );
    },
    Preview: function( itemid, type ) {
        if ( $( this ).hasClass( 'previewing' ) ) {
            return true;
        }
        $( this ).addClass( 'previewing' ).siblings().removeClass( 'previewing' );
        var infotext = $( '<span />' )
            .text( 'Παρακαλώ περιμένετε' )
            .addClass( 'infotext' )
            .fadeIn( 1000 );
        $( '#preview .content' ).empty().append( infotext );
        infotext.center();
        
        var data = $.get( type + 's/' + itemid, { 'preview': 'yes' } );
        axslt( data, '/social/entry', function() {
            $( '#preview .content' ).empty().append( $( this ).filter( '.contentitem' ) );
        } );
        return false;
    },
    Init: function() {
        $( '#preview .infotext' ).center();
        News.Prepare( $( '.feed li' ) );
    }
}