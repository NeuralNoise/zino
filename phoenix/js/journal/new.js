$( document ).ready( function() {
    if ( $( '#journalnew' ) ) {
        WYSIWYG.Create( document.getElementById( 'wysiwyg' ), 'text', [
            {
                'tooltip': '������ �����',
                'image': 'http://static.zino.gr/phoenix/text_bold.png',
                'command': 'bold'
            },
            {
                'tooltip': '������ �����',
                'image': 'http://static.zino.gr/phoenix/text_italic.png',
                'command': 'italic'
            },
            {
                'tooltip': '�����������',
                'image': 'http://static.zino.gr/phoenix/text_underline.png',
                'command': 'underline'
            },
            {
                'tooltip': '�������� �������',
                'image': 'http://static.zino.gr/phoenix/picture.png'
            },
            {
                'tooltip': '�������� Video',
                'image': 'http://static.zino.gr/phoenix/television.png'
            }
        ] );
    }
} );
