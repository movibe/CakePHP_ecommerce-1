<div class="header-bottom navbar-inner" style="width:315px; margin:20px auto; height:50px; border-radius:10px; box-shadow:0px 0px 10px 1px #c5cbcb inset; ">
	<div class="container" style="width:100%;">
		<div id="rsg_buttons-wrap" class="buttons-wrap">
		<?php
		foreach($buttons as $controller => $button){
			if($button['isActive']){
				echo $this->Html->link($button['text'], array('controller' => $controller, 'action' => 'index'), array('class' => 'btn btn-green active'));
			} else {
				echo $this->Html->link($button['text'], array('controller' => $controller, 'action' => 'index'), array('class' => 'btn'));
			}
		}
		?>
		</div>
		<!--/.nav-collapse -->
	</div>
</div>
