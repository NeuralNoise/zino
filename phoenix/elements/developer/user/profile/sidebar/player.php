<?php
    class ElementDeveloperUserProfileSidebarPlayer extends Element {
        public function Render( $theuser ) {
			global $user;

			Element( 'developer/user/profile/sidebar/flash', $theuser );
			if ( $theuser->Betastatus == 1 ) {
				?>
				<div id="mplayersearchmodal">
					<div class="toolbar">
						<div class="exit"></div>
					</div>
					<div class="search">
						<div class="input">
							<input type="text" value="Αναζήτηση..." />
							<div class="search"></div>
						</div>
					</div>
					<div class="list">
						<table>
							<thead>
								<tr class="hidden">
									<th class="name">Όνομα</th>
									<th class="artist">Καλλιτέχνης</th>
									<th class="album">Άλμπουμ</th>
								</tr>
							</thead>
							<tbody></tbody>
						</table>
					</div>
				</div>
				<?php
			}
		}
    }
?>
