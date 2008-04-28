var pms = {
	unreadpms : 0,
	activefolder : 0,
	node : 0,
	activepm : 0,
	pmsinfolder : 0,
	messagescontainer : $( '#messages' )[ 0 ],
	writingnewpm : false,
	ShowFolder : function( folder , folderid ) {
		if ( pms.activefolder === 0 ) {
			pms.node = $( '#firstfolder' )[ 0 ];
			pms.activefolder = pms.node;
		}
		if ( pms.activefolder != pms.node ) {
			pms.activefolder.className = 'folder top';
		}
		else {
			pms.activefolder.className = 'folder';
		}
		if ( folder != pms.node ) {
			folder.className = 'activefolder top';
		}
		else {
			folder.className = 'activefolder';
		}
		pms.activefolder = folder;
		Coala.Cold( 'pm/showfolder' , { folderid : folderid } , function( errcode ) {
			alert( 'Coala error: ' + errcode );
		} );	
	}
	,
	ShowFolderPm : function( folder , folderid ) {
		//this function uses the ShowFolder function to show the contents of a folder using a little animation
		pms.activepm = 0;
		pms.writingnewpm = false;
		pms.ShowAnimation( 'Παρακαλώ περιμένετε...' );
		pms.ShowFolder( folder , folderid );
	}
	,
	ExpandPm : function( pmdiv , notread , pmid ) {
		//the function is responsible for expanding and minimizing pms, allowing only one expanded pm
		//notread is true when the pm hasn't been read else it is true
		var messagesdivdivs = $( '#pm_' + pmid + ' div')[ 0 ];
		var textpm = $( '#pm_' + pmid + ' div.text' )[ 0 ];
		var lowerlinepm = $( '#pm_' + pmid + ' div.lowerline' )[ 0 ];
		$( textpm ).toggle();
		$( lowerlinepm ).toggle();
		
		pms.activepm = pmdiv;
		if ( notread ) {
			//remove the unread icon
			var unreadicon = $( '#pm_' + pmid + ' div.infobar img' )[ 1 ];
			Coala.Warm( 'pm/expandpm' , { pmid : pmid } );
			if ( unreadicon ) {
				pms.UpdateUnreadPms( - 1 );
				$( unreadicon ).animate( { opacity: "0" , width: "0" } , 800 , function() {
					$( unreadicon ).remove();
				});
			}
		}
	}
	,
	NewFolder : function() {
		//showing modal dialog for new folder name
		var newfolderdiv = $( '#newfolderlink' )[ 0 ];
		var newfoldermodal = document.getElementById( 'newfoldermodal' ).cloneNode( true );
		$( newfoldermodal ).show();
		newfoldermodalinput = newfoldermodal.getElementsByTagName( 'input' );
		textbox = newfoldermodalinput[ 0 ];
		Modals.Create( newfoldermodal , 250 , 80 );
		textbox.focus();
		textbox.select();
		$( newfolderdiv ).css( "background-color" , "#e1e9f2" );
		var newfolderdivlinks = newfolderdiv.getElementsByTagName( 'a' );
		var newfolderlink = newfolderdivlinks[ 0 ];
		$( newfolderlink ).css( "color" , "#aaa8a8" ).css( "font-weight" , "bold" );
		if ( pms.activefolder === 0 ) {
			pms.node = $( '#firstfolder' )[ 0 ];
			pms.activefolder = pms.node;
		}
		if ( pms.activefolder != pms.node ) {
			pms.activefolder.className = 'folder top';
		}
		else {
			pms.activefolder.className = 'folder';
		}
	}
	,
	CancelNewFolder : function () {
		if ( pms.activefolder === 0 ) {
			pms.node = $( '#firstfolder' )[ 0 ];
			pms.activefolder = pms.node;
		}
		if ( pms.activefolder != pms.node ) {
			pms.activefolder.className = 'activefolder top';
		}
		else {
			pms.activefolder.className = 'activefolder';
		}
		$( '#newfolderlink' ).css( "background-color" , "#ffffff" );
		$( $( '#newfolderlink a' )[ 0 ] ).css( "color" , "#d0cfcf" ).css( "font-weight" , "normal" );
		Modals.Destroy();
	}
	,
    ValidFolderName : function ( text ) {
		var name = text.replace(/(\s+$)|(^\s+)/g , '');
		if ( name == 'Εισερχόμενα' || name == 'Απεσταλμένα' ) {
            return false;
		}
		else if ( name.length <= 2 ) {
            return false;
		}
		else if ( name === '' ) {
            return false;
		}
        return true;
    }
    ,
	CreateNewFolder : function ( formnode ) {
		//creating a new folder and showing it (using a coala call)
		var formnodeinput = formnode.getElementsByTagName( 'input' );
		inputbox = formnodeinput[ 0 ];
		var foldername = inputbox.value;
		if ( !pms.ValidFolderName( foldername ) ) {
			alert( 'Δεν μπορείς να ονομάσεις έτσι τον φάκελό σου' );
			inputbox.select();
            return;
		}
        pms.ShowAnimation( 'Δημιουργία φακέλου...' );
        Coala.Warm( 'pm/makefolder' , { foldername : foldername } );
	}
	,
	DeleteFolder : function( folderid ) {
		//the function for deleting a pm folder
		Modals.Confirm( 'Θέλεις σίγουρα να σβήσεις τον φάκελο;' , function () {
			$( '#folder_' + folderid ).animate( { opacity : '0' , height : '0' } , function() {
				$( this ).remove();
				if ( !pms.writingnewpm ) {
					pms.ShowFolderPm( $( '#firstfolder' )[ 0 ] , -1 );
				}
			} );
			Coala.Warm( 'pm/deletefolder' , { folderid : folderid } );
		} );
	}
	,
    RenameFolder : function ( folderid ) {
        var name = prompt( 'Πληκτρολόγησε ένα νέο όνομα για τον φάκελό σου' );
        if ( name === null ) {
            return;
        }
        if ( !pms.ValidFolderName( name ) ) {
            alert( 'Δεν μπορείς να ονομάσεις έτσι τον φάκελό σου' );
            return;
        }
        Coala.Warm( 'pm/folder/rename', {
            'folderid': folderid,
            'newname': name
        } );
        $( '#folder_' + folderid + ' a.folderlinks' ).html( name );
    }
    ,
	NewMessage : function( touser , answertext ) {
		pms.ClearMessages();
		var receiversdiv = document.createElement( 'div' );
		var receiversinput = document.createElement( 'input' );
		receiversinput.type = 'text';
		$( receiversinput ).css( "width" , "250px" ).css( "color" , "#9d9d9d" );
		if ( touser !== '' ) {
			receiversinput.value = touser;
		}
		pms.messagescontainer.appendChild( receiversdiv );
		if ( answertext !== '' ) {
			var textmargin = document.createElement( 'div' );
			$( textmargin ).css( "border" , "1px dotted #b9b8b8" ).css( "padding" , "4px" ).css( "color" , "#767676" ).css( "width" , "550px" );
			$( textmargin ).append( document.createTextNode( answertext ) );
			$( pms.messagescontainer ).append( textmargin ).append( document.createElement( 'br' ) ).append( document.createElement( 'br' ) );
		}
		var receiverstext = document.createElement( 'span' );
		$( receiverstext ).css( "padding-right" , "30px" );
		receiverstext.appendChild( document.createTextNode( 'Παραλήπτες' ) );
		$( receiverstext ).css( "font-weight" , "bold" );
		$( receiversdiv ).append( receiverstext ).append( receiversinput ).append( document.createElement( 'br' ) ).append( document.createElement( 'br' ) ); 
		var pmtext = document.createElement( 'textarea' );
		$( pmtext ).css( "width" , "550px" ).css( "height" , "330px" );
		var sendbutton = document.createElement( 'input' );
		$( sendbutton ).attr( { type : 'button' , value : 'Αποστολή' } );
		$( sendbutton ).click( function() {	
			pms.SendPm();
		});
		var cancelbutton = document.createElement( 'input' );
		$( cancelbutton ).attr( { type : 'button' , value : 'Επαναφορά' } );
		$( cancelbutton ).click( function() {
			receiversinput.value = '';
			pmtext.value = '';
		});
		var actions = document.createElement( 'div' );
		$( actions ).append( sendbutton ).append( cancelbutton );
		$( pms.messagescontainer ).append ( pmtext ).append( document.createElement( 'br' ) ).append( document.createElement( 'br' ) ).append( actions );
		pms.ShowFolderNameTop( 'Νέο μήνυμα' );
		receiversinput.focus();
		receiversinput.select();
		pms.writingnewpm = true;
	}
	,
	SendPm : function() {
		//responsible for sending the pm to the specified user or users
		var messagesdivinputlist = pms.messagescontainer.getElementsByTagName( 'input' );
		var receiverslist = messagesdivinputlist[ 0 ];
		var messagesdivtextarealist = pms.messagescontainer.getElementsByTagName( 'textarea' );
		var pmtext = messagesdivtextarealist[ 0 ];
		pms.ShowAnimation( 'Αποστολή μηνύματος...' );
		Coala.Warm( 'pm/sendpm' , { usernames : receiverslist.value , pmtext : pmtext.value } );
	}
	,
	DeletePm : function( msgnode , pmid , read ) {
		Modals.Confirm( 'Θέλεις σίγουρα να διαγράψεις το μήνυμα;' , function() {
			pms.activepms = 0;
			var delimg2 = $( '#pm_' + pmid + ' img' )[ 1 ];
			$( '#pm_' + pmid + ' div.lowerline' ).hide();
			$( $( '#pm_' + pmid + ' img' )[ 0 ] ).hide();
			if ( delimg2 ) {
				//if the message is already read there is no such image
				$( delimg2 ).hide();
			}
			$( msgnode ).hide( 700 , function() {
				$( this ).remove();
			} );
			//check whether the msg is read or not, if it in unread only then execute the next function : TODO
			if ( !read ) {
				pms.UpdateUnreadPms( -1 );
			}
			pms.pmsinfolder--;
			pms.WriteNoPms();
			Coala.Warm( 'pm/deletepm' , { pmid : pmid } );
		} );
		
	},
	UpdateUnreadPms : function( specnumber ) {
		//reduces the number of unread messages by one
		//if specnumber is - 1 the unread pms number is reduced by one, else the specnumber is used as the number for the unread msgs
		var unreadmsgbanner = $( '#messagesunread' )[ 0 ];
		var incomingdiv = $( '#firstfolder' )[ 0 ];
		var incominglink = incomingdiv.firstChild;
		var newtext;
		var newtext2;
		$( incominglink.firstChild ).remove();
		$( unreadmsgbanner.firstChild ).remove();
		if ( unreadpms > 1 ) {
			if ( specnumber == -1 ) {
				--unreadpms;
				newtext = document.createTextNode( 'Εισερχόμενα (' + unreadpms + ')' );
				if ( unreadpms == 1 ) {
					newtext2 = document.createTextNode( '1 Νέο Μήνυμα' );
				}
				else {
					newtext2 = document.createTextNode( unreadpms + ' Νέα Μηνύματα' );
				}
			}
			else {
				newtext = document.createTextNode( 'Εισερχόμενα (' + specnumber + ')' );
				newtext2 = document.createTextNode( specnumber + ' Νέα Μηνύματα' );
			}
		}
		else {
			newtext2 = document.createElement( 'img' );
			$( newtext2 ).attr( { src : 'http://static.zino.gr/images/icons/email.png' , alt : 'Μηνύματα' } ).css( "width" , "16px" ).css( "height" , "16px" ).css( "vertical-align" , "bottom" );
			newtext = document.createTextNode( 'Εισερχόμενα' );
		}
		$( unreadmsgbanner ).append( newtext2 );
		$( incominglink ).append( newtext );
	}
	,
	ShowFolderNameTop : function( texttoshow ) {
		//showing the name of the folder in the right upper corner
		var messagesdivparent = pms.messagescontainer.parentNode.parentNode;
		var messagesdivdiv = messagesdivparent.getElementsByTagName( 'div' );
		var foldertext = messagesdivdiv[ 1 ];
		$( foldertext.firstChild ).remove();
		$( foldertext ).append( document.createTextNode( texttoshow ) );
	}
	,
	ShowAnimation : function( texttoshow ) {
		pms.ClearMessages();
		var loadinggif = document.createElement( 'img' );
		$( loadinggif ).attr( { src : 'http://static.zino.gr/images/ajax-loader.gif' , alt : texttoshow , title : texttoshow } );
		var loadingtext = document.createTextNode( ' ' + texttoshow );
		$( pms.messagescontainer ).append( loadinggif ).append( loadingtext );
	}
	,
	ClearMessages : function() {
		//clears the area where pms appear
		$( pms.messagescontainer ).empty();
	},
	WriteNoPms : function() {
		var messagescontainerdivlist = pms.messagescontainer.getElementsByTagName( 'div' );
		if ( messagescontainerdivlist.length / 12 == 1 ) {
			nopmsspan = document.createElement( 'span' );
			$( nopmsspan ).html( 'Δεν έχεις μηνύματα σε αυτό το φάκελο.<br />Μετακίνησε κάποια μηνύματα με το ποντίκι σε αυτό το φάκελο για να τα μεταφέρεις εδώ.' );
			$( pms.messagescontainer ).append( nopmsspan );
			$( nopmsspan ).animate( { opacity : "1" } , 2000 );
		}
	}
};
$( document ).ready( function() {
	$( 'div.message' ).draggable( { 
		helper : 'original',
		cursor : 'move'
	} );
	$( 'div.createdfolder' ).droppable( {
		accept: "div.message",
		hoverClass: "hoverfolder",
		tolerance: "pointer",
		drop : function(ev, ui) {
			//alert( 'pmid is ' + ui.draggable.attr( "id" ).substring( 3 ) + ' folderid: ' + $( this ).attr( "id" ).substring( 7 ) );
			Coala.Warm( 'pm/transfer' , { pmid : ui.draggable.attr( "id" ).substring( 3 ) , folderid : $( this ).attr( "id" ).substring( 7 ) } );
			ui.draggable.animate( { 
				opacity: "0",
				height: "0"
				} , 700 , function() {
					ui.draggable.remove();
			} );
		}
	} );
} );