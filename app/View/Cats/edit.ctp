<div class="cats form">
<?php echo $this->Form->create('Cat'); ?>
	<fieldset>
		<legend><?php echo __('Edit Cat'); ?></legend>
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

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Cat.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Cat.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Cats'), array('action' => 'index')); ?></li>
	</ul>
</div>
