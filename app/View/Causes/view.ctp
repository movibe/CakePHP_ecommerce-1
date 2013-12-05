<div class="row-fluid" style="position:relative;">
<div style="position:absolute; right:0px; top:0px; z-index:1001; background-color:#5897A9; width:110px; height:50px; padding:5px;">
	<div style="width:70px; margin-left:10px;">
	
<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.spreadnest.com%2F&amp;send=false&amp;layout=button_count&amp;width=90&amp;show_faces=true&amp;action=like&amp;colorscheme=light&amp;font&amp;height=20" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:90px; height:20px;" allowTransparency="true"></iframe>
			
	</div>
	<div style="height:1px; background-color:#7FBED0;"></div>
	<div style="margin-top:5px; margin-left:10px;">
		<script>
		function fbs_click() {
			u=location.href;t=document.title;window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');return false;
		}
		</script>
		<style>
			html .fb_share_link { padding:2px 0 0 20px; height:16px; background:url(http://static.ak.facebook.com/images/share/facebook_share_icon.gif?6:26981) no-repeat top left; }
		</style>
		<a rel="nofollow" href="http://www.facebook.com/share.php?u=<;url>" onclick="return fbs_click()" target="_blank" class="fb_share_link">Share</a>	
	</div>
</div>

<div style="cursor:pointer; position:absolute; right:0; z-index:1000;">
    <div style="position:relative; margin-right:20px; margin-top:60px;">
    <a href="http://pinterest.com/" style="margin-right:10px;"><?php echo $this->Html->image('icons/73-pinterest-128.png')?></a>
    <a href="http://www.twitter.com" style="margin-right:10px;"><?php echo $this->Html->image('icons/45-twitter-128.png')?></a>
    <a href="http://www.facebook.com" style="margin-right:10px;"><?php echo $this->Html->image('icons/46-facebook-128.png')?></a>
    </div>
</div>
<div id="rsg_popup_close" onclick="rsg_modal_end();">
    <?php echo $this->Html->image('close_rsg.png')?>
</div>
<script>
    function rsg_modal_end() {
        $('.modal').modal('hide');
    }
</script>
<?php
//pr($cause);
?>
	<div class="popup-mainimage text-center">
		<div id="myCarousel" class="carousel slide">
			<!-- Carousel items -->
			<div class="carousel-inner">
            <?php $img_counter_rsg = 0;?>
			<?php foreach($cause['Image'] as $key => $image){?>
				<div class="item <?php echo $key == 0 ? 'active' : '';?>">
					<?php echo $this->Html->image('uploaded/' . $image['filename'])?>
				</div>
			<?php $img_counter_rsg = $img_counter_rsg + 1; }?>
			</div>
            <?php if($img_counter_rsg>1) {?>
			<a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
			<a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
            <?php }?>
			<!--<br/>
			<div class="image-text">
			<?php //echo "Show your support by liking this cause, then, visit us when the shirt designs are up for voting";?>
			</div>-->
		</div>
	</div>
</div>



<div id="id_rsg3"> 

<div id="id_rsg1">
	<div class="row-fluid">
		<div class="span6">
        
        	<div class="cause-popup-header">
                <div class="cause-profilepic">
                <?php echo $this->Html->image($cause['User']['profileImageUrl'], array('url' => array('controller' => 'users', 'action' => 'view', $cause['User']['id']), 'alt' => $cause['User']['name']));?>
                </div><!-- /cause-profilepic -->
                
                <h2 class="cause-popup-title"><?php echo $cause['Cause']['title'];?></h2>
            </div><!-- /cause-popup-header -->
            
            <div class="clear"></div>
            
            <div class="horizontal-line"></div><!-- /horizontal-line -->
            
           <!--
			<strong>Goal to raise: $
                <?php 
                    echo $cause['Cause']['goal'];
                ?>
            </strong>
            -->
            
            <div class="cause-popup-addinfo">
                <p><?php echo "Cause: " . $cause['Cause']['cause_name']; ?><br />
                <?php echo "Posted by " . $cause['User']['name']; ?>&nbsp;&nbsp;
                <?php $a = getdate(strtotime($cause['Cause']['created'])); 
                printf('%s %d, %d',$a['month'],$a['mday'],$a['year']);
                ?>
                </p>
            </div><!-- /cause-popup-addinfo -->
                
		</div><!-- /span6 -->
       


                            
		<div class="span3">

			<?php $val=$cause['Cause']['description'];?> 
		    <?php /*$val = str_replace( array("\n"), '<br />', $val );*/
			
			echo "<p>" . implode( "</p>\n\n<p>", preg_split( '/\n(?:\s*\n)+/', $val ) ) . "</p>";
			
			?>
            
		
		</div><!-- span3 -->
        
	</div>
</div>


<div id="id_rsg2">

<!--
		<p class="output">
			<a href="http://www.spreadnest.com/ajax_content.html" rel="append-content">Append new content</a>
			<a href="http://www.spreadnest.com/ajax_content.html" rel="load-content">Load new content</a>
		</p>
-->		
		<div id="content_1" class="content">
			<div class="popup-wrap" id="<?php echo $cause['Cause']['id'];?>" style="">
			</div>
		</div>    

	<script>
		$(document).ready(function() {
				$("#content_1").mCustomScrollbar({
					scrollButtons:{
						enable:true
					}
				});
		});

	</script>

	<div id="id_rsg4">
		<div class="row-fluid" data-cause="<?php echo $cause['Cause']['id'];?>" data-parent="0">
			<div class="fbcomment-addcomment">
            
            <!--[if gte IE 9]>
              <style type="text/css">
                .gradient {
                   filter: none;
                }
              </style>
            <![endif]-->
            
				<textarea class="span12 text-box gradient" placeholder="Add a comment"></textarea>
			</div>
			<button data-loading-text="loading ..."	class="btn-green btn pull-left reply" type="button">Comment</button>
		</div>

		<div class="row-fluid popup-loader" id="id<?php echo $cause['Cause']['id'];?>">
			<div class="offset4 span4">
				<?php echo $this->Html->image('loaders/loader12.gif', array('escape' => false));?>
			</div>
		</div>		
	</div>
	</div>
<div style="clear:both;"></div>
</div>

