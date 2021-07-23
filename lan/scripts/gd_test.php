<?php
//ini_set('display_errors',1);

	
	$imgfile = "add.jpg";
	$imgfile_thumb = "mini_" . $imgfile;
	$imgdir = "../img/uploads/previous_lan/";
	$thumbdir = "../img/uploads/previous_lan/thumbnails/";

	list($old_width, $old_height) = getimagesize($imgdir . $imgfile);
	
	$new_width = $old_width/10;
	$new_height = $old_height/10;
	echo "new_width = " . $new_width . " new_height = " . $new_height;
	
	$new_image = imagecreatetruecolor($new_width, $new_height);
	$old_image = imagecreatefromjpeg($imgdir . $imgfile);

	imagecopyresampled($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);

	imagejpeg($new_image, $thumbdir . $imgfile_thumb);
	imagedestroy($old_image);
	imagedestroy($new_image);

?>
