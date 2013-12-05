<?php
	$this->start('header');
	$buttons['shop']['isActive'] =  true;
	echo $this->element('Common/header', array('buttons' => $buttons));
	$this->end();
?>

<div class="container">

	<div class="row">
		<div class="span12">
			<div class="join-now-wrap">
				<i><strong>Lorem Ipsum dolor sit amet, Consectetuer
				adipiscing elit, </strong> sed diam nonummy</i>
				<button type="submit" class="btn">Submit Now</button>
			</div>
			
		</div>
	</div>
	<div class="row">
	<?php foreach($products as $index => $product){?>
		<div class="span3 border">
			<div class="boxRepeat">
				<div class="boxImage">
					<?php echo $this->Html->image('uploaded/medium/' . $product['CoverImage']['filename'], array('class' => 'big-image'));?>
				</div>
				<div class="contentBox">
					<div class="smallImage">
						<?php echo $this->Html->image($product['User']['profileImageUrl'], array('url' => array('controller' => 'users', 'action' => 'view', $product['User']['id']), 'alt' => $product['User']['name'], 'class' => 'small-image'));?>
					</div>
					<div class="imageDescription">
						<p><?php echo $product['Product']['title'];?></p>
					</div>
				</div>
			</div>
			<div class="boxIcons">
				<div class="social-icons">
					<ul>
						<li>
							<?php echo $this->Html->link('<i class="icon-heart"></i>' . $product['Product']['likeText'], '#', array('escape' => false, 'class' => 'like', 'id' => $product['Product']['id']));?>
						</li>
						<li>
							<?php echo $this->Html->link('<i class="icon-comment"></i>Comment', '#', array('escape' => false, 'class' => 'comment', 'id' => $product['Product']['id']));?>
						</li>
						<li>
							<?php echo $this->Html->link('<i class="icon-share"></i>Share', '#', array('escape' => false, 'class' => 'share', 'id' => $product['Product']['id']));?>
						</li>
					</ul>
				</div>
			</div>
		</div>
	<?php }?>
	</div>
		

	</div>

</div>
<!--/span-->
<div id="push"></div>
<script type="text/javascript">
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
			'data[Like][model]': 'Product',
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
		$('body').append(newPopupDiv);
		$('#popup' + imageId).modal({
			remote: '<?php echo $this->Html->url(array('action' => 'view'));?>' + '/' + imageId
		});
	}
	else{
		$('#popup' + imageId).modal();
	}
});
$(".reply").live('click', function(e){
	e.preventDefault();
	var self = $(this);
	designId = $(this).parent().attr('data-design');
	parentId = $(this).parent().attr('data-parent');
	comment = $(this).parent().children('div').children('textarea').val();

	btn = $(this);
	$(this).button('loading');
	
	
	$.ajax({
		type: "POST",
		url: '<?php echo $this->Html->url(array('controller' => 'comments', 'action' => 'add.json')); ?>',
		data: {
			'data[Comment][foreign_key]': designId,
			'data[Comment][model]': 'Product',
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
					btn.after('Please login');
					$('#popup' + designId).modal('hide');
					$('#loginModal').modal();
				}
			}
		}									
	});

	
});

$("a[id^=anchorReply]").live('click', function(e){
	e.preventDefault();
	var commentId = $(this).attr('id').substr(11);
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
</script>