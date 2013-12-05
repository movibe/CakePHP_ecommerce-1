<?php
	$this->start('header');
	$buttons['causes']['isActive'] =  true;
	echo $this->element('Common/header', array('buttons' => $buttons));
	$this->end();
?>
<div class="container">
	<div class="row">
		<div class="span12">
			<div class="join-now-wrap">
				<i><strong>Have a cause to submit? </strong></i>
				<?php echo $this->Html->link('Submit Now', array('action' => 'add'), array('class' => 'btn submit-cause'))?>
			</div>
		</div>
	</div>
	<div class="span12">
		<div class="offset2 span6">
			<b class="text-error title-img">Title: </b> <span
				class="title-name"> <?php 
				if(isset($cause['Cause']))
					echo $cause['Cause']['title'];
				else
					echo "No Title";
				?> </span>
			
			<div class="row-fluid">
				<div class="span12">
					<div id="myCarousel" class="carousel slide">
						<!-- Carousel items -->
						<div class="carousel-inner">
						<?php foreach($cause['Image'] as $key => $image){?>
							<div class="item <?php echo $key == 0 ? 'active' : '';?>">
								<?php echo $this->Html->image('uploaded/' . $image['filename'])?>
							</div>
						<?php }?>
						</div>
						<a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
						<a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
					</div>
				</div>
			</div>
						
			<div>
				<b class="title-img">Description: </b> <span class="title-name"> <?php 
				if(isset($cause['Cause']))
				echo $cause['Cause']['description'];
				else
				echo "No description";
				?> </span>
				<div class="navbar"></div>
				<b class="title-img">Information: </b><span class="title-name">abbo
					picchi keka asalu abbo picchi keka asalu abbo picchi keka asalu </span>
			</div>

		</div>
	</div>
	<?php
	$count = 0;
	foreach($causes as $monthCause){
		if(($count / 4) == 0){
			if($count != 0){
				echo '</ul>';
				echo "\n";
				echo '</div>';
			}
			echo '<div class="row">';
			echo "\n";
			echo '<ul class="thumbnails">';
		}
		?>
	<li class="span3"><a href="#"><?php 
	echo '<a href="'.$this->Html->url(array('controller' => 'causes', 'action' =>'month/'.($count+1))).'">';
	if(isset($monthCause['Image'][0])){
		echo $this->Html->image('uploaded/'.$monthCause['Image'][0]['filename']);
	}else{
		echo $this->Html->image('Tulips.jpg');
	}
	echo '</a>';
	?> </a>
	</li>
	<?php
	$count++;
	}
	echo '</div>';
	?>
	</div>
<div id="push"></div>