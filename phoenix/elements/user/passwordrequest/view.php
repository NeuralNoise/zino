<?php
    class ElementUserPasswordRequestView extends Element {
        public function Render() {
            ?><h2>��������� ������� ���������</h2>
            <form action="user/passwordrequest" method="post">
                <p>
                    ������������� �� ��������� ���:
                    <input type="text" value="" name="username" />
                    <input type="submit" value="���������" />
                </p>
            </form><?php
        }
    }
?>
