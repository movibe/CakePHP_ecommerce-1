<?php
//ravi
if($isSaved){
	if(1 == $isSaved){
		$strFlash = '<p><strong>INFORMATION: </strong>The category "<strong>'.$category.'</strong>" has been added!</p>';
	}
	else{
		$strFlash = '<p><strong>INFORMATION: </strong>The category "<strong>'.$category.'</strong>" cannot be added! Please try again</p>';
	}
}
?>

<div class="wrapper">
<div class="nNote nInformation hideit">
	<?php 
		echo $strFlash;
	?>
</div>
<?php
	echo $this->Form->create('Cat', array('class' => 'form'));
?>
	<fieldset>
		<div class="widget">
			<div class="title"><?php echo $this->Html->image('crown/icons/dark/list.png', array('class' => 'titleIcon'))?><h6>Add News Category</h6></div>
			
			<div class="formRow">
				<?php	
					echo $this->Form->input('name');
				?>					
				<div class="clear"></div>
			</div>
			
			<div class="formSubmit">
				<?php 
					echo $this->Form->end(__('Submit'));
				?>	
			</div>
			<div class="clear"></div>
		</div>
	</fieldset>

</div>	
<script>
	 $(document).ready(function() {
/* 		$('.nNote').hide();
		$('.submit').click(function(e){
			e.preventDefault();	
			$('#CategoryAdminAddForm').submit();
			$('.nNote').show();
		}) */
		
		//Ravi
		$('.nNote').hide();
		isSaved = '<?php echo $isSaved ?>';
		if(isSaved){
			$('.nNote').show();
		} 
		$('.nNote').click(function(e){
			e.preventDefault();	
			//$('#CategoryAdminAddForm').submit();
			$('.nNote').hide();
		}) 
	});	
</script>	