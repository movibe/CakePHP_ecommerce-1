<div style="margin-top:70px; margin-left:20px;">
<?php
if(!empty($msg)){echo $msg;}
?>
</div>
<?php echo $this->Html->script('jquery-1.4.2.min'); ?>
<?php echo $this->Html->script('swfupload/swfupload.js'); ?>
<?php echo $this->Html->script('jquery.swfupload.js'); ?>
<?php 
$finalCount=8;
?>
<legend><?php //echo $session->flash()?></legend>
<style type="text/css" >
#swfupload-control{ margin-left:20px;}
#swfupload-control p{ margin:10px 5px; font-size:0.9em; }
#log{ margin:0; padding:0; width:500px;}
#log li{ list-style-position:inside; margin:2px; border:1px solid #ccc; padding:10px; font-size:12px;
font-family:Arial, Helvetica, sans-serif; color:#333; background:#fff; position:relative;}
#log li .progressbar{ border:1px solid #333; height:5px; background:#fff; }
#log li .progress{ background:#999; width:0%; height:5px; }
#log li p{ margin:0; line-height:18px; }
#log li.success{ border:1px solid #339933; background:#ccf9b9; }
#log li span.cancel{ position:absolute; top:5px; right:5px; width:20px; height:20px;
background:url('js/swfupload/cancel.png') no-repeat; cursor:pointer; }
</style>

<script type="text/javascript">
$(function(){
$('#swfupload-control').swfupload({
upload_url: "<?php echo $this->Html->url(array('controller'=>'images','action'=>'multiimages',$id))?>",
file_post_name: 'data[Image][images]',
file_size_limit : "2048",
file_types : "*.jpg;*.png;*.gif",
file_types_description : "Image files",
file_upload_limit :<?php echo $finalCount; ?>,
flash_url : "<?php echo $this->webroot;?>js/swfupload/swfupload.swf",
button_image_url : '<?php echo $this->webroot;?>js/swfupload/wdp_buttons_upload_114x29.png',
button_width : 129,
button_height : 32,
<?php if($finalCount<=0): ?>
button_disabled : true,
<?php endif; ?>
button_placeholder : $('#button')[0],
debug: false
})
.bind('fileQueued', function(event, file){
var listitem='<li id="'+file.id+'" >'+
'File: <em>'+file.name+'</em> ('+Math.round(file.size/1024)+' KB) <span class="progressvalue" ></span>'+
'<div class="progressbar" ><div class="progress" ></div></div>'+
'<p class="status" >Pending</p>'+
'<span class="cancel" >&nbsp;</span>'+
'</li>';
$('#log').append(listitem);
$('li#'+file.id+' .cancel').bind('click', function(){
var swfu = $.swfupload.getInstance('#swfupload-control');
swfu.cancelUpload(file.id);
$('li#'+file.id).slideUp('fast');
});
// start the upload since it's queued
$(this).swfupload('startUpload');
})
.bind('fileQueueError', function(event, file, errorCode, message){
alert('Size of the file '+file.name+' is greater than limit');
})
.bind('fileDialogComplete', function(event, numFilesSelected, numFilesQueued){
$('#queuestatus').text('Files Selected: '+numFilesSelected+' / Queued Files: '+numFilesQueued);
})
.bind('uploadStart', function(event, file){
$('#log li#'+file.id).find('p.status').text('Uploading...');
$('#log li#'+file.id).find('span.progressvalue').text('0%');
$('#log li#'+file.id).find('span.cancel').hide();
})
.bind('uploadProgress', function(event, file, bytesLoaded){
//Show Progress
var percentage=Math.round((bytesLoaded/file.size)*100);
$('#log li#'+file.id).find('div.progress').css('width', percentage+'%');
$('#log li#'+file.id).find('span.progressvalue').text(percentage+'%');
})
.bind('uploadSuccess', function(event, file, serverData){
var item=$('#log li#'+file.id);
item.find('div.progress').css('width', '100%');
item.find('span.progressvalue').text('100%');
//var pathtofile='<a href="uploads/'+file.name+'" target="_blank" >view &raquo;</a>';
item.addClass('success').find('p.status').html('Done!!! | ');
item.fadeOut(1000);
updateme();
})
.bind('uploadComplete', function(event, file){
// upload has completed, try the next one in the queue
$(this).swfupload('startUpload');
})

});
</script>
<div id="updateme"> </div>
	<div id="swfupload-control">
				  <p>Upload upto <?php echo $finalCount; ?> image files(jpg, png, gif), each having maximum size of 1MB</p>
				  <input type="button" id="button" />
	 			  <p id="queuestatus" ></p>
    			  <ol id="log"></ol>
  <a href="/index.php/images/thankyou/"><?php echo $this->html->image("/img/done_btn.png",array('action'=>'thankyou','height'=>32,'width'=>98));?></a>
   </div>
		
        <script>
		 function updateme()
		  {
		  <?php echo $this->ajax->remoteFunction(array('url'=>'/images/viewImages/'.$id,'update'=>'updateme'));?>
		  }
		  <?php echo $this->ajax->remoteFunction(array('url'=>'/images/viewImages/'.$id,'update'=>'updateme'));?>
		</script>		
		
		