<?php
    class ElementAboutAdvertiseText extends Element {
        public function Render() {        
            ?><h2>Διαφήμιση στο Zino</h2>
                
                <h3>Τι είναι το Zino</h3>
                <p>
                    Το Zino είναι ένα ταχύτατα αναπτυσσόμενο community νέων ανθρώπων, κυρίως μαθητών και φοιτητών με μέσο όρο ηλικίας τα 19 χρόνια.
                </p>
                <h3>Γιατί να διαφημιστείτε στο Zino</h3>
                <p>
                    To site μας εξελίσεται ταχύτατα τον τελευταίο καιρό με μεγάλο αριθμό προβολών σε καθημερινή βάση και με υποσχέσεις για 
                    ένα ακόμα καλύτερο μέλλον. Επίσης, ο μικρός μέσος όρος ηλικίας, το υψηλό πνευματικό επίπεδο των χρηστών και οι χαμηλές τιμές καθιστούν 
                    το Zino ιδανική περίπτωση για την προβολή της εταιρείας σας. Το Zino χρησιμοποιεί πρωτόγνωρες για την Ελλάδα τεχνολογικές
                    πρωτοπορίες που δεν έχουν ξαναεμφανιστεί στο ελληνικό διαδίκτυο.
                </p>
                <h3>Δυνατότητες διαφήμισης</h3>
                <p>
                    Στο Zino υπάρχει δυνατότητα να διαφημιστείτε σε τέσσερα διαφορετικά σημεία με banners διαστάσεων 370x80 pixels. Τα σημεία που μπορούν να
                    τοποθετηθούν τα banners είναι η κεντρική σελίδα, το προφίλ των χρηστών, η προβολή φωτογραφιών και τα άρθρα. Η ελάχιστη συχνότητα εμφάνισης
                    των banners σας σε σχέση με τα άλλα που εμφανίζονται ταυτόχρονα είναι 50% (ενδεικτικά αναφέρουμε ότι στον Ελληνικό χώρο η συχνότητες εμφάνισης
                    είναι κατά κανόνα κάτω από 25%).
                </p>
                <h3>Τιμές</h3>
                <ul>
                    <li>Κεντρική σελίδα</li>
                        <strong>1 μήνας</strong> 250&euro;<br />
                        <strong>3 μήνες</strong> 220&euro;/μήνα
                    <li>Προφίλ χρηστών</li>
                        <strong>1 μήνας</strong> 220&euro;<br />
                        <strong>3 μήνες</strong> 200&euro;/μήνα
                    <li>Προβολή φωτογραφιών</li>
                        <strong>1 μήνας</strong> 200&euro;<br />
                        <strong>3 μήνες</strong> 180&euro;/μήνα
                    <li>Άρθρα</li>
                        <strong>1 μήνας</strong> 150&euro;
                </ul>
                <h3>Ειδικές προσφορές</h3>
                <ul>
                    <li>Κεντρική σελίδα και προφίλ χρηστών</li>
                        <strong>1 μήνας</strong> 400&euro;<br />
                        <strong>3 μήνες</strong> 350&euro;/μήνα
                    <li>Κεντρική σελίδα, προφίλ χρηστών και προβολή φωτογραφιών</li>
                        <strong>1 μήνας</strong> 600&euro;<br />
                        <strong>3 μήνες</strong> 550&euro;/μήνα
                    <li>Κεντρική σελίδα, προφίλ χρηστών, προβολή φωτογραφιών και άρθρα</li>
                        <strong>1 μήνας</strong> 750&euro;<br />
                        <strong>3 μήνες</strong> 650&euro;/μήνα
                </ul>
                <h3>Τρόποι πληρωμής</h3>
                <p>
                Είμαστε ανοιχτοί στον τρόπο πληρωμής που εσείς επιθυμείτε. Κατά προτίμηση προτιμούμε την
                ηλεκτρονική μεταφορά χρημάτων (λ.χ. PayPal) ή την κατάθεση σε τραπεζικό λογαριασμό. Τα
                έξοδα μεταφοράς χρημάτων επιβαρύνουν τον διαφημιζόμενο.
                </p>
                <h3>Επικοινωνία</h3>
                <p>
                    Για περισσότερες πληροφορίες μην διστάσετε να επικοινωνείσετε μαζί μας. Παρακαλούμε
                    σημειώστε το e-mail σας ώστε να μπορέσουμε να σας απαντήσουμε, ή, αν επιθυμείτε, 
                    κάποιον άλλο τρόπο επικοινωνίας (όπως τηλεφωνικά), και θα έρθουμε σε επαφή μαζί σας
                    εντός 48 ωρών. Αν έχετε οποιαδήποτε ερώτηση, σημειώστε την στην περιοχή "Σχόλια" και
                    θα προσπαθήσουμε να σας εξυπηρετήσουμε.
                    <br/><br/>
                    <form action="do/about/advertisemail/sendmail" method="post">
                        Email<br />
                        <input type="text" name="from" style="width:250px;" /><br /><br />
                        Σχόλια (προαιρετικό)<br />
                        <textarea name="text" style="width:400px;height:200px;"></textarea><br /><br />
                        <input type="submit" value="&#187;Αποστολή" />
                    </form>
                </p>
            <?php
        }
    }
?>
