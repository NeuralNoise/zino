<?php
    class ElementUserPasswordRequestView extends Element {
        public function Render( tText $username ) {
            $username = $username->Get();
            
            $userfinder = New UserFinder();
            $user = $userfinder->FindByUsername( $username );
            ?><h2>�����<?php
            if ( $user->Gender == 'f' ) {
                ?>�<?php
            }
            else {
                ?>��<?php
            }
            ?>!</h2>
            <form action="user/passwordrequest" method="post">
                <p>
                    ��� ������ ������� ��� e-mail �� ������� ��� �� ��� �� �������� ��� ������ ���.
                </p>
            </form><?php
        }
    }
?>
