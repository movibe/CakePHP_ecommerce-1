<!--
<script>
$(".like").click(function(e){
	e.preventDefault();	
	elementId = $(this).attr('id');
	imageId = $(this).attr('id');
	imageTag = '<?php echo $this->Html->image('loaders/loader.gif', array('class' => 'like', 'id' => '#&#', 'escape' => false));?>';
	imageTag = imageTag.replace('#&#', imageId);

	$(this).hide();
	$(this).after(imageTag);
	$.ajax({
		type: "POST",
		url: '<?php echo $this->Html->url(array('controller' => 'likes', 'action' => 'add.json')); ?>',
		data: {
			'data[Like][foreign_key]': imageId,
			'data[Like][model]': 'Cause',
			'data[Like][like_type]': 1
		},
		dataType: 'json',	
		success: function(result){
			if(result.success){
				$('img#' + imageId + '.like').remove();
				$('a#' + imageId + '.like').append('d');
				$('a#' + imageId + '.like').show();
			}else{
				if(result.responseCode == 2002){
					$('img#' + imageId + '.like').remove();
					$('a#' + imageId + '.like').show();
					$('#loginModal').modal();
				}
			}
		}									
	});
});
</script>

<?php $likes_num = 0;?>
-->
<!--
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=440004826068724";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
-->
<?php foreach($comments as $key => $comment){?>
	<div class="row-fluid fbcomment-container">
    
		<div class="fbcomment-profilepic">
			<?php echo $this->Html->image($comment['User']['profileImageUrl'], array('url' => array('controller' => 'users', 'action' => 'view', $comment['User']['id']), 'alt' => $comment['User']['name']));?>
		</div>
        
		<div class="fbcomment-comment">
				<p>
				<strong><?php echo $this->Html->link($comment['User']['name'], array('url' => array('controller' => 'users', 'action' => 'view', $comment['User']['id'])));?></strong>
                
				<?php echo $comment['Comment']['comment'];?>
				</p>
                
                <div class="fbcomment-time">
					Posted 
					<?php 
                    if($this->Time->wasWithinLast('1 hour', $comment['Comment']['created'])){
                        echo $this->Time->timeAgoInWords($comment['Comment']['created'], array('format' => 'F jS, Y', 'accuracy' => array('month' => 'month')));
                    }
                    else{
                        echo $this->Time->niceShort($comment['Comment']['created']);
                    }
                    ?>
                </div><!-- /fbcomment-time -->
		</div><!-- /fbcomment-comment -->
	</div>
	<div>
		<div>
			<div>
<!--			
<?php
$link = mysql_connect('localhost', 'snest_earlydove', 'funny5');
if (!$link) {
    die('Not connected : ' . mysql_error());
}

$db_selected = mysql_select_db('snest_main', $link);
if (!$db_selected) {
    die ('Can\'t use snest_main : ' . mysql_error());
}

$result = mysql_query('SELECT likes FROM causes');
if (!$result) {
    die('Could not query:' . mysql_error());
}

echo mysql_result($result, $likes_num); // outputs third employee's name
$likes_num = $likes_num + 1;
$result = mysql_query('SELECT id FROM causes');
$rsg_imgid = mysql_result($result, $likes_num);
mysql_close($link);
?>&nbsp;      
															
				<a href="#" id="<?php echo $rsg_imgid;?>" class="like">Likes</a>
-->
<div class="fb-like" data-href="http://www.spreadnest.com/" data-send="false" data-layout="button_count" data-width="450" data-show-faces="true" data-font="arial"></div>				
			</div>
			
<!--			
			<div class="span1" style="width:15%;">
				<a id="anchorReply<?php echo $comment['Comment']['id']; ?>" href="#"
					class="pull-left"> Likes</a>
			</div>
-->			
		</div>
	</div>
	<div id="commentTextarea<?php echo $comment['Comment']['id']; ?>"
		style="display: none" class="row-fluid"
		data-model="<?php echo $foreignKey;?>"
		data-parent="<?php echo $comment['Comment']['id'];?>">
		<div class="offset1 span11">
			<textarea rows="1" class="span12 text-box" placeholder="Add a comment"></textarea>
		</div>
		<button class="btn btn-mini btn-primary pull-right reply"
			data-loading-text="loading stuff..." type="button">Comment</button>
	</div>

	<?php foreach($comment['ChildComment'] as $key => $childComment){?>
		<div class="row-fluid reply">
			<div class="offset1 span11">
				<div class="span1">
					<div class="span12">
					<?php echo $this->Html->image($childComment['User']['profileImageUrl'], array('url' => array('controller' => 'users', 'action' => 'view', $childComment['User']['id']), 'alt' => $childComment['User']['name']));?>
					</div>
				</div>
				<div class="span11">
					<div class="span12">
						<p>
						<?php echo $this->Html->link($childComment['User']['name'], array('url' => array('controller' => 'users', 'action' => 'view', $childComment['User']['id'])));?>
						<?php echo $childComment['comment'];?>
		
						</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="offset1 span11">
				<div class="span5 muted">
					<?php 
					if($this->Time->wasWithinLast('1 hour', $childComment['created'])){
						echo $this->Time->timeAgoInWords($childComment['created'], array('format' => 'F jS, Y', 'accuracy' => array('month' => 'month')));
					}
					else{
						echo $this->Time->niceShort($childComment['created']);
					}
					?>
				</div>
			</div>
		</div>
	<?php }?>

<?php }?>
<!--
	<div class="row-fluid">
		<div class="offset4 span4" style='width:100%;'>
			<?php 
			echo $this->Paginator->next('Load More Comments', array('class' => 'load-more', 'id' => $foreignKey), null, array('class' => 'hide'));
			?>
		</div>
	</div>
-->	
<div></div>