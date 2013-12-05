<div class="textures form">
<?php echo $this->Form->create('Texture'); ?>
	<fieldset>
		<legend><?php echo __('Edit Texture'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('is_active');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Texture.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Texture.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Textures'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Designs'), array('controller' => 'designs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Design'), array('controller' => 'designs', 'action' => 'add')); ?> </li>
	</ul>
</div>
