<?php
	function ElementCommentList() {
		global $page;
		
		$page->AttachStyleSheet( 'css/comment.css' );
		
		?><div class="comment newcomment">
			<div class="toolbox">
				<span class="time">�� ������ ����� ������������ ��� ��� �������</span>
			</div>
			<div class="who">
				<a href="user/dionyziz">
					<img src="images/avatars/dionyziz.jpg" class="avatar" alt="Dionyziz" />
					dionyziz
				</a>�������� ��� ������ ��� ������ ���
			</div>
			<div class="text">
				<textarea></textarea>
			</div>
			<div class="bottom">
				<input type="submit" value="��������!" />
			</div>
		<?php //</div> ?>
		<div class="comment" style="border-color: #dee;">
			<div class="toolbox">
				<span class="time">���� 12 �����</span>
			</div>
			<div class="who">
				<a href="user/smilemagic">
					<img src="images/avatars/smilemagic.jpg" class="avatar" alt="SmilEMagiC" />
					SmilEMagiC
				</a> ����:
			</div>
			<div class="text">
				�� ��� �� ����� ���� ��� ������� ��� ������ ���? ���� �������...
				<img src="images/emoticons/tongue.png" alt=":P" title=":P" /><br />
				��� �� �����...<br />
				����!! <img src="images/emoticons/teeth.png" alt=":D" title=":D" /><br />
				�� �������� �� ��!??
			</div>
			<div class="bottom">
				<a href="" onclick="return false;">������</a> �� ���� �� ������
			</div>
		</div>
		<div class="comment" style="margin-left: 20px; border-color: #eed;">
			<div class="toolbox" style="margin-right: 20px">
				<span class="time">���� 10 �����</span>
			</div>
			<div class="who">
				<a href="user/kostis90gr">
					<img src="images/avatars/kostis90gr.jpg" class="avatar" alt="kostis90gr" />
					kostis90gr
				</a> ����:
			</div>
			<div class="text">
				�������� ����� �� ����!!...
			</div>
			<div class="bottom">
				<a href="" onclick="return false;">������</a> �� ���� �� ������
			</div>
		</div>
		<div class="comment" style="margin-left: 20px; border-color: #ded">
			<div class="toolbox" style="margin-right: 20px">
				<span class="time">���� 9 �����</span>
			</div>
			<div class="who">
				<a href="user/izual">
					<img src="images/avatars/izual.jpg" class="avatar" alt="izual" />
					izual
				</a> ����:
			</div>
			<div class="text">
				���� ��� ������ ���� ��� �����..
			</div>
			<div class="bottom">
				<a href="" onclick="return false;">������</a> �� ���� �� ������
			</div>
		</div>
		<div class="comment" style="margin-left: 40px; border-color: #dee">
			<div class="toolbox" style="margin-right: 40px">
				<span class="time">���� 9 �����</span>
			</div>
			<div class="who">
				<a href="user/smilemagic">
					<img src="images/avatars/smilemagic.jpg" class="avatar" alt="SmilEMagiC" />
					SmilEMagiC
				</a> ����:
			</div>
			<div class="text">
				��� ����� �� ��� ��!!
			</div>
			<div class="bottom">
				<a href="" onclick="return false;">������</a> �� ���� �� ������
			</div>
		</div>
		<div class="comment">
			<div class="toolbox">
				<span class="time">���� 12 �����</span>
			</div>
			<div class="who">
				<a href="user/titi">
					<img src="images/avatars/titi.jpg" class="avatar" alt="Titi" />
					Titi
				</a> ����:
			</div>
			<div class="text">
				��������� �� ����� �� ����� ������, ��� ��� ���������� ����!!!
			</div>
			<div class="bottom">
				<a href="" onclick="return false;">������</a> �� ���� �� ������
			</div>
		</div>
		<div class="comment" style="margin-left: 20px">
			<div class="toolbox" style="margin-right: 20px">
				<span class="time">���� 12 �����</span>
				<a href="" onclick="return false"><img src="images/delete.png" alt="��������" title="��������" /></a>
			</div>
			<div class="who">
				<a href="user/dionyziz">
					<img src="images/avatars/dionyziz.jpg" class="avatar" alt="Dionyziz" />
					dionyziz
				</a> ����:
			</div>
			<div class="text">
				������! ����� black light?
			</div>
			<div class="bottom">
				<a href="" onclick="return false;">������</a> �� ���� �� ������
			</div>
		</div>
		<div class="comment oldcomment">
			<div class="toolbox">
				<a href="" onclick="return false" class="rss">
					<img src="images/feed.png" alt="rss" title="RSS Feed" class="rss" />
				</a>
			</div>
			<div class="who">
				<a href="user/dionyziz">
					412 ��������� ������
				</a>
			</div>
			<div class="text">
			</div>
			<div class="bottom">
			</div>
		</div><?php
	}
?>