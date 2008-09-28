<?php
	class ElementPollResultView extends Element {
		public function Render( $poll, $showresults ) {
			?><ul><?php
				$finder = New PollOptionFinder();
				$options = $finder->FindByPoll( $poll );
				foreach ( $options as $option ) {
					?><li>
						<dl><?php
							if ( $showresults ) {
								?><dt class="resultterm"><?php
									echo htmlspecialchars( $option->Text );
								?></dt>
								<dd><?php //max width will be 220px and minimum 24px
								?><div class="option">
									<div class="percentagebar" style="width:<?php
									echo 24 + round( $option->Percentage * 196 );
									?>px;">
										<div class="leftrounded"></div>
										<div class="rightrounded"></div>
										<div class="middlerounded"></div>
									</div>
								</div><?php
									echo round( $option->Percentage * 100, 0 );
								?>%</dd><?php
							}
							else {
								?><dt class="voteterm"><input type="radio" name="poll_<?php
								echo $poll->Id;
								?>" value="<?php
								echo $option->Id;
								?>" onclick="PollView.Vote( '<?php
								echo $option->Id;
								?>' , '<?php
								echo $poll->Id;
								?>' , this );" /></dt>
								<dd class="votedefinition"><?php
								echo htmlspecialchars( $option->Text );
								?></dd><?php
							}
						?></dl>
					</li><?php
				}
			?></ul><?php
		}
	}
?>
