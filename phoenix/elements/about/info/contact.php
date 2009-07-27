<?php
    class ElementAboutInfoContact extends Element {
        public function Render() {
            global $page;
            global $user;
            
            $page->SetTitle( 'Επικοινωνία' );
            
            ?><form>
                <div><?php
                    if ( $user->Exists() ) {
                        ?><label>Το ψευδώνυμό σου:</label>
                        <strong><?php
                        echo $user->Name;
                        ?></strong><?php
                    }
                    else {
                       ?><label>Το e-mail σου:</label>
                       <input type="text" name="email" value="" /><?php
                    }
                    ?>
                </div>
                <div>
                   <label>Επικοινωνώ επειδή:</label>
                   <select name="reason">
                    <option value="support">Έχω τεχνικό πρόβλημα στο Zino</option>
                    <option value="feature">Έχω μία ιδέα για το Zino</option>
                    <option value="abuse">Αναφέρω παραβίαση των Όρων Χρήσης</option>
                    <option value="biz">Θα ήθελα να συνεργαστούμε</option>
                    <option value="press">Είμαι δημοσιογράφος</option>
                    <option value="purge">Θέλω να διαγράψω το λογαριασμό μου</option>
                   </select>
                </div>
                <div id="contact_support">
                    <div>
                        <label>Σε ποια σελίδα συνέβη το πρόβλημα; (διεύθυνση)</label>
                        <input type="text" name="url" />
                    </div>
                    <div>
                        <label>Τι ακριβώς συνέβη; Περιέγραψε με όσες λεπτομέρειες μπορείς.</label>
                        <textarea cols="50" rows="10" name="description"></textarea>
                    </div>
                    <div>
                        <label>Τι λειτουργικό σύστημα χρησιμοποιείς;</label>
                        <select name="os">
                         <option value="windows">Windows</option>
                         <option value="linux">Linux</option>
                         <option value="mac">Mac OS</option>
                         <option value="other">Κάποιο άλλο</option>
                         <option value="dontknow">Δεν γνωρίζω</option>
                        </select>
                    </div>
                    <div>
                        <label>Ποιο browser χρησιμοποιείς;</label>
                        <select name="os">
                         <option value="ie">Internet Explorer</option>
                         <option value="ff">Mozilla Firefox</option>
                         <option value="chrome">Google Chrome</option>
                         <option value="opera">Opera</option>
                         <option value="safari">Safari</option>
                         <option value="other">Κάποιο άλλο</option>
                         <option value="dontknow">Δεν γνωρίζω</option>
                        </select>
                    </div>
                </div>
            </form><?php
        }
    }
?>
