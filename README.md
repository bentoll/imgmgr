imgmgr
======

version 0.1 beta

I built this in about 3-4 hours and is my first release of something open source. I still
have a lot of ideas I will implement, but my goal is to create a non-database dependent 
photo browser for the web app that is efficient and extremely simple. If you like what you
see, feel free to contribute or send me feedback/ideas. The app/documentation are not 
complete, but useable!

php/js Image Manager/Browser
- 	Your original images are not modified.
- 	This web app will generate (resized) images and thumbnails, and store them in a 
	specified (settings) folder.
-	This web app includes imgmgr.php and admin.php to handle the end user functionality,
	but you may use the class on your own in your own web apps.

Folders (none are required)
-	css, images, js
	These folders are all for lightbox and jquery used in admin.php and imgmgr.php
-	imgmgr.photos
	This folder is where (according to the settings) generated pictures (and their 
	corresponding folders) will be organized.
-	photos
	This is the master photo directory where you can put your albums (folders) in. In 
	admin.php, you can crawl the directory for new add ons. The app will not modify/delete
	anything in this folder.
	
Files
-	admin.php
	This page will use imgmgr.class.php's admin functions (deleting albums, regenerating
	all pictures/thumbnails, and finding new content to generate)
-	imgmgr.php
	This page will use imgmgr.class.php to display the generated pictures.
-	imgmgr.settings.php
	This page has defined settings that will be used by imgmgr.php. 
-	imgmgr.class.php
	This is the main class that handles all of the image manipulation. 

LICENSE
-	There is no warrenty for this web app and if you download/fork/run the files in anyway, you are doing so at your own risk.
-	You agree to the GPL 3.0 license when using/viewing this web app. The license can be loacted here: http://opensource.org/licenses/gpl-3.0.html
	