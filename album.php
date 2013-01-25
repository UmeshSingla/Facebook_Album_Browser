<?php
if( !isset($_POST['id']) )
	die("No direct access allowed!");
require 'lib/facebook.php';
$facebook = new Facebook(array(
	'appId'  => '194305640709555',
	'secret' => '2c81563357ce6f3022af6833e2e69fb5',
	'cookie' => true,
));
		$params = array();
		$params['fields'] = 'name,source,images';
		$params = http_build_query($params, null, '&'); //Use to generate a query string
		$album_photos = $facebook->api("/{$_POST['id']}/photos?$params");// Photos for the corresponding album id are accessed with their name, source and photo itself
		$photos = array();
		if(!empty($album_photos['data'])) {
			foreach($album_photos['data'] as $photo) {
				$temp = array();
				$temp['id'] = $photo['id'];
				$temp['name'] = (isset($photo['name'])) ? $photo['name']:'';
				$temp['picture'] = $photo['images'][1]['source'];
				$temp['source'] = $photo['source'];
				$photos[] = $temp;
			}
		}
?>
	<?php if(!empty($photos)) { ?>
	<?php
	//Outputs Photo Links if User has requested Slide show
	if($_POST['type']=='slideshow'){
		$count = 0;
		foreach($photos as $photo) {
			$lastChild = "";
			$count++;
			echo "<a href='".$photo['source']."' class='gallery'></a>";
		}
	}
	
	//Creates a zip file of photos if user asks to download the album
	if($_POST['type']=='download'){
		$_SESSION['files']=array();
		$zip = new ZipArchive();
		$zipname="albums.zip";
		# create a temp file & open it
			$zip->open('albums.zip',ZipArchive::CREATE);
	# loop through each file
		foreach($photos as $photo) {
			$ch = curl_init($photo['source']);
			$filename="new_".$photo['id'].".jpg";
			$fp = fopen($filename, 'wb');
			curl_setopt($ch, CURLOPT_FILE, $fp);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$file=curl_exec($ch);
			curl_close($ch);
			file_put_contents($fp,file_get_contents($photo['source']));
			$zip->addFile($filename,$photo['id'].".jpg");
			array_push($_SESSION['files'],$filename);
			fclose($fp);
		}
		$zip->close();
		foreach($_SESSION['files'] as $filename){
			unlink($filename);
		}
	}
	?>
    
	<?php } ?>
