var JournalView = {
	Edit : function( journalid ) {
		
	},
	Delete : function( journalid ) {
		if ( confirm( "������ ������� �� ���������� ��� ����������;" ) ){
			document.body.style.cursor = 'wait';
			Coala.Warm( 'journal/delete' , { journalid : journalid } );
		}
	}
};