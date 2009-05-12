<?php
    // Content-type: text/plain

    class ElementUserEmailWelcome extends Element {
        public function Render( User $target, $link ) {
            global $rabbit_settings;

            ?>Γεια σου <?php
            echo $target->Name;
            ?>, 

Πρόσφατα δημιούργησες ένα λογαριασμό στο Zino χρησιμοποιώντας αυτή την ηλεκτρονική διεύθυνση.

Κάνε κλικ στον παρακάτω σύνδεσμο για να επιβεβαιώσεις το e-mail σου:
<?php
        echo $link;
    ?>
    
(Αν δεν μπορείς να κάνεις κλικ στον σύνδεσμο, δοκίμασε να τον αντιγράψεις και να τον επικολλήσεις στην θέση διεύθυνσης.)

Αν δεν δημιούργησες λογαριασμό στο Zino, παρακαλούμε αγνόησε αυτό το μήνυμα.

Αυτό το e-mail είναι ο μόνος τρόπος επιβεβαίωσης του λογαριασμού σου στο Zino. Για την δική σου ασφάλεια, να θυμάσαι ότι κάποιο μέλος της Ομάδας Ανάπτυξης του Zino δεν πρόκειται να σου ζητήσει ποτέ τον κωδικό πρόσβασής σου σε καμία απολύτως περίπτωση!

Μπορείς να επικοινωνήσεις με το info@zino.gr για οποιαδήποτε ερώτηση.
    <?php    
        Element( 'email/footer', false );

        return 'Zino - Καλώς ήρθες';
        }
    }
?>
