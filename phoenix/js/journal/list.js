var JournalList = {
	AddFav : function( journalid , linknode ) {
		/*
		do not add this function with jquery, as a parameter is needed according to the journal 
		that needs to be faved. Maybe a user id is also needed to fav a journal
		*/
		if ( $( linknode ).hasClass( 'add' ) ) {
			$( linknode ).animate( { opacity: "0" } , 800 , function() {
				$( linknode ).attr( {
					href : '',
					title : 'Είναι αγαπημένο'
				} )
				.removeClass( 'add' )
				.addClass( 'isadded' )
				.animate( { opacity: "1" } , 800 )
			});
			alert( journalid );
			alert( Types.Journal );
			Coala.Warm( 'favourites/add' , { itemid : journalid , typeid : Types.Journal } );
		}
		
		//make Coala call
	}
};