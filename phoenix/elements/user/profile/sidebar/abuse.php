<?php
    class ElementUserProfileSidebarAbuse extends Element {
        public function Render() {
            ?><a href="" class="report">������� ����� ������</a>
            <div id="reportabusemodal" style="display:none">
                <h3 class="modaltitle">������� ����� ������</h3>
                <form>
                    <p>��������� �� ��������� ��� ��������� ��� ���� ������ ��� ������� ����� ��� ������.</p>
                    <p><strong>� ������� ���� ����� ������������.</strong></p>
                    <p>�� ��� ��������� ����� �� ��� ������� ����� ��� ������, ����� ��� ����� ����� ��� ��
                    ��� ���������. ��� �� ���������� ���� ���� �� ��������� ����������� ���������� ��� Zino
                    ��� ��� �� ���� ������.
                    <label for="reportreason">����������:</label>
                    <select name="reportreason" id="reportreason">
                        <option>������� ���</option>
                        <option>�������� ������������� ������</option>
                        <option>���������� �������</option>
                        <option>�������� ������������� � ����������������� ������������ (spam)</option>
                        <option>����������� ��� ��� ����������� �� ���������� ������� (fake)</option>
                    </select>
                    <label for="reportcomments">������:</label>
                    <textarea name="reportcomments" id="reportcomments"></textarea>
                    <div class="buttons">
                        <a href="" class="button">�������� ��������</a>
                        <a href="" class="button">�������</a>
                    </div>
                </form>
            </div><?php
        }
    }
?>
