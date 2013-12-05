	<div class="row">
		<div class="span10 offset1">
        	<div class="cause-steps">
            
            <!-- xxxxxx STEP 1 xxxxxx -->
            <div class="cause-step-container<?php $url1 = '/index.php/causes/add';
                    
                    if($url1 == $this->here){
                        echo " selected";
                    }
                    else{
                        echo "";	
                    }
                    ?>">
                    
            	<div class="cause-step1"></div>
            	<h4 class="stepTitle">Cause Information</h4>
                <div class="clear"></div>
                <p>Enter all your information regarding your charity here including cause title, cause name, and your experience / description.</p>
            </div><!-- /cause-step-container -->
            
            <!-- xxxxxx STEP 2 xxxxxx -->
            <div class="cause-step-container<?php $url='/index.php/images/images/';
                    $address = $this ->here;
					$findme = strpos($address, $url);
                    if($findme === false){
                        echo "";
                    }
                    else{
                        echo " selected";	
                    }
                    ?>">
                    
            	<div class="cause-step2"></div>
            	<h4 class="stepTitle">Upload Image</h4>
                <div class="clear"></div>
                <p>Upload your main picture for your cause. You will have the chance to crop it the way you like. This image will be used for the thumbnail</p>
            </div><!-- /cause-step-container -->
            
            <!-- xxxxxx STEP 3 xxxxxx -->
            <div class="cause-step-container<?php /*$url = $this->here; 
                    
                    if($url=='/index.php/causes/add'){
                        echo " selected";
                    }
                    else{
                        echo "";	
                    }
                    */?>">
            	<div class="cause-step3"></div>
            	<h4 class="stepTitle">Additional Images</h4>
                <div class="clear"></div>
                <p>upload any additional images that will be helpful in showcasing your charity. Once done your cause will be submitted to the cause</p>
            </div><!-- /cause-step-container -->
            
            </div><!-- /cause-steps -->
        </div><!-- /span10 offset1 -->
    </div><!-- /row -->