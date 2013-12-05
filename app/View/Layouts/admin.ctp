<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<title><?php echo 'Header'; ?></title>

<?php 
	echo $this->Html->css(array('crown/main','crown/datatable','css/bootstrap'));//,'crown/datatable','crown/elfinder','crown/fullcalendar','crown/prettyPhoto','crown/reset','crown/ui-cutom.css'));		//css files
	echo $this->Html->script(array(
									'http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js',
									'crown/crown_js/plugins/spinner/ui.spinner.js',
								    'crown/crown_js/spinner/jquery.mousewheel.js',
								    'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js',
								    'crown/crown_js/plugins/charts/excanvas.min.js',
								    'crown/crown_js/plugins/charts/jquery.sparkline.min.js',
								    'crown/crown_js/plugins/forms/uniform.js',
								    'crown/crown_js/plugins/forms/jquery.cleditor.js',
								    'crown/crown_js/plugins/forms/jquery.validationEngine-en.js',
								    'crown/crown_js/plugins/forms/jquery.validationEngine.js',
								    'crown/crown_js/plugins/forms/jquery.tagsinput.min.js',
								    'crown/crown_js/plugins/forms/autogrowtextarea.js',
								    'crown/crown_js/plugins/forms/jquery.maskedinput.min.js',
								    'crown/crown_js/plugins/forms/jquery.dualListBox.js',
								    'crown/crown_js/plugins/forms/jquery.inputlimiter.min.js',
								    'crown/crown_js/plugins/forms/chosen.jquery.min.js',
								    'crown/crown_js/plugins/wizard/jquery.form.js',
								    'crown/crown_js/plugins/wizard/jquery.validate.min.js',
								    'crown/crown_js/plugins/wizard/jquery.form.wizard.js',
									'crown/crown_js/plugins/uploader/plupload.js',
								    'crown/crown_js/plugins/uploader/plupload.html5.js',
								    'crown/crown_js/plugins/uploader/plupload.html4.js',
								    'crown/crown_js/plugins/uploader/jquery.plupload.queue.js',			
								    'crown/crown_js/plugins/tables/datatable.js',
								    'crown/crown_js/plugins/tables/tablesort.min.js',
								    'crown/crown_js/plugins/tables/resizable.min.js',
								    'crown/crown_js/plugins/ui/jquery.tipsy.js',
								    'crown/crown_js/plugins/ui/jquery.collapsible.min.js',
								    'crown/crown_js/plugins/ui/jquery.prettyPhoto.js',
								    'crown/crown_js/plugins/ui/jquery.progress.js',
								    'crown/crown_js/plugins/ui/jquery.timeentry.min.js',
								    'crown/crown_js/plugins/ui/jquery.colorpicker.js',
								    'crown/crown_js/plugins/ui/jquery.jgrowl.js',
								    'crown/crown_js/plugins/ui/jquery.breadcrumbs.js',
								    'crown/crown_js/plugins/ui/jquery.sourcerer.js',
								    'crown/crown_js/plugins/calendar.min.js',
								    'crown/crown_js/plugins/elfinder.min.js',
									'crown/crown_js/custom.js'
									
								)
								
							);	//js files		

?>


</head>
<body>
<!-- Left side content -->
<div id="leftSide">
    <div class="logo"><a href="index.html"><?php echo $this->Html->image('crown/logo.png'); ?></a></div>
    
    <div class="sidebarSep mt0"></div>
    <!--
   
    <form action="" class="sidebarSearch">
        <input type="text" name="search" placeholder="search..." id="ac" />
        <input type="submit" value="" />
    </form>
    
    <div class="sidebarSep"></div>

    
    <div class="genBalance">
        <a href="#" title="" class="amount">
            <span>General balance:</span>
            <span class="balanceAmount">$10,900.36</span>
        </a>
        <a href="#" title="" class="amChanges">
            <strong class="sPositive">+0.6%</strong>
        </a>
    </div>
    

    <div class="nextUpdate">
        <ul>
            <li>Next update in:</li>
            <li>23 hrs 14 min</li>
        </ul>
        <div class="pWrapper"><div class="progressG" title="78%" style="width: 78%;"></div></div>
    </div>
    
    <div class="sidebarSep"></div>
    -->
    <!-- Left navigation -->
    <ul id="menu" class="nav">
        <li class="files"><?php echo $this->Html->link("<span>Causes</span>",array('controller' => 'causes','action' => 'admin_causes'),array('escape' => false)) ;?></li>
        <li class="files"><?php echo $this->Html->link("<span>Products</span>",array('controller' => 'products','action' => 'admin_products'),array('escape' => false)) ;?></li>
        <li class="files"><?php echo $this->Html->link("<span>Designs</span>",array('controller' => 'designs','action' => 'admin_designs'),array('escape' => false)) ;?></li>
		<li class="files"><?php echo $this->Html->link("<span>Settings</span>",array('controller' => 'settings','action' => 'admin_settings'),array('escape' => false)) ;?></li>
		<li class="forms"><a href="#" title="" class="exp" ><span>Comments</span><strong>3</strong></a>
			<ul class="sub">
				<li><?php echo $this->Html->link('Causes',array('controller' => 'comments','action' => 'admin_causes_comments')) ;?></li>
                <li><?php echo $this->Html->link('Designs',array('controller' => 'comments','action' => 'admin_designs_comments')) ;?></li>
                <li><?php echo $this->Html->link('Products',array('controller' => 'comments','action' => 'admin_products_comments')) ;?></li>
            </ul>
		</li>
		 <li class="forms"><a href="#" title="" class="exp"><span>News</span><strong>2</strong></a>
            <ul class="sub">
                <li><?php echo $this->Html->link('Add News', array('controller' => 'Blogs', 'action' => 'admin_add'));?></li>
                <li><?php echo $this->Html->link('Add Categories', array('controller' => 'Cats', 'action' => 'admin_add'));?></li>
            </ul>
        </li>
		<li class="files"><?php echo $this->Html->link("<span>Users</span>",array('controller' => 'users','action' => 'admin_users_list'),array('escape' => false)) ;?></li>
        <!--li class="typo"><a href="#" title="" class="exp"><span>Other pages</span><strong>3</strong></a>
            <ul class="sub">
                <li><a href="typography.html" title="">Typography</a></li>
                <li><a href="calendar.html" title="">Calendar</a></li>
                <li class="last"><a href="gallery.html" title="">Gallery</a></li>
            </ul>
        </li-->
    </ul>
</div>
<!-- Right side -->
<div id="rightSide">

    <!-- Top fixed navigation -->
    <div class="topNav">
        <div class="wrapper">
            <div class="welcome"><a href="#" title=""><?php echo $this->Html->image('crown/userPic.png'); ?></a><span>Welcome administrator!</span></div>
            <div class="userNav">
                <ul>
                    <!--<li><a href="#" title=""><?php echo $this->Html->image('crown/icons/topnav/profile.png'); ?><span>Profile</span></a></li>
                    <li><a href="#" title=""><?php echo $this->Html->image('crown/icons/topnav/tasks.png'); ?><span>Tasks</span></a></li>
                    <li class="dd"><a title=""><?php echo $this->Html->image('crown/icons/topnav/messages.png'); ?><span>Messages</span><span class="numberTop">8</span></a>
                        <ul class="userDropdown">
                            <li><a href="#" title="" class="sAdd">new message</a></li>
                            <li><a href="#" title="" class="sInbox">inbox</a></li>
                            <li><a href="#" title="" class="sOutbox">outbox</a></li>
                            <li><a href="#" title="" class="sTrash">trash</a></li>
                        </ul>
                    </li>
                    <li><a href="#" title=""><img src="crown/icons/topnav/settings.png" alt="" /><span>Settings</span></a></li>-->
                    <li><?php echo $this->Html->link('<img src="crown/icons/topnav/logout.png" alt="" /><span>Logout</span>',array('controller' => 'causes','action' =>'logout'),array('escape' => false)); ?></li>
                </ul>
            </div>
            <div class="clear"></div>
        </div>
    </div>

<!-- Responsive header -->
    <div class="resp">
        <div class="respHead">
            <a href="index.html" title=""><?php echo $this->Html->image('crown/loginLogo.png'); ?></a>
        </div>
        
        <div class="cLine"></div>
        <!--<div class="smalldd">
            <span class="goTo"><?php echo $this->Html->image('crown/icons/light/home.png'); ?>Dashboard</span>
            <ul class="smallDropdown">
                <li><a href="index.html" title=""><?php echo $this->Html->image('crown/icons/light/home.png'); ?>Dashboard</a></li>
                <li><a href="charts.html" title=""><?php echo $this->Html->image('crown/icons/light/stats.png'); ?>Statistics and charts</a></li>
                <li><a href="#" title="" class="exp"><?php echo $this->Html->image('crown/icons/light/pencil.png'); ?><Forms stuff<strong>4</strong></a>
                    <ul>
                        <li><a href="forms.html" title="">Form elements</a></li>
                        <li><a href="form_validation.html" title="">Validation</a></li>
                        <li><a href="form_editor.html" title="">WYSIWYG and file uploader</a></li>
                        <li class="last"><a href="form_wizards.html" title="">Wizards</a></li>
                    </ul>
                </li>
                <li><a href="ui_elements.html" title=""><?php echo $this->Html->image('crown/icons/light/users.png'); ?>Interface elements</a></li>
                <li><a href="tables.html" title="" class="exp"><?php echo $this->Html->image('crown/icons/light/frames.png'); ?>Tables<strong>3</strong></a>
                    <ul>
                        <li><a href="table_static.html" title="">Static tables</a></li>
                        <li><a href="table_dynamic.html" title="">Dynamic table</a></li>
                        <li class="last"><a href="table_sortable_resizable.html" title="">Sortable &amp; resizable tables</a></li>
                    </ul>
                </li>
                <li><a href="#" title="" class="exp"><?php echo $this->Html->image('crown/icons/light/fullscreen.png'); ?>Widgets and grid<strong>2</strong></a>
                    <ul>
                        <li><a href="widgets.html" title="">Widgets</a></li>
                        <li class="last"><a href="grid.html" title="">Grid</a></li>
                    </ul>
                </li>
                <li><a href="#" title="" class="exp"><?php echo $this->Html->image('crown/icons/light/alert.png'); ?>Error pages<strong>6</strong></a>
                    <ul class="sub">
                        <li><a href="403.html" title="">403 page</a></li>
                        <li><a href="404.html" title="">404 page</a></li>
                        <li><a href="405.html" title="">405 page</a></li>
                        <li><a href="500.html" title="">500 page</a></li>
                        <li><a href="503.html" title="">503 page</a></li>
                        <li class="last"><a href="offline.html" title="">Website is offline</a></li>
                    </ul>
                </li>
                <li><a href="file_manager.html" title=""><?php echo $this->Html->image('crown/icons/light/files.png'); ?>File manager</a></li>
                <li><a href="#" title="" class="exp"><?php echo $this->Html->image('crown/icons/light/create.png'); ?>Other pages<strong>3</strong></a>
                    <ul>
                        <li><a href="typography.html" title="">Typography</a></li>
                        <li><a href="calendar.html" title="">Calendar</a></li>
                        <li class="last"><a href="gallery.html" title="">Gallery</a></li>
                    </ul>
                </li>
            </ul>
        </div>-->
        <div class="cLine"></div>
    </div>
       <!-- Title area -->
    <!--<div class="titleArea">
        <div class="wrapper">
            <div class="pageTitle">
                <h5>Dashboard</h5>
                <span>Do your layouts deserve better than Lorem Ipsum.</span>
            </div>
            <div class="middleNav">
                <ul>
                    <li class="mUser"><a title=""><span class="users"></span></a>
                        <ul class="mSub1">
                            <li><a href="#" title="">Add user</a></li>
                            <li><a href="#" title="">Statistics</a></li>
                            <li><a href="#" title="">Orders</a></li>
                        </ul>
                    </li>
                    <li class="mMessages"><a title=""><span class="messages"></span></a>
                        <ul class="mSub2">
                            <li><a href="#" title="">New tickets<span class="numberRight">8</span></a></li>
                            <li><a href="#" title="">Pending tickets<span class="numberRight">12</span></a></li>
                            <li><a href="#" title="">Closed tickets</a></li>
                        </ul>
                    </li>
                    <li class="mFiles"><a href="#" title="Or you can use a tooltip" class="tipN"><span class="files"></span></a></li>
                    <li class="mOrders"><a title=""><span class="orders"></span><span class="numberMiddle">8</span></a>
                        <ul class="mSub4">
                            <li><a href="#" title="">Pending uploads</a></li>
                            <li><a href="#" title="">Statistics</a></li>
                            <li><a href="#" title="">Trash</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
    </div>-->
    
    <div class="line"></div>
    <!-- Page statistics and control buttons area -->
    <!--<div class="statsRow">
        <div class="wrapper">
        	<div class="controlB">
            	<ul>
                	<li><a href="#" title=""><?php echo $this->Html->image('crown/icons/control/32/plus.png'); ?><span>Add new session</span></a></li>
                    <li><a href="#" title=""><?php echo $this->Html->image('crown/icons/control/32/database.png'); ?><span>New DB entry</span></a></li>
                    <li><a href="#" title=""><?php echo $this->Html->image('crown/icons/control/32/hire-me.png'); ?><span>Add new user</span></a></li>
                    <li><a href="#" title=""><?php echo $this->Html->image('crown/icons/control/32/statistics.png'); ?><span>Check statistics</span></a></li>
                    <li><a href="#" title=""><?php echo $this->Html->image('crown/icons/control/32/comment.png'); ?><span>Review comments</span></a></li>
                    <li><a href="#" title=""><?php echo $this->Html->image('crown/icons/control/32/order-149.png'); ?><span>Check orders</span></a></li>
                </ul>
                <div class="clear"></div>
            </div>
        </div>
    </div>-->
    
    <div class="line"></div>
    
	<?php    
		echo $this->fetch('content');
	?>
	 <!-- Footer line -->
    <div id="footer">
        <div class="wrapper">As usually all rights reserved. And as usually brought to you by <a href="#" title="">Earlydove</a></div>
    </div>	
	
</div>

<div class="clear"></div>

</body>
</html>