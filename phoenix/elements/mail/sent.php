<?php
	function ElementMailSent( tString $success ) {
		// Get Parameter
		$success = $success->Get();
		
		if ( $success == "yes" ) {
			?><p>�� ������ �������� ��������.</p><?php
		}
		else {
			?><p>������������� �������� ���� �������� ��� ���������. ��������, ����������� ���� ��������.</p><?php
		}
	}
?>
