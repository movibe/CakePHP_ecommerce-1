<?php
	$this->start('header');
	echo $this->element('Common/header');
	$this->end();
?>

<div class="container">

	<?php  echo $this->element('Custom/causesteps'); ?>

	<div class="row">
		<div class="span10 offset1">
        <h3 class="title">Submit Your Cause</h3>
        <div class="horizontal-line"></div>
        
        				<p>Browse and search for registered US charities <a href="http://www.irs.gov/Charities-&-Non-Profits/Search-for-Charities" target="_blank">here</a></p>
			<?php 
				echo $this->Form->create('Cause', array(
					'inputDefaults' => array('div' => false)
				));
			?>
			<fieldset>
				<!--<legend>Submit your cause</legend>-->
				
				<?php
					echo $this->Form->input('title', array('class' => 'span10', 'label' => 'Title'));
				?>
				<?php
					echo $this->Form->input('cause_name', array('class' => 'span10'));
					echo $this->Form->input('description',array('class' => 'spancustom', 'type' => 'textarea', 'label' =>'<b>Share your experience with this cause</b>'));
					
				?>
           
				<!--<div class="input-append">-->
				<?php /* <?php
					echo $this->Form->input('goal', array('class' => 'span3', 'label' => 'Goal', 'value' => 1000, 'readonly' => true));
				?>*/?>
					<!--<span class="add-on">$</span>
				</div>
				<div class="uiRangeSlider"></div>
				<div class="control-group">-->
					<button type="submit" class="btn btn-green">Submit</button>
				</div>
				
			</fieldset>
		</div>
	</div>

</div>
<!--/span-->
<!--<div id="push"></div>
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
</script>-->