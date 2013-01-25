<?php
require_once 'config.php';
//Get facebook User Id
$user = $facebook->getUser();
?>

<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
        <title>Facebook Albums Browser and Downloader</title>
        <link href="css/style.css" media="screen" type="text/css" rel="stylesheet">
        <link href="lib/css/colorbox.css" media="screen" type="text/css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script src="lib/js/jquery.colorbox-min.js"></script>
    </head>
    <body>
        <div id="wrapper">
            <div id="header">
                <h1><a href="index.php">My Facebook Albums Browser and Downloader</a></h1>
                <div class="links">
                    <?php 
                        if ($user){	
                            try {
                                $user_albums = $facebook->api('/me/albums'); //call to Facebook Graph API method to access users album
                                $albums = array();
                                if(!empty($user_albums['data'])) {
                                    foreach($user_albums['data'] as $album) {
                                        $temp = array();
                                        $temp['id'] = $album['id'];//Album Id
                                        $temp['name'] = $album['name'];//Album Name
                                        $temp['thumb'] = "https://graph.facebook.com/{$album['id']}/picture?type=album&access_token={$facebook->getAccessToken()}";//Access Album Cover
                                        $temp['count'] = (!empty($album['count'])) ? $album['count']:0;// Number of Photos in the Album
                                        if($temp['count']>1 || $temp['count'] == 0)
                                            $temp['count'] = $temp['count'] . " photos";  //Display Number of Photos on index
                                        else
                                            $temp['count'] = $temp['count'] . " photo";
                                        $albums[] = $temp;
                                    }
                                }
                            } 
                            catch (FacebookApiException $e) {
                                error_log($e); //Checks if user is logged out and generate an exception if access token is invalid
                                var_dump($e);
                                $user = null; //set user id Null if user has logged out
                                setcookie('fbm_'.$facebook->getAppId(), '', time()-100, '/', 'agbcorplegal.com/'); //Delete cookies as the user logs out and we still ave a user id.
                            }
                                //$logout_url=(string) html_entity_decode($facebook->getLogoutUrl()); //Obtain the logout url using facebook object
                            echo'<a class="login" href="logout.php"><img src="lib/img/fb_logout.png" title="Logout from Facebook" alt="Logout from Facebook" /></a>';
                         }
                         else{
                                $params = array(
                                      'scope' => 'publish_stream, user_photos,friends_photos',   //Requesting User Permissions through Facebook App
                                      'redirect_uri' => 'http://www.agbcorplegal.com/Online_Assignment/' //User is redirected after Login
                                    );
                                $login_url = $facebook->getLoginUrl( $params );//Login URL
                                echo '<a href="' . $login_url . '"><img src="lib/img/facebook-login.png" title="Login With Facebook" alt="Login with Facebook" /></a>';
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
                                    foreach($albums as $album) { //Prints out each album details 
                                        if( $count%6 == 0 ) 
                                            echo "</tr><tr>";
                                        echo	"<td class='albumcontainer'>" .
                                                "<a href='#' class='album' id='".$album['id']."'  title='".$album['name']."'>" .
                                                "<div class=\"thumb\" style=\"background: url({$album['thumb']}) no-repeat 50% 50%\"></div>" .
                                                "<div class='info'><p class='albumname'>{$album['name']}</p>" .
                                                "<p>{$album['count']}</p>" .
                                                "</a><!-- Onclick activates the Jquery function in fb.js -->
                                                <a href='#' id='".$album['id']."' class='download'>Download this album</a> <!--Call to jquery function in fb.js -->
                                                <div id='image".$album['id']."' class='wait'><img src='lib/img/downloading1.gif' title='Downloading Album..' alt='Downloading Album.....'/></div></td></div>";
                                        $count++;
                                    }
                                ?>
                            </tr>
                        </table>
            <?php } ?>
            </div>
        </div>
        <div id="maximage"> <!--Slideshow Div -->
            
        </div>
        <script type="text/javascript" src="js/fb.js"></script>
    </body>
</html>
