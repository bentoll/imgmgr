<?php
	ini_set('max_execution_time', 300);
	$SETTINGS['imageDirectory'] = 'photos/';							// Where will your photos be stored?
	$SETTINGS['imageTypes'] = array('gif','jpeg','jpg','png','tiff');	// What are your allowed photo types?
	$SETTINGS['pImageDirectory'] = 'imgmgr.photos/';					// Where will your processed images be stored (thumbnails, resized)
	$SETTINGS['thumbnail']['height'] = 150;								// Height of thumbnails (px)
	$SETTINGS['thumbnail']['width'] = 200;								// Width of thumbnails (px)
	$SETTINGS['resized']['height'] = 768;								// Resized photo height (px)
	$SETTINGS['resized']['width'] = 1024;								// Resized photo width (px)
	$SETTINGS['compressionQuality'] = 75;
?>