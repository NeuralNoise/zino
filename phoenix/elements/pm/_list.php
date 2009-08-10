<?php
    class ElementPmList extends Element {
        public function Render() {
            global $page;
            global $water;
            global $libs;
            global $user;
            global $rabbit_settings;

            if ( !$user->Exists() ) {
                return;
            }

            $libs->Load( 'pm/pm' );
            $libs->Load( 'user/count' );
            
            $page->SetTitle( 'Προσωπικά μηνύματα' );
            
            $finder = New PMFolderFinder();
            $folders = $finder->FindByUser( $user );
            $unreadCount = $user->Count->Unreadpms;

            $folder_dump = array();
            foreach ( $folders as $folder ) {
                $folder_dump[] = array( $folder->Userid, $folder->Name, $folder->Typeid );
            }
            ?><script type="text/javascript">
            var unreadpms = <?php
            echo $unreadCount;
            ?></script>
            <br /><br /><br /><br />
            <div id="pms">
            <div class="body">
                <div class="upper">
                    <span class="title">Μηνύματα</span>
                    <div class="subheading">Εισερχόμενα</div>
                </div>
                <div class="leftbar">
                    <div class="folders" id="folders"><?php
                        $inbox = false;
                        foreach ( $folders as $folder ) {
                            if ( $folder->Typeid == PMFOLDER_INBOX ) {
                                $inbox = $folder;
                            }
                            Element( 'pm/folder/link', $folder );
                        }

                        ?></div><br />
                    <a href="" class="folder_links newpm" onclick="return pms.NewMessage( '' , '' )"><span class="s1_0032">&nbsp;</span>Νέο μήνυμα</a><br />
                    <a href="" id="deletefolderlink" class="folder_links deletefolder" onclick="return false" style="display:none"><span class="s1_0029">&nbsp;</span>Διαγραφή φακέλου</a>
                    <a href="" id="renamefolderlink" class="folder_links renamefolder" onclick="return false" style="display:none"><span class="s1_0030">&nbsp;</span>Μετονομασία φακέλου</a>
                </div>
                <div class="rightbar" style="float:left">
                    <div class="messages" id="messages"><?php
                        Element( 'pm/folder/view', $inbox );
                    ?></div>
                </div>
                <div style="clear:left"></div>
                <div class="newfoldermodal" id="newfoldermodal" style="display:none">
                    Δώσε ένα όνομα για τον φάκελό σου<br /><br />
                    <form id="newfolderform" onsubmit="pms.CreateNewFolder( this );return false" action="" method="">
                        <input type="textbox" style="width:130px" /> 
                        <a href="" class="s1_0065" onclick="pms.CreateNewFolder( this.parentNode );return false">&nbsp;</a>
                        <a href="" class="s1_0066" onclick="pms.CancelNewFolder();return false">&nbsp;</a>
                    </form>
                </div>
            </div>
            </div><?php
            $page->AttachInlineScript( 'pms.OnLoad();' );
        }
    }
?>
