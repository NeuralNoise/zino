<?php
    /// Content-type: text/plain ///
    
    class ElementStoreMailPurchased extends Element {
        public function Render( StorePurchase $purchase ) {
            global $user;
            
            ?>�' ������������ ��� ��� ����� ���, <?php
            echo $user->Name;
            ?>!

���������� �� ������������ ��� ����������� ���.

������: Zino Necklace ��������
����: 15�
����������/����� �������������: 0� (����������� ��� �� Zino)
��������: <?php
            switch ( $user->Profile->Placeid ) {
                case 1:
                case 2:
                case 102:
                case 107:
                    ?>����-��-���� (��� ����������� ���)
                    
�� ��������������� ������� ���� ��� ���������� ��� ��� �������� ��� ���������. <?php
                    break;
                default:
                    ?>����������� (�� ������������)
                    
�� �������� ����������� �� �������������� ������� ���� ��� ������ ��� ��� �������� ��� ���������. <?php
            }

?>

��� ����������� ������ ������� �� ��� ����� ���, ������������ ���� ��� ��� info@zino.gr 
��� ������� ��� ������ ��� �����������: <?php
            echo $purchase->Userid;
            ?>/<?php
            echo $purchase->Id;
            ?>.

�' ������������ ��� ���� ��� ���� ��� ��� ����� ���! �� ������ ������� ���� �� Zino �� ���������� ��� 
�� ��������� �� ������� :-)<?php

            Element( 'email/footer' );
            
            return '� ���������� ��� ��� �� ZinoSTORE!';
        }
    }
?>
