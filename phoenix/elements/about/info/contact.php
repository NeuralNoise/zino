<?php
    class ElementAboutInfoContact extends Element {
        public function Render() {
            global $page;
            
            $page->SetTitle( '�����������' );
            
            ?><p>����� ������, ������� � ���������; ��� ��������� �� ������ �� ����� ���� ���, ���� ��� �������� ������ ������������:</p>
            <form action="do/about/contactmail/sendmail" method="post">
                Email:<br />
                <input type="text" name="from" style="width:250px"/><br /><br />
                ������:<br />
                <textarea name="text" style="width:400px;height:200px"></textarea><br /><br />
                <input type="submit" value="��������" />
            </form><?php
        }
    }
?>
