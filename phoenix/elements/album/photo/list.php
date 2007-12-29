<?php
	function ElementAlbumPhotoList() {
		global $page;
		
		$page->AttachStyleSheet( 'css/album/photo/list.css' );
		
		Element( 'user/sections' );
		?><div id="photolist">
			<h2>������������</h2>
			<dl>
				<dt class="photonum">29 �����������</dt>
				<dt class="commentsnum">328 ������</dt>
			</dl>
			<ul>
				<li>
					<div class="photo">
						<a href="">
							<img src="images/ph1.jpg" alt="������������ 1" title="������������ 1" /><br />
							������ ������������
						</a>
						<div>
							<span class="addfav"><a href=""><img src="images/heart_add.png" alt="�������� ��� ���������" title="�������� ��� ���������" /></a></span>
							<span class="commentsnum">202</span>
						</div>
					</div>
				</li>
				<li>
					<div class="photo">
						<a href="">
							<img src="images/ph2.jpg" alt="������������ 2" title="������������ 2" /><br />
							������������ �� ����������� �����
						</a>
						<div>
							<span class="addfav"><a href=""><img src="images/heart_add.png" alt="�������� ��� ���������" title="�������� ��� ���������" /></a></span>
							<span class="commentsnum">34</span>
						</div>
					</div>
				</li>
				<li>
					<div class="photo">
						<a href="">
							<img src="images/ph3.jpg" alt="������������ 3" title="������������ 3" /><br />					
							2 skyscrapers
						</a>
						<div>
							<span class="addfav"><a href=""><img src="images/heart_add.png" alt="�������� ��� ���������" title="�������� ��� ���������" /></a></span>
							<span class="commentsnum">53</span>
						</div>
					</div>
				</li>
				<li>
					<div class="photo">
						<a href="">
							<img src="images/ph4.jpg" alt="������������ 4" title="������������ 4" /><br />
							kaboom
						</a>
						<div>
							<span class="addfav"><a href=""><img src="images/heart_add.png" alt="�������� ��� ���������" title="�������� ��� ���������" /></a></span>
							<span class="commentsnum">298</span>
						</div>
					</div>
				</li>
				<li>
					<div class="photo">
						<a href="">
							<img src="images/ph5.jpg" alt="������������ 5" title="������������ 5" /><br />
							������
						</a>
						<div>
							<span class="addfav"><a href=""><img src="images/heart_add.png" alt="�������� ��� ���������" title="�������� ��� ���������" /></a></span>
							<span class="commentsnum">21</span>
						</div>
					</div>
				</li>
				<li>
					<div class="photo">
						<a href="">
							<img src="images/ph6.jpg" alt="������������ 6" title="������������ 6" /><br />
							����� �������
						</a>
						<div>
							<span class="addfav"><a href=""><img src="images/heart_add.png" alt="�������� ��� ���������" title="�������� ��� ���������" /></a></span>
							<span class="commentsnum">931</span>
						</div>
					</div>
				</li>
				<li>
					<div class="photo">
						<a href="">
							<img src="images/ph7.jpg" alt="������������ 7" title="������������ 7" /><br />
							�������� �;;
						</a>
						<div>
							<span class="addfav"><a href=""><img src="images/heart_add.png" alt="�������� ��� ���������" title="�������� ��� ���������" /></a></span>
							<span class="commentsnum">31</span>
						</div>	
					</div>
				</li>
				<li>
					<div class="photo">
						<a href="">
							<img src="images/ph8.jpg" alt="������������ 8" title="������������ 8" /><br />
							��� ��������� ������
						</a>
						<div>
							<span class="addfav"><a href=""><img src="images/heart_add.png" alt="�������� ��� ���������" title="�������� ��� ���������" /></a></span>
							<span class="commentsnum">12</span>
						</div>
					</div>
				</li>
				<li>
					<div class="photo">
						<a href="">
							<img src="images/ph9.jpg" alt="������������ 9" title="������������ 9" /><br />
							��� ����� �� ������
						</a>
						<div>
							<span class="addfav"><a href=""><img src="images/heart_add.png" alt="�������� ��� ���������" title="�������� ��� ���������" /></a></span>
							<span class="commentsnum">87</span>
						</div>
					</div>
				</li>
				<li>
					<div class="photo">
						<a href="">
							<img src="images/ph10.jpg" alt="������������ 10" title="������������ 10" /><br />
							����� �������
						</a>
						<div>
							<span class="addfav"><a href=""><img src="images/heart_add.png" alt="�������� ��� ���������" title="�������� ��� ���������" /></a></span>
							<span class="commentsnum">342</span>
						</div>
					</div>
				</li>
				<li>
					<div class="photo">
						<a href="">
							<img src="images/ph11.jpg" alt="������������ 11" title="������������ 11" /><br />
							��� 2 �����
						</a>
						<div>
							<span class="addfav"><a href=""><img src="images/heart_add.png" alt="�������� ��� ���������" title="�������� ��� ���������" /></a></span>
							<span class="commentsnum">21</span>
						</div>
					</div>
				</li>
				<li>
					<div class="photo">
						<a href="">
							<img src="images/ph12.jpg" alt="������������ 12" title="������������ 12" /><br />
							��� Dubai, ���� ������
						</a>
						<div>
							<span class="addfav"><a href=""><img src="images/heart_add.png" alt="�������� ��� ���������" title="�������� ��� ���������" /></a></span>
							<span class="commentsnum">223</span>
						</div>
					</div>
				</li>
			
			</ul>
			<div class="eof"></div>
			<div class="ads" style="margin: 10px 0;text-align:center;overflow:hidden;height: 60px;">
				<img src="images/ads/ad234.jpg" alt="��������� 1" style="width:234px;height:60px;margin: 0 5px;" />
				<img src="images/ads/ad234.jpg" alt="��������� 2" style="width:234px;height:60px;margin: 0 5px;" />
				<img src="images/ads/ad234.jpg" alt="��������� 3" style="width:234px;height:60px;margin: 0 5px;" />
				<img src="images/ads/ad234.jpg" alt="��������� 4" style="width:234px;height:60px;margin: 0 5px;" />
			</div>
			<div class="ads" style="margin: 10px 0;text-align:center;overflow:hidden;height: 90px;">
				<img src="images/ads/ad728.jpg" alt="��������� 5" style="width:728px;height:90px;margin: 0 5px;" />
			</div>
		</div><?php
	}
?>