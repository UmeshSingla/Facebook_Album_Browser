<?php 
	header('Content-Description: File Transfer');
    header('Content-Type: application/zip');
	header("Content-Type: application/force-download");
    header('Content-Disposition: attachment; filename="albums.zip"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize('albums.zip'));
    readfile('albums.zip');
	ob_clean();
    flush();
	unlink('albums.zip');
	exit;

?>