<?php
    class ElementAdManagerDemographics extends Element {
        public function Render( tInteger $adid, tBoolean $canskip ) {
            global $libs;
			global $page;
            global $user;
            
            $libs->Load( 'admanager' );
            $libs->Load( 'place' );
            $libs->Load( 'rabbit/helpers/http' );
            
            $page->AttachInlineScript( 'AdManager.Demographics.OnLoad();' );
			$page->AttachInlineScript( 'AdManager.Demographics.OnLoad();' );
			
            $adid = $adid->Get();
            $canskip = $canskip->Get();
            
            $ad = New Ad( $adid );
            
            if ( !$ad->Exists() ) {
                return Redirect( '?p=ads&error=notexist' );
            }
            if ( !$user->Exists() || $user->Id != $ad->Userid ) {
                return Redirect( '?p=ads&error=notowner' );
            }
            
            ?><div class="buyad">
                <h2 class="ad">Διαφήμιση στο Zino</h2>
                <form action="do/admanager/demographics" method="post">
                <input type="hidden" value="<?php
                echo $adid;
                ?>" name="adid" />
                <div class="create demographics">
                    <h3>Επιλέξτε target group</h3>
                    <div class="left" style="width:400px;padding-left:50px">
                        <div class="input" style="float:left">
                            <label>Φύλο:</label>
                            <select name="sex" id="sex"><?php
                                $genders = array(
                                    0 => 'Αδιάφορο',
                                    1 => 'Άνδρες',
                                    2 => 'Γυναίκες'
                                );
                                
                                foreach ( $genders as $value => $gender ) {
                                    ?><option value="<?php
                                    echo $value;
                                    ?>"<?php
                                    if ( $ad->Sex == $value ) {
                                        ?> selected="selected"<?php
                                    }
                                    ?>><?php
                                    echo $gender;
                                    ?></option><?php
                                }
                                ?>
                            </select>
                        </div>

                        <div class="input" style="margin-left: 230px">
                            <label>Ηλικία:</label>
                            <div>Από: <select name="minage" id="minage">
                                <option value="0"<?php
                                if ( $ad->Minage == 0 ) {
                                    ?> selected="selected"<?php
                                }
                                ?>>Αδιάφορο</option><?php
                                    for ( $i = 13; $i <= 64; ++$i ) {
                                        ?><option value="<?php
                                        echo $i;
                                        ?>"<?php
                                        if ( $ad->Minage == $i ) {
                                            ?> selected="selected"<?php
                                        }
                                        ?>><?php
                                        echo $i;
                                        ?></option><?php
                                    }
                                ?>
                            </select></div>
                            <div>Έως: <select name="maxage" id="maxage">
                                <option value="0"<?php
                                if ( $ad->Maxage == 0 ) {
                                    ?> selected="selected"<?php
                                }
                                ?>>Αδιάφορο</option><?php
                                    for ( $i = 14; $i <= 65; ++$i ) {
                                        ?><option value="<?php
                                        echo $i;
                                        ?>"<?php
                                        if ( $ad->Maxage == $i ) {
                                            ?> selected="selected"<?php
                                        }
                                        ?>><?php
                                        echo $i;
                                        ?></option><?php
                                    }
                                ?>
                            </select></div>
                        </div>
                        
                        <?php
                        $placefinder = New PlaceFinder();
                        $places = $placefinder->FindAll();
                        ?>
                        <div class="input">
                            <label>Περιοχή:</label>
                            <select name="place" id="place">
                                <option value="0" selected="selected">Αδιάφορο</option>
                                <?php
									$i = 0;
                                    foreach ( $places as $place ) {
                                        ?><option value="<?php
                                        echo $place->Id;
                                        ?>"><?php
                                        echo htmlspecialchars( $place->Name );
                                        ?></option><?php
                                    }
                                ?>
                            </select>
                        </div>
                        <p id="target" style="margin-top:30px">Στοχεύετε σε όλο το εύρος ατόμων</p>
                        <input type="hidden" name="adcreationphase" value="<?php
                        if ( $canskip ) {
                            ?>yes<?php
                        }
                        else {
                            ?>no<?php
                        }
                        ?>" />
                        <input type="submit" class="submit" value="Αποθήκευση" style="margin-top:10px" />
                        <?php
                        if ( $canskip ) {
                            ?><a href="?p=admanager/checkout&amp;adid=<?php
                            echo $adid;
                            ?>" class="skip" onclick="return false;">ή παραλείψτε αυτό το βήμα</a><?php
                        }
                        ?>
                    </div>
                    <div class="right">
                        <div class="ads"><?php
                            Element( 'admanager/view', $ad, false );
                        ?></div>
                    </div>
                    <div class="eof"></div>
                </div>
                </form>
            </div><?php
        }
    }
?>
