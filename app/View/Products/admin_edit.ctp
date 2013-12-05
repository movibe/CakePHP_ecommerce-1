<div class="wrapper">
	<div class="widget">
		<div class=" offset1">
			<?php 
				echo $this->Form->create('Product', array(
					'inputDefaults' => array('div' => false)
				));
			?>
				<div class="title"><?php echo $this->Html->image('crown/icons/dark/list.png', array('class' => 'titleIcon'))?><h6>Edit product</h6></div>
			
			<div class="formRow">
				<?php
					echo $this->Form->input('title', array('class' => 'formRight'));
				?>
				<div class="clear"></div>
			</div>
			<div class="formRow">
				<?php
					echo $this->Form->input('description', array('class' => 'formRight'));
				?>
				<div class="clear"></div>
			</div>
			<div class="formRow">
				<div class="input-append">
				<?php
					echo $this->Form->input('price', array('class' => 'formRight'));
				?>
					<span class="add-on">($)</span>
				</div>
				<div class="clear"></div>
			</div>
			<div class="formRow">
				<?php
					echo $this->Form->input('style_id', array('class' => 'formRight'));
				?>
				<div class="clear"></div>
			</div>
			<div class="formRow">
				<?php
					echo $this->Form->input('gender_id', array('class' => 'formRight'));
				?>
				<div class="clear"></div>
			</div>
			<div class="formRow">
				<?php
					echo $this->Form->input('size_id', array('class' => 'formRight'));
				?>
				<div class="clear"></div>
			</div>
			<div class="formRow">
				<?php
					echo $this->Form->input('brand_id', array('class' => 'formRight'));
				?>
				<div class="clear"></div>
			</div>
			<div class="formRow">
				<?php
					echo $this->Form->input('category_id', array('class' => 'formRight'));
				
				?>
				<div class="clear"></div>
			</div>
			<div class="formRow">
				<div class="control-group">
					<button type="submit" class="button blueB">Submit</button>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>
	<?php echo $this->Html->link('<span>Manage Images</span>',array('controller' => 'products','action' => 'images', 'admin' => false, $product['Product']['id']),array('class' =>'button blueB','escape' => false, 'target' => '_blank'));?>
	

</div>
