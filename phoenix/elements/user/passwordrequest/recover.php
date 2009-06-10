<?php
    class ElementUserPasswordRequestRecover extends Element {
        public function Render( tInteger $requestid, tText $hash ) {
            global $libs;
            
            $requestid = $requestid->Get();
            $hash = $hash->Get();
            
            $libs->Load( 'passwordrequest' );
            
            $request = New PasswordRequest( $requestid );
            if ( $request->Used || $request->Hash != $hash ) {
                return Redirect( 'forgot/success' );
            }
            
            ?><h2>������ ������� ���������</h2>
            
            <form action="do/user/password/recover" method="post">
                <div>
                    <label>������������� ��� ��� ��� ������:</label>
                    <input type="password" name="password" value="" />
                </div>
                <input type="hidden" value="<?php
                echo htmlspecialchars( $hash );
                ?>" name="hash" />
                <input type="hidden" value="<?php
                echo htmlspecialchars( $requestid );
                ?>" name="requestid" />
                <input type="submit" value="������ �������" />
            </form><?php
        }
    }
?>
