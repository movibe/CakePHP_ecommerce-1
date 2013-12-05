<?php
        echo $this->Html->script('jquery-pack.js');
        echo $this->Html->script('jquery.imgareaselect.min.js');

?>
<?php
if(isset($val)){
        echo $val;
		echo $this->Html->script('jquery-pack.js');
        echo $this->Html->script('jquery.imgareaselect.min.js');
        echo  $this->Form->create('Image', array('action' => 'createimage_step3',"enctype" => "multipart/form-data"));    
        echo  $this->Form->input('id');
        echo  $this->Form->hidden('name');
		echo $uploadTo ='http://localhost/cake/app/webroot/img/upload/prefix_'.$val;
        echo  $this->Cropimage->createJavaScript(800,600,151,151);
	  	echo  $this->Cropimage->createForm($uploadTo, 151,151);
        echo  $this->Form->submit('Done', array("id"=>"save_thumb"));
        echo  $this->Form->end();
}
		?> 
