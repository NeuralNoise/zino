var Uni = {
	Create : function() {
		var unitext = document.getElementById( 'uniname' );
		var unilist = document.getElementById( 'unilist' );
		alert( unitext.value );
		if ( unitext.value != '' ) {
			var newuni = document.createElement( 'div' );
			var unitype = document.getElementById( 'uniaei' );
			newuni.appendChild( document.createTextNode( unitext.value ) );
			if ( unitype.checked ) {
				newuni.appendChild( document.createTextNode( " - ���" ) );
			}
			else {
				newuni.appendChild( document.createTextNode( " - ���" ) );
			}
			
			unilist.appendChild( newuni );
		}
		else {
			alert( '���� ��� ������ ����� �������������' );
		}	
	}
}