<?php
    
    class ElementBanner extends Element {
        public function Render() {
            global $page;
            global $user;
            global $rabbit_settings;
            
            ?><div class="header" id="banner">
            <a href="http://oniz.zino.gr/journals/Xristougenniatiko_Zino_Meeting_sta_Iwannina" style="display:block;position:absolute;right:105px;top:0;z-index:10;width:120px;height:74px;background-image:url('http://static.zino.gr/phoenix/xmas4.png');background-position:right;background-repeat: no-repeat;" title="Χριστουγεννιάτικο Zino Meeting στα Ιωάννινα">&nbsp;</a>
            <h1><a href="<?php
            echo $rabbit_settings[ 'webaddress' ];
?>"><img src="<?php
            echo $rabbit_settings[ 'imagesurl' ];
            ?>zino.png" alt="Zino" /></a></h1>
            <a href="#content" class="novisual">Πλοήγηση στο περιεχόμενο</a>
            <?php   
                if ( !$user->Exists() ) {
                    ?><form action="do/user/login" method="post">
                        <ul>
                        <li><a href="join" class="register icon"><span>&nbsp;</span>Δημιούργησε λογαριασμό</a></li>
                        <li>·</li>
                        <li><a href="?#login" onclick="Banner.Login();return false" class="login icon"><span>&nbsp;</span>Είσοδος</a></li>
                        <li style="display:none">·</li>
                        <li style="display:none">Όνομα: <input type="text" name="username" /> Κωδικός: <input type="password" name="password" /></li>
                        <li style="display:none"><input type="submit" value="Είσοδος" class="button" /></li>
                        </ul>
                    </form><?php
                }
                else {
                    ?><ul>
                    <li title="Προβολή προφίλ"><a href="<?php
                    Element( 'user/url', $user->Id , $user->Subdomain );
                    ?>" class="profile"><?php
                    if ( $user->Avatar->Id > 0 ) {
                        Element( 'image/view', $user->Avatar->Id , $user->Id , $user->Avatar->Width , $user->Avatar->Height ,  IMAGE_CROPPED_100x100 , '' , $user->Name, '' , true , 16 , 16 , 0 );
                    }
                    Element( 'user/name', $user->Id , $user->Name , $user->Subdomain , false );
                    ?></a></li>
                    <li>·</li>
                    <li><a id="unreadmessages" href="messages" class="messages icon<?php
                    $unreadCount = $user->Count->Unreadpms;
                    if ( $unreadCount > 0 ) {
                        ?> unread<?php
                    }
                    ?>"><span>&nbsp;</span><?php
                        if ( $unreadCount > 0 ) {
                            echo $unreadCount;
                            ?> νέ<?php
                            if( $unreadCount == 1 ) {
                                ?>ο μήνυμα<?php  
                            }
                            else {
                                ?>α μηνύματα<?php
                            }
                        }
                        else {
                            ?>Μηνύματα<?php
                        }
                    ?></a></li>
                    <li>·</li>
                    <li><a href="settings" class="settings icon"><span>&nbsp;</span>Ρυθμίσεις</a></li>
                    </ul><?php
                }
            if ( $user->Exists() ) {
                ?><form method="post" action="do/user/logout"><a href="" onclick="this.parentNode.submit(); return false" class="logout">Έξοδος<span>&nbsp;</span></a></form><?php
            }
            ?>
            <a class="search" href="?p=search"><img src="<?php
                echo $rabbit_settings[ 'imagesurl' ];
            ?>glass.jpg" alt="Αναζήτηση" title="Αναζήτησε Φίλους!" /></a>
            <div class="eof"></div>
            </div><?php
        }
    }
?>
