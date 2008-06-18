<?php
    function UnitPmMakefolder( tString $foldername ) {
        global $user;
        global $libs;
        
        $libs->Load( 'pm/pm' );
        $foldername = $foldername->Get();
        $folder = new PMFolder();
        $folder->Name = $foldername;
        $folder->Userid = $user->Id;
        $folder->Save();
        $folderid = $folder->Id;
        $foldername = w_json_encode( $foldername );
        ?>var newfolderlink = document.getElementById( 'newfolderlink' );
        var newfolder = document.createElement( 'div' );
        $( newfolder ).attr( { id : '<?php
        echo $folderid;
        ?>' , alt : <?php
        echo $foldername;
        ?> , title : <?php
        echo $foldername;
        ?> } ).addClass( 'folder' ).addClass( 'top' );
        newfolder.id = 'folder_<?php
        echo $folderid;
        ?>';
        var newfolderhref = document.createElement( 'a' );
        $( newfolderhref ).attr( { href : '' } ).addClass( 'folderlinks' ).click( function( folder , folderid ) {
            pms.ShowFolderPm( newfolder , <?php
            echo $folderid;
            ?> );
        }).append( document.createTextNode( <?php
        echo $foldername;
        ?> ) );
        $( newfolder ).append( newfolderhref ).droppable( {
            accept: "div.message",
            hoverClass: "hoverfolder",
            tolerance: "pointer",
            drop: function(ev, ui) {
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
        newfolderlink.parentNode.insertBefore( newfolder , newfolderlink );
        pms.activefolder = newfolder;
        pms.CancelNewFolder();
        pms.ShowFolderPm( newfolder , <?php
        echo $folderid;
        ?> );<?php
    }
?>
