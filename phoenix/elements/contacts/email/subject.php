<?php
    class ElementContactsEmailSubject extends Element{
        function Render(){
            global $user;
            ?>��������� ��� <?php
            if ( $user->Gender == 'f' ) {
                ?>��� <?php
            }
            else {
                ?>��� <?php
            }
            echo $user->Name;
            ?> ��� Zino<?php
        }
    }
?>
