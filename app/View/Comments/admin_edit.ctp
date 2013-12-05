<div class="wrapper">
	<div class="widget">
		<?php echo $this->Form->create('Comment'); ?>
		<div class="title"><?php echo $this->Html->image('crown/icons/dark/list.png', array('class' => 'titleIcon'))?><h6>Edit design</h6></div>
		<div class="formRow">
			<?php echo $this->Form->input('comment',array('class' => 'formRight')); ?>
			<div class="clear"></div>
		</div>
		<div class="formRow">
			<?php echo $this->Form->input('model',array('class' => 'formRight')); ?>
			<div class="clear"></div>
		</div>
		<div class="formRow">
			<div class="control-group">
				<button type="submit" class="button blueB">Submit</button>
			</div>
		</div>
	</div>
</div>