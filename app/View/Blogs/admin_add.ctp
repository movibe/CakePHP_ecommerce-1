<?php
//ravi
/* if($isSaved){
	if(1 == $isSaved){
		$strFlash = '<p><strong>INFORMATION: </strong>The category "<strong>'.$category.'</strong>" has been added!</p>';
	}
	else{
		$strFlash = '<p><strong>INFORMATION: </strong>The category "<strong>'.$category.'</strong>" cannot be added! Please try again</p>';
	}
} */
?>

<div class="wrapper">
<div class="nNote nInformation hideit">
	<?php 
		//echo $strFlash;
	?>
</div>
<?php
	echo $this->Form->create('Blog', array('class' => 'form'));
?>
	<fieldset>
		<div class="widget">
			<div class="title"><?php echo $this->Html->image('crown/icons/dark/list.png', array('class' => 'titleIcon'))?><h6>Add News</h6></div>
			<div class="formRow">
				<?php	
					echo $this->Form->input('title');
				?>					
				<div class="clear"></div>
			</div>
			<div class="formRow">
				<div class="widget" style="margin-top: 0px">
					<div class="title"><?php echo $this->Html->image('crown/icons/dark/pencil.png', array('class' => 'titleIcon'))?><h6>News Editor</h6></div>
					<?php
						echo $this->Form->input('content', array('label' => false));	
					?>	
				</div>
			</div>
			
			<!--div class="formRow">
				<label for="tags">Tags:</label>
				<div class="formRight">
					<?php
						//echo $this->Form->input('tags', array('label' => false, 'class' => 'tags'));	
					?>
				</div>	
				<div class="clear"></div>
			</div>
			
			<div class="formRow">
				<label for="tags">Keywords:</label>
				<div class="formRight">
					<?php
						//echo $this->Form->input('keywords', array('label' => false, 'class' => 'tags'));	
					?>
				</div>
				<div class="clear"></div>
			</div-->
		
			<div class="formRow">
				<label>Categories:</label>
				<div class="formRight">
					<?php
						//echo $this->Form->checkbox('published', array('value' => 'Y'));
						foreach($categories as $category){
							echo $this->Form->checkbox('categoty'. $category['Cat']['id']);
							echo '<label for="check' . $category['Cat']['id']. '">' . $category['Cat']['name'] . '</label>';
						}
					?>											               
				</div>
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
		$('#BlogTags').tagsInput({width:'100%'});
		$('#BlogKeywords').tagsInput({width:'100%'});
		$("select, input:checkbox, input:radio, input:file").uniform();
		$("#BlogContent").cleditor({
            width:        '100%', // width not including margins, borders or padding
            height:       '100%', // height not including margins, borders or padding
            controls:     // controls to add to the toolbar
                          "bold italic underline strikethrough subscript superscript | font size " +
                          "style | color highlight removeformat | bullets numbering | outdent " +
                          "indent | alignleft center alignright justify | undo redo | " +
                          "rule image link unlink | cut copy paste pastetext | print source",
            colors:       // colors in the color popup
                          "FFF FCC FC9 FF9 FFC 9F9 9FF CFF CCF FCF " +
                          "CCC F66 F96 FF6 FF3 6F9 3FF 6FF 99F F9F " +
                          "BBB F00 F90 FC6 FF0 3F3 6CC 3CF 66C C6C " +
                          "999 C00 F60 FC3 FC0 3C0 0CC 36F 63F C3C " +
                          "666 900 C60 C93 990 090 399 33F 60C 939 " +
                          "333 600 930 963 660 060 366 009 339 636 " +
                          "000 300 630 633 330 030 033 006 309 303",   
            fonts:        // font names in the font popup
                          "Arial,Arial Black,Comic Sans MS,Courier New,Narrow,Garamond," +
                          "Georgia,Impact,Sans Serif,Serif,Tahoma,Trebuchet MS,Verdana",
            sizes:        // sizes in the font size popup
                          "1,2,3,4,5,6,7",
            styles:       // styles in the style popup
                          [["Paragraph", "<p>"], ["Header 1", "</p><h1>"], ["Header 2", "</h1><h2>"],
                          ["Header 3", "</h2><h3>"],  ["Header 4","</h3><h4>"],  ["Header 5","</h4><h5>"],
                          ["Header 6","</h5><h6>"]],
            useCSS:       false, // use CSS to style HTML when possible (not supported in ie)
            docType:      // Document type contained within the editor
                          '',
            docCSSFile:   // CSS file used to style the document contained within the editor
                          "",
            bodyStyle:    // style to assign to document body contained within the editor
                          "margin:4px; font:10pt Arial,Verdana; cursor:text"
          });
/* 		$('.nNote').hide();
		$('.submit').click(function(e){
			e.preventDefault();	
			$('#CategoryAdminAddForm').submit();
			$('.nNote').show();
		}) */
		
		//Ravi
		/* $('.nNote').hide();
		isSaved = '<?php echo $isSaved ?>';
		if(isSaved){
			$('.nNote').show();
		} 
		$('.nNote').click(function(e){
			e.preventDefault();	
			//$('#CategoryAdminAddForm').submit();
			$('.nNote').hide();
		})  */
	});	
</script>	

