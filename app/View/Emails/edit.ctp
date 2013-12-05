<div class="emails form">
<?php echo $this->Form->create('Email'); ?>
	<fieldset>
		<legend><?php echo __('Edit Email'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('from');
		echo $this->Form->input('to');
		echo $this->Form->input('subject');
		echo $this->Form->input('body');
		echo $this->Form->input('headers');
		echo $this->Form->input('is_sent');
		echo $this->Form->input('attempts');
		echo $this->Form->input('created_date');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Email.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Email.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Emails'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Emails'), array('controller' => 'emails', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Email'), array('controller' => 'emails', 'action' => 'add')); ?> </li>
	</ul>
</div>
