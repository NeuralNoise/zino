var JournalView = {
	Edit : function( journalid ) {
		
	},
	Delete : function( journalid ) {
		$( 'div#journalview div.owner div.delete a' ).css( 'backgroundImage' , 'url( "' + ExcaliburSettings.imagesurl + 'delete.gif" )' );
		if ( confirm( "Θέλεις σίγουρα να διαγράψεις την καταχώρηση;" ) ){
			document.body.style.cursor = 'wait';
			Coala.Warm( 'journal/delete' , { journalid : journalid } );
		}
		//$( 'div#journalview div.owner div.delete a' ).css( 'background-image' , 'url( "' + ExcaliburSettings.imagesurl + 'delete2.gif" )' );
	}
};
$( document ).ready( function() {
	if ( $( 'div#journalnew' )[ 0 ] ) {
		$( 'div#journalnew form div.title input' )[ 0 ].select();
	}
} );