<?php

    function ElementUserInvalid() {
        global $user;

        if ( $user->Exists() ) {
            Redirect();
        }

        ?>�������������� ���� �� ������ ��������� �������� ������ ��� ������� ���������.<?php
    }

?>
