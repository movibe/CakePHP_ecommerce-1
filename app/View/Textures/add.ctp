<div class="textures form">
<?php echo $this->Form->create('Texture'); ?>
	<fieldset>
		<legend><?php echo __('Add Texture'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('is_active');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Html->link(__('List Textures'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Designs'), array('controller' => 'designs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Design'), array('controller' => 'designs', 'action' => 'add')); ?> </li>
	</ul>
</div>
