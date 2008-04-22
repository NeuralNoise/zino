<?php
	
	function ElementPollSmall( $showcommnum = false ) {
		//global $page;
		
		//$showcommnum is a boolean variable checking whether the number of comments should appear at the bottom
		//$page->AttachStyleSheet( 'css/poll/small.css' );
		
		?><div class="pollsmall">
			<h4><a href="">Πόσες φορές τη μέρα βαράς μαλακία;</a></h4>
			<div class="results">	
				<ul>
					<li>
						<dl>
							<dt style="float:right;">
								Μία
							</dt>
							<dd><?php //max width will be 220px and minimum 24px?>
								<div class="percentagebar" style="width:120px;">
									<div class="leftrounded"></div>
									<div class="rightrounded"></div>
									<div class="middlerounded"></div>
								</div>
								<span>30%</span>
							</dd>
						</dl>
					</li>
					<li>
						<dl>
							<dt style="float:right;">
								Μεταξύ 2 και 5
							</dt>
							<dd>
								<div class="percentagebar" style="width:150px;">
									<div class="leftrounded"></div>
									<div class="rightrounded"></div>
									<div class="middlerounded"></div>
								</div>
								<span>64%</span>
							</dd>
						</dl>
					</li>
					<li>
						<dl>
							<dt style="float:right;">
								Από 5 μέχρι 10
							</dt>
							<dd>
								<div class="percentagebar" style="width:34px;">
									<div class="leftrounded"></div>
									<div class="rightrounded"></div>
									<div class="middlerounded"></div>
								</div>
								<span>5,3%</span>
							</dd>
						</dl>
					</li>
					<li>
						<dl>
							<dt style="float:right;">
								Από 10 και πάνω
							</dt>
							<dd>
								<div class="percentagebar" style="width:18px;">
									<div class="leftrounded"></div>
									<div class="rightrounded"></div>
									<div class="middlerounded"></div>
								</div>
								<span>0,7%</span>
							</dd>
						</dl>
					</li>	
				</ul><?php
				if ( $showcommnum ) {
					?><dl class="pollinfo">
						<dd><a href="">43 σχόλια</a></dd>
					</dl><?php
				}
			?></div>
		</div><?php
	}
?>

