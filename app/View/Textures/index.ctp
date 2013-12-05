<div class="textures index">
	<h2><?php echo __('Textures'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('is_active'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
	foreach ($textures as $texture): ?>
	<tr>
		<td><?php echo h($texture['Texture']['id']); ?>&nbsp;</td>
		<td><?php echo h($texture['Texture']['name']); ?>&nbsp;</td>
		<td><?php echo h($texture['Texture']['is_active']); ?>&nbsp;</td>
		<td><?php echo h($texture['Texture']['created']); ?>&nbsp;</td>
		<td><?php echo h($texture['Texture']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $texture['Texture']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $texture['Texture']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $texture['Texture']['id']), null, __('Are you sure you want to delete # %s?', $texture['Texture']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Texture'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Designs'), array('controller' => 'designs', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Design'), array('controller' => 'designs', 'action' => 'add')); ?> </li>
	</ul>
</div>
