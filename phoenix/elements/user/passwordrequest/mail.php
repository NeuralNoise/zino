<?php
    /* Content-type: text/plain */
    class ElementUserPasswordRequestMail extends Element {
        public function Render( $username, $requestid, $hash ) {
            global $rabbit_settings;
            
            ?>������� ������� �� �������� ��� ������ ���� ���������� ��� ��� Zino.

��� �� �� ������, ���������� ��� �������� ��������:

<?php
            echo $rabbit_settings[ 'webaddress' ];
            ?>/forgot/recover/<?php
            echo $requestid;
            ?>?hash=<?php
            echo $hash;
            ?>

�� � ��������� ��� ����������, �������� �� ��� ����������� ���� ������ �����������.

�� ��� ����� ������� �� �������� ��� ������ ���, ������� �� ��������� ���� �� ������.<?php
            Element( 'email/footer', false );
        }
    }
?>
