var PhotoListing = {
    Initialized: false,
    PhotoList: null,
    PhotoPrototype: null,
    CurrentPage: 1,
    LastLoaded: null,
    Loading: false,
    Init: function(){
        this.PhotoList = $( '.photostream ul' );
        this.PlaceholderHTML = '';
        for( var i = 0; i < 100; ++i ){
            this.PlaceholderHTML += '<li><a><img /></a></li>';
        }
        this.LastLoaded = $( '.photostream ul li:last' )[ 0 ];
        this.AssignEvents();
        this.Initialized = true;
    },
    ScrollHandler: function(){
        if( PhotoListing.PhotoList.height() - $( window ).scrollTop() - $( window ).height() < 500 ){
            PhotoListing.FetchNewPhotos()
        }
    },
    AssignEvents: function(){
        $( window ).bind( 'scroll', PhotoListing.ScrollHandler );
    },
    RemoveEvents: function(){
        $( window ).unbind( 'scroll', PhotoListing.ScrollHandler );
    },
    FetchNewPhotos: function(){
        if( PhotoListing.Loading ){
            return;
        }
        PhotoListing.Loading = true;
        PhotoListing.RemoveEvents();
        PhotoListing.PhotoList[ 0 ].innerHTML += PhotoListing.PlaceholderHTML;
        PhotoListing.LastLoaded = $( '.photostream ul li')[ PhotoListing.CurrentPage * 100 - 1];
        PhotoListing.CurrentPage++;
        $.get( 'photos',
        { 'page': PhotoListing.CurrentPage },
        function( xml ){
            $( xml ).find( 'entry' ).each( function(){
                var id = $( this ).attr( 'id' );
                var url = $( this ).find( 'media' ).attr( 'url' );
                var count = $( this ).find( 'discussion' ).attr( 'count' );
                do {
                    PhotoListing.LastLoaded = PhotoListing.LastLoaded.nextSibling;
                } while ( PhotoListing.LastLoaded.nodeType != 1);

                if ( url ) {
                    $( 'img', $( PhotoListing.LastLoaded ) ).attr( 'src', url );
                }
                else {
                    alert( id );
                }
                $( 'a', $( PhotoListing.LastLoaded ) ).attr( 'href', 'photos/' + id );
                if( count != 0 ){
                    $( 'a', $( PhotoListing.LastLoaded ) ).append( $( '<span class="countbubble">' + count + '</span>' ) );
                }
            } );
            PhotoListing.Loading = false;
            PhotoListing.AssignEvents();
            PhotoListing.ScrollHandler();
        } );
    }
};