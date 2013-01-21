<?php
require_once 'config.php';
$user = $facebook->getUser();
?>
<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>

<title>Facebook Albums Browser and Downloader</title>
<link href="css/style.css" media="screen" type="text/css" rel="stylesheet">
<link href="colorbox/colorbox.css" media="screen" type="text/css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="colorbox/jquery.colorbox-min.js"></script>
</head>
<body>

<div id="wrapper">
	<div id="header">
		<h1><a href="index.php">My Facebook Albums Browser and Downloader</a></h1>
		<div class="links">
			<?php 
				if ($user){	
					try {
						$user_albums = $facebook->api('/me/albums');
						$albums = array();
						if(!empty($user_albums['data'])) {
							foreach($user_albums['data'] as $album) {
								$temp = array();
								$temp['id'] = $album['id'];
								$temp['name'] = $album['name'];
								$temp['thumb'] = "https://graph.facebook.com/{$album['id']}/picture?type=album&access_token={$facebook->getAccessToken()}";
								$temp['count'] = (!empty($album['count'])) ? $album['count']:0;
								if($temp['count']>1 || $temp['count'] == 0)
									$temp['count'] = $temp['count'] . " photos";
								else
									$temp['count'] = $temp['count'] . " photo";
								$albums[] = $temp;
							}
						}
					} 
					catch (FacebookApiException $e) {
						error_log($e);
						var_dump($e);
						$user = null;
						setcookie('fbm_'.$facebook->getAppId(), '', time()-100, '/', 'agbcorplegal.com/');
					}
						$logout_url=(string) html_entity_decode($facebook->getLogoutUrl());
						echo'<a class="login" href="logout.php"><img src="img/fb_logout.png" title="Logout from Facebook" alt="Logout from Facebook" /></a>';
           		 }
            	else {
						$params = array(
							  'scope' => 'publish_stream, user_photos,friends_photos',
							  'redirect_uri' => 'http://www.agbcorplegal.com/Online_Assignment/'
							);
                      $login_url = $facebook->getLoginUrl( $params );
                      echo '<a href="' . $login_url . '"><img src="img/facebook-login.png" title="Login With Facebook" alt="Login with Facebook" /></a>';
                } 
                
             ?>      
		</div>
	</div>
	<div id="content">
	<?php if(!empty($albums)) { ?>
	<table id="albums">
	<tr>
	<?php
	$count = 1;
	foreach($albums as $album) {
		if( $count%6 == 0 )
			echo "</tr><tr>";
		echo	"<td class='albumcontainer'>" .
				"<a href='#' class='album' id='".$album['id']."'  title='".$album['name']."'>" .
				"<div class=\"thumb\" style=\"background: url({$album['thumb']}) no-repeat 50% 50%\"></div>" .
				"<p>{$album['name']}</p>" .
				"<p>{$album['count']}</p>" .
				"</a>
				<a href='#' id='".$album['id']."' class='download'>Download this album</a>
				<div id='image".$album['id']."' class='wait'><img src='img/downloading1.gif' title='Downloading Album..' alt='Downloading Album.....'/></div></td>";
		$count++;
	}
	?>
	</tr>
	</table>
	<?php } ?>
	</div>
</div>
<div id="maximage">
	
</div>
<script type="text/javascript" src="js/fb.js"></script>
</body>
</html>
