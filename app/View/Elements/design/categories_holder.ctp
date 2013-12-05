<div class="btn22 btn-large2 btn-block3">
	CATEGORIES<a href="#"><i class=" icon-chevron-down"></i> </a>
</div>
<div class="categoriesAll">
	<?php
	echo $this->Form->create('Product');
	echo $this->Form->input('style_id', array('label' => false, 'div' => false));
	echo $this->Form->input('gender_id', array('legend' => false, 'type' => 'radio', 'div' => array('class' => 'button-radio')));
	echo $this->Form->input('min_price', array('type' => 'hidden', 'value' => $minPrice));
	echo $this->Form->input('max_price', array('type' => 'hidden', 'value' => $maxPrice));
	?>
	Price Range: <span id="rangeAmount">$<?php echo $minPrice;?> - $<?php echo $maxPrice?></span>
	<div class="uiRangeSlider"></div>
	<?php
	$options = array('selected' => 0, 'label' => false);
	echo $this->Form->input('size_id', $options);
	echo $this->Form->input('style_id', $options);
	echo $this->Form->input('category_id', $options);
	?>
	
	<div class="divider-img">
	<?php echo $this->Html->image('toi9dewm.png');?>
	</div>
	<div class="updateSearch">
		<button type="submit" class="btn btn-search" type="button">UPDATE SEARCH</button>
	</div>
	<?php echo $this->Form->end();?>
</div>
