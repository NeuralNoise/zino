<?php
	
	function ElementFooter() {
		//global $page;
		
		//$page->AttachStylesheet( 'css/footer.css' );
		?><div class="footer">
			<ul>
				<li><a href="http://www.kamibu.com/">Η ομάδα μας</a></li>
				<li><a href="contact" onclick="return false">Επικοινώνησε</a></li>
				<li><a href="?p=tos">Όροι χρήσης</a></li>
				<li><a href="?p=advertise">Διαφημίσου εδώ</a></li>
				<li><a href="help" onclick="return false">Βοήθεια</a></li>
			</ul>
			<div class="copy">
				&copy; 2008 <a href="http://www.kamibu.com/">Kamibu</a>
			</div>
		</div><?php
	}
?>
