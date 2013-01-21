<?php 
   require 'config.php';
   $my_url = "http://agbcorplegal.com/Online_Assignment/logout.php";

   session_start();
   $token = $_SESSION["access_token"];
 if($token) {
     $graph_url = "https://graph.facebook.com/me/permissions?method=delete&access_token=" 
       . $token;

     $result = json_decode(file_get_contents($graph_url));
     if($result) {
        session_destroy();
        echo("User is now logged out.");
     }
   } else {
     echo("User already logged out.");
   }
?>