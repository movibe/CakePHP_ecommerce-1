<?php
	$this->start('header');
	$buttons['designs']['isActive'] =  true;
	echo $this->element('Common/header', array('buttons' => $buttons));
    $this->end();
?>

<div class="container">
<div class="toplinks">
<?php echo $this->Html->image('design.jpg');?>
</div>
<!--	<div class="row">-->
	<div class="row" style="border:1px solid #d2d8d8; border-radius:5px 5px 5px 5px; background-color:#fff; padding:5px; color:#9a9c9b; margin-top:25px;">
		<div style="background-color:#f6f6f6;">
			<div id="rsg_submit" style="width:30%; float:left;">
				<div style="">
					<div style="float:left; padding:10px;"><?php echo $this->Html->image('rsg_img/home_shirt.png')?></div>
					<div style="padding:10px 0;">Once we have a cause chosen, submit and choose the best shirt design here. Design prizes will be awarded to the top artist</div>
				</div>
				<div style="padding:4px; text-align:center;"><?php echo $this->Html->link('Submit', array('action' => 'add'), array('class' => 'btn submit-cause'))?></div>
			</div>
			<div style="float:left; width:70%;">
				<div style="float:left; width:190px; padding:10px 0;">
					<div id="8" class="comment" style="cursor:pointer;"><?php echo $this->Html->image("upload/thumb/prefix_$find_filename")?></div>
				</div>
				<div style="float:left; width:68%; padding:10px;">
					<div style="color:#5695a4; font-weight:bold; font-size:20px;">
						<?php echo $find_title;?>
					</div>
					<div style="font-weight:bold;">
						<?php echo $find_modified;?>
					</div>
					<div style="height:15px;">
					</div>
					<div style="height:135px; overflow:hidden;">
						<?php echo $find_description;?>
					</div>

			<div class="boxIcons">
				<div class="social-icons">
					<ul>
						<li>
							<a id="8" class="like" href="#">1<i class="icon-heart"></i>Like</a>
						</li>
						<li>
							<a id="8" class="comment" href="#">14<i class="icon-comment"></i>Comment</a>
						</li>
						<li>
							<a id="8" class="share"><i class="icon-share"></i>Share</a>							
						</li>
					</ul>
				</div>
				
				<div class="row-fluid collapse" id="shareDropDown<?php echo $design['Design']['id'];?>">
					<div class="span6">
						<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $this->Html->url(array('controller' => 'designs', 'action' => 'index', $design['Design']['id']), true);?>" data-size="small">Tweet</a>
						<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
					</div>
					<div class="span6 pull-right">
						<fb:like href="<?php echo $this->Html->url(array('controller' => 'designs', 'action' => 'index', $design['Design']['id']), true);?>" layout="button_count" width="100" show_faces="true"></fb:like>
					</div>
				</div>				
			</div>
				</div>
			</div>
			<div style="clear: both"></div>
		</div>
<!--
		<div class="span12">
			<div class="join-now-wrap">
				<i><strong>Once we have a cause chosen, submit and choose the best shirt design here. </strong></i><br>
				<i><strong>Design prizes will be awarded to the top artist </strong></i><br>
				<?php echo $this->Html->link('Submit Now', array('action' => 'add'), array('class' => 'btn submit-design'))?>
			</div>
			
		</div>
-->
	</div>
	<div class="row">
		<?php
			$selected = 'top-rated';
			$filters = array(
				'top-rated' => array('text' => 'Top Rated', 'isActive' => false),
				'most-comments' => array('text' => 'Most Comments', 'isActive' => false),
				'newest' => array('text' => 'Newest', 'isActive' => false)
			);
			if(isset($this->params['pass'][0])){
				if(!is_numeric($this->params['pass'][0])){
					$selected = $this->params['pass'][0];
				}
			}
			$filters[$selected]['isActive'] = true;
		?>
		<div class="offset9 span3">
			<select name="filter" id="filter">
				<?php
				foreach ($filters as $key => $filter) {
					if($filter['isActive']){
						?>
						<option selected value="<?php echo $key?>"><?php echo $filter['text'];?></option>	
						<?php 
					} else{
						?>
						<option value="<?php echo $key?>"><?php echo $filter['text'];?></option>	
						<?php
					}
				}
				?>
			</select>
		</div>
	</div>
	<div class="row">
	<?php foreach($designs as $index => $design){?>
		<div class="span3">
			<div class="boxRepeat">
				<div class="boxImage">
					<?php echo $this->Html->image('uploaded/medium/' . $design['CoverImage']['filename'], array('class' => 'comment', 'id' => $design['Design']['id']));?>
				</div>
				<div class="contentBox">
					<div class="smallImage">
						<?php echo $this->Html->image($design['User']['profileImageUrl'], array('url' => array('controller' => 'users', 'action' => 'view', $design['User']['id']), 'alt' => $design['User']['name']));?>
					</div>
					<div class="imageDescription">
						<p><?php echo $design['Design']['title'];?></p>
					</div>
				</div>
			</div>
			<div class="boxIcons">
				<div class="social-icons">
					<ul>
						<li>
							<?php echo $this->Html->link('<i class="icon-heart"></i>' . $design['Design']['likeText'], '#', array('escape' => false, 'class' => 'like', 'id' => $design['Design']['id']));?>
						</li>
						<li>
							<?php echo $this->Html->link('<i class="icon-comment"></i>Comment', '#', array('escape' => false, 'class' => 'comment', 'id' => $design['Design']['id']));?>
						</li>
						<li>
							<a class="share" id="<?php echo $design['Design']['id'];?>"><i class="icon-share"></i>Share</a>
							
						</li>
					</ul>
				</div>
				
				<div class="row-fluid collapse" id="shareDropDown<?php echo $design['Design']['id'];?>">
					<div class="span6">
						<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $this->Html->url(array('controller' => 'designs', 'action' => 'index', $design['Design']['id']), true);?>" data-size="small">Tweet</a>
						<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
					</div>
					<div class="span6 pull-right">
						<fb:like href="<?php echo $this->Html->url(array('controller' => 'designs', 'action' => 'index', $design['Design']['id']), true);?>" layout="button_count" width="100" show_faces="true"></fb:like>
					</div>
				</div>	
				
			</div>
			
		</div>
	<?php }?>
	</div>
		
	<div class="row">
		<div class="offset4 span4">
			<?php 
			echo $this->Paginator->next('Load More', array(), null, array('class' => 'hide'));
			?>
		</div>
	</div>
	
	<div id="shareModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
				<?php echo $this->Html->image('close.png', array('alt' => 'Close')) ?>
			</button>
			<h3 id="myModalLabel">Share</h3>
		</div>
		<div class="modal-body">
			<!-- AddThis Button BEGIN -->
			<div class="addthis_toolbox addthis_default_style addthis_32x32_style">
				<a class="addthis_button_preferred_1"></a>
				<a class="addthis_button_preferred_2"></a>
				<a class="addthis_button_preferred_3"></a>
				<a class="addthis_button_preferred_4"></a>
				<a class="addthis_button_compact"></a>
				<a class="addthis_counter addthis_bubble_style"></a>
			</div>
			<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-513b0afe2c1d1337"></script>
			<!-- AddThis Button END -->
		</div>
		<!--<div class="modal-footer">-->
			<!--<button class="btn btn-info" data-dismiss="modal" aria-hidden="true">Close</button>-->
			<!--<button class="btn btn-primary">Save changes</button>-->
		<!--</div>-->
	</div>

</div>
<!--/span-->
<div id="push"></div>
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
			'data[Like][model]': 'Design',
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

//$('.collapse').collapse();
$(".share").click(function(e){
	e.preventDefault();
	// id = $(this).attr('id');
	// $('#shareDropDown' + id).toggleClass('in', 10, 'swing');
	
	$('#shareModal').modal('show');
});

/*
$(".comment").click(function(e){
	e.preventDefault();	
	elementId = $(this).attr('id');
	imageId = $(this).attr('id');
	imageTag = '<?php echo $this->Html->image('loaders/loader.gif', array('class' => 'like', 'id' => '#&#', 'escape' => false));?>';
	imageTag = imageTag.replace('#&#', imageId);

	if($('#popup' + imageId).length == 0){
		var newPopupDiv = '<div id="popup' + imageId + '" class="modal hide fade popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">\
								<div class="modal-body">\
								</div>\
							</div>';
		$('body').prepend(newPopupDiv);
		$('body,html').css('overflow', 'hidden');


		$('#popup' + imageId).live('hidden', function(){
			$('body,html').css('overflow', 'visible');
		});
		
		$('#popup' + imageId).live('shown', function(){
			var url = '<?php echo $this->Html->url(array('controller' => 'comments', 'action' => 'index', 'Design'));?>' + '/' + imageId;
			$.ajax({
				type: "GET",
				url: url,
				success: function(result){	
					$('.popup-loader#id' + imageId).remove();
					$('.popup-wrap#' + imageId).append(result);					
				}									
			});	
		});


		$('#popup' + imageId).modal({
			remote: '<?php echo $this->Html->url(array('action' => 'view'));?>' + '/' + imageId
		});

		
			
	}
	else{
		$('#popup' + imageId).modal();
	}	
});
*/
$(".comment").click(function(e){
	e.preventDefault();	
	elementId = $(this).attr('id');
	imageId = $(this).attr('id');
		
	imageTag = '<?php echo $this->Html->image('loaders/loader.gif', array('class' => 'like', 'id' => '#&#', 'escape' => false));?>';
	imageTag = imageTag.replace('#&#', imageId);

	if($('#popup' + imageId).length == 0){
		var newPopupDiv = '<div id="popup' + imageId + '" class="modal hide fade popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">\
								<div class="modal-body">\
								</div>\
							</div>';
		$('body').prepend(newPopupDiv);
		$('body,html').css('overflow', 'hidden');


		$('#popup' + imageId).live('hidden', function(){
			$('body,html').css('overflow', 'visible');
		});
		
		$('#popup' + imageId).live('shown', function(){
			var url = '<?php echo $this->Html->url(array('controller' => 'comments', 'action' => 'index', 'Cause'));?>' + '/' + imageId;
			$.ajax({
				type: "GET",
				url: url,
				success: function(result){	
					$('.popup-loader#id' + imageId).remove();
					$('.popup-wrap#' + imageId).html(result);
/*
					if(scrollbar_count == 0) {
						$("#content_1").mCustomScrollbar({
							scrollButtons:{
								enable:true
							}
						});*/
						$("#content_1 .mCSB_container" + ".popup-wrap#" + imageId).html(result);
						$("#content_1").mCustomScrollbar("update");	
						$("#content_1").mCustomScrollbar("scrollTo",".popup-wrap > div:last",{scrollInertia:2500,scrollEasing:"easeInOutQuad"}); 
//						scrollbar_count = scrollbar_count + 1;
//					}	
				}									
			});	
		});


		$('#popup' + imageId).modal({
			remote: '<?php echo $this->Html->url(array('action' => 'view'));?>' + '/' + imageId
		});

		
			
	}
	else{
		$('#popup' + imageId).modal();
	}
});

$('.modal').live('click', function(e){
	if($.inArray("modal", e.target.classList) > -1)
		$('#popup' + imageId).modal('hide');
});
/*
$(".reply").live('click', function(e){
	e.preventDefault();
	var self = $(this);
	designId = $(this).parent().attr('data-model');
	parentId = $(this).parent().attr('data-parent');
	comment = $(this).parent().children('div').children('textarea').val();

	btn = $(this);
	$(this).button('loading');
	
	
	$.ajax({
		type: "POST",
		url: '<?php echo $this->Html->url(array('controller' => 'comments', 'action' => 'add.json')); ?>',
		data: {
			'data[Comment][foreign_key]': designId,
			'data[Comment][model]': 'Design',
			'data[Comment][comment]': comment,
			'data[Comment][parent_id]': parentId
		},
		dataType: 'json',	
		success: function(result){
			if(result.success){
				btn.button('reset');
				htmlCode = '<div class="row-fluid">\
								<div class="span1 ">\
									<div class="span12">\
									<?php echo $this->Html->image($user['profileImageUrl'], array('url' => array('controller' => 'users', 'action' => 'view', $user['id']), 'alt' => $user['name']));?>\
									</div>\
								</div>\
								<div class="span11">\
									<div class="span12">\
										<p>\
										<?php echo $this->Html->link($user['name'], array('url' => array('controller' => 'users', 'action' => 'view', $user['id'])));?>\
										' + comment +'\
										</p>\
									</div>\
								</div>\
							</div>\
							<div class="row-fluid">\
								<div class="offset1 span11">\
									<a href="#" class="pull-left"> Reply</a>\
								</div>\
							</div>';
					if(parentId != 0){
						htmlCode = '<div class="row-fluid">\
										<div class="offset1 span11">\
											<div class="span1 ">\
												<div class="span12">\
												<?php echo $this->Html->image($user['profileImageUrl'], array('url' => array('controller' => 'users', 'action' => 'view', $user['id']), 'alt' => $user['name']));?>\
												</div>\
											</div>\
											<div class="span11">\
												<div class="span12">\
													<p>\
													<?php echo $this->Html->link($user['name'], array('url' => array('controller' => 'users', 'action' => 'view', $user['id'])));?>\
													' + comment +'\
													</p>\
												</div>\
											</div>\
										</div>\
									</div>';
					}
				btn.parent().after(htmlCode);
				self.parent().find('textarea').val('');
			}else{
				if(result.responseCode == 2002){
					btn.button('reset');
					$('#popup' + designId).modal('hide');
					$('#loginModal').modal();
				}
			}
		}									
	});

	
});
*/
var rsg_top = 0;
$(".reply").live('click', function(e){
	e.preventDefault();
	var self = $(this);
	causeId = $(this).parent().attr('data-cause');
	parentId = $(this).parent().attr('data-parent');
	comment = $(this).parent().children('div').children('textarea').val();

	btn = $(this);
	$(this).button('loading');
	
	
	$.ajax({
		type: "POST",
		url: '<?php echo $this->Html->url(array('controller' => 'comments', 'action' => 'add.json')); ?>',
		data: {
			'data[Comment][foreign_key]': causeId,
			'data[Comment][model]': 'Cause',
			'data[Comment][comment]': comment,
			'data[Comment][parent_id]': parentId
		},
		dataType: 'json',	
		success: function(result){
			if(result.success){
				btn.button('reset');
				htmlCode = '<div class="row-fluid">\
								<div class="span1 " style="width:15%; margin-right:10px;">\
									<div class="span12">\
									<?php echo $this->Html->image($user['profileImageUrl'], array('url' => array('controller' => 'users', 'action' => 'view', $user['id']), 'alt' => $user['name']));?>\
									</div>\
								</div>\
								<div class="span11" style="width:80%;">\
									<div class="span12">\
										<p>\
										<?php echo $this->Html->link($user['name'], array('url' => array('controller' => 'users', 'action' => 'view', $user['id'])));?>\
										' + comment +'\
										</p>\
									</div>\
								</div>\
							</div>\
							<div class="row-fluid">\
								<div class="offset1 span11" style="width:26%; color:#0088CC;">\
								<div class="fb-like" data-href="http://www.spreadnest.com/" data-send="false" data-layout="button_count" data-width="450" data-show-faces="true" data-font="arial"></div>\
								</div>\
							</div>\
							<div id="commentTextarea' + result.data + '"\
								style="display: none" class="row-fluid"\
								data-model="' + causeId + '"\
								data-parent="' + result.data + '">\
								<div class="offset1 span11">\
									<textarea rows="1" class="span12 text-box" placeholder="Add a comment"></textarea>\
								</div>\
								<button class="btn btn-mini btn-primary pull-right reply"\
									data-loading-text="loading stuff..." type="button">Comment</button>\
							</div>';
					if(parentId != 0){
						htmlCode = '<div class="row-fluid">\
										<div class="offset1 span11">\
											<div class="span1 ">\
												<div class="span12">\
												<?php echo $this->Html->image($user['profileImageUrl'], array('url' => array('controller' => 'users', 'action' => 'view', $user['id']), 'alt' => $user['name']));?>\
												</div>\
											</div>\
											<div class="span11">\
												<div class="span12">\
													<p>\
													<?php echo $this->Html->link($user['name'], array('url' => array('controller' => 'users', 'action' => 'view', $user['id'])));?>\
													' + comment +'\
													</p>\
												</div>\
											</div>\
										</div>\
									</div>';
					}
				//btn.parent().after(htmlCode);
				$(btn).parent().parent().parent().find('.popup-wrap').first().append(htmlCode);
				$(btn).parent().parent().parent().find('.popup-wrap').first().append('<div></div>');
				
//				$("#content_1 .mCSB_container" + ".popup-wrap#" + imageId).first().append(htmlCode);
/*
//				console.log($('.popup-wrap').css('height'));
				if(parseInt($('.popup-wrap').css('height'))>400) {
				rsg_top = rsg_top - 80;
				$('.popup-wrap').css('margin-top', rsg_top+'px');
//				console.log('top=', $('.popup-wrap').css('margin-top'));
				}
*/				
				if(htmlCode) {
				$("#content_1").mCustomScrollbar("update"); //update scrollbar according to newly appended content
				$("#content_1").mCustomScrollbar("scrollTo",".popup-wrap > div:last",{scrollInertia:2500,scrollEasing:"easeInOutQuad"}); //scroll to appended content
				}				

				self.parent().find('textarea').val('');
			}else{
				if(result.responseCode == 2002){
					btn.button('reset');
					$('#popup' + causeId).modal('hide');
					$('#loginModal').modal();
				}
			}
		}									
	});

	
});

$("a[id^=anchorReply]").live('click', function(e){
	e.preventDefault();
	var commentId = $(this).attr('id').substr(11);
	$("div[id^=commentTextarea]").hide();
	$("#commentTextarea" + commentId).show();
});

$("div[id^=commentTextarea] button.reply").live('click', function(){
	$(this).parent().hide();
});

$('#filter').on('change', function(){
	var filter = $('#filter').val();
	var url = '<?php echo $this->Html->url(array('controller' => 'designs'));?>';
	url = url + '/' + filter;
	$(location).attr('href', url);
});

page = 1;
$('.load-more a').live('click', function(e){
	e.preventDefault();
	id = $(this).attr('id');
	var url = $(this).attr('href');
	that = $(this);

	var loaderHtml = '<div class="row-fluid popup-loader" id="id' + id + '">\
						<div class="offset4 span4">\
							<?php echo $this->Html->image('loaders/loader12.gif', array('escape' => false));?>\
						</div>\
					</div>';
	that.parent().parent().parent().before(loaderHtml);

	
	$.ajax({
		type: "GET",
		url: url,
		success: function(result){	
			$('.load-more').remove();
			$('.popup-loader#id' + id).remove();
			$('.popup-wrap#' + id).append(result);
		}									
	});
});

<?php if(!$loggedIn){?>
	$('.submit-design').click(function(e){
		e.preventDefault();
		$('#loginModal').modal();
	});
<?php }?>

$(document).ready(function(){
	//$('.collapse').collapse();
<?php 
	if(isset($designId)){
		?>
		var designId = <?php echo $designId?>;
		$(".comment#" + designId).click();
		<?php
	}
?>
});
</script>