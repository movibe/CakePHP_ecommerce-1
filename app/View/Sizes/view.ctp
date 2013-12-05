<div class="sizes view">
<h2><?php  echo __('Size'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($size['Size']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($size['Size']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Is Active'); ?></dt>
		<dd>
			<?php echo h($size['Size']['is_active']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($size['Size']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($size['Size']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Size'), array('action' => 'edit', $size['Size']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Size'), array('action' => 'delete', $size['Size']['id']), null, __('Are you sure you want to delete # %s?', $size['Size']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Sizes'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Size'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Items'), array('controller' => 'items', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Item'), array('controller' => 'items', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Items'); ?></h3>
	<?php if (!empty($size['Item'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Price'); ?></th>
		<th><?php echo __('Style Id'); ?></th>
		<th><?php echo __('Gender Id'); ?></th>
		<th><?php echo __('Brand Id'); ?></th>
		<th><?php echo __('Size Id'); ?></th>
		<th><?php echo __('Category Id'); ?></th>
		<th><?php echo __('User Id'); ?></th>
		<th><?php echo __('Likes'); ?></th>
		<th><?php echo __('Comments'); ?></th>
		<th><?php echo __('Is Active'); ?></th>
		<th><?php echo __('Created'); ?></th>
		<th><?php echo __('Modified'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($size['Item'] as $item): ?>
		<tr>
			<td><?php echo $item['id']; ?></td>
			<td><?php echo $item['title']; ?></td>
			<td><?php echo $item['description']; ?></td>
			<td><?php echo $item['price']; ?></td>
			<td><?php echo $item['style_id']; ?></td>
			<td><?php echo $item['gender_id']; ?></td>
			<td><?php echo $item['brand_id']; ?></td>
			<td><?php echo $item['size_id']; ?></td>
			<td><?php echo $item['category_id']; ?></td>
			<td><?php echo $item['user_id']; ?></td>
			<td><?php echo $item['likes']; ?></td>
			<td><?php echo $item['comments']; ?></td>
			<td><?php echo $item['is_active']; ?></td>
			<td><?php echo $item['created']; ?></td>
			<td><?php echo $item['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'items', 'action' => 'view', $item['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'items', 'action' => 'edit', $item['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'items', 'action' => 'delete', $item['id']), null, __('Are you sure you want to delete # %s?', $item['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Item'), array('controller' => 'items', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
