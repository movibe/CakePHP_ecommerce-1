<div class="blogCats view">
<h2><?php  echo __('Blog Cat'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($blogCat['BlogCat']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Blog'); ?></dt>
		<dd>
			<?php echo $this->Html->link($blogCat['Blog']['title'], array('controller' => 'blogs', 'action' => 'view', $blogCat['Blog']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Cat'); ?></dt>
		<dd>
			<?php echo $this->Html->link($blogCat['Cat']['name'], array('controller' => 'cats', 'action' => 'view', $blogCat['Cat']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Blog Cat'), array('action' => 'edit', $blogCat['BlogCat']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Blog Cat'), array('action' => 'delete', $blogCat['BlogCat']['id']), null, __('Are you sure you want to delete # %s?', $blogCat['BlogCat']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Blog Cats'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Blog Cat'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Blogs'), array('controller' => 'blogs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Blog'), array('controller' => 'blogs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cats'), array('controller' => 'cats', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cat'), array('controller' => 'cats', 'action' => 'add')); ?> </li>
	</ul>
</div>
