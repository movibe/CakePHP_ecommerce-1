<?php
	$this->start('header');
	$buttons['causes']['isActive'] =  true;
	echo $this->element('Common/header', array('buttons' => $buttons));
	$this->end();
?>

<div class="container">
<!--	<div class="row" style="border:1px solid #d2d8d8; border-radius:5px 5px 5px 5px; background-color:#fff; padding:5px; color:#9a9c9b;">-->
	<div class="row">
		<div class="span12">
			<div class="join-now-wrap">
				<h4><i><strong>Share your experience with a cause.. </strong></i></h4>
				<?php echo $this->Html->link('Submit it Now', array('action' => 'add'), array('class' => 'btn submit-cause'))?> <br>
								
			</div>
		</div>

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
	
	<?php foreach($causes as $index => $cause){?>
		<?php if($index % 4 == 0){?>
		<div class="row items">
		<?php }?>
		<div class="span2">
			<div class="boxRepeat">
				<div class="boxImage">
					<?php echo $this->Html->image('upload/thumb/prefix_' . $cause['CoverImage']['filename'], array('class' => 'comment', 'id' => $cause['Cause']['id']));?>
				</div>
				<div class="contentBox">
					<div class="smallImage">
						<?php echo $this->Html->image($cause['User']['profileImageUrl'], array('url' => array('controller' => 'users', 'action' => 'view', $cause['User']['id']), 'alt' => $cause['User']['name']));?>
					</div>
					<div class="imageDescription">
						<p><?php echo $cause['Cause']['title'];?></p>
					</div>
				</div>

				<div class="boxIcons">
					<div class="social-icons">
						<ul>
							<li>
								<?php echo $this->Html->link($cause['Cause']['likes'] . ' <i class="icon-heart"></i>' . $cause['Cause']['likeText'], '#', array('escape' => false, 'class' => 'like', 'id' => $cause['Cause']['id']));?>
							</li>
							<li>
								<?php echo $this->Html->link($cause['Cause']['comments'] . '<i class="icon-comment"></i>Comment', '#', array('escape' => false, 'class' => 'comment', 'id' => $cause['Cause']['id']));?>
							</li>
							<li>
								<a class="share" id="<?php echo $cause['Cause']['id'];?>"><i class="icon-share"></i>Share</a>
								
							</li>
						</ul>
					</div>
				
					<div class="row-fluid collapse" id="shareDropDown<?php echo $cause['Cause']['id'];?>">
						<div class="span6">
							<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $this->Html->url(array('controller' => 'causes', 'action' => 'index', $cause['Cause']['id']), true);?>" data-size="small">Tweet</a>
							<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
						</div>
						<div class="span6 pull-right">
							<!-- fb:like href="<?php echo $this->Html->url(array('controller' => 'causes', 'action' => 'index', $cause['Cause']['id']), true);?>" layout="button_count" width="100" show_faces="true"></fb:like  -->
							<fb:send href="<?php echo $this->Html->url(array('controller' => 'causes', 'action' => 'index', $cause['Cause']['id']), true);?>"></fb:send>
						</div>
					</div>	
				
				</div>
			</div>
			
		</div>
		<?php if($index % 4 == 3){?>
		</div>
		<?php }?>
	<?php }?>
		
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
		<div class="modal-footer">
			<button class="btn btn-info" data-dismiss="modal" aria-hidden="true">Close</button>
			<!--<button class="btn btn-primary">Save changes</button>-->
		</div>
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

//$('.collapse').collapse();
$(".share").click(function(e){
	e.preventDefault();
	// id = $(this).attr('id');
	// $('#shareDropDown' + id).toggleClass('in', 10, 'swing');
	
	$('#shareModal').modal('show');
});
var scrollbar_count = 0;
var id_temp = "";
var id_bool = false;
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
	var url = '<?php echo $this->Html->url(array('controller' => 'causes', 'action' => 'index', ''));?>';
	url = url + '/' + filter;
	$(location).attr('href', url);
});

page = 1;
/*
$('.load-more a').live('click', function(e){
	e.preventDefault();
	id = $(this).attr('id');
	var url = $(this).attr('href');
	that = $(this);

	var loaderHtml = '<div class="row-fluid popup-loader" id="id' + id + '">\
						<div id="rsg_img" class="offset4 span4">\
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
			$("#content_1").mCustomScrollbar("update"); //update scrollbar according to newly appended content
			$("#content_1").mCustomScrollbar("scrollTo",".popup-wrap > div:last",{scrollInertia:2500,scrollEasing:"easeInOutQuad"}); //scroll to appended content				
		}									
	});
});
*/
<?php if(!$loggedIn){?>
	$('.submit-cause').click(function(e){
		e.preventDefault();
		$('#loginModal').modal();
	});
<?php }?>

$(document).ready(function(){
	//$('.collapse').collapse();
<?php 
	if(isset($causeId)){
		?>
		var causeId = <?php echo $causeId?>;
		$(".comment#" + causeId).click();
		<?php
	}
?>
});
</script>