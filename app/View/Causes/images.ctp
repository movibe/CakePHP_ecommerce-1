<?php
	//$this->start('header');
	//echo $this->element('Common/header');
	//$this->end();
?>
<div style="margin-top:71px; margin-left:20px;">
<?php echo $this->Form->create('Cause', array('action' => 'images', "enctype" => "multipart/form-data"));?>
    <?php
     
	   echo $this->Form->input('image',array("type" => "file")); 
        echo $this->Form->end('Upload');
		     ?>
<?php

if(isset($val)){
  		echo $this->Html->script('jquery-pack.js');
        echo $this->Html->script('jquery.imgareaselect.min.js');
        echo  $this->Form->create('Cause', array('action' => 'createimage_step3',"enctype" => "multipart/form-data"));    
        echo  $this->Form->input('id');
        echo  $this->Form->hidden('name');
		echo $this->Form->hidden('val', array('value' => $val));
		$uploadTo ='http://www.spreadnest.com/app/webroot/img/upload/prefix_'.$val;
        $uploadToTh='img/upload/thumb/prefix_'.$val;
	    echo  $this->Cropimage->createJavaScript($uploaded['imageWidth'],$uploaded['imageHeight'],151,151);
	  	echo  $this->Cropimage->createForm($uploadTo, 151,151);
        echo  $this->Form->submit('Done', array("id"=>"save_thumb"));
        echo  $this->Form->end();
}
		?> 

</div>
	