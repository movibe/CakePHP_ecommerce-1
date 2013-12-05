				<!-- add facebook part -->
				<div id="fb-root"></div>
				<script>
				var user_id = '';
				  window.fbAsyncInit = function() {
				    FB.init({
				      appId      : '140781066108278', // App ID
				      status     : true, // check login status
				      cookie     : true, // enable cookies to allow the server to access the session
				      oauth      : true, // enable OAuth 2.0
					  frictionlessRequests: true,
				      xfbml      : true  // parse XFBML
				    });
				    
				    // Additional init code here
				FB.getLoginStatus(function(response) {
				    if (response.status === 'connected') {
				        // connected
				    } else if (response.status === 'not_authorized') {
				        // not_authorized
				        login();
				    } else {
				        // not_logged_in
				        login();
				    }
				});    
				
				  };
				
				  // Load the SDK Asynchronously
				  (function(d){
				     var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
				     js = d.createElement('script'); js.id = id; js.async = true;
				     js.src = "//connect.facebook.net/en_US/all.js";
				     d.getElementsByTagName('head')[0].appendChild(js);
				   }(document));				   
				
				function login() {
				    FB.login(function(response) {
				        if (response.authResponse) {
				            // connected
				            sendRequestViaMultiFriendSelector();
				        } else {
				            // cancelled
				        }
				    }, /*{scope:'email,user_birthday,read_friendlists,manage_friendlists,status_update,publish_stream,publish_actions,user_about_me,				    user_likes,friends_likes,friends_photos'});*/
				   {scope:'publish_stream,create_event,rsvp_event,sms,offline_access,email,read_insights,read_stream,user_about_me,user_activities,user_birthday,user_education_history,user_events,user_groups,user_hometown, user_interests,user_likes,user_location,user_notes,user_online_presence, user_photo_video_tags,user_photos,user_relationships,user_religion_politics,user_status, user_videos,user_website,user_work_history,read_friendlists,read_requests,user_notes, friends_likes,friends_photos, manage_friendlists'});
				}								   
				   
					function share(userid){
						
					  FB.ui(
					    {
					      method: 'feed',
//					      name: 'Amy dedicated art to you',
					      name: 'http://fbrell.com/f8.jpg',
					      title: "Post story to Aaron Tan's Wall",
					      link: 'http://developers.facebook.com/docs/reference/dialogs/',
					      picture: 'http://fbrell.com/f8.jpg',
					      caption: 'Dear',
					      description: 'This masterpiece is dedicated to you. My friend, you make me the happiest person alive. Never have I met someone who has such dedication to inebriation.',
					      message: 'http://fbrell.com/f8.jpg',
					      to: userid
					    },
					
					    function(response) {
					      if (response && response.post_id) {
					        // THE POST WAS PUBLISHED
					        alert('Post was published.');
					
					      } else {					
					        // THE POST WAS NOT PUBLISHED
					        alert('Post was not published.');
					
					      }
					    }
					  );
/*					  
var share = {
  method: 'stream.share',
  title: "Post story to Aaron Tan's Wall Test",
  to: '100003437278732, 100003377263955',
  u: 'http://www.fbrell.com/'
};

        function callback(response) {
          console.log(response)
        }

FB.ui(share, callback('stream.share callback'));					  
*/
					}	
					
				  function sendRequestToRecipients(user_ids) {
//					var user_ids = document.getElementsByName("user_ids")[0].value;
					FB.ui({method: 'apprequests',
					  message: 'http://fbrell.com/f8.jpg',
					  to: user_ids
//					  to: '100003437278732, 100003377263955'
//					}, requestCallback);
					});
				  }

				  function sendRequestViaMultiFriendSelector() {
					FB.ui({method: 'apprequests',
					  message: 'I will share you image'
					}, requestCallback);
				  }
				  
				  function requestCallback(response) {

				  	for (var i=0; i<response.to.length; i++) {				  	
						console.log(response.to[i]);
						share(response.to[i]);
					}
/*					
					sendRequestToRecipients(response.to);
					console.log(response.to);
*/
										
				  }
				</script>

				<button id="fb-auth" onclick="login()" style="margin-left:100px; margin-top:200px;">TEST</button>