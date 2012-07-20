<?php
	session_start();
	require_once "imgmgr.settings.php";
	require_once "imgmgr.class.php";
	
	if(isset($_GET['action']) && $_GET['action'] == "regenerate") {
		imgmgr::deleteAllGeneratedImages($SETTINGS['pImageDirectory']);
		imgmgr::crawlDirectoryForResize(
		$SETTINGS['imageDirectory'],
		$SETTINGS['pImageDirectory'],
		$SETTINGS['imageTypes'],
		$SETTINGS['resized'],
		$SETTINGS['thumbnail'],
		$SETTINGS['compressionQuality']
		);
	}
	
	if(isset($_GET['action']) && $_GET['action'] == "generate") {
		imgmgr::crawlDirectoryForResize(
		$SETTINGS['imageDirectory'],
		$SETTINGS['pImageDirectory'],
		$SETTINGS['imageTypes'],
		$SETTINGS['resized'],
		$SETTINGS['thumbnail'],
		$SETTINGS['compressionQuality'],
		false);
	}
	
	if(isset($_GET['action']) && isset($_GET['album']) && $_GET['action'] == "delete") {
		if(is_dir($SETTINGS['pImageDirectory'].$_GET['album'])) {
			imgmgr::deleteAllGeneratedImages($SETTINGS['pImageDirectory'].$_GET['album']);
			rmdir($SETTINGS['pImageDirectory'].$_GET['album']);
		}
	}
	
	
	if(isset($_GET['album'])) {
		$albumDir = $_GET['album'];
	} else {
		$albumDir = '';
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>imgmgr - admin</title>
		<style type="text/css">
			body { background-color: #EEE; font-family: Arial; }
			#Main { width: 900px; margin: 20px auto; }
			#Content { border: 1px solid #DDD; background-color: #FFF; padding: 15px 0 15px 25px; }
			#Content h2 { font-size: 18px; }
			ul#ImageList { margin: 0; padding: 0; }
			ul#ImageList li { list-style: none; float: left; margin: 0 15px 15px 0; }
		</style>
		<link type="text/css" href="css/jquery.lightbox-0.5.css" rel="stylesheet" />
		<script src="js/jquery.js" type="text/javascript"></script>
		<script src="js/jquery.lightbox-0.5.min.js" type="text/javascript"></script>
		
		<script type="text/javascript">
		$(function() {
			$('#gallery a').lightBox(); // Select all links in object with gallery ID
			// This, or...
			$('a.lightbox').lightBox(); // Select all links with lightbox class
		});
		</script>
	</head>
	<body>
		<div id="Main">
			<header>
				<h1>imgmgr - admin</h1>
			</header>
			<nav>
				<ul>
					<li><a href="?action=regenerate">Regenerate All Images from Originals</a><br /><span>This will delete all generated images</span></li>
					<li><a href="?action=generate">Generate Images for new Albums (Folders)</a><br /><span>This will only look for new folders that have been added and generate images for them</span></li>
				</ul>
			</nav>
			<div id="Content">
			<?
				// Provide a link to go up a level
				if($albumDir != '') { echo "<a href=\"?album=".imgmgr::getParentDirectoryLink($albumDir)."\">Go Back</a>"; }
				
				// Display a list of albums
				if($albums = imgmgr::getAlbums($SETTINGS['pImageDirectory'].$albumDir)) {
					echo "<h2>Albums</h2><ul id=\"FolderList\">";
					foreach($albums as $album) {
						echo "<li><a href=\"?album=".$albumDir.$album."/\">".$album."</a> - <a href=\"?action=delete&album=".$albumDir.$album."\">Delete</a></li>";
					}
					echo "</ul>";
				}
				
				if($images = imgmgr::getImages($SETTINGS['pImageDirectory'].$albumDir)) {
					echo "<ul id=\"ImageList\">";
					foreach($images as $image) {
						$resizedImageFileName = substr($image, 10);
						echo "<li><a class=\"lightbox\" href=\"".$SETTINGS['pImageDirectory'].$albumDir.$resizedImageFileName."\"><img src=\"".$SETTINGS['pImageDirectory'].$albumDir."/".$image."\" /></a></li>";
					}
					echo "</ul><div style=\"clear: both;\"></div>";
				} else {
					if($albumDir != '') {
						echo "There are no pictures in this album.";
					}
				}
			?>
			</div>
		</div>
	</body>
</html>