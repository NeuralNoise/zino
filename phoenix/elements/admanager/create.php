<?php
    class ElementAdManagerCreate extends Element {
        public function Render() {
            ?><div class="buyad">
                <h2 class="ad">��������� ��� Zino</h2>
                <div class="create">
                    <h3>��������� �� ��������� ���</h3>
                    <div class="left">
                        <div class="input">
                            <label>������:</label>
                            <input type="text" />
                        </div>
                        
                        <div class="input">
                            <label>�������:</label>
                            <textarea></textarea>
                        </div>
                        
                        <div class="input">
                            <label>������: <span>�����������. � ������ �� �������� ��� 200x85 pixels.</span></label>
                            <input type="file" />
                        </div>

                        <div class="input url">
                            <label>��������� �������: <span>�����������. (�.�. www.i-selida-sas.gr)</span></label>
                            
                            <span>http://</span>
                            <input type="text" class="url" />
                        </div>
                    </div>
                    <div class="right">
                        <p>�� ����������� ���������� ��� �� ������������� ��� 
                        ����������� ��� ������������ ���. ��� ���������� �� 
                        ��������� ��� <a href="">������� ����� ��� ���������������</a>.</p>
                    </div>
                    <div class="eof"></div>
                    <h3 style="margin-bottom:0">�������������</h3>
                    <div class="ads" style="font-size: 90%;background-color:white;border-bottom:1px solid transparent;padding: 10px 0 10px 0;margin:0 10px 0 10px">
                        <div class="ad" style="width:200px;border:1px solid #ddd;padding: 5px;margin: auto;">
                            <h4 style="margin: 5px 0 5px 0"><a href="" style="color: #357;">��������� ��������� ISIC</a></h4>
                            <a href=""><img src="http://static.zino.gr/phoenix/mockups/college-students-health.jpg" alt="..." style="display: block; margin: auto" /></a>
                            <p><a href="" style="color: black">������� ��������� ���������. �������� �������� ��� ���������. ����� � ISIC ����� ������� ��� ���� ����� ���!
                               ����� ISIC �� 9 ����!</a></p>
                        </div>
                    </div>

                    <a href="" class="start">����������</a>
                </div>
            </div><?php
        }
    }
?>
