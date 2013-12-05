<div class="wrapper">
	<div class="widget">
		<div class="span10 offset1">
			<?php 
				echo $this->Form->create('Design', array(
					'inputDefaults' => array('div' => false)
				));
			?>
			<div class="title"><?php echo $this->Html->image('crown/icons/dark/list.png', array('class' => 'titleIcon'))?><h6>Edit design</h6></div>
				<div class="formRow">
				<?php
					echo $this->Form->input('texture_id', array('class' => 'formRight')); ?>
					<div class="clear"></div>
				</div>
				<div class="formRow">
				<?php
					echo $this->Form->input('title', array('class' => 'formRight'));
				?>
					<div class="clear"></div>
				</div>
				<div class="formRow">
				<?php
					echo $this->Form->input('description', array('class' => 'formRight', 'type' => 'textarea'));
				?>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="control-group">
						<button type="submit" class="button blueB">Submit</button>
					</div>
				</div>
			
		</div>
	</div>
	<?php echo $this->Html->link('<span>Manage Images</span>',array('controller' => 'designs','action' => 'images', 'admin' => false, $design['Design']['id']),array('class' =>'button blueB','escape' => false, 'target' => '_blank'));?>
</div>
