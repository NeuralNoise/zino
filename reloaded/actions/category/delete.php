<?php
	function ActionCategoryDelete( tInteger $id ) {
		global $user;
		global $libs;
		
		$libs->Load( 'category' );
		
		if ( !( $user->CanModifyCategories() ) ) {
			die( "��� ����� �� ���������� ���������� ��� �������� ����������!" );
		}
		
		$id = $id->Get();
		$sqlcategory = MyCategory( $id );
		if ( $sqlcategory == "" ) {
			die( "� ��������� ��� ���������� �� ���������� ��� �������!" );
		}
		else {
			$parentcategoryid = $sqlcategory[ "parentcategoryid" ];
			$categorydeleted = KillCategory( $id );
			
			switch ( $categorydeleted ) {
				case 1:
					if ( $parentcategoryid == 0 ) {
						return Redirect();
					}
					return Redirect( "?p=category&id=$parentcategoryid" );
				default:
					die( "KillCategory() error: Return code: " . $categorydeleted );
			}
		}
	}
?>
