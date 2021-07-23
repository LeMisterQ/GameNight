	<?php 
	$argument1 = $_GET['steamid'];
	//$argument2 = $_GET['argument2'];
	$curl = curl_init('https://store.steampowered.com/api/appdetails?appids=' . $argument1 . '&cc=fr&l=fr');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_COOKIESESSION, true);
	$return = curl_exec($curl);
	curl_close($curl);
	
	echo $return
	
	?>