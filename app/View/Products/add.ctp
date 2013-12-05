<?php
	$this->start('header');
	echo $this->element('Common/header');
	$this->end();
?>

<div class="container">
	<div class="row">
		<div class="span10 offset1">
			<?php 
				echo $this->Form->create('Product', array(
					'inputDefaults' => array('div' => false)
				));
			?>
			<fieldset>
				<legend>Add product</legend>
				<?php
					echo $this->Form->input('title', array('class' => 'span10'));
					echo $this->Form->input('description', array('class' => 'span10'));
				?>
				<div class="input-append">
				<?php
					echo $this->Form->input('price', array('class' => 'span3'));
				?>
					<span class="add-on">$</span>
				</div>
				<?php
					echo $this->Form->input('style_id', array('class' => 'span3'));
					echo $this->Form->input('gender_id', array('class' => 'span3'));
					
					echo $this->Form->input('cause_id', array('class' => 'span3'));
					echo $this->Form->input('design_id', array('class' => 'span3'));
					
					echo $this->Form->input('size_id', array('class' => 'span1'));
					
					echo $this->Form->input('brand_id', array('class' => 'span3'));
					echo $this->Form->input('category_id', array('class' => 'span3'));
				
				?>
				<div class="control-group">
					<button type="submit" class="btn btn-green">Submit</button>
				</div>
			</fieldset>
		</div>
	</div>

</div>
<!--/span-->
<div id="push"></div>