<?php
	class ElementDeveloperDionyzizWYSIWYG extends Element {
		public function Render() {
			?><br /><br />
			<br /><br />
			<br /><br />
			
			<form method="post" action="do/wysiwyg"><?php
			Element( 'wysiwyg', 'lookatme' );
			?><br />
			<input type="submit" value="Submit" /></form><?php
			
		}
	}
?>
