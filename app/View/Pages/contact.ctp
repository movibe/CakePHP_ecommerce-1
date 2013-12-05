<?php
	$this->start('header');
	//$buttons['designs']['isActive'] =  true;
	echo $this->element('Common/header', array('buttons' => $buttons));
	$this->end();
?>
<div class="container other">
	<div class="row">
		<div class="span9">
        <h3 class="title">Contact Us</h3>
        <div class="horizontal-line"></div>
        <form class="form-horizontal" method="Post" action="<?php echo $this->Html->url(array('controller'=>'emails', 
												'action' => 'send_mail'
											)); ?>">
		<fieldset>
			<?php //echo $this->Form->create(null, array('url' => array('controller' => 'Emails', 'action' => 'send_email'))); ?>
		
			<div class="control-group">

				  <!-- Text input-->
				  <label class="control-label" for="input01"><strong>NAME</strong></label>
				  <div class="controls">
					<input placeholder="Enter Your Full Name"  name="data[Email][0][name]" class="input-xlarge" type="text">
					<p class="help-block"></p>
				  </div>
			</div>
			<div class="control-group">

				  <!-- Text input-->
				  <label class="control-label"  for="input01"><strong>EMAIL ID</strong></label>
				  <div class="controls">
					<input placeholder="Enter Your Email Id"  name="data[Email][1][from]"class="input-xlarge" type="text">
					<p class="help-block"></p>
				  </div>
			</div>
			

			<div class="control-group">

				  <!-- Text input-->
				  <label class="control-label" for="input01"><strong>SUBJECT</strong></label>
				  <div class="controls">
					<input placeholder="Enter Subject" name="data[Email][2][subject]" class="input-xlarge" type="text">
					<p class="help-block"></p>
				  </div>
				</div><div class="control-group">

				  <!-- Textarea -->
				  <label class="control-label"><strong>MAIL CONTENT</strong></label>
				  <div class="controls">
					<div class="textarea">
						  <textarea placeholder="Enter Mail Content " type="" name="data[Email][3][content]" rows="8" class="input-xlarge"> </textarea>
					</div>
				  </div>
				</div>

			<div class="control-group">
				 <label class="control-label"></label>
				  <!-- Button -->
				  <div class="controls">
					 <button type="submit" class="btn-green btn">SUBMIT</button> 
				  </div>
			</div>
    </fieldset>
  </form>

</div>

    <div class="span3">
    	<div class="right-sidebar">
    		<h3 class="title">Contact Us</h3>
        	<div class="horizontal-line"></div>
        
        	<p><strong>Alex Golke (Founder)</strong><p>
                
                
			<p><strong>Address</strong><br />
            27510-40 Ave<br>
			Langley, BC</p>
            
			<p><strong>Phone</strong><br />
            1-604-845-4725<br>
			<a href="mailto:agolke@spreadnest.com">agolke@spreadnest.com</a><br></p>
            
            <div class="break"></div>
            
            <h3 class="title">Social Media</h3>
        	<div class="horizontal-line"></div>
            
						<ul class="inline">
							<!--<li><?php //echo $this->Html->image('social-icons/google.png');?>
							</li>-->
							<li><?php echo $this->Html->image('social-icons/twitter.png');?>
							</li>
							<li><?php echo $this->Html->image('social-icons/facebook.png');?>
							</li>
						</ul>
            
        </div><!-- /.right-sidebar -->

</div>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$("#contact-us-page").addClass("active-menu");
	});
</script>
