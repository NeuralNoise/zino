<?php
    class ElementUserSettingsEmailValidate extends Element {
        public function Render( tInteger $userid, tString $hash ) {
            $userid = $userid->Get();
            $hash = $hash->Get();
            
            if ( !ValidateEmail( $userid, $hash ) ) {
                ?><p>� ����������� ��� e-mail ��� ��� ���� ������ �� ���������������.<br />
                ����������� ������������.</p><?php
                return;
            }
            
            return Redirect( '?validated=y' );
        }
    }
?>
