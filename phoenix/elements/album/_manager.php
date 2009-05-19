<?php 
	class ElementAlbumManager extends Element {
		public function Render () { 
			
			global $user;
			
			if ( !$user->Exists() ) { 
				?>������ �� ����� ������������ ��� �� ��������������� ����� ��� ����������<?php
				return;
			}
			
			
			?><div class="photomanager" id="photomanager">
				<h2>���������� �����������</h2>
				<div class="manager" id="manager">
					<div class="albums" id="albums">
						<h2>Albums</h2>
						<div class="albumlist">
							<ul style="height: 400px; position: relative;"><?php
							?></ul>
						</div>
					</div>
					<div class="photos" id="photos">
					<div id="pages" class="pagination" ></div>
						<ul class="photolist" id="photolist">
						</ul>
					</div>
				</div>
				<div class="eof"/>
			</div><?php
		}
	}
?>