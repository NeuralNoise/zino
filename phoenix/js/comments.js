var Comments = {
	Create : function() {
		var texter = $("div.newcomment div.text textarea").value;
		if ( texter === "" ) {
			alert( "Δε μπορείς να δημοσιεύσεις κενό μήνυμα" );
			return;
		}
		var showcomment = $("div.newcomment").clone( true );
		var a = document.createElement( 'a' );
		a.onclick = false;
		//TODO: who
		$( showcomment ).removeClass( "newcomment" ).find( "span.time" ).text( "πριν λίγο" ).end().find( "div.text textarea" ).remove().text( texter );
		$( showcomment ).find( "div.bottom" )[0].remove().append( a ).append( document.createTextNode( "Απάντα" ) ).end().insertAfter( "div.newcomment" );
	}
};
