<?php
    class ElementStoreThanks extends Element {
        public function Render() {
            global $user;
            global $libs;
            
            $libs->Load( 'user/profile' );
            
            if ( !$user->Exists() ) {
                return;
            }
            
            ?>
            <h1>
                <div class="city">
                    <div class="cityend1">
                    </div>
                </div>
                <span>
                    <a href="http://www.zino.gr/"><img src="http://static.zino.gr/phoenix/logo-trans.png" alt="Zino" /></a>
                    <a href="http://store.zino.gr/"><img src="http://static.zino.gr/phoenix/store/store.png" alt="STORE" /></a>
                </span>
            </h1>
            <a class="back" href="http://www.zino.gr/">���� ��� zino</a>
            <div class="content">
            <h3>�' ������������ ��� ��� ����� ���, <?php
            echo $user->Name;
            ?>!</h3>

            <p>��� e-mail ���� ��������� ���� ��������� ���, <?php
                        echo $user->Profile->Email;
            ?>, �� ��� ������������ ������� �� ��� ���������� ���.</p>

            <ul>
            <li><strong>������:</strong> Zino Necklace ��������</li>
            <li><strong>����:</strong> 15�</li>
            <li><strong>����������/����� �������������:</strong> 0� (����������� ��� �� Zino)</li>
            <li><strong>��������:</strong> <?php
            switch ( $user->Profile->Placeid ) {
                case 1:
                case 2:
                case 102:
                case 107:
                    ?>����-��-���� (��� ����������� ���)</li></ul>
                    
                    <p>�� ��������������� ������� ���� ��� ���������� ��� ��� �������� ��� ���������.</p><?php
                    break;
                default:
                    ?>����������� (�� ������������)</li></ul>
                    
                    <p>�� �������� ����������� �� �������������� ������� ���� ��� ������ ��� ��� �������� ��� ���������.</p><?php
            }

            ?>
            <p>��� ����������� ������ ������� �� ��� ����� ���, ������������ ���� ��� ��� <strong>info@zino.gr</strong>
            � ��������� ��� ������ ��� <a href="http://oniz.zino.gr/">������ ��� ������ ��� Zino</a>.</p>

            <p>�' ������������ ��� ���� ��� ���� ��� ��� ����� ���! �� ������ ������� ���� �� Zino �� ���������� ��� 
            �� ��������� �� ������� <span class="emoticon-smile">.</span></p>
            
            <p>���� ��� <a href="store.php?p=product&name=necklace">Zino Necklace ��������</a></p><?php
        }
    }
?>
