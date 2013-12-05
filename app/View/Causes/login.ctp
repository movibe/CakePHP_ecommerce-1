<div align="center" style="margin-top:70px;">
		<?php //echo $this->session->flash();?>
		<legend><?php __('Login'); ?></legend>
		<?php 
			echo $this->Form->create('AdminUser');
			echo $this->Form->input('username');
			echo $this->Form->input('password');
		  ?>
		<?php echo $this->Form->end('Submit');?>
</div>