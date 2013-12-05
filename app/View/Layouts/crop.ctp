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
echo $this->Html->css(array('crop', 'style', 'uniformjs', 'http://blueimp.github.com/Bootstrap-Image-Gallery/css/bootstrap-image-gallery.min.css', 'fileupload/jquery.fileupload-ui'));?>




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
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=440004826068724";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<!-- Login alert Modal -->
	<?php /*?><div id="loginModal" class="modal hide fade" tabindex="-1" role="dialog"
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
	</div><?php */?>
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
							<!-- li><a href="#contact">Blog</a>
							</li-->
							<li><?php echo $this->Html->link('Contact', array('controller' => 'pages', 'action' => 'display', 'contact'),array('id' => 'contact-us-page',));?>
							</li>

						</ul>
                        
                        <div class="right-header-buttons">
							<div class="facebook-header"><span class="fb pull-right"><fb:like href="<?php echo $this->Html->url(array('controller' => 'causes', 'action' => 'index'), true);?>" layout="button_count" width="100" show_faces="true"></fb:like></span></div>
							<div class="twitter-header"><span class="fb pull-right">
							<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $this->Html->url(array('controller' => 'causes', 'action' => 'index'), true);?>" data-size="small"></a>
							<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
						</span></div>
						<?php
						if(!$loggedIn){
							echo $this->Html->link('Sign Up', array('controller' => 'users', 'action' => 'login'), array('class' => 'btn pull-right'));
							echo $this->Html->link('Sign In', array('controller' => 'users', 'action' => 'login', 'facebook'), array('class' => 'btn btn-green pull-right'));
						} else{
							echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout'), array('class' => 'btn btn-green pull-right'));
						}
						?>
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
							<li><?php echo $this->Html->image('social-icons/twitter.png');?>
							</li>
							<li><?php echo $this->Html->image('social-icons/facebook.png');?>
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
