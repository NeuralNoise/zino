var Uni = {
	Create : function() {
		var unitext = document.getElementById( 'uniname' );
		var unilist = document.getElementById( 'unilist' );
		if ( unitext.value !== '' ) {
			var newuni = document.createElement( 'div' );
			var unitype = document.getElementById( 'uniaei' );
			var uniplace = document.getElementById( 'uni_area' );
			var placeid = uniplace.value;
			var typeid;
			newuni.appendChild( document.createTextNode( unitext.value ) );
			if ( unitype.checked ) {
				typeid = 0;
				newuni.appendChild( document.createTextNode( " - ΑΕΙ" ) );
			}
			else {
				typeid = 1;
				newuni.appendChild( document.createTextNode( " - ΤΕΙ" ) );
			}
			newuni.appendChild( document.createTextNode( " - " + document.getElementById( placeid ).innerHTML ) );
			unilist.appendChild( newuni );
			Coala.Warm( 'universities/create' , { uniname : unitext.value , typeid : typeid , placeid : placeid } );
		}
		else {
			alert( 'Δώσε ένα έγκυρο όνομα πανεπιστημίου' );
		}
		unitext.focus();
		unitext.select();
	},
	Edit : function( uniid ) {
		area = document.getElementById( 'testmodaluni' ).cloneNode( true );
		area.style.display = '';
		var inputlist = area.getElementsByTagName( 'input' );
		var selectlist = area.getElementsByTagName( 'select' );
		var uniname = document.getElementById( 'name' + uniid ).innerHTML;
		var unitypeid = document.getElementById( 'type' + uniid ).innerHTML;
		//var uniplaceid = document.getElementById( 'place' + uniid ).innerHTML;
		inputlist[ 0 ].value = uniname;
		if ( unitypeid == 0 ) {
			inputlist[ 1 ].checked = true;
		}
		else {
			inputlist[ 2 ].checked = true;
		}
		var inputcreate = document.createElement( 'input' );
		inputcreate.type = "submit";
		inputcreate.value = "Αποθήκευση";
		inputcreate.onclick = ( function( node , uniid ) {
			return function() {
				Uni.SaveEdit( node , uniid );
				return false;
			};
		})( area , uniid );
		area.appendChild( inputcreate );
		var inputcancel = document.createElement( 'input' );
		inputcancel.type = "submit";
		inputcancel.value = "Ακύρωση";
		inputcancel.onclick = ( function() {
			return function () {
				Modals.Destroy();
				return false;
			}
		});
		area.appendChild( inputcancel );
		Modals.Create( area, 400, 200 );
	}, 
	SaveEdit : function( modaldiv , uniid ) {
		var inputlist = modaldiv.getElementsByTagName( 'input' );
		var selectlist = modaldiv.getElementsByTagName( 'select' );
		var uniname = inputlist[ 0 ].value;
		if ( uniname === '' ) {
			alert( 'Πρέπει να δώσεις ένα έγκυρο όνομα' );
			return;
		}
		var typeid;
		if ( inputlist[ 1 ].checked ) {
			typeid = 0;
		}
		else {
			typeid = 1;
		}
		var uniplaceid = selectlist[ 0 ].value;
		var unidiv = document.getElementById( 'uni' + uniid );
		unidiv.appendChild( document.createTextNode( '  Αποθηκεύτηκε' ) );
		Coala.Warm( 'universities/edit' , { uniid : uniid , uniname : uniname , unitypeid : typeid , uniplaceid : uniplaceid } );
		Modals.Destroy();
	},
	Delete : function( uniid ) {
		if ( confirm( 'Θέλεις σίγουρα να διαγράψεις αυτό το πανεπιστήμιο;' ) ) {
			var unidiv = document.getElementById( 'uni' + uniid );
			unidiv.parentNode.removeChild( unidiv );
			Coala.Warm( 'universities/delete' , { uniid : uniid } );
		}
	},
	SetUni : function() {
		var modaluni = document.getElementById( 'testmodaluni' ).cloneNode( true );
		modaluni.style.display = '';
		modaluni.id = 'modaluni';
		Modals.Create( modaluni , 220 , 200 );
	
	},
	CreateUniList : function () {
		var modaluni = document.getElementById( 'modaluni' );
		var selectlist = modaluni.getElementsByTagName( 'select' );
		var firstselect = selectlist[ 0 ];
		if ( firstselect.value == 0 ) {
			alert( 'Παρακαλώ επέλεξε μια έγκυρη τοποθεσία' );
			return;
		}
		Coala.Cold( 'universities/profilelist' , { townid : firstselect.value } );
	},
	SaveUni : function() {
		var modaluni = document.getElementById( 'modaluni' );
		var modaluniselectlist = modaluni.getElementsByTagName( 'select' );
		var uniid = modaluniselectlist[ 1 ].value;
		if ( uniid == 0 ) {
			alert( 'Παρακαλώ επέλεξε ένα έγκυρο ίδρυμα' );
			return;
		}
		Coala.Warm( 'universities/set' , { uniid : uniid } );
	}
};