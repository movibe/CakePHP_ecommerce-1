<div class="blogs view">
<h2><?php  echo __('Blog'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($blog['Blog']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Title'); ?></dt>
		<dd>
			<?php echo h($blog['Blog']['title']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Content'); ?></dt>
		<dd>
			<?php echo h($blog['Blog']['content']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Keywords'); ?></dt>
		<dd>
			<?php echo h($blog['Blog']['keywords']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Is Active'); ?></dt>
		<dd>
			<?php echo h($blog['Blog']['is_active']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('User'); ?></dt>
		<dd>
			<?php echo $this->Html->link($blog['User']['name'], array('controller' => 'users', 'action' => 'view', $blog['User']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($blog['Blog']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($blog['Blog']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Blog'), array('action' => 'edit', $blog['Blog']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Blog'), array('action' => 'delete', $blog['Blog']['id']), null, __('Are you sure you want to delete # %s?', $blog['Blog']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Blogs'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Blog'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Users'), array('controller' => 'users', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New User'), array('controller' => 'users', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Blog Cats'), array('controller' => 'blog_cats', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Blog Cat'), array('controller' => 'blog_cats', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Blog Cats'); ?></h3>
	<?php if (!empty($blog['BlogCat'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Blog Id'); ?></th>
		<th><?php echo __('Cat Id'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($blog['BlogCat'] as $blogCat): ?>
		<tr>
			<td><?php echo $blogCat['id']; ?></td>
			<td><?php echo $blogCat['blog_id']; ?></td>
			<td><?php echo $blogCat['cat_id']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'blog_cats', 'action' => 'view', $blogCat['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'blog_cats', 'action' => 'edit', $blogCat['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'blog_cats', 'action' => 'delete', $blogCat['id']), null, __('Are you sure you want to delete # %s?', $blogCat['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Blog Cat'), array('controller' => 'blog_cats', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
