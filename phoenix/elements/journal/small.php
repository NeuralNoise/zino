<?php
	function ElementJournalSmall() {
		global $page;
		
		$page->AttachStyleSheet( 'css/journal/small.css' );
		
		?><h3><a href="">The MacGyver sandwich</a></h3>
		<p>
		���������� ��� �� ������ ������ �������. ���� ������ ������ �� ���� ��� �� ����� �� ��������� 
		��� ��������������� ��� ��� �����������. ������ ���� �� ������ ������� �� ���� ��������� �����. 
		�������...
		</p>
		<ul>
			<li class="readwhole"><a href="">������� ���������&raquo;</a></li>
			<li>
				<dl>
					<dt class="addfav"><a href=""><img src="images/heart_add.png" alt="�������� ��� ���������" title="�������� ��� ���������" /></a></dt>
				</dl>
			</li>
			<li>
				<dl>
					<dt class="commentsnum"><a href="">54 ������</a></dt>
				</dl>
			</li>
		</ul>
		<div class="barfade">
			<div class="leftbar"></div>
			<div class="rightbar"></div>
		</div><?php
	}
?>