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
				$temp['name'] = (isset($photo['name'])) ? $photo['name']:'';
				$temp['picture'] = $photo['images'][1]['source'];
				$temp['source'] = $photo['source'];
				$photos[] = $temp;
			}
		}
?>
	<?php if(!empty($photos)) { ?>
	<?php
	$count = 0;
	foreach($photos as $photo) {
		$lastChild = "";
		$count++;
		echo "<a href='".$photo['source']."' class='gallery'></a>";
	}
	?>
	<?php } ?>
