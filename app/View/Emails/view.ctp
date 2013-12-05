<div class="emails view">
<h2><?php  echo __('Email'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($email['Email']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('From'); ?></dt>
		<dd>
			<?php echo h($email['Email']['from']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('To'); ?></dt>
		<dd>
			<?php echo h($email['Email']['to']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Subject'); ?></dt>
		<dd>
			<?php echo h($email['Email']['subject']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Body'); ?></dt>
		<dd>
			<?php echo h($email['Email']['body']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Headers'); ?></dt>
		<dd>
			<?php echo h($email['Email']['headers']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Is Sent'); ?></dt>
		<dd>
			<?php echo h($email['Email']['is_sent']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Attempts'); ?></dt>
		<dd>
			<?php echo h($email['Email']['attempts']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Created Date'); ?></dt>
		<dd>
			<?php echo h($email['Email']['created_date']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Email'), array('action' => 'edit', $email['Email']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Email'), array('action' => 'delete', $email['Email']['id']), null, __('Are you sure you want to delete # %s?', $email['Email']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Emails'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Email'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Emails'), array('controller' => 'emails', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Email'), array('controller' => 'emails', 'action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Emails'); ?></h3>
	<?php if (!empty($email['email'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Id'); ?></th>
		<th><?php echo __('From'); ?></th>
		<th><?php echo __('To'); ?></th>
		<th><?php echo __('Subject'); ?></th>
		<th><?php echo __('Body'); ?></th>
		<th><?php echo __('Headers'); ?></th>
		<th><?php echo __('Is Sent'); ?></th>
		<th><?php echo __('Attempts'); ?></th>
		<th><?php echo __('Created Date'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($email['email'] as $email): ?>
		<tr>
			<td><?php echo $email['id']; ?></td>
			<td><?php echo $email['from']; ?></td>
			<td><?php echo $email['to']; ?></td>
			<td><?php echo $email['subject']; ?></td>
			<td><?php echo $email['body']; ?></td>
			<td><?php echo $email['headers']; ?></td>
			<td><?php echo $email['is_sent']; ?></td>
			<td><?php echo $email['attempts']; ?></td>
			<td><?php echo $email['created_date']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'emails', 'action' => 'view', $email['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'emails', 'action' => 'edit', $email['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'emails', 'action' => 'delete', $email['id']), null, __('Are you sure you want to delete # %s?', $email['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Email'), array('controller' => 'emails', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>
