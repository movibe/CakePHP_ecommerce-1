<?php
	//$this->start('header');
	//echo $this->element('Common/header');
	//$this->end();
?>
<!--<script>
function imagevalidate()
{
 if(document.getElementById('ImageImage').value==''){
  alert('please eneter the file name');
   return false;
  } else{
    document.getElementById('ImageImagesForm').submit();
    return true;
	}
}
</script>-->
<div class="container">

	<?php  echo $this->element('Custom/causesteps'); ?>

	<div class="row">
		<div class="span10 offset1">
        <h3 class="title">Submit Your Cause</h3>
        <div class="horizontal-line"></div>
<?php echo $this->Form->create('Image', array('action' => 'images', "enctype" => "multipart/form-data"));?>
    <?php
     
	    echo $this->Form->input('image',array("type" => "file")); 
    	echo $this->Form->hidden('id', array('value' => $id));
	    if(!empty($id)){echo $this->Form->hidden('uid', array('value' => $uid));}
          echo $this->Form->end('Upload');
		     ?>
		
<?php

if(isset($val)){
  		echo $this->Html->script('jquery-pack.js');
        echo $this->Html->script('jquery.imgareaselect.min.js');
        echo  $this->Form->create('Image', array('action' => 'createimage_step3',"enctype" => "multipart/form-data"));    
        echo  $this->Form->input('id');
        echo  $this->Form->hidden('name');
		echo $this->Form->hidden('val', array('value' => $val));
		
		$uploadTo ='http://www.spreadnest.com/app/webroot/img/uploaded/'.$val;
        echo  $this->Cropimage->createJavaScript(800,600,200,245);
	  	echo  $this->Cropimage->createForm($uploadTo, 245,200);
        echo  $this->Form->submit('Done', array("id"=>"save_thumb"));
        echo  $this->Form->end();
}
		?> 
        	</div><!-- /span10 offset1 -->
		</div><!-- /row -->
</div><!-- /container -->
	