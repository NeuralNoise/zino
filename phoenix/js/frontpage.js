var Frontpage = {
	Closenewuser : function ( node ) {
		$( 'div.frontpage div.ybubble' ).animate( { height : '0'} , 800 , function() {
			$( this ).remove();
		} );
	},
	Showunis : function( node ) {
		var divlist = node.getElementsByTagName( 'div' );
		var contenthtml = "<span style=\"padding-left:5px;\">������������:</span><select><option value=\"0\" selected=\"selected\">-</option><option value=\"2\">���������</option><option value=\"6\">������������ ��������� &amp; ��������� �����������</option><option value=\"9\">�������</option><option value=\"23\">�����������</option><option value=\"25\">���������</option><option value=\"43\">��������</option><option value=\"35\">�����������</option><option value=\"67\">��������� �����������</option><option value=\"98\">�������������</option></select>";
		var newdiv = document.createElement( 'div' );
		newdiv.innerHTML = contenthtml;
		node.insertBefore( newdiv, divlist[ 0 ].nextSibling );
	},
	DeleteShout : function() {
	
	}
};
$( document ).ready( function() {
	if ( $( 'div.frontpage div.inshoutbox' )[ 0 ] ) {
		$( 'div.frontpage div.inshoutbox div.shoutbox div.comments div.newcomment div.bottom input' ).click( function() {
			alert( $( 'div.frontpage div.inshoutbox div.shoutbox div.comments div.newcomment div.text textarea' )[ 0 ].innerHTML );
		} );
	}
} );