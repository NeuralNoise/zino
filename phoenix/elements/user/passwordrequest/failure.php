<?php
    class ElementUserPasswordRequestView extends Element {
        public function Render( tText $username ) {
            $username = $username->Get();
            
            $userfinder = New UserFinder();
            $user = $userfinder->FindByUsername( $username );
            ?><h2>��� �������� �� ������������ ��� ������ ��� <span class="emoticon-cry">.</span></h2>
            <form action="user/passwordrequest" method="post">
                <p>
                    ��������, ���� ��� ��������� �� ������������ ��� ������ ���, ������ <?php
                    if ( $user->Exists() ) {
                        ?>��� ����� ������� ��� ������ ��������� e-mail ���� ��� ������� ���.
                        </p><p>
                        <strong><a href="http://www.zino.gr/?p=join" title="������ ��� ��� ������">����������� 
                        ��� ��� ������</a></strong>!<?php
                    }
                    else {
                        ?>�� ����� ������ </strong><?php
                        echo htmlspecialchars( $username );
                        ?></strong> ��� �������.</p>
                        
                        <strong><a href="http://www.zino.gr/?p=join&amp;username=<?php
                        echo htmlspecialchars( $username );
                        ?>" title="������ ��� ��� ������">����������� ��</a></strong>!<?php
                    }
                    ?>
                </p>
            </form><?php
        }
    }
?>
