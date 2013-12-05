<?php
	$this->start('header');
	//$buttons['designs']['isActive'] =  true;
	echo $this->element('Common/header', array('buttons' => $buttons));
	$this->end();
?>
<div class="container other">
	<div class="row">
    	<div class="span9">
		<h3 class="title">About Us</h3>
        <div class="horizontal-line"></div>
		<p>
			<strong>Welcome.</strong>  Spreadnest is a community of people who believe in making a positive difference for the most important issues, affecting today's world. Month over month we want
to raise money, create a few jobs, and measure the success and impact of our donations. Through selecting a cause, choosing the top design, and
making really high quality clothing, we can create a fun way to promote equality.
		</p>

		<p>
			25% of every sale will go directly to the cause selected by you. The first question you should be asking is why only 25%? Let me explain, charities
don't spend every dollar you donate on their program (a program could be building wells, distributing medicine"). Charitable organizations spend anywhere
from 1-97% of their donations on administration and fundraising expenses (ex. overhead, staff, advertising, printing, publicity, rent,
legal fees, accounting, gasoline, vehicles). The more well known a charity is, the less it has to spend on fundraising. Take the American Red Cross
for example, they are a very well known charity , 92% of donations are spent on their program. At the opposite end, the Children's Charity Fund Inc
spends only 8% of your donations on their program for children. Because we are a very small service, we are currently for profit, because we are
aiming to grow the size of our community as quickly as possible. With 25% of your money going directly to a cause, and the rest going to pay fees
that we require to grow, hire more creative minds and maintain our community (ex. employees, hosting, programming, rent, accounting, legal, insurance, t-shirt materials,
printing costs, computers).
		</p>

		<p>
			We're in it for the long haul, we aim to provide the best experience and always offer choice to our users. If you have any questions, I will gladly
be available to answer them. If I don't know the answer, I will find it for you.
		</p>

	</div><!-- /span9 -->
    
    <div class="span3">
    	<div class="right-sidebar">
    		<h3 class="title">Contact Us</h3>
        	<div class="horizontal-line"></div>
        
        	<p><strong>Alex Golke (Founder)</strong><p>
                
                
			<p><strong>Address</strong><br />
            27510-40 Ave<br>
			Langley, BC</p>
            
			<p><strong>Phone</strong><br />
            1-604-845-4725<br>
			<a href="mailto:agolke@spreadnest.com">agolke@spreadnest.com</a><br></p>
            
            <div class="break"></div>
            
            <h3 class="title">Social Media</h3>
        	<div class="horizontal-line"></div>
            
						<ul class="inline">
							<!--<li><?php //echo $this->Html->image('social-icons/google.png');?>
							</li>-->
							<li><?php echo $this->Html->image('social-icons/twitter.png');?>
							</li>
							<li><?php echo $this->Html->image('social-icons/facebook.png');?>
							</li>
						</ul>
            
        </div><!-- /.right-sidebar -->
        
    </div><!-- /.span3 -->
    
   </div> <!-- /.row -->
   
</div><!-- /.container -->
<script type="text/javascript">
	$(document).ready(function(){
		$("#abouts-us-page").addClass("active-menu");
	});
</script>