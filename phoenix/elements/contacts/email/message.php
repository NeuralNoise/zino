<?php
    class ElementContactsEmailMessage extends Element{
        function Render( $toname = '', $contact = null ){
            global $user;
            global $rabbit_settings;
            if ( !$user->Exists() ){
                return false;
            }
            if ( $toname == '' ){
                $toname == '(����� �����)';
            }

            ?>���� ��� <?php
            echo $toname;
            ?>,

�� ��� ��������� ����� ������ ��� ��� Zino. ���� ����� ��� Zino ��� �� ���� �� ������ ��� ����� ���, �� �������� �� ���� ���, ��� �� ���������� ��� ����������� ��� �� ��� ���.

��� �� ���� �� ������ <?php
            if ( $user->Gender == 'f' ) {
                ?>��� <?php
            }
            else {
                ?>��� <?php
            }
            echo $user->Name;
            ?> ��� Zino, ������� ���:
<?php
            echo $rabbit_settings[ 'webaddress' ];
            if ( $contact == null ){
                ?>/join<?php
            }
            else{
                ?>/join?id="<?php
                echo $contact->Id;
                ?>&validtoken=<?php
                echo $contact->Validtoken;
            }
            ?>
���������,
<?php
            echo $user->Name;
        }
    }
?>
