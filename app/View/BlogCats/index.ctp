<div class="blogCats index">
	<h2><?php echo __('Blog Cats'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('blog_id'); ?></th>
			<th><?php echo $this->Paginator->sort('cat_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
	foreach ($blogCats as $blogCat): ?>
	<tr>
		<td><?php echo h($blogCat['BlogCat']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($blogCat['Blog']['title'], array('controller' => 'blogs', 'action' => 'view', $blogCat['Blog']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($blogCat['Cat']['name'], array('controller' => 'cats', 'action' => 'view', $blogCat['Cat']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $blogCat['BlogCat']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $blogCat['BlogCat']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $blogCat['BlogCat']['id']), null, __('Are you sure you want to delete # %s?', $blogCat['BlogCat']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>

	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Blog Cat'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Blogs'), array('controller' => 'blogs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Blog'), array('controller' => 'blogs', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Cats'), array('controller' => 'cats', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Cat'), array('controller' => 'cats', 'action' => 'add')); ?> </li>
	</ul>
</div>
