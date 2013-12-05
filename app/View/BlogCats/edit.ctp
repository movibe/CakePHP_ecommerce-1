<div class="blogCats form">
<?php echo $this->Form->create('BlogCat'); ?>
	<fieldset>
		<legend><?php echo __('Edit Blog Cat'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('blog_id');
		echo $this->Form->input('cat_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('BlogCat.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('BlogCat.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Blog Cats'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Blogs'), array('controller' => 'blogs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Blog'), array('controller' => 'blogs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cats'), array('controller' => 'cats', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cat'), array('controller' => 'cats', 'action' => 'add')); ?> </li>
	</ul>
</div>
