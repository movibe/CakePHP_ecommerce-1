<div class="textures view">
<h2><?php  echo __('Texture'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($texture['Texture']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($texture['Texture']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Is Active'); ?></dt>
		<dd>
			<?php echo h($texture['Texture']['is_active']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created'); ?></dt>
		<dd>
			<?php echo h($texture['Texture']['created']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Modified'); ?></dt>
		<dd>
			<?php echo h($texture['Texture']['modified']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Texture'), array('action' => 'edit', $texture['Texture']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Texture'), array('action' => 'delete', $texture['Texture']['id']), null, __('Are you sure you want to delete # %s?', $texture['Texture']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Textures'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Texture'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Designs'), array('controller' => 'designs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Design'), array('controller' => 'designs', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Designs'); ?></h3>
	<?php if (!empty($texture['Design'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('Title'); ?></th>
		<th><?php echo __('Description'); ?></th>
		<th><?php echo __('Texture Id'); ?></th>
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
		foreach ($texture['Design'] as $design): ?>
		<tr>
			<td><?php echo $design['id']; ?></td>
			<td><?php echo $design['title']; ?></td>
			<td><?php echo $design['description']; ?></td>
			<td><?php echo $design['texture_id']; ?></td>
			<td><?php echo $design['user_id']; ?></td>
			<td><?php echo $design['likes']; ?></td>
			<td><?php echo $design['comments']; ?></td>
			<td><?php echo $design['is_active']; ?></td>
			<td><?php echo $design['created']; ?></td>
			<td><?php echo $design['modified']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'designs', 'action' => 'view', $design['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'designs', 'action' => 'edit', $design['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'designs', 'action' => 'delete', $design['id']), null, __('Are you sure you want to delete # %s?', $design['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Design'), array('controller' => 'designs', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
