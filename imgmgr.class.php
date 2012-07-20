<?php
	class imgmgr {
		/*
			@name: resizePhoto
			@parameters:
				- $file: file (including path) of original image
				- $destination: where should the resized image be saved
				- $toWidth: the width of the resize
				- $toHeight: the height of the resize
				- $compression (int): quality of the resize
		*/
		static function resizePhoto($file,$destination,$imageTypes,$toSize,$thumbnailSize,$compression) {
			
			// If the file being resized has a size of 0 or less, return false.
			if(is_file($file) && filesize($file) <= 0) { return false; }
			if(!in_array(strtolower(end(explode('.',$file))),$imageTypes)) { return false; }
			
			// Get the size of the current image
			list($original_width, $original_height) = getimagesize($file);
			
			// Create a pallet for the new image to generate on
			$resizedImage = imagecreatetruecolor($toSize['width'],$toSize['height']);
			$thumbnailImage = imagecreatetruecolor($thumbnailSize['width'],$thumbnailSize['height']);
			
			// Resize the image
			$original_image = imagecreatefromjpeg($file);
      		imagecopyresampled($resizedImage, $original_image, 0, 0, 0, 0, $toSize['width'], $toSize['height'], $original_width, $original_height);
      		imagecopyresampled($thumbnailImage, $original_image, 0, 0, 0, 0, $thumbnailSize['width'], $thumbnailSize['height'], $original_width, $original_height);
      		
      		// Make sure the destination exists. If it doesn't, let's create it.
      		if(!is_dir($destination)) { mkdir($destination); } 
      		      		
      		// Save the image (as a jpeg)
			imagejpeg($resizedImage,$destination.'/'.basename($file),$compression);
			imagejpeg($thumbnailImage,$destination.'/'.thumbnail.'-'.basename($file),$compression);
			
			return true;
		}
		
		/*
			@name: crawlDirectoryForResize
			@parameters:
				- $fromDirectory: directory where the pictures are from
				- $toDirectory: directory where gallery photos are stored
				- $imageTypes: what file types are allowed
				- $toSize (array): 'width' and 'height' of the resized photos
				- $thumbnailSize (array): 'width' and 'height' of the thumbnail photos
				- $compression (int): quality of the resize
		*/
		static function crawlDirectoryForResize($fromDirectory,$toDirectory,$imageTypes,$toSize,$thumbnailSize,$compression,$overwrite=true) {
		
			if(!is_dir($fromDirectory)) { return false; }	// Return false if a directory isn't given
			if(!is_array($imageTypes)) { return false; }	// Return false if image type array isn't given
						
			// Let's open the directory
			if($dh = opendir($fromDirectory)) {
			
				// Now, let's go through each file...
				while(($file = readdir($dh)) !== false) {
					
					if($file == '.' || $file == '..') { continue; }
					
					// If $overwrite == false, let's skip this file if it already was processed
					if($overwrite == false && file_exists($toDirectory.$file)) { continue; }
					
					// Ignore anything not on the list
					$fileType = end(explode('.',$file));
					if(in_array(strtolower($fileType),$imageTypes)) {
						self::resizePhoto(
							$fromDirectory.'/'.$file,
							$toDirectory,
							$imageTypes,
							$toSize,
							$thumbnailSize,
							$compression
							);							
					}
					
					// If there is a directory within a directory, resize those images too and put them in the corresponding folder.					
					if(is_dir($fromDirectory.$file)) {
						self::crawlDirectoryForResize($fromDirectory.$file.'/',$toDirectory.$file.'/',$imageTypes,$toSize,$thumbnailSize,$compression);
					}
				}
				
				// Since we are no longer using the directory, let's close it.
				closedir($dh);
			}
		}
		
		// This will get the first layer of directories in the given folder (folders are considered albums)
		static function getAlbums($folder) {
			if (is_dir($folder) && $handle = opendir($folder)) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != ".." && $entry != 'Thumb.db' && $entry != 'Thumbs.db' && $entry != '.DS_Store') {
						if(is_dir($folder.$entry)) {
							$albumList[] = $entry;
						}
					}
				}
				return $albumList;
				closedir($handle);
			}
		}
		
		// This function will get the first level of images in a given directory
		static function getImages($folder) {
			if (is_dir($folder) && $handle = opendir($folder)) {
				while (false !== ($entry = readdir($handle))) {
					if ($entry != "." && $entry != ".." && $entry != 'Thumb.db' && $entry != 'Thumbs.db' && $entry != '.DS_Store') {
						if(!is_dir($folder.$entry)) {
							if(substr($entry, 0, 10) == "thumbnail-") {
								$imgList[] = $entry;
							}
						}
					}
				}
				return $imgList;
				closedir($handle);
			}
		}
		
		static function getParentDirectoryLink($link) {
			$linkExploded = explode('/',$link);
			$newLink = '';
			$range = (count($linkExploded)-2);
			for($i = 0; $i < $range; $i++) {
				$newLink .= $linkExploded[$i].'/';
			}
			return $newLink;
		}
		
		static function deleteAllGeneratedImages($dir) {
			
			if (is_dir($dir)) {
			 	$objects = scandir($dir);
			 	foreach ($objects as $object) {
			   		if ($object != "." && $object != "..") {
				 		if (filetype($dir."/".$object) == "dir") self::deleteAllGeneratedImages($dir."/".$object); else unlink($dir."/".$object);
			   		}
			 	}
			 	reset($objects);
		   	}
		}
	}
?>