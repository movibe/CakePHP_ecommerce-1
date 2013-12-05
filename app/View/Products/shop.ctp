<?php
	$this->start('header');
	$buttons['shop']['isActive'] =  true;
	echo $this->element('Common/header', array('buttons' => $buttons));
	$this->end();
?>
<div class="container">
<div class="toplinks">
<?php echo $this->Html->image('shop.jpg');?>
</div>
	<div class="row">
		<div class="span12">
			<div class="join-now-wrap">
				<i><strong>25% of all sales will go to the cause chosen by the community</strong></i><br>
				<i><strong>All designs will be printed on the highest quality Tees</strong></i><br>
    			
			</div>
		</div>
	</div>
	<div class="row">
		<div class="span3">
			<?php
				echo $this->element('design/categories_holder');
				//echo $this->element('design/collections_holder');
			?>
		</div>

		<div class="span9">
			<div class="row-fluid">
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
			<?php foreach($products as $index => $product){?>
				<div class="span3">
					<div class="boxRepeat">
						<div class="boxImage">
							<?php echo $this->Html->image('uploaded/medium/' . $product['CoverImage']['filename'], array('class' => 'comment', 'id' => $product['Product']['id']));?>
						</div>
						<div class="contentBox">
							<div class="smallImage">
								<?php echo $this->Html->image($product['User']['profileImageUrl'], array('url' => array('controller' => 'users', 'action' => 'view', $product['User']['id']), 'alt' => $product['User']['name']));?>
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
									<a class="share" id="<?php echo $product['Product']['id'];?>"><i class="icon-share"></i>Share</a>
									
								</li>
							</ul>
						</div>
						
						<div class="row-fluid collapse" id="shareDropDown<?php echo $product['Product']['id'];?>">
							<div class="span6">
								<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $this->Html->url(array('controller' => 'products', 'action' => 'index', $product['Product']['id']), true);?>" data-size="small">Tweet</a>
								<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
							</div>
							<div class="span6 pull-right">
								<fb:like href="<?php echo $this->Html->url(array('controller' => 'products', 'action' => 'index', $product['Product']['id']), true);?>" layout="button_count" width="100" show_faces="true"></fb:like>
							</div>
						</div>	
						
					</div>
					
				</div>
			<?php }?>
			</div>
				
		</div>
	</div>
	<div class="row">
		<div class="span1 offset11 data-original-title title">
			<a class="scroll" href="#"><i class="icon-search icon-arrow-up"></i> Scroll to top</a>
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
<div id="push"></div>
<?php
	echo $this->Html->script(array(
		'uniformjs/jquery.uniform'
	));
?>
<script type="text/javascript">
$("select,input[type=radio]").uniform();
/* Range slider */
$(".uiRangeSlider").slider({
	range : true,
	min : <?php echo $minPrice;?>,
	max : <?php echo $maxPrice;?>,
	values : [ <?php echo $minPrice;?>, <?php echo $maxPrice;?>],
	slide : function(event, ui) {
		$('#ProductMinPrice').val(ui.values[0]);
		$('#ProductMaxPrice').val(ui.values[1]);
		$("#rangeAmount").html(
				"$" + ui.values[0] + " - $"
						+ ui.values[1]);
	}
});

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

//$('.collapse').collapse();
$(".share").click(function(e){
	e.preventDefault();
	// id = $(this).attr('id');
	// $('#shareDropDown' + id).toggleClass('in', 10, 'swing');
	
	$('#shareModal').modal('show');
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
		$('body').prepend(newPopupDiv);
		$('body,html').css('overflow', 'hidden');


		$('#popup' + imageId).live('hidden', function(){
			$('body,html').css('overflow', 'visible');
		});
		
		$('#popup' + imageId).live('shown', function(){
			var url = '<?php echo $this->Html->url(array('controller' => 'comments', 'action' => 'index', 'Product'));?>' + '/' + imageId;
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

$('.modal').live('click', function(e){
	if($.inArray("modal", e.target.classList) > -1)
		$('#popup' + imageId).modal('hide');
});

$(".reply").live('click', function(e){
	e.preventDefault();
	var self = $(this);
	productId = $(this).parent().attr('data-model');
	parentId = $(this).parent().attr('data-parent');
	comment = $(this).parent().children('div').children('textarea').val();

	btn = $(this);
	$(this).button('loading');
	
	
	$.ajax({
		type: "POST",
		url: '<?php echo $this->Html->url(array('controller' => 'comments', 'action' => 'add.json')); ?>',
		data: {
			'data[Comment][foreign_key]': productId,
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
					$('#popup' + productId).modal('hide');
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
	var url = '<?php echo $this->Html->url(array('controller' => 'products'));?>';
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
	$('.submit-product').click(function(e){
		e.preventDefault();
		$('#loginModal').modal();
	});
<?php }?>

$(document).ready(function(){
	//$('.collapse').collapse();
<?php 
	if(isset($productId)){
		?>
		var productId = <?php echo $productId?>;
		$(".comment#" + productId).click();
		<?php
	}
?>
});

</script>