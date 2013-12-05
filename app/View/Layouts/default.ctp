<!DOCTYPE html>
<html xmlns:fb="http://ogp.me/ns/fb#">
<head>
<meta charset="utf-8">
<title>Spreadnest - Creative Choice</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le styles -->
<?php
echo $this->Html->css(array('bootstrap', 'style', 'uniformjs', 'http://blueimp.github.com/Bootstrap-Image-Gallery/css/bootstrap-image-gallery.min.css', 'fileupload/jquery.fileupload-ui', 'jquery.mCustomScrollbar'));

echo $this->Html->script(array(
		'jquery',
		'jquery-migrate',
		'holder/holder',
		'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js',
		'ui/jquery.tipsy',
		'ui/jquery.collapsible.min',
		'ui/jquery.prettyPhoto',
		'ui/jquery.progress',
		'ui/jquery.timeentry.min',
		'ui/jquery.colorpicker',
		'ui/jquery.jgrowl',
		'ui/jquery.breadcrumbs',
		'ui/jquery.sourcerer',
        'ui/jquery.tinyscrollbar.min',
		'bootstrap',
		'jquery.nicescroll.min',
		'jquery.mCustomScrollbar'
		));
		?>


<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

<!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144"
	href="/outsourcing/spreadnest/app/webroot/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114"
	href="/outsourcing/spreadnest/app/webroot/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72"
	href="/outsourcing/spreadnest/app/webroot/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed"
	href="/outsourcing/spreadnest/app/webroot/ico/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon"
	href="/outsourcing/spreadnest/app/webroot/ico/favicon.png">

</head>

<body>
<?php
 #code to find the geolocation of the country
    $user_ip_address=$_SERVER['REMOTE_ADDR'];  
    echo "User's Ip address = " . $user_ip_address;
?>

	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=440004826068724";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<!-- Login alert Modal -->
	<div id="loginModal" class="modal hide fade" tabindex="-1" role="dialog"
		aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal"
				aria-hidden="true"><?php echo $this->Html->image('close.png');?></button>
			<h5 id="myModalLabel">Please login to proceed</h5>
		</div>
		<div class="modal-body text-center">
			<p><?php echo $this->Html->image('facebook.png', array('url' => array('controller' => 'users', 'action' => 'login', 'facebook')));?></p>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		</div>
	</div>
	<div id="wrap" style="background-color:#eaf0f0;">
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner navbar-eswar">
				<div class="container">
					<?php /* echo $this->Html->link("Spreadnest",array('controller' => 'pages','action' =>'display'),array('class'=>'brand'));*/ ?>
                    <div class="main-logo"><a href="http://www.spreadnest.com"><?php echo $this->Html->image('spreadnest_logo.png');?></a></div>
					<div class="nav-collapse collapse">
						<ul class="nav">
							<!--<li class="active dropdown"><a href="#" class="dropdown-toggle"
								data-toggle="dropdown">Explore <b class="caret"></b> </a>
								<ul class="dropdown-menu">
									<li><?php echo $this->Html->link('Causes', array('controller' => 'causes', 'action' => 'index'))?>
									</li>
									<li><?php echo $this->Html->link('Designs', array('controller' => 'designs', 'action' => 'index'))?>
									</li>
									<li><?php echo $this->Html->link('Shop', array('controller' => 'pages', 'action' => 'display','shop'))?>
									</li>
								</ul>
							</li>-->
							<li><?php echo $this->Html->link('About Us', array('controller' => 'pages', 'action' => 'display', 'about-us'),array('id' => 'abouts-us-page'));?>
							</li>
							<!-- <li><a href="#contact">Blog</a>
							</li>-->
							<li><?php echo $this->Html->link('Contact', array('controller' => 'pages', 'action' => 'display', 'contact'),array('id' => 'contact-us-page',));?>
							</li>

						</ul>
                        
                        <div class="right-header-buttons">
				<div class="facebook-header">
					<span class="fb pull-right">
						<fb:like href="<?php echo $this->Html->url(array('controller' => 'causes', 'action' => 'index'), true);?>" layout="button_count" width="100" show_faces="true"></fb:like>
					</span>
				</div>	
							<div class="twitter-header"><span class="fb pull-right">
							<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $this->Html->url(array('controller' => 'causes', 'action' => 'index'), true);?>" data-size="small"></a>
							<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
											
							</script>
						</span>
						<li class="fbright"><a href="https://facebook.com"><?php echo $this->Html->image('social-icons/facebook.png');?></a></li>
						
						<li class="flags">
						<?php 
						#flags
						   if (isset($_SERVER['HTTP_CLIENT_IP'])) {$real_ip_adress=$_SERVER['HTTP_CLIENT_IP'];} 
								 else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {$real_ip_adress=$_SERVER['HTTP_X_FORWARDED_FOR'];} 
								 else {$user_ip_address=$_SERVER['REMOTE_ADDR'];}
								$country = file_get_contents('http://api.hostip.info/country.php?ip='.$user_ip_address);
							      if($country=='XX' || $country=='CA'){
								     echo $this->Html->image('social-icons/can.png',array('width'=>60,'height'=>'35'));
								    }
								   if($country=='AUS'){
									 echo $this->Html->image('social-icons/aus.jpg',array('width'=>60,'height'=>'35'));
									}
								   if($country=='UK'){
									 echo $this->Html->image('social-icons/uk.jpg',array('width'=>60,'height'=>'35'));
									}
								   if($country=='US')
									{
									  echo $this->Html->image('social-icons/us.jpg',array('width'=>60,'height'=>'35'));
									
									}
						          	if($country=='IN'){
									  echo $this->Html->image('social-icons/can.png',array('width'=>60,'height'=>'35'));
									}
						
						  
						#End
						
						
						?>
						</li>
						</div>
						<?php
						if(!$loggedIn){
							echo $this->Html->link('Sign Up', array('controller' => 'users', 'action' => 'login'), array('class' => 'btn pull-right'));
							echo $this->Html->link('Sign In', array('controller' => 'users', 'action' => 'login', 'facebook'), array('class' => 'btn btn-green pull-right'));
						} else{
							echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'), array('class' => 'btn btn-green pull-right'));
						}
						?>
							<script>
								$('.btn.btn-green.pull-right').mousedown( function() {
									$('.btn.btn-green.pull-right').css('color','#333');
									$('.btn.btn-green.pull-right').css('background-color','#f5f5f5');
									$('.btn.btn-green.pull-right').css('background-image','-moz-linear-gradient(top, #ffffff, #e6e6e6)');
									$('.btn.btn-green.pull-right').css('background-image','-webkit-gradient(linear, 0 0, 0 100%, from(#ffffff), to(#e6e6e6))');
									$('.btn.btn-green.pull-right').css('background-image','-webkit-linear-gradient(top, #ffffff, #e6e6e6)');
									$('.btn.btn-green.pull-right').css('background-image','-o-linear-gradient(top, #ffffff, #e6e6e6)');
									$('.btn.btn-green.pull-right').css('background-image','linear-gradient(to bottom, #ffffff, #e6e6e6)');
									$('.btn.btn-green.pull-right').css('background-repeat','repeat-x');
									$('.btn.btn-green.pull-right').css('border','1px solid #cccccc');
									$('.btn.btn-green.pull-right').css('border-color','#e6e6e6 #e6e6e6 #bfbfbf');
									$('.btn.btn-green.pull-right').css('border-color','rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25)');
									$('.btn.btn-green.pull-right').css('border-bottom-color','#b3b3b3');
									$('.btn.btn-green.pull-right').css('-webkit-border-radius','4px');
									$('.btn.btn-green.pull-right').css('-moz-border-radius','4px');
									$('.btn.btn-green.pull-right').css('border-radius','4px');
									$('.btn.btn-green.pull-right').css('-webkit-box-shadow','inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05)');
									$('.btn.btn-green.pull-right').css('-moz-box-shadow','inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05)');
									$('.btn.btn-green.pull-right').css('box-shadow','inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05)');
								});
/*								
								$('.btn.pull-right').mousedown( function() {
									$('.btn.btn-green.pull-right').css('color','#ffffff');
									$('.btn.btn-green.pull-right').css('text-shadow','0 -1px 0 rgba(0, 0, 0, 0.25)');
									$('.btn.btn-green.pull-right').css('background-image','-moz-linear-gradient(top, #4095aa, #5ebdb9)');
									$('.btn.btn-green.pull-right').css('background-image','-webkit-gradient(linear, 0 0, 0 100%, from(#4095aa), to(#74b211) )');
									$('.btn.btn-green.pull-right').css('background-image','-webkit-linear-gradient(top, #4095aa, #5ebdb9)');
									$('.btn.btn-green.pull-right').css('background-image','-o-linear-gradient(top, #4095aa, #5ebdb9)');
									$('.btn.btn-green.pull-right').css('background-image','linear-gradient(to bottom, #4095aa, #5ebdb9)');
									$('.btn.btn-green.pull-right').css('background-repeat','repeat-x');
									$('.btn.btn-green.pull-right').css('border-color','#4faeac #4faeac #4faeac');
									$('.btn.btn-green.pull-right').css('border-color','rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25)');
								});	*/							
							</script>						
                        </div>

					</div>
					<!--/.nav-collapse -->
				</div>
			</div>
			<?php echo $this->fetch('header'); ?>

		</div>

		<?php
		echo $this->fetch('content');
		?>
	</div>

	<div id="footer">
		<div class="container">
			<div class="row">
				<div class="span6">
					<ul class="inline muted copyright">
						<li><strong><?php echo $this->Html->link('Contact', array('controller' => 'pages', 'action' => 'display', 'contact'),array('id' => 'contact-us-page',));?></strong>
						</li>
						<li><strong>Terms of Service</strong>
						</li>
						<li><strong>Privacy Policy</strong>
						</li>
						<!--<li><strong>Blog</strong>-->
						</li>
					</ul>
				</div>
				<div class="span6">
					<span class="pull-right">
						<ul class="inline">
							<!--<li><?php //echo $this->Html->image('social-icons/google.png');?>
							</li>-->
							<li><a href="https://twitter.com/"><?php echo $this->Html->image('social-icons/twitter.png');?></a>
							</li>
							<li><a href="https://facebook.com"><?php echo $this->Html->image('social-icons/facebook.png');?></a>
							</li>
						</ul> </span>
				</div>
			</div>
			<div class="row">
				<div class="span6">
					<ul class="inline muted copyright">
						<li>Copyright &copy; Spreadnest</li>
						<li>Terms of Use</li>
					</ul>
				</div>
				<div class="span6"></div>
			</div>
		</div>
	</div>
</body>
</html>

