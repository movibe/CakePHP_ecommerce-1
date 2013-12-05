<!--
<div class="row-fluid">
	<div class="span12">
		<div id="myCarousel" class="carousel slide">
			<div class="carousel-inner">

			<?php foreach($design['Image'] as $key => $image){?>
				<div class="item <?php echo $key == 0 ? 'active' : '';?>">
					<?php echo $this->Html->image('uploaded/' . $image['filename'])?>
				</div>
			<?php }?>
			</div>
		
			<a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
			<a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>

		</div>
	</div>
</div>

<div class="popup-wrap" id="<?php echo $design['Design']['id'];?>">
	<div class="row-fluid"
		data-design="<?php echo $design['Design']['id'];?>" data-parent="0">
		<div class="span12">
			<textarea class="span12 text-box" placeholder="Add a comment"></textarea>
		</div>
		<button data-loading-text="loading ..."
			class="btn btn-mini btn-primary pull-right reply" type="button">Comment</button>
	</div>
	<div class="row-fluid popup-loader" id="id<?php echo $design['Design']['id'];?>">
		<div class="offset4 span4">
			<?php echo $this->Html->image('loaders/loader12.gif', array('escape' => false));?>
		</div>
	</div>
</div>
-->

<div class="row-fluid" style="position:relative;">
<div style="cursor:pointer; position:absolute; right:0; z-index:1000;">
    <div style="position:relative; margin-right:20px;">
    <a href="http://pinterest.com/" style="margin-right:10px;"><?php echo $this->Html->image('icons/73-pinterest-128.png')?></a>
    <a href="http://www.twitter.com" style="margin-right:10px;"><?php echo $this->Html->image('icons/45-twitter-128.png')?></a>
    <a href="http://www.facebook.com" style="margin-right:10px;"><?php echo $this->Html->image('icons/46-facebook-128.png')?></a>
    </div>
</div>
<div id="rsg_popup_close" style="cursor:pointer; position:absolute; right:0; z-index:1000;" onclick="rsg_modal_end();">
    <?php echo $this->Html->image('close_rsg.png')?>
</div>
<script>
    function rsg_modal_end() {
        $('.modal').modal('hide');
    }
</script>
	<div class="span12 text-center">
		<div id="myCarousel" class="carousel slide">
			<!-- Carousel items -->
			<div class="carousel-inner">
            <?php $img_counter_rsg = 0;	?>
			<?php foreach($tests as $key => $image){?>
				<div class="item <?php echo $key == 0 ? 'active' : '';?>">
					<?php echo $this->Html->image('uploaded/' . $image['images']['filename'])?>
				</div>
			<?php $img_counter_rsg = $img_counter_rsg + 1; }?>
			</div>
            <?php if($img_counter_rsg>1) {?>
			<a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
			<a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
            <?php }?>
		</div>
	</div>
</div>

<div id="id_rsg3"> 
	<div id="id_rsg1">
		<div class="row-fluid">
			<div class="span6">
			   <!--
				<strong>Goal to raise: $
					<?php 
						echo $cause['Cause']['goal'];
					?>
				</strong>
				-->
				<strong style="font-size:25px; font-weight:bold;"> 
					<?php 
						echo $find_causename;
					?>
				</strong>            
			</div>
       
			<div class="offset1 span5">
				<div class="row-fluid pull-right">
					<div class="span9">
						<div class="row-fluid" style="width:24%; float:left;">
							<strong>Written by </strong>
						</div>
						<div class="row-fluid" style="width:75%; float:left;">
							<?php echo $design['User']['name'];?>
						</div>                      
					</div>
								
					<div class="span3">
						<div>
						<div style="width:15%; float:left; margin-right:10px;">
						<?php echo $this->Html->image($design['User']['profileImageUrl'], array('url' => array('controller' => 'users', 'action' => 'view', $design['User']['id']), 'alt' => $design['User']['name']));?>
						</div>
						<div style="font-size:15px; font-weight:bold;"><?php echo $find_title;?></div>
						</div>
						<div><?php echo $find_description;?> </div>
					</div>             
				</div>			
			</div>
		</div>
	</div>


<div id="id_rsg2">

<!--
		<p class="output">
			<a href="http://www.spreadnest.com/ajax_content.html" rel="append-content">Append new content</a>
			<a href="http://www.spreadnest.com/ajax_content.html" rel="load-content">Load new content</a>
		</p>
-->		
		<div id="content_1" class="content">
			<div class="popup-wrap" id="<?php echo $design['Design']['id'];?>" style="">
			</div>
		</div>    

	<script>
		$(document).ready(function() {
				$("#content_1").mCustomScrollbar({
					scrollButtons:{
						enable:true
					}
				});
		});

	</script>

	<div id="id_rsg4">
		<div class="row-fluid" data-cause="<?php echo $design['Design']['id'];?>" data-parent="0" style="width:97%;">
			<div class="span12">
				<textarea class="span12 text-box" placeholder="Add a comment"></textarea>
			</div>
			<button data-loading-text="loading ..."	class="btn btn-mini btn-primary pull-right reply" type="button">Comment</button>
		</div>

		<div class="row-fluid popup-loader" id="id<?php echo $design['Design']['id'];?>">
			<div class="offset4 span4">
				<?php echo $this->Html->image('loaders/loader12.gif', array('escape' => false));?>
			</div>
		</div>	
		
	</div>
	</div>
	<div style="clear:both;"></div>
</div>


