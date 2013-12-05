<div class="wrapper">
	<div class="widget">
		<div class="span10 offset1">
			<?php 
				echo $this->Form->create('Cause'
				);
			?>
			<div class="title"><?php echo $this->Html->image('crown/icons/dark/list.png', array('class' => 'titleIcon'))?><h6>Edit Cause</h6></div>
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
				<?php
					echo $this->Form->input('goal', array('class' => 'formRight', 'label' => 'Goal', 'value' => 1000, 'readonly' => true));
				?>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="uiRangeSlider"></div>
				</div>
				<div class="formRow">
					<div class="control-group">
						<button type="submit" class="button blueB">Submit</button>
					</div>
				</div>
			
		</div>
	</div>
	<?php echo $this->Html->link('<span>Manage Images</span>',array('controller' => 'causes','action' => 'images', 'admin' => false, $cause['Cause']['id']),array('class' =>'button blueB','escape' => false, 'target' => '_blank'));?>

</div>
<script>
$(function() {
	$(".uiRangeSlider").slider({
		range: "min",
		min: 1000,
		max: 30000,
		value : 1000,
		slide : function(event, ui) {
			$('#CauseGoal').val(ui.value);
		}
	});
});
</script>