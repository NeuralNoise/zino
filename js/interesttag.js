var InterestTag = {
    Submit: function( e ) {
        if ( !e ) {
            e = window.event;
        }
        if ( e.keyCode == 13 ) {
            var inp = g( 'newinteresttag' );
            var val = inp.value;
            if ( val.length === 0 || val.indexOf( ',' ) != -1 || val.indexOf( ' ' ) != -1 ) {
            	alert( "Δεν μπορείς να δημιουργήσεις κενό ενδιαφέρον ή να χρησιμοποιήσεις κόμμα (,) ή κενά" );
            	return;
            }
            Coala.Warm( 'interesttag/new', { 'text': val, 'callback' : InterestTag.SubmitCallback } );
        }
    },
    SubmitCallback : function( val ) {
    	var inp = g( 'newinteresttag' );
    	inp.parentNode.insertBefore( d.createTextNode( val + " " ), inp );
        inp.value = '';
        inp.focus();
    },
    Create : function() {
    	var div = d.createElement( 'div' );
    	var allinterests = g( 'interests' ).firstChild.nodeValue;
    	allinterests = allinterest.split( " " );
    	for ( var i in allinterests ) {
		}
    }
};
