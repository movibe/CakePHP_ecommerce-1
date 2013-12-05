<?php
	//debug($designs);
?>
<div class="wrapper">
	<div class="widget">
	   <div class="title"><?php echo $this->HTML->image("crown/icons/dark/frames.png",array('alt'=>'','class'=>'titleIcon')); ?><h6>Users Information</h6></div>
		<?php echo $this->Form->Create('Users',array('url' => array('controller' =>'Emails','action' => 'admin_send_email'))); ?>
		<div class = "formRow">
			<?php echo $this->Form->input("Subject",array('class' => "formRight")); ?>
			<div class="clear"></div>
		</div>
		<div class = "formRow">
			<?php echo $this->Form->input("Message",array('class' => "formRight editor",'type' => 'textarea','id' =>"editor",'style' => 'width: 1578px; height: 150px;')); ?>
			<div class="clear"></div>
		</div>
		<div class="formRow">	
		<table cellpadding="0" cellspacing="0" border="0" class="display dataTable">
			<thead>
				<tr>
					<th style="width:4%"><input id="select" type="checkbox" name="select" value="0" /></th>
					<th style="width:4%">Sl.No</th>
					<th>Name</th>
					<th>Email</th>
					<!--<th>More</th> -->
						
				</tr>
			</thead>
			<tbody>
				<?php 
					$count = 0;
					foreach($users as $user){
						
					?>
					<tr class="gradeC">
						<td><input class="email" type="checkbox" name="email[]" value="<?php echo $user['User']['email']; ?>" /></td>
						<td><?php echo ++$count; ?></td>
						<td> <?php echo $user['User']['name']; ?> </td>
						<td> <?php echo $user['User']['email']; ?> </td>
						<!--<td>
							<?php
								/*echo $this->Html->link('<span>Edit</span>',array('controller' => 'users','action' => 'admin_user_edit',$user['User']['id']),array('class' =>'button blueB','escape' => false));
								echo "<br/>";
								echo "<br/>";
								echo $this->Html->link('<span>Delete</span>',array('controller' => 'users','action' => 'admin_user_delete',$user['User']['id']),array('class' =>'button blueB','escape' => false*/
													
							?>
						</td>-->
					</tr>
				<?php } ?>
			</tbody>
			</table>
				<div class="clear"></div>
			</div>
			<div class="formRow">
				<?php echo $this->Form->submit('Send Email',array('class' => 'button blueB editor','escape' => false,"style" => 'float:right')); ?>
				<div class="clear"></div>
			</div>
	</div>
</div>	
<script type = "text/javascript">
	$(function(){
		
		// Using plug-in API function of datatables
		$.fn.dataTableExt.oApi.fnGetHiddenNodes = function ( oSettings ){
			// Note the use of a DataTables 'private' function thought the 'oApi' object
			var anNodes = this.oApi._fnGetTrNodes( oSettings );
			var anDisplay = $('tbody tr', oSettings.nTable);
			  
			// Remove nodes which are being displayed
			for ( var i=0 ; i<anDisplay.length ; i++ ){
				var iIndex = jQuery.inArray( anDisplay[i], anNodes );
				if ( iIndex != -1 ){
					anNodes.splice( iIndex, 1 );
				}
			}
			return anNodes;
		};
		
		var oTable = $('.dataTable').dataTable({
			"bJQueryUI": true,
			"bAutoWidth": false,
			"sPaginationType": "full_numbers",
			"sDom": '<"H"l>t<"F"fp>'
		});
		
		if($("#select").is(':checked')){
			$('input', oTable.fnGetNodes()).attr('checked', true);
		}
		else{
			$('input', oTable.fnGetNodes()).attr('checked', false);
		}
		
		$("#select").click(function(){
			$('input', oTable.fnGetNodes()).attr('checked',this.checked);
		});
		
		$("input.email").live('click', function(){
			var anSelected = oTable.fnGetNodes();
			var anSelectedLength = oTable.fnGetNodes().length;
			var checkedCount = 0, unCheckedCount = 0;
			$.each(anSelected, function(i, v){
				var checkBoxStatus = $(v).find('input.email').is(':checked');
				if(checkBoxStatus){
					checkedCount++;
				}
				else{
					unCheckedCount++;
				}
			});
			if(checkedCount == anSelectedLength){
				$("#select").attr("checked", true);
			}
			else if(unCheckedCount == anSelectedLength){
				$("#select").attr("checked", false);
			}
			else{
				$("#select").attr("checked", false);
			}
		});
		
		$('form').submit( function() {
			$(oTable.fnGetHiddenNodes()).find('input:checked').appendTo(this);
		} );
		
	});
</script>	
					