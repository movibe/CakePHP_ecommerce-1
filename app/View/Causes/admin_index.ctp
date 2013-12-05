<div class="wrapper">
	<div class="widget">
	   <div class="title"><?php echo $this->HTML->image("crown/icons/dark/frames.png",array('alt'=>'','class'=>'titleIcon')); ?><h6>Causes Information</h6></div>                    
		<table cellpadding="0" cellspacing="0" border="0" class="display dTable">
			<thead>
				<tr>
				
					<th style="width:4%">Sl.No</th>
					<th>Title</th>
					<th>Description</th>
					<th>Image</th>
					<th>More</th>
						
				</tr>
			</thead>
			<tbody>
				<?php 
					$count = 0;
					foreach($causes as $cause){
						
					?>
					<tr class="gradeC">
						<td><?php echo ++$count; ?></td>
						<td> <?php echo $cause['Cause']['title']; ?> </td>
						<td> <?php echo $cause['Cause']['description']; ?> </td>
						<td> <?php
								if(isset($cause['CoverImage']['filename']))
									echo $this->Html->image("uploaded/".$cause['CoverImage']['filename'],array('alt' => $cause['CoverImage']['title'],'width' => '100px','height' => '100px'));
								else
									echo "No image";
	
								?>
						</td>
						<td>
							<?php
								echo $this->Html->link('<span>Edit</span>',array('controller' => 'causes','action' => 'admin_edit',$cause['Cause']['id']),array('class' =>'button blueB','escape' => false));
								echo "<br/>";
								echo "<br/>";
								echo $this->Html->link('<span>Delete</span>',array('controller' => 'causes','action' => 'admin_delete',$cause['Cause']['id']),array('class' =>'button blueB','escape' => false));
													
							?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
			</table>
	</div>
</div>		
					