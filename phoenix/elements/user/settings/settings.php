<?php
    class ElementUserSettingsSettings extends Element {
        public function Render() {
            global $rabbit_settings;
            global $user; 
            
            ?><div class="email">
                <label>E-mail:</label>
                <div id="email">
                    <input type="text" name="email" class="small" value="<?php
                    echo htmlspecialchars( $user->Profile->Email );
                    ?>" />
                    <span>
                       <span class="s_invalid">&nbsp;</span>Το email δεν είναι έγκυρο
                    </span>
                    <div class="explanation">Το e-mail δεν εμφανίζεται στο προφίλ σου.</div>
                </div>
            </div>
            <div class="barfade emailbarfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
                <div class="changepwdl"><label><a class="changepwdlink" href="">Αλλαγή κωδικού πρόσβασης</a></label></div>
            <div class="barfade emailbarfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
                <div class="changepwdl"><label><a class="changepwdlink" href="">Διαγραφή λογαριασμού</a></label></div>
            <div class="barfade pwdbarfade">
                <div class="leftbar"></div>
                <div class="rightbar"></div>
            </div>
            <span class="notifyme">Να λαμβάνω ειδοποιήσεις</span>
            <div class="setting">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>Μέσω e-mail</th>
                            <th>Μέσα στο site</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Σχόλια στο προφίλ μου:</th>
                            <td><input id="emailprofilecomment" type="checkbox" <?php
                            if ( $user->Preferences->Emailprofilecomment == 'yes' ) {
                                ?>checked="checked"<?php
                            }
                            ?>/></td>
                            <td><input id="notifyprofilecomment" type="checkbox" <?php
                            if ( $user->Preferences->Notifyprofilecomment == 'yes' ) {
                                ?>checked="checked"<?php
                            }
                            ?>/></td>
                        </tr>
                        <tr>
                            <th>Σχόλια στις εικόνες μου:</th>
                            <td><input id="emailphotocomment" type="checkbox" <?php
                            if ( $user->Preferences->Emailphotocomment == 'yes' ) {
                                ?>checked="checked"<?php
                            }
                            ?>/></td>
                            <td><input id="notifyphotocomment" type="checkbox" <?php
                            if ( $user->Preferences->Notifyphotocomment == 'yes' ) {
                                ?>checked="checked"<?php
                            }
                            ?>/></td>
                        </tr>
                        <tr>
                            <th>Σχόλια στις δημοσκοπήσεις μου:</th>
                            <td><input id="emailpollcomment" type="checkbox" <?php
                            if ( $user->Preferences->Emailpollcomment == 'yes' ) {
                                ?>checked="checked"<?php
                            } 
                            ?>/></td>
                            <td><input id="notifypollcomment" type="checkbox" <?php
                            if ( $user->Preferences->Notifypollcomment == 'yes' ) {
                                ?>checked="checked"<?php
                            }
                            ?>/></td>
                        </tr>
                        <tr>
                            <th>Σχόλια στα ημερολόγιά μου:</th>
                            <td><input id="emailjournalcomment" type="checkbox" <?php
                            if ( $user->Preferences->Emailjournalcomment == 'yes' ) {
                                ?>checked="checked"<?php
                            }
                            ?>/></td>
                            <td><input id="notifyjournalcomment" type="checkbox" <?php
                            if ( $user->Preferences->Notifyjournalcomment == 'yes' ) {
                                ?>checked="checked"<?php
                            }
                            ?>/></td>                        
                        </tr>
                        <tr>
                            <th>Απαντήσεις στα σχόλιά μου:</th>
                            <td><input id="emailreply" type="checkbox" <?php
                            if ( $user->Preferences->Emailreply == 'yes' ) {
                                ?>checked="checked"<?php
                            }
                            ?>/></td>
                            <td><input id="notifyreply" type="checkbox" <?php
                            if ( $user->Preferences->Notifyreply == 'yes' ) {
                                ?>checked="checked"<?php
                            }
                            ?>/></td>
                        </tr>
                        <tr>
                            <th>Νέοι φίλοι:</th>
                            <td><input id="emailfriendaddition" type="checkbox" <?php
                            if ( $user->Preferences->Emailfriendaddition == 'yes' ) {
                                ?>checked="checked"<?php
                            }
                            ?>/></td>
                            <td><input id="notifyfriendaddition" type="checkbox" <?php
                            if ( $user->Preferences->Notifyfriendaddition == 'yes' ) {
                                ?>checked="checked"<?php
                            }
                            ?>/></td>
                        </tr>
                        <tr>
                            <th>Νέες Σημάνσεις Φωτογραφιών:</th>
                            <td><input id="emailtagcreation" type="checkbox" <?php
                            if ( $user->Preferences->Emailphototag == 'yes' ) {
                                ?>checked="checked"<?php
                            }
                            ?>/></td>
                            <td><input id="notifytagcreation" type="checkbox" <?php
                            if ( $user->Preferences->Notifyphototag == 'yes' ) {
                                ?> checked="checked"<?php
                            }
                            ?>/></td>
                        </tr>
                        <tr>
                            <th>Προσθήκες αγαπημένων:</th>
                            <td><input id="emailfavourite" type="checkbox" <?php
                            if ( $user->Preferences->Emailfavourite == 'yes' ) {
                                ?>checked="checked"<?php
                            }
                            ?>/></td>
                            <td><input id="notifyfavourite" type="checkbox" <?php
                            if ( $user->Preferences->Notifyfavourite == 'yes' ) {
                                ?> checked="checked"<?php
                            }
                            ?>/></td>
                        </tr>
                        <tr>
                            <th>Γενέθλια Φίλων:</th>
                            <td><input id="emailbirthday" type="checkbox" <?php
                            if ( $user->Preferences->Emailbirthday == 'yes' ) {
                                ?>checked="checked"<?php
                            }
                            ?>/></td>
                            <td><input id="notifybirthday" type="checkbox" <?php
                            if ( $user->Preferences->Notifybirthday == 'yes' ) {
                                ?> checked="checked"<?php
                            }
                            ?>/></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id ="pwdmodal">
                <h3 class="modaltitle">Αλλαγή κωδικού πρόσβασης</h3>
                <div class="oldpassword">
                    <span>Κωδικός πρόσβασης:</span>
                    <div>
                        <input type="password" />
                        <div class="wrongpwd">
                        <span>
                        <img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>exclamation.png" alt="Προσοχή" title="Προσοχή" />Ο κωδικός πρόσβασης δεν είναι σωστός
                        </span>
                        </div>
                    </div>
                </div>
                <div class="newpassword">
                    <span>Νέος κωδικός πρόσβασης:</span>
                    <div>
                        <input type="password" />
                        <div class="shortpwd">
                        <span>
                        <img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>exclamation.png" alt="Προσοχή" title="Προσοχή" />Πρέπει να δώσεις έναν κωδικό πρόσβασης με τουλάχιστον 4 χαρακτήρες!
                        </span>
                        </div>
                    </div>
                </div>
                <div class="renewpassword">
                    <span>Επιβεβαίωση νέου κωδικού:</span>
                    <div>
                        <input type="password" />
                        <div class="wrongrepwd">
                        <span><img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>exclamation.png" alt="Προσοχή" title="Προσοχή" />Δεν έχεις πληκτρολογήσει σωστά τον κωδικό πρόσβασης!
                        </span>
                        </div>
                    </div>
                </div>
                <div class="save">
                    <a href="" class="button">Αποθήκευση</a>
                </div>
            </div>
            
            <div id ="deletemodal">
                <h3 class="modaltitle">Διαγραφή λογαριασμού</h3>
                <p>
                    Πρόκειται να διαγράψεις τον λογαριασμό σου. Θα διαγραφεί επίσης όλο το περιεχόμενο που έχεις αναρτήσει.
                    Το όνομα του λογαριασμού που χρησιμοποίησες δεν θα μπορέσει να ξαναχρησιμοποιηθεί από άλλο μέλος.
                </p>
                <div style="font-weight:bold;color:#f00">Προσοχή: Η διαγραφή λογαριασμού δεν μπορεί να ακυρωθεί!</div>
                <div class="oldpassword">
                    <span>Κωδικός πρόσβασης:</span>
                    <div>
                        <input type="password" />
                        <div class="wrongpwd">
                        <span>
                        <img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>exclamation.png" alt="Προσοχή" title="Προσοχή" />Ο κωδικός πρόσβασης δεν είναι σωστός
                        </span>
                        </div>
                    </div>
                </div>
                <div class="save">
                    <a href="" class="button">Διαγραφή του λογαριασμού μου</a>
                </div>
            </div><?php
        }
    }
?>
