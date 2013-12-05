<div class="wrapper">
	<div class="widget">
	   <div class="title"><?php echo $this->HTML->image("crown/icons/dark/frames.png",array('alt'=>'','class'=>'titleIcon')); ?><h6>Causes Information</h6></div>                    
		<table cellpadding="0" cellspacing="0" border="0" class="display dTable">
			<thead>
				<tr>
				
					<th style="width:4%">Sl.No</th>
					<th>Comment</th>
					<th>Design Title</th>
					<th>More</th>
						
				</tr>
			</thead>
			<tbody>
				<?php 
					$count = 0;
					foreach($designsComments as $comment){
						
					?>
					<tr class="gradeC">
						<td><?php echo ++$count; ?></td>
						<td> <?php echo $comment['Comment']['comment']; ?> </td>
						<td> <?php echo $comment['Design']['title']; ?> </td>
						<td>
							<?php
								echo $this->Html->link('<span>Edit</span>',array('controller' => 'comments','action' => 'admin_comment_edit',$comment['Comment']['id']),array('class' =>'button blueB','escape' => false));
								echo "<br/>";
								echo "<br/>";
								echo $this->Html->link('<span>Delete</span>',array('controller' => 'comments','action' => 'admin_comment_delete',$comment['Comment']['id']),array('class' =>'button blueB','escape' => false));
													
							?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
			</table>
	</div>
</div>		