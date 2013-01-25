<?php
   require 'config.php';
   //ovewrites the cookie
   	unset($_COOKIE['PHPSESSID']);
	$signed_request_cookie = 'fbsr_' . $_fbApiKey;
	setcookie($signed_request_cookie, "", time()-3600, '/', $_SERVER['HTTP_HOST']);
	session_destroy();
	$facebook->destroySession();
?>

<html>
<head></head>
<body>
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    // init the FB JS SDK
    FB.init({
      appId      : '194305640709555', // App ID from the App Dashboard
      channelUrl : '//WWW.agbcorplegal.com/Online_Assignment/lib/channel.html', // Channel File for x-domain communication
      status     : true, // check the login status upon init?
      cookie     : true, // set sessions cookies to allow your server to access the session?
      xfbml      : true  // parse XFBML tags on this page?
    });
	FB.logout(function(response) {
		  FB.Auth.setAuthResponse(null, 'unknown');
		});
    // Additional initialization code such as adding Event Listeners goes here

  };
</script>
<script type="text/javascript">
	window.location='index.php';
</script>
</body>
</html>


