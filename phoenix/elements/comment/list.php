<?php
	function ElementCommentList() {
		global $page;
		global $user;
		
		$page->AttachStyleSheet( 'css/comment.css' );
		
		if ( $user->Exists() ) {
		?><div class="comment newcomment">
			<div class="toolbox">
				<span class="time">τα σχόλια είναι επεξεργάσημα για ένα τέταρτο</span>
			</div>
			<div class="who"><?php
				Element( 'user/display' , $user );
				/*
				<a href="user/dionyziz">
					<img src="http://static.zino.gr/phoenix/mockups/dionyziz.jpg" class="avatar" alt="Dionyziz" />
					dionyziz
				</a>
				*/
				?> πρόσθεσε ένα σχόλιο στο προφίλ σου
			</div>
			<div class="text">
				<textarea></textarea>
			</div>
			<div class="bottom">
				<input type="submit" value="Σχολίασε!" />
			</div>
		</div><?php
		}
		?><div class="comment" style="border-color: #dee;">
			<div class="toolbox">
				<span class="time">πριν 12 λεπτά</span>
			</div>
			<div class="who">
				<a href="user/smilemagic">
					<img src="http://static.zino.gr/phoenix/mockups/smilemagic.jpg" class="avatar" alt="SmilEMagiC" />
					SmilEMagiC
				</a> είπε:
			</div>
			<div class="text">
				ρε μλκ τι είναι αυτά που γράφεις στο προφίλ μου? μωρή μαλακία...
				<img src="http://static.zino.gr/phoenix/emoticons/tongue.png" alt=":P" title=":P" /><br />
				άμα σε πιάσω...<br />
				χαχα!! <img src="http://static.zino.gr/phoenix/emoticons/teeth.png" alt=":D" title=":D" /><br />
				θα βρεθούμε το ΣΚ!??
			</div>
			<div class="bottom">
				<a href="" onclick="return false;">Απάντα</a> σε αυτό το σχόλιο
			</div>
		</div>
		<div class="comment" style="margin-left: 20px; border-color: #eed;">
			<div class="toolbox" style="margin-right: 20px">
				<span class="time">πριν 10 λεπτά</span>
			</div>
			<div class="who">
				<a href="user/kostis90gr">
					<img src="http://static.zino.gr/phoenix/mockups/kostis90gr.jpg" class="avatar" alt="kostis90gr" />
					kostis90gr
				</a> είπε:
			</div>
			<div class="text">
				αχαχαχαχ έλεος ρε νίκο!!...
			</div>
			<div class="bottom">
				<a href="" onclick="return false;">Απάντα</a> σε αυτό το σχόλιο
			</div>
		</div>
		<div class="comment" style="margin-left: 20px; border-color: #ded">
			<div class="toolbox" style="margin-right: 20px">
				<span class="time">πριν 9 λεπτά</span>
			</div>
			<div class="who">
				<a href="user/izual">
					<img src="http://static.zino.gr/phoenix/mockups/izual.jpg" class="avatar" alt="izual" />
					izual
				</a> είπε:
			</div>
			<div class="text">
				αφού τον ξέρεις μωρέ πώς κάνει..
			</div>
			<div class="bottom">
				<a href="" onclick="return false;">Απάντα</a> σε αυτό το σχόλιο
			</div>
		</div>
		<div class="comment" style="margin-left: 40px; border-color: #dee">
			<div class="toolbox" style="margin-right: 40px">
				<span class="time">πριν 9 λεπτά</span>
			</div>
			<div class="who">
				<a href="user/smilemagic">
					<img src="http://static.zino.gr/phoenix/mockups/smilemagic.jpg" class="avatar" alt="SmilEMagiC" />
					SmilEMagiC
				</a> είπε:
			</div>
			<div class="text">
				για πλάκα τα λέω ρε!!
			</div>
			<div class="bottom">
				<a href="" onclick="return false;">Απάντα</a> σε αυτό το σχόλιο
			</div>
		</div>
		<div class="comment">
			<div class="toolbox">
				<span class="time">πριν 12 λεπτά</span>
			</div>
			<div class="who">
				<a href="user/titi">
					<img src="http://static.zino.gr/phoenix/mockups/titi.jpg" class="avatar" alt="Titi" />
					Titi
				</a> είπε:
			</div>
			<div class="text">
				αδερφούλη το πάρτυ θα είναι γαμάτο, έχω ήδη μαγειρέψει αίμα!!!
			</div>
			<div class="bottom">
				<a href="" onclick="return false;">Απάντα</a> σε αυτό το σχόλιο
			</div>
		</div>
		<div class="comment" style="margin-left: 20px">
			<div class="toolbox" style="margin-right: 20px">
				<span class="time">πριν 12 λεπτά</span>
				<a href="" onclick="return false"><img src="images/delete.png" alt="Διαγραφή" title="Διαγραφή" /></a>
			</div>
			<div class="who">
				<a href="user/dionyziz">
					<img src="http://static.zino.gr/phoenix/mockups/dionyziz.jpg" class="avatar" alt="Dionyziz" />
					dionyziz
				</a> είπε:
			</div>
			<div class="text">
				Τέλεια! Πήρες black light?
			</div>
			<div class="bottom">
				<a href="" onclick="return false;">Απάντα</a> σε αυτό το σχόλιο
			</div>
		</div>
		<div class="comment oldcomment">
			<div class="toolbox">
				<a href="" onclick="return false" class="rss">
					<img src="http://static.zino.gr/phoenix/feed.png" alt="rss" title="RSS Feed" class="rss" />
				</a>
			</div>
			<div class="who">
				<a href="user/dionyziz">
					412 παλιότερα σχόλια
				</a>
			</div>
			<div class="text">
			</div>
			<div class="bottom">
			</div>
		</div><?php
	}
?>