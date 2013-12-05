<?php
	$this->start('header');
	echo $this->element('Common/header');
	$this->end();
?>
<div class="container">
	<div class="row">
		<div class="span3">
			<?php
				echo $this->element('design/categories_holder');
				echo $this->element('design/collections_holder');
			?>
		</div>

		<div class="span9">
			<div class="row">
				<div class="span3">
					<div class="boxRepeat">
						<div class="boxImage">
							<?php echo $this->Html->image('Tulips.jpg');?>
						</div>
						<div class="contentBox">
							<div class="smallImage">
								<a href="#"><?php echo $this->Html->image('Tulips.jpg');?>
								</a>
							</div>
							<div class="imageDescription">
								<P>Lorem Ipsum dolor sit amet, Consectetuer or adipiscing
									elit,sed diam nonummy</p>
							</div>
						</div>
					</div>
					<div class="boxIcons">
						<div class="social-icons">
							<ul>
								<li><a href="#"><i class="icon-heart"></i>Like</a>
								</li>
								<li><a href="#"><i class="icon-comment"></i>Comment</a>
								</li>
								<li><a href="#"><i class="icon-share"></i>Share</a>
								</li>
								<li><a href="#"><i class="icon-thumbs-up"></i>Vote</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="span3">
					<div class="boxRepeat">
						<div class="boxImage">
							<?php echo $this->Html->image('Tulips.jpg');?>
						</div>
						<div class="contentBox">
							<div class="smallImage">
								<a href="#"><?php echo $this->Html->image('Tulips.jpg');?>
								</a>
							</div>
							<div class="imageDescription">
								<P>Lorem Ipsum dolor sit amet, Consectetuer or adipiscing
									elit,sed diam nonummy</p>
							</div>
						</div>
					</div>
					<div class="boxIcons">
						<div class="social-icons">
							<ul>
								<li><a href="#"><i class="icon-heart"></i>Like</a>
								</li>
								<li><a href="#"><i class="icon-comment"></i>Comment</a>
								</li>
								<li><a href="#"><i class="icon-share"></i>Share</a>
								</li>
								<li><a href="#"><i class="icon-thumbs-up"></i>Vote</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="span3">
					<div class="boxRepeat">
						<div class="boxImage">
							<?php echo $this->Html->image('Tulips.jpg');?>
						</div>
						<div class="contentBox">
							<div class="smallImage">
								<a href="#"><?php echo $this->Html->image('Tulips.jpg');?>
								</a>
							</div>
							<div class="imageDescription">
								<P>Lorem Ipsum dolor sit amet, Consectetuer or adipiscing
									elit,sed diam nonummy</p>
							</div>
						</div>
					</div>
					<div class="boxIcons">
						<div class="social-icons">
							<ul>
								<li><a href="#"><i class="icon-heart"></i>Like</a>
								</li>
								<li><a href="#"><i class="icon-comment"></i>Comment</a>
								</li>
								<li><a href="#"><i class="icon-share"></i>Share</a>
								</li>
								<li><a href="#"><i class="icon-thumbs-up"></i>Vote</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="span1 offset11 data-original-title title">
			<a class="scroll" href="#"><i class="icon-search icon-arrow-up"></i> Scroll to top</a>
		</div>
	</div>
</div>	
<div id="push"></div>
<?php
	echo $this->Html->script(array(
		'uniformjs/jquery.uniform'
	));
?>
<script>
	//On load, style typical form elements
	$(function() {
		$("select,input[type=radio]").uniform();
		/* Range slider */
		$(".uiRangeSlider").slider({
			range : true,
			min : 0,
			max : 500,
			values : [ 75, 300 ],
			slide : function(event, ui) {
				$("#rangeAmount").val(
						"$" + ui.values[0] + " - $"
								+ ui.values[1]);
			}
		});
	});
</script>