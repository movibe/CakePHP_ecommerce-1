<?php if(!empty($images)){?>
<div class="main">
<div class="users index">
	<?php /*?><?php
	echo "<pre>";
	print_r($images);
	?><?php */?>
 <table cellpadding="0" cellspacing="0" width="20%" style="margin-left:100px;">
	<tr>
	<?php
 	$j=0;
	$i = 0;
	foreach ($images as $image):
     $i++;
	 ?>

	    <td><?php echo $this->html->image("/img/uploaded/".$images[$j]['Image']['filename'],array('height'=>125,'width'=>155));?>
        <?php echo $this->Html->link(__('Delete', true), array('action' => 'deleteImages', $images[$j]['Image']['id'],$imageid), null, 'Are  you really want to delete the image'); ?>
	    </td>
	  <td>&nbsp;&nbsp;</td>
		 <?php /* ?> <td><?php echo $html->image($image['PropertyImage']['images'],array('height'=>100,'width'=>100));?> </td><?php */ ?>
		 <?php if($i%5==0){echo '</tr>'; } ?>
<?php $j++;endforeach; ?>
	
	</tr>
 </table>
 <?php } ?>	