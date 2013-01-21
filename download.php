<?php
if( !isset($_POST['id']) )
	die("No direct access allowed!");
require 'facebook.php';
$facebook = new Facebook(array(
	'appId'  => '194305640709555',
	'secret' => '2c81563357ce6f3022af6833e2e69fb5',
	'cookie' => true,
));

if( !isset($_POST['id']) )
	die("No direct access allowed!");
		$root = realpath($_SERVER["DOCUMENT_ROOT"]);
		$params = array();
		if( isset($_GET['offset']) )
			$params['offset'] = $_GET['offset'];
		if( isset($_GET['limit']) )
			$params['limit'] = $_GET['limit'];
		$params['fields'] = 'name,source,images';
		$params = http_build_query($params, null, '&');
		$album_photos = $facebook->api("/{$_POST['id']}/photos?$params");
		if( isset($album_photos['paging']) ) {
			if( isset($album_photos['paging']['next']) ) {
				$next_url = parse_url($album_photos['paging']['next'], PHP_URL_QUERY) . "&id=" . $_GET['id'];
			}
			if( isset($album_photos['paging']['previous']) ) {
				$pre_url = parse_url($album_photos['paging']['previous'], PHP_URL_QUERY) . "&id=" . $_GET['id'];
			}
		}
		$photos = array();
		if(!empty($album_photos['data'])) {
			foreach($album_photos['data'] as $photo) {
				$temp = array();
				$temp['id'] = $photo['id'];
				$temp['name'] = (isset($photo['name'])) ? $photo['name']:'photo_'.$temp['id'];
				$temp['picture'] = $photo['images'][1]['source'];
				$temp['source'] = $photo['source'];
				$photos[] = $temp;
			}
		}
?>
	<?php if(!empty($photos)) { ?>
	<?php
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
} ?>
