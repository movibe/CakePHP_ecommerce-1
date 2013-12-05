<div class="wrapper">
	<?php 
			echo "<br/>";
			echo $this->Html->link('<span>Add</span>',array('controller' => 'products','action' => 'admin_add'),array('style' => 'float:right;','target' => '_blank','class' => 'button blueB','escape' => false)); 
			echo "<br/>";
		?> 
	<div class="widget">
		
	   <div class="title"><?php echo $this->HTML->image("crown/icons/dark/frames.png",array('alt'=>'','class'=>'titleIcon')); ?><h6>products Information</h6></div>                  
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
					foreach($products as $product){
						
					?>
					<tr class="gradeC">
						<td><?php echo ++$count; ?></td>
						<td> <?php echo $product['Product']['title']; ?> </td>
						<td> <?php echo $product['Product']['description']; ?> </td>
						<td> <?php
							if(isset($product['CoverImage']['filename']))
								echo $this->Html->image("uploaded/".$product['CoverImage']['filename'],array('alt' => $product['CoverImage']['title'],'width' => '100px','height' => '100px')); 
							else
								echo "No image";
						?>
								
						</td>

						<td>
							<?php
								echo $this->Html->link('<span>Edit</span>',array('controller' => 'products','action' => 'admin_edit',$product['Product']['id']),array('class' =>'button blueB','escape' => false));
								echo "<br/>";
								echo "<br/>";
								echo $this->Html->link('<span>Delete</span>',array('controller' => 'products','action' => 'admin_delete',$product['Product']['id']),array('class' =>'button blueB','escape' => false));
													
							?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
			</table>
	</div>
</div>		
					