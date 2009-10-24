<?php
    class ElementUserSettingsSidebar extends Element {
        public function Render() {
            global $rabbit_settings;
            global $user;
            
            ?><ol id="settingslist">
                <li class="personal"><a href="" onclick="Settings.SwitchSettings( 'personal' );return false"><span class="s1_0036">&nbsp;</span>Πληροφορίες</a></li>
                <li class="characteristics"><a href="" onclick="Settings.SwitchSettings( 'characteristics' );return false"><span class="s1_0039">&nbsp;</span>Χαρακτηριστικά</a></li>
                <li class="interests"><a href="" onclick="Settings.SwitchSettings( 'interests' );return false"><span class="s1_0037">&nbsp;</span>Ενδιαφέροντα</a></li>
                <li class="contact"><a href="" onclick="Settings.SwitchSettings( 'contact' );return false"><span class="s1_0040">&nbsp;</span>Επικοινωνία</a></li>
                <li class="settings"><a href="" onclick="Settings.SwitchSettings( 'settings' );return false"><span class="s1_0038">&nbsp;</span>Λογαριασμός</a></li>
            </ol>
            <div class="savesettings">
                <div class="saving invisible">
                    <img src="<?php
                    echo $rabbit_settings[ 'imagesurl' ];
                    ?>ajax-loader.gif" alt="Αποθήκευση" /><span>Αποθήκευση... </span>
                </div>
                <div class="savebutton">
                    <a href="" class="button disabled">Αποθήκευση ρυθμίσεων</a>
                </div>
            </div>
            <div class="backtoprofile">
                <a href="<?php
                Element( 'user/url' , $user->Id , $user->Subdomain );
                ?>" class="button">Επιστροφή στο προφίλ</a>
            </div><?php
        }
    }
?>
