<?php
    function ElementAboutContactText() {
        ?><p>Έχετε σχόλια, απορίες ή προτάσεις; Μην διστάσετε να έρθετε σε επαφή μαζί μας, μέσω της παρακάτω φόρμας επικοινωνίας:</p>
		<form action="do/about/advertise/sendmail" method="post">
			Email:<br />
			<input type="text" name="from" style="width:250px;"/><br /><br />
			Σχόλια:<br />
			<textarea name="text" style="width:400px;height:200px;"></textarea><br />
			<input type="submit" value="Αποστολή" />
		</form><?php
    }
?>
