<?php 
	class ElementAlbumManager extends Element {
		public function Render () { 
			
			global $user;
			
			if ( !$user->Exists() ) {
				die( "������ �� ����� ������������ ��� �� ��������������� ����� ��� ����������" );
			}
			
			?>test<?php
			
		}
	}
?>