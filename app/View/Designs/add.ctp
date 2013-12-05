<?php
	$this->start('header');
	echo $this->element('Common/header');
	$this->end();
?>

<div class="container">
	<div class="row">
		<div class="span10 offset1">
			<?php 
				echo $this->Form->create('Design', array(
					'inputDefaults' => array('div' => false)
				));
			?>
			<fieldset>
				<legend>Add design</legend>
				<?php
					echo $this->Form->input('texture_id', array('class' => 'span10'));
					echo $this->Form->input('title', array('class' => 'span10'));
					echo $this->Form->input('description', array('class' => 'span10', 'type' => 'textarea'));
				?>
				<div class="control-group">
					<button type="submit" class="btn btn-green">Submit</button>
				</div>
			</fieldset>
		</div>
	</div>

</div>
<!--/span-->
<div id="push"></div>