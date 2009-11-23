<?php
    class ElementUserProfileSidebarPlayer extends Element {
        public function Render( $theuser ) {
			Element( 'user/profile/sidebar/flash', $theuser->Profile->Songwidgetid );
			?>
			<div id="mplayersearchmodal">
				<div class="toolbar">
					<div class="exit"></div>
				</div>
				<div class="search">
					<div class="input">
						<input type="text" value="Αναζήτηση..." />
						<input type="image" src="" alt="" />
					</div>
				</div>
				<div class="list">
					<table>
						<thead>
							<tr class="head">
								<th>Όνομα</th>
								<th>Καλλιτέχνης</th>
								<th>Άλμπουμ</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div><?php
		}
    }
?>
