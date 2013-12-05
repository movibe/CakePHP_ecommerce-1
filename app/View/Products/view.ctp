<div class="row-fluid">
	<div class="span12">
		<div id="myCarousel" class="carousel slide">
			<!-- Carousel items -->
			<div class="carousel-inner">
			<?php foreach($product['Image'] as $key => $image){?>
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

<div class="popup-wrap" id="<?php echo $product['Product']['id'];?>">
	<div class="row-fluid">
		<div class="offset1 span5">
			<ul>
				<li><?php echo $product['Product']['price'];?></li>
				<li><?php echo $product['Style']['name'];?></li>
				<li><?php echo $product['Gender']['name'];?></li>
			</ul>
		</div>
		<div class="offset1 span5">
			<ul>
				<li><?php echo $product['Cause']['title'];?></li>
				<li><?php echo $product['Design']['title'];?></li>
				<li><?php echo $product['Size']['name'];?></li>
			</ul>
		</div>
	</div>
	<div class="row-fluid"
		data-product="<?php echo $product['Product']['id'];?>" data-parent="0">
		<div class="span12">
			<textarea class="span12 text-box" placeholder="Add a comment"></textarea>
		</div>
		<button data-loading-text="loading ..."
			class="btn btn-mini btn-primary pull-right reply" type="button">Comment</button>
	</div>
	<div class="row-fluid popup-loader" id="id<?php echo $product['Product']['id'];?>">
		<div class="offset4 span4">
			<?php echo $this->Html->image('loaders/loader12.gif', array('escape' => false));?>
		</div>
	</div>
</div>